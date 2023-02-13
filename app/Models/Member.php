<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;
use App\Models\Rank;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MemberPromotion;
use App\Models\MemberJob;
use App\Models\BankName;
use App\Models\Unit;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\AgeForm;
use App\Models\DeathForm;
use App\Models\DisabledForm;
use App\Models\FellowshipGrantForm;
use App\Models\MarriageForm;
use App\Models\MemberForm;
use App\Models\Membership;
use App\Models\RefundForm;
use App\Models\RelativeDeathForm;

class Member extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $fillable = [
        'military_number',
        'seniority_number',
        'rank_id',
        'is_institute_graduate',
        'is_nco',
        'category_id',
        'is_general_staff',
        'name',
        'address',
        'home_phone_number',
        'mobile_phone_number',
        'beneficiary_name',
        'beneficiary_title',
        'class',
        'department_id',
        'graduation_date',
        'birth_date',
        'travel_date',
        'return_date',
        'national_id_number',
        'bank_account_number',
        'pension_date',
        'pension_reason',
        'death_date',
        'is_subscribed',
        'notes',
        'bank_name_id',
        'bank_branch_name',
        'register_number',
        'file_number',
        'review',
    ];

    protected $cascadeDeletes = [
        'jobs',
        'promotions',
        'relativeDeathForms',
        'refundForms',
        'projectClosureForms',
        'memberships',
        'memberForms',
        'marriageForms',
        'fellowshipGrantForms',
        'disabledForms',
        'deathForms',
        'ageForms',
    ];

    /**
     * Get the category that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the department that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the rank that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }

    /**
     * Get all of the jobs for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(MemberJob::class);
    }

    /**
     * Get all of the promotions for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function promotions(): HasMany
    {
        return $this->hasMany(MemberPromotion::class);
    }

    /**
     * Get the bankName that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankName(): BelongsTo
    {
        return $this->belongsTo(BankName::class);
    }

    public function getRankName()
    {
        if ($this->is_general_staff)
        {
            return implode(" ", [$this->rank->name, "أ ح"]);
        }
        
        if ($this->is_institute_graduate)
        {
            return implode(" ", [$this->rank->name, "معهد فني"]);
        }
        return $this->rank->name;
    }

    public function getUnit(): Unit
    {
        return $this->jobs()->orderByDesc('created_at')->first()?->unit;
    }

    /**
     * Get all of the ageForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ageForms(): HasMany
    {
        return $this->hasMany(AgeForm::class);
    }

    /**
     * Get all of the deathForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deathForms(): HasMany
    {
        return $this->hasMany(DeathForm::class);
    }

    /**
     * Get all of the disabledForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disabledForms(): HasMany
    {
        return $this->hasMany(DisabledForm::class);
    }

    /**
     * Get all of the fellowshipGrantForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fellowshipGrantForms(): HasMany
    {
        return $this->hasMany(FellowshipGrantForm::class);
    }

    /**
     * Get all of the marriageForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function marriageForms(): HasMany
    {
        return $this->hasMany(MarriageForm::class);
    }

    /**
     * Get all of the memberForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberForms(): HasMany
    {
        return $this->hasMany(MemberForm::class);
    }

    /**
     * Get all of the memberships for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get all of the projectClosureForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectClosureForms(): HasMany
    {
        return $this->hasMany(ProjectClosureForm::class);
    }

    /**
     * Get all of the refundForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refundForms(): HasMany
    {
        return $this->hasMany(RefundForm::class);
    }

    /**
     * Get all of the relativeDeathForms for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function relativeDeathForms(): HasMany
    {
        return $this->hasMany(RelativeDeathForm::class);
    }
    
}
