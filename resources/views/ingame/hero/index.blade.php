@extends('ingame.layouts.main')

@section('content')
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&display=swap" rel="stylesheet">

    <style>
        #herocomponent {
            --hero-bg-deep: #0a0a14;
            --hero-bg-panel: #14141f;
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
        #herocomponent h2 { font-family: 'Cinzel', serif; }

        .hero-card-list { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 12px; }

        .hero-card {
            display: block;
            width: 240px;
            background: var(--hero-bg-panel);
            border: 1px solid var(--hero-border);
            border-radius: 8px;
            padding: 14px;
            text-decoration: none;
            color: inherit;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .hero-card:hover {
            border-color: var(--hero-accent-soft);
            box-shadow: 0 0 14px rgba(138, 124, 255, 0.2);
        }

        .hero-card__avatar {
            width: 64px; height: 64px; border-radius: 50%;
            background: var(--hero-bg-deep);
            border: 1px solid var(--hero-accent-soft);
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; color: var(--hero-text-muted);
            margin-bottom: 8px;
        }
        .hero-card__avatar img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }

        .hero-card__name { margin: 0 0 2px; font-family: 'Cinzel', serif; font-size: 16px; }
        .hero-card__subtitle { margin: 0 0 8px; color: var(--hero-text-muted); font-size: 12px; }

        .xp-bar { background: #000; border: 1px solid var(--hero-border); border-radius: 4px; overflow: hidden; height: 8px; margin-bottom: 8px; }
        .xp-bar__fill { background: linear-gradient(90deg, var(--hero-accent-soft), var(--hero-accent)); height: 100%; }

        .hero-card__meta { font-size: 11px; color: var(--hero-text-muted); }
    </style>

    <div id="herocomponent" class="maincontent">
        <div id="content">
            <div id="inhalt">
                <h2>{{ __('t_heroes.ui.page_title') }}</h2>

                @if ($heroCards->isEmpty())
                    <p>{{ __('t_heroes.ui.no_heroes') }}</p>
                @else
                    <div class="hero-card-list">
                        @foreach ($heroCards as $card)
                            @php $hero = $card['hero']; @endphp
                            <a href="{{ route('heroes.show', $hero) }}" class="hero-card">
                                <div class="hero-card__avatar">
                                    @if ($hero->avatar)
                                        <img src="{{ $hero->avatar }}" alt="{{ $hero->name }}">
                                    @else
                                        {{ __('t_heroes.ui.page_title') }}
                                    @endif
                                </div>

                                <h3 class="hero-card__name">{{ $hero->name }}</h3>
                                <p class="hero-card__subtitle">{{ $hero->archetype }} — {{ __('t_heroes.ui.level') }} {{ $hero->level }}</p>

                                <div class="hero-card__meta" style="margin-bottom:2px;">{{ __('t_heroes.ui.xp') }}: {{ $hero->xp }} / {{ $card['xp_required'] }}</div>
                                <div class="xp-bar"><div class="xp-bar__fill" style="width:{{ $card['xp_progress_percentage'] }}%;"></div></div>

                                <div class="hero-card__meta">
                                    {{ $card['talent_points_available'] }} {{ __('t_heroes.ui.talent_points_available') }}
                                    ({{ __('t_heroes.ui.talent_points_detail', ['spent' => $hero->talent_points_spent, 'earned' => $card['talent_points_earned']]) }})
                                </div>
                                <div class="hero-card__meta">{{ __('t_heroes.status.' . $hero->status) }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
