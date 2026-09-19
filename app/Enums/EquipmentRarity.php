<?php

namespace OGame\Enums;

/**
 * Equipment rarity tiers for the hero inventory system (GDD section 5.7).
 * Ordered blanc < vert < bleu < violet < orange, as specified.
 */
enum EquipmentRarity: int
{
    case COMMON = 1;
    case UNCOMMON = 2;
    case RARE = 3;
    case EPIC = 4;
    case LEGENDARY = 5;

    /**
     * Display name (French — see project convention: game content is
     * authored in French for now, international locales come later).
     */
    public function getName(): string
    {
        return match ($this) {
            self::COMMON => 'Blanc',
            self::UNCOMMON => 'Vert',
            self::RARE => 'Bleu',
            self::EPIC => 'Violet',
            self::LEGENDARY => 'Orange',
        };
    }

    /**
     * Hex color used to render the rarity in the UI (borders, item names).
     */
    public function getColor(): string
    {
        return match ($this) {
            self::COMMON => '#c0c0c0',
            self::UNCOMMON => '#1eff00',
            self::RARE => '#0070dd',
            self::EPIC => '#a335ee',
            self::LEGENDARY => '#ff8000',
        };
    }
}
