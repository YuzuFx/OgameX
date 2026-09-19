<?php

namespace OGame\Http\Controllers;

use Illuminate\View\View;
use OGame\Models\Hero;
use OGame\Models\TalentNode;
use OGame\Services\HeroLevelingService;

/**
 * Pilot controller for the hero/governor system (GDD section 5.5).
 * Minimal and non-functional: lists the current player's heroes and shows
 * a hero sheet (talents, equipment). No recruitment, mission, or bonus
 * logic yet.
 */
class HeroController extends OGameController
{
    /**
     * Shows the hero index page.
     *
     * @param HeroLevelingService $levelingService
     * @return View
     */
    public function index(HeroLevelingService $levelingService): View
    {
        $this->setBodyId('heroes');

        $heroes = Hero::where('user_id', auth()->id())->get();

        $heroCards = $heroes->map(fn (Hero $hero) => $this->buildProgressionData($hero, $levelingService));

        return view('ingame.hero.index')->with([
            'heroCards' => $heroCards,
        ]);
    }

    /**
     * Shows a single hero's sheet: talents and equipment inventory.
     *
     * @param Hero $hero
     * @param HeroLevelingService $levelingService
     * @return View
     */
    public function show(Hero $hero, HeroLevelingService $levelingService): View
    {
        abort_unless($hero->user_id === auth()->id(), 403);

        $this->setBodyId('heroes');

        $hero->load(['talents', 'equipment.equipmentItem']);

        $equipped = $hero->equipment->where('equipped', true);
        $inventory = $hero->equipment->where('equipped', false);

        // Full tree for this hero's archetype, with the hero's allocated
        // rank (0 if not yet invested) attached to each node. Grouped by
        // branch (column) then tier (row) for the WoW-style vertical
        // layout; a null branch is a capstone spanning every column.
        $allocatedRanks = $hero->talents->pluck('rank', 'talent_node_id');
        $nodesWithRank = TalentNode::where('archetype', $hero->archetype)
            ->orderBy('tier')
            ->orderBy('id')
            ->get()
            ->map(fn (TalentNode $node) => [
                'node' => $node,
                'rank' => $allocatedRanks->get($node->id, 0),
            ]);

        $talentBranches = $nodesWithRank
            ->filter(fn (array $entry) => $entry['node']->branch !== null)
            ->groupBy(fn (array $entry) => (string) $entry['node']->branch)
            ->map(fn ($entries) => $entries->groupBy(fn (array $entry) => $entry['node']->tier));

        $talentCapstones = $nodesWithRank->filter(fn (array $entry) => $entry['node']->branch === null);

        return view('ingame.hero.show')->with([
            'card' => $this->buildProgressionData($hero, $levelingService),
            'equipped' => $equipped,
            'inventory' => $inventory,
            'talentBranches' => $talentBranches,
            'talentCapstones' => $talentCapstones,
        ]);
    }

    /**
     * Builds the level/XP/talent-point summary shared by the index cards
     * and the hero sheet.
     *
     * @param Hero $hero
     * @param HeroLevelingService $levelingService
     * @return array<string, mixed>
     */
    private function buildProgressionData(Hero $hero, HeroLevelingService $levelingService): array
    {
        $talentPointsEarned = $levelingService->talentPointsEarned($hero->level);

        return [
            'hero' => $hero,
            'xp_required' => $levelingService->xpRequiredForLevel($hero->level),
            'xp_progress_percentage' => $levelingService->progressPercentage($hero->level, $hero->xp),
            'talent_points_earned' => $talentPointsEarned,
            'talent_points_available' => max(0, $talentPointsEarned - $hero->talent_points_spent),
        ];
    }
}
