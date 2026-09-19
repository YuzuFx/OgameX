<?php

namespace OGame\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A hero's allocation of talent points into a single talent node.
 *
 * @property int $id
 * @property int $hero_id
 * @property int $talent_node_id
 * @property int $rank
 */
class HeroTalent extends Model
{
    /**
     * Explicit table name: Eloquent's default pluralization does not
     * pluralize "Talent" reliably in this combination.
     *
     * @var string
     */
    protected $table = 'hero_talents';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'hero_id',
        'talent_node_id',
        'rank',
    ];

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
    public function talentNode(): BelongsTo
    {
        return $this->belongsTo(TalentNode::class);
    }
}
