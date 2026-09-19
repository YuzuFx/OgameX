<?php

namespace OGame\Console\Commands\Dev;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use OGame\Enums\EquipmentRarity;
use OGame\Models\EquipmentItem;
use OGame\Models\Hero;
use OGame\Models\HeroEquipment;
use OGame\Models\HeroTalent;
use OGame\Models\TalentNode;
use OGame\Models\User;

/**
 * Seeds a single demo hero ("John Doe") with a full 3-branch talent tree
 * and a full set of equipment items across all 8 slots, to serve as a
 * visual reference for the hero sheet UI (talent tree layout, Diablo-style
 * paper-doll inventory). Recreates everything on each run.
 *
 * The archetype ("Guerrier") and all talent/item content here are
 * placeholders/"random" content for visualization purposes: the real
 * archetype roster and talent/item balance are still [TBD] in the GDD
 * (sections 5.5 and 5.7).
 */
#[Description('Seed a demo hero ("John Doe") with a full talent tree and equipment set for hero sheet UI reference. Recreates on each run.')]
#[Signature('ogamex:dev:seed-demo-hero {--username=Legor : Username of the player that owns the demo hero}')]
class SeedDemoHero extends Command
{
    private const string ARCHETYPE = 'Guerrier';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $username = (string) $this->option('username');
        $user = User::where('username', $username)->first();

        if (!$user) {
            $this->error("User '{$username}' not found. Run ogamex:admin:init-legor or ogamex:dev:seed-users first.");
            return Command::FAILURE;
        }

        $talentNodes = $this->seedTalentTree();
        $equipmentItems = $this->seedEquipmentItems();

        // Recreate on each run for idempotency.
        Hero::where('user_id', $user->id)->where('name', 'John Doe')->delete();

        $hero = Hero::create([
            'user_id' => $user->id,
            'planet_id' => null,
            'name' => 'John Doe',
            'avatar' => null,
            'archetype' => self::ARCHETYPE,
            'level' => 12,
            'xp' => 1200,
            'talent_points_spent' => 6,
            'status' => 'available',
            // Demo values for a level 12 hero — not derived from a formula
            // yet (see GDD 5.5, stats system still TBD for Phase 6 balancing).
            'strength' => 86,
            'intelligence' => 22,
            'willpower' => 34,
            'dexterity' => 41,
            'attack_power' => 612,
            'armor' => 258,
            'life' => 1840,
        ]);

        // 6 points spent: rank 2 in the tier-1 "Voie de la Lame" node,
        // rank 1 in a handful of others across branches — enough to show
        // a partially-filled tree without maxing anything out.
        $allocations = [
            'lame_t1' => 2,
            'lame_t2' => 1,
            'bastion_t1' => 1,
            'bastion_t2' => 1,
            'serment_t1' => 1,
        ];

        foreach ($allocations as $key => $rank) {
            HeroTalent::create([
                'hero_id' => $hero->id,
                'talent_node_id' => $talentNodes[$key]->id,
                'rank' => $rank,
            ]);
        }

        // Equip 4 of the 8 slots, leave the rest owned but in the
        // inventory grid, so the sheet shows both states at once.
        $equippedSlots = ['weapon_main_hand', 'weapon_off_hand', 'chest', 'head'];

        foreach ($equipmentItems as $item) {
            HeroEquipment::create([
                'hero_id' => $hero->id,
                'equipment_item_id' => $item->id,
                'equipped' => in_array($item->slot, $equippedSlots, true),
            ]);
        }

        $this->info('--------------------------------');
        $this->info('Demo hero created:');
        $this->info('- ID: ' . $hero->id);
        $this->info('- Owner: ' . $user->username);
        $this->info('- Name: ' . $hero->name);
        $this->info('- Archetype: ' . $hero->archetype . ' (placeholder, GDD roster still TBD)');
        $this->info('- Level: ' . $hero->level . ' (' . $hero->xp . ' XP)');
        $this->info('--------------------------------');

