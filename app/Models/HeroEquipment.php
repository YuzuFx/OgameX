<?php

namespace OGame\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An equipment item owned by a hero — equipped or sitting in the
 * Diablo-style inventory grid.
 *
 * @property int $id
 * @property int $hero_id
 * @property int $equipment_item_id
 * @property bool $equipped
 */
class HeroEquipment extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'hero_id',
        'equipment_item_id',
        'equipped',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'equipped' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }

    /**
     * @return BelongsTo
     */
    public function equipmentItem(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class);
    }
}
