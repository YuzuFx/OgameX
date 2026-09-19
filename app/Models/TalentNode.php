<?php

namespace OGame\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single node in an archetype's talent tree (GDD section 5.5).
 * Skeleton: structure only, tree content to be designed later.
 *
 * @property int $id
 * @property string $archetype
 * @property string|null $branch
 * @property string $name
 * @property string|null $description
 * @property int $tier
 * @property int $point_cost
 * @property int $max_rank
 * @property int|null $prerequisite_talent_node_id
 */
class TalentNode extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'archetype',
        'branch',
        'name',
        'description',
        'tier',
        'point_cost',
        'max_rank',
        'prerequisite_talent_node_id',
    ];

    /**
     * The talent node that must be (partially) invested in before this one
     * can be allocated. Null if this node has no prerequisite.
     *
     * @return BelongsTo
     */
    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(self::class, 'prerequisite_talent_node_id');
    }
}
