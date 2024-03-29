<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasMember;
use App\Models\Position;

class MemberJob extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;

    protected $fillable = [
        'member_id',
        'job_filled_date',
        'position_id'
    ];

    /**
     * Get the position that owns the MemberJob
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
