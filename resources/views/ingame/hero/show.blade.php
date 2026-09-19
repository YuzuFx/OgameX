@extends('ingame.layouts.main')

@section('content')
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&display=swap" rel="stylesheet">

    @php
        $hero = $card['hero'];
        $paperDollSlots = ['head', 'weapon_off_hand', 'chest', 'weapon_main_hand', 'hands', 'belt', 'legs', 'boots'];
        $equippedBySlot = $equipped->keyBy(fn ($e) => $e->equipmentItem->slot);
        $primaryStats = ['strength', 'intelligence', 'willpower', 'dexterity'];
        $derivedStats = ['attack_power', 'armor', 'life'];
    @endphp

    <style>
        #herosheetcomponent {
            --hero-bg-deep: #0a0a14;
            --hero-bg-panel: #14141f;
            --hero-bg-slot: #1a1a29;
            --hero-border: #33334d;
            --hero-accent: #8a7cff;
            --hero-accent-soft: #5b4fc4;
            --hero-text: #e4e2f5;
            --hero-text-muted: #8f8caf;

            background: radial-gradient(ellipse at top, #16162a 0%, var(--hero-bg-deep) 60%);
            color: var(--hero-text);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--hero-border);
        }

        #herosheetcomponent h1, #herosheetcomponent h2, #herosheetcomponent h3 {
            font-family: 'Cinzel', serif;
        }

        #herosheetcomponent a.back-link {
            color: var(--hero-text-muted);
            font-size: 12px;
            text-decoration: none;
        }

        .hero-sheet__layout {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .hero-sheet__sidebar {
            width: 220px;
            flex-shrink: 0;
        }

        .hero-avatar {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 6px;
            background: var(--hero-bg-panel);
            border: 1px solid var(--hero-accent-soft);
            box-shadow: 0 0 16px rgba(138, 124, 255, 0.15) inset;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: var(--hero-text-muted);
            margin-bottom: 10px;
        }
        .hero-avatar img { width: 100%; height: 100%; border-radius: 6px; object-fit: cover; }

        .hero-sheet__name { margin: 0 0 2px; font-size: 20px; letter-spacing: .5px; }
        .hero-sheet__subtitle { margin: 0 0 10px; color: var(--hero-text-muted); font-size: 13px; }

        .xp-bar { background: #000; border: 1px solid var(--hero-border); border-radius: 4px; overflow: hidden; height: 8px; margin-bottom: 4px; }
        .xp-bar__fill { background: linear-gradient(90deg, var(--hero-accent-soft), var(--hero-accent)); height: 100%; }
        .xp-label { font-size: 11px; color: var(--hero-text-muted); margin-bottom: 10px; }

        .stat-panel { border-top: 1px solid var(--hero-border); padding-top: 8px; margin-top: 8px; }
        .stat-panel__title { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--hero-accent); margin-bottom: 6px; }
        .stat-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px; }
        .stat-row strong { color: var(--hero-text); }

        .hero-sheet__main { flex: 1; min-width: 320px; }
        .hero-sheet__section { margin-bottom: 28px; }
        .hero-sheet__section-title { font-size: 15px; color: var(--hero-accent); margin: 0 0 12px; }

        /* Equipment: Diablo-style paper doll */
        .equipment-row { display: flex; gap: 32px; flex-wrap: wrap; }

        .paperdoll {
            display: grid;
            grid-template-columns: 76px 76px 76px;
            grid-template-rows: repeat(5, 76px);
            grid-template-areas:
                ".    head    ."
                "woff chest   wmain"
                "hands .      belt"
                ".    legs    ."
                ".    boots   .";
            gap: 6px;
        }
        .slot-head { grid-area: head; }
        .slot-weapon_off_hand { grid-area: woff; }
        .slot-chest { grid-area: chest; }
        .slot-weapon_main_hand { grid-area: wmain; }
        .slot-hands { grid-area: hands; }
        .slot-belt { grid-area: belt; }
        .slot-legs { grid-area: legs; }
        .slot-boots { grid-area: boots; }

        .equip-slot {
            width: 76px; height: 76px;
            background: var(--hero-bg-slot);
            border: 2px solid var(--hero-border);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            text-align: center; font-size: 9px; padding: 4px;
            color: var(--hero-text-muted);
        }
        .equip-slot--filled { color: var(--hero-text); }

        .inventory-grid { display: grid; grid-template-columns: repeat(auto-fill, 68px); gap: 8px; align-content: start; }
        .inventory-slot {
            width: 68px; height: 68px;
            background: var(--hero-bg-slot);
            border: 2px solid var(--hero-border);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            text-align: center; font-size: 9px; padding: 4px;
        }

        /* Talent tree: WoW-style vertical columns */
        .talent-tree { display: flex; justify-content: center; gap: 28px; flex-wrap: wrap; }
        .talent-branch { display: flex; flex-direction: column; align-items: center; width: 170px; }
        .talent-branch__title { font-size: 12px; color: var(--hero-accent); margin-bottom: 8px; text-align: center; }
        .talent-connector { width: 2px; height: 14px; background: var(--hero-border); }
        .talent-connector--lit { background: var(--hero-accent); box-shadow: 0 0 6px var(--hero-accent); }

        .talent-node {
            width: 150px;
            background: var(--hero-bg-panel);
            border: 1px solid var(--hero-border);
            border-radius: 6px;
            padding: 8px;
            opacity: .55;
        }
        .talent-node--invested {
            opacity: 1;
            border-color: var(--hero-accent);
            box-shadow: 0 0 10px rgba(138, 124, 255, 0.25);
        }
        .talent-node__name { font-weight: bold; font-size: 12px; margin-bottom: 2px; }
        .talent-node__desc { font-size: 10px; color: var(--hero-text-muted); margin-bottom: 4px; }
        .talent-node__meta { font-size: 10px; color: var(--hero-text-muted); }

        .talent-capstone-row { display: flex; justify-content: center; margin-top: 8px; }
        .talent-capstone-row .talent-node { width: 260px; text-align: center; }
    </style>

    <div id="herosheetcomponent" class="maincontent">
        <div id="content">
            <div id="inhalt">
                <a href="{{ route('heroes.index') }}" class="back-link">&larr; {{ __('t_heroes.ui.back_to_list') }}</a>

                <div class="hero-sheet__layout">
                    {{-- Sidebar: portrait + stats --}}
                    <div class="hero-sheet__sidebar">
                        <div class="hero-avatar">
                            @if ($hero->avatar)
                                <img src="{{ $hero->avatar }}" alt="{{ $hero->name }}">
                            @else
                                {{ __('t_heroes.ui.page_title') }}
                            @endif
                        </div>

                        <h2 class="hero-sheet__name">{{ $hero->name }}</h2>
                        <p class="hero-sheet__subtitle">{{ $hero->archetype }} — {{ __('t_heroes.ui.level') }} {{ $hero->level }}</p>

                        <div class="xp-label">{{ __('t_heroes.ui.xp') }}: {{ $hero->xp }} / {{ $card['xp_required'] }}</div>
                        <div class="xp-bar"><div class="xp-bar__fill" style="width:{{ $card['xp_progress_percentage'] }}%;"></div></div>

                        <div class="xp-label">
                            {{ __('t_heroes.status.' . $hero->status) }} —
                            {{ $card['talent_points_available'] }} {{ __('t_heroes.ui.talent_points_available') }}
                            ({{ __('t_heroes.ui.talent_points_detail', ['spent' => $hero->talent_points_spent, 'earned' => $card['talent_points_earned']]) }})
                        </div>

                        <div class="stat-panel">
                            <div class="stat-panel__title">{{ __('t_heroes.sheet.stats_title') }}</div>
                            @foreach ($derivedStats as $stat)
                                <div class="stat-row"><span>{{ __('t_heroes.stat.' . $stat) }}</span><strong>{{ number_format($hero->{$stat}) }}</strong></div>
                            @endforeach
                        </div>
                        <div class="stat-panel">
                            @foreach ($primaryStats as $stat)
                                <div class="stat-row"><span>{{ __('t_heroes.stat.' . $stat) }}</span><strong>{{ number_format($hero->{$stat}) }}</strong></div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Main: equipment + talent tree --}}
                    <div class="hero-sheet__main">
                        <div class="hero-sheet__section">
                            <h3 class="hero-sheet__section-title">{{ __('t_heroes.sheet.equipment_title') }}</h3>
                            <div class="equipment-row">
                                <div class="paperdoll">
                                    @foreach ($paperDollSlots as $slot)
                                        @php $item = $equippedBySlot->get($slot); @endphp
                                        <div class="equip-slot slot-{{ $slot }} {{ $item ? 'equip-slot--filled' : '' }}"
                                             style="{{ $item ? 'border-color:' . $item->equipmentItem->rarityEnum->getColor() . '; box-shadow: 0 0 8px ' . $item->equipmentItem->rarityEnum->getColor() . '55;' : '' }}"
                                             title="{{ $item ? $item->equipmentItem->name . ' (' . $item->equipmentItem->rarityEnum->getName() . ')' : __('t_heroes.slot.' . $slot) }}">
                                            @if ($item)
                                                <span style="color:{{ $item->equipmentItem->rarityEnum->getColor() }};">{{ $item->equipmentItem->name }}</span>
                                            @else
                                                <span>{{ __('t_heroes.slot.' . $slot) }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div style="flex:1; min-width:200px;">
                                    <div class="stat-panel__title" style="margin-bottom:8px;">{{ __('t_heroes.sheet.inventory_title') }}</div>
                                    @if ($inventory->isEmpty())
                                        <p style="font-size:12px; color:var(--hero-text-muted);">{{ __('t_heroes.sheet.inventory_empty') }}</p>
                                    @else
                                        <div class="inventory-grid">
                                            @foreach ($inventory as $item)
                                                <div class="inventory-slot" style="border-color:{{ $item->equipmentItem->rarityEnum->getColor() }};" title="{{ $item->equipmentItem->name }} ({{ $item->equipmentItem->rarityEnum->getName() }})">
                                                    <span style="color:{{ $item->equipmentItem->rarityEnum->getColor() }};">{{ $item->equipmentItem->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="hero-sheet__section">
                            <h3 class="hero-sheet__section-title">{{ __('t_heroes.sheet.talents_title') }}</h3>

                            @if ($talentBranches->isEmpty())
                                <p style="font-size:13px; color:var(--hero-text-muted);">{{ __('t_heroes.sheet.talents_empty') }}</p>
                            @else
                                <div class="talent-tree">
                                    @foreach ($talentBranches as $branchLabel => $tiers)
                                        <div class="talent-branch">
                                            <div class="talent-branch__title">{{ $branchLabel }}</div>
                                            @php $previousTierInvested = true; @endphp
                                            @foreach ($tiers->sortKeys() as $tier => $entries)
                                                @if (!$loop->first)
                                                    <div class="talent-connector {{ $previousTierInvested ? 'talent-connector--lit' : '' }}"></div>
                                                @endif
                                                @foreach ($entries as $entry)
                                                    @php $node = $entry['node']; $rank = $entry['rank']; @endphp
                                                    <div class="talent-node {{ $rank > 0 ? 'talent-node--invested' : '' }}">
                                                        <div class="talent-node__name">{{ $node->name }}</div>
                                                        <div class="talent-node__desc">{{ $node->description }}</div>
                                                        <div class="talent-node__meta">
                                                            {{ __('t_heroes.sheet.talent_rank', ['rank' => $rank, 'max_rank' => $node->max_rank]) }}
                                                            · {{ __('t_heroes.sheet.talent_cost', ['cost' => $node->point_cost]) }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @php $previousTierInvested = $entries->contains(fn ($e) => $e['rank'] > 0); @endphp
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($talentCapstones->isNotEmpty())
                                <div class="talent-capstone-row">
                                    @foreach ($talentCapstones as $entry)
                                        @php $node = $entry['node']; $rank = $entry['rank']; @endphp
                                        <div class="talent-node {{ $rank > 0 ? 'talent-node--invested' : '' }}">
                                            <div class="talent-node__name">★ {{ $node->name }}</div>
                                            <div class="talent-node__desc">{{ $node->description }}</div>
                                            <div class="talent-node__meta">
                                                {{ __('t_heroes.sheet.talent_rank', ['rank' => $rank, 'max_rank' => $node->max_rank]) }}
                                                · {{ __('t_heroes.sheet.talent_cost', ['cost' => $node->point_cost]) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
