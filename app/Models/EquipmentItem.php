<?php

namespace OGame\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use OGame\Enums\EquipmentRarity;

/**
 * Catalog entry for an equipment item (GDD section 5.7 — craft/forge).
 * Skeleton: a handful of demo items only, full recipe/rarity table to be
 * designed later.
 *
 * @property int $id
 * @property string $name
 * @property string $slot
 * @property int $rarity
 * @property string|null $description
 */
class EquipmentItem extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slot',
        'rarity',
        'description',
    ];

    /**
     * @return Attribute<EquipmentRarity, int>
     */
    protected function rarityEnum(): Attribute
    {
        return Attribute::make(
            get: fn () => EquipmentRarity::from($this->rarity),
        );
    }
}