        return Command::SUCCESS;
    }

    /**
     * Seeds a 3-branch, 5-tier talent tree plus one capstone, for the demo
     * archetype. Point costs rise with tier so the 29-point career budget
     * (level cap 30) cannot fill more than a fraction of the full tree.
     *
     * @return array<string, TalentNode>
     */
    private function seedTalentTree(): array
    {
        TalentNode::where('archetype', self::ARCHETYPE)->delete();

        $branches = [
            'lame' => [
                'label' => 'Voie de la Lame',
                'nodes' => [
                    ['name' => 'Frappe acérée', 'description' => 'Augmente les dégâts de l\'attaque de base.', 'cost' => 1, 'max_rank' => 3],
                    ['name' => 'Fureur contenue', 'description' => 'Chaque coup porté accumule de la rage.', 'cost' => 1, 'max_rank' => 1],
                    ['name' => 'Estoc du rupteur', 'description' => 'Chance de percer l\'armure ennemie.', 'cost' => 2, 'max_rank' => 1],
                    ['name' => 'Lame ivre de sang', 'description' => 'Soigne le héros sur les coups critiques.', 'cost' => 2, 'max_rank' => 1],
                    ['name' => 'Danse des mille coupures', 'description' => 'Enchaîne plusieurs attaques rapides.', 'cost' => 3, 'max_rank' => 1],
                ],
            ],
            'bastion' => [
                'label' => 'Voie du Bastion',
                'nodes' => [
                    ['name' => 'Peau de granit', 'description' => 'Augmente l\'armure passivement.', 'cost' => 1, 'max_rank' => 3],
                    ['name' => 'Posture inébranlable', 'description' => 'Réduit les dégâts subis en position défensive.', 'cost' => 1, 'max_rank' => 1],
                    ['name' => 'Rempart vivant', 'description' => 'Absorbe une partie des dégâts destinés aux alliés.', 'cost' => 2, 'max_rank' => 1],
                    ['name' => 'Sang du serment', 'description' => 'Régénère la vie en combat prolongé.', 'cost' => 2, 'max_rank' => 1],
                    ['name' => 'Dernier souffle', 'description' => 'Survit à un coup fatal, une fois par mission.', 'cost' => 3, 'max_rank' => 1],
                ],
            ],
            'serment' => [
                'label' => 'Voie du Serment',
                'nodes' => [
                    ['name' => 'Écho du serment', 'description' => 'Augmente la résistance aux effets d\'altération.', 'cost' => 1, 'max_rank' => 3],
                    ['name' => 'Pas de l\'ombre', 'description' => 'Réduit le temps de trajet en mission.', 'cost' => 1, 'max_rank' => 1],
                    ['name' => 'Vigilance ancestrale', 'description' => 'Révèle la garnison adverse avant l\'engagement.', 'cost' => 2, 'max_rank' => 1],
                    ['name' => 'Résonance des Straumar', 'description' => 'Accélère la régénération de la jauge de prestige.', 'cost' => 2, 'max_rank' => 1],
                    ['name' => 'Marque du gardien', 'description' => 'Réduit la durée d\'indisponibilité en cas d\'échec.', 'cost' => 3, 'max_rank' => 1],
                ],
            ],
        ];

        $created = [];
        $lastLameNode = null;

        foreach ($branches as $branchKey => $branch) {
            $previousNode = null;

            foreach ($branch['nodes'] as $tierIndex => $nodeData) {
                $tier = $tierIndex + 1;

                $node = TalentNode::create([
                    'archetype' => self::ARCHETYPE,
                    'branch' => $branch['label'],
                    'name' => $nodeData['name'],
                    'description' => $nodeData['description'],
                    'tier' => $tier,
                    'point_cost' => $nodeData['cost'],
                    'max_rank' => $nodeData['max_rank'],
                    'prerequisite_talent_node_id' => $previousNode?->id,
                ]);

                $created[$branchKey . '_t' . $tier] = $node;
                $previousNode = $node;

                if ($branchKey === 'lame' && $tier === 5) {
                    $lastLameNode = $node;
                }
            }
        }

        // Capstone: branch = null renders it spanning all columns at the
        // bottom of the tree. Gated behind the top of "Voie de la Lame"
        // as a simple demo prerequisite.
        $created['capstone'] = TalentNode::create([
            'archetype' => self::ARCHETYPE,
            'branch' => null,
            'name' => 'Colère du Gouffre',
            'description' => 'Talent ultime : déchaîne une puissance empruntée aux gardiens du Gouffre.',
            'tier' => 6,
            'point_cost' => 5,
            'max_rank' => 1,
            'prerequisite_talent_node_id' => $lastLameNode?->id,
        ]);

        return $created;
    }

    /**
     * Seeds one demo equipment item per slot (8 total), across a spread of
     * rarities, to fill out the paper-doll + inventory grid demo.
     *
     * @return array<int, EquipmentItem>
     */
    private function seedEquipmentItems(): array
    {
        $demoNames = [
            'Heaume du Gardien Déchu',
            'Cuirasse des Ruines Elfiques',
            'Gantelets du Serment Rompu',
            'Ceinturon de Toile d\'Ombre',
            'Jambières de l\'Éclat Oublié',
            'Bottes du Passeur de Straumar',
            'Tranche-Brume, Lame des Vahrun',
            'Bouclier du Dernier Rempart',
        ];
        EquipmentItem::whereIn('name', $demoNames)->delete();

        $definitions = [
            ['name' => 'Heaume du Gardien Déchu', 'slot' => 'head', 'rarity' => EquipmentRarity::RARE, 'description' => 'Porté par un gardien tombé aux frontières du Gouffre.'],
            ['name' => 'Cuirasse des Ruines Elfiques', 'slot' => 'chest', 'rarity' => EquipmentRarity::EPIC, 'description' => 'Forgée avant la Rupture, encore chargée de mana résiduel.'],
            ['name' => 'Gantelets du Serment Rompu', 'slot' => 'hands', 'rarity' => EquipmentRarity::UNCOMMON, 'description' => 'Marqués du sceau des nains qui refusèrent la Grande Conjonction.'],
            ['name' => 'Ceinturon de Toile d\'Ombre', 'slot' => 'belt', 'rarity' => EquipmentRarity::COMMON, 'description' => 'Tissé à partir de fibres récupérées sur un Éclat isolé.'],
            ['name' => 'Jambières de l\'Éclat Oublié', 'slot' => 'legs', 'rarity' => EquipmentRarity::UNCOMMON, 'description' => 'Récupérées sur un Éclat que plus personne ne sait situer.'],
            ['name' => 'Bottes du Passeur de Straumar', 'slot' => 'boots', 'rarity' => EquipmentRarity::RARE, 'description' => 'Permettent de sentir la proximité d\'un Straumr sous ses pieds.'],
            ['name' => 'Tranche-Brume, Lame des Vahrun', 'slot' => 'weapon_main_hand', 'rarity' => EquipmentRarity::LEGENDARY, 'description' => 'Une lame dont l\'origine exacte glace le sang de ceux qui la reconnaissent.'],
            ['name' => 'Bouclier du Dernier Rempart', 'slot' => 'weapon_off_hand', 'rarity' => EquipmentRarity::COMMON, 'description' => 'Un bouclier robuste, sans histoire particulière — pour l\'instant.'],
        ];

        return collect($definitions)
            ->map(fn (array $def) => EquipmentItem::create([
                'name' => $def['name'],
                'slot' => $def['slot'],
                'rarity' => $def['rarity']->value,
                'description' => $def['description'],
            ]))
            ->all();
    }
}
