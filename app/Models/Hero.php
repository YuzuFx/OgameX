<?php

namespace OGame\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Pilot model for the hero/governor system (GDD section 5.5).
 * Minimal and non-functional: no recruitment, mission, or bonus logic yet.
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $planet_id
 * @property string $name
 * @property string|null $avatar
 * @property string $archetype
 * @property int $level
 * @property int $xp
 * @property int $talent_points_spent
 * @property string $status
 * @property int $strength
 * @property int $intelligence
 * @property int $willpower
 * @property int $dexterity
 * @property int $attack_power
 * @property int $armor
 * @property int $life
 */
class Hero extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'planet_id',
        'name',
        'avatar',
        'archetype',
        'level',
        'xp',
        'talent_points_spent',
        'status',
        'strength',
        'intelligence',
        'willpower',
        'dexterity',
        'attack_power',
        'armor',
        'life',
    ];

    /**
     * The user (player) that recruited this hero.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The planet this hero governs, if assigned.
     *
     * @return BelongsTo
     */
    public function planet(): BelongsTo
    {
        return $this->belongsTo(Planet::class);
    }

    /**
     * This hero's talent point allocations.
     *
     * @return HasMany
     */
    public function talents(): HasMany
    {
        return $this->hasMany(HeroTalent::class);
    }

    /**
     * This hero's owned equipment (equipped + inventory).
     *
     * @return HasMany
     */
    public function equipment(): HasMany
    {
        return $this->hasMany(HeroEquipment::class);
    }
}
