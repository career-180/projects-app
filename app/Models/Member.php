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
use app\Settings\SystemConstantsSettings;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\MemberWallet;
use Illuminate\Contracts\Database\Eloquent\Builder;

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
        'notes',
        'bank_name_id',
        'bank_branch_name',
        'register_number',
        'file_number',
        'review',
        'membership_start_date',
        'wallet'
    ];

    protected $casts = [
        'graduation_date' => 'date',
        'birth_date' => 'date',
        'travel_date' => 'date',
        'return_date' => 'date',
        'pension_date' => 'date',
        'death_date' => 'date',
        'membership_start_date' => 'date',
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

    /**
     * Get all of the memberWallets for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberWallets(): HasMany
    {
        return $this->hasMany(MemberWallet::class);
    }

    public function getIsSubscribedAttribute()
    {
        return !is_null($this->membership_start_date);
    }

    public function getSubscriptionValue(): int
    {
        if ($this->is_nco)
        {
            if (is_null($this->pension_date)) {
                return app(SystemConstantsSettings::class)->subscription_fees_nco_in_service;
            } else {
                return app(SystemConstantsSettings::class)->subscription_fees_nco_out_service;
            }
            
        } else {
            if (is_null($this->pension_date)) {
                return app(SystemConstantsSettings::class)->subscription_fees_co_in_service;
            } else {
                return app(SystemConstantsSettings::class)->subscription_fees_co_out_service;
            }
        }
    }

    // public function getMembershipUnpaidMonths(Carbon $until): ?array
    // {
    //     $membershipDate = $this->membership_start_date;
    // }

    public function getTotalMembershipMonths(?Carbon $until = null): array
    {
        $monthDays = [];
        $until = $until ?? now()->subMonth();
        $diffInMonths = Carbon::parse($this->membership_start_date)
            ->diffInMonths($until);
        
        $membershipDate = Carbon::parse($this->membership_start_date);
        $period = $membershipDate->range($until, 1, 'month');
        foreach($period as $date)
        {
            $monthDays[] = $date->format('Y-m-01');
        }

        return $monthDays;
    }

    public function getUnpaidMembershipMonths(): array
    {
        $paidMembershipMonths = Membership::where('member_id', 1)->pluck('membership_date')->toArray();
        $paidMembershipMonths = array_map(function($record) {
            return $record->format('Y-m-d');
        }, $paidMembershipMonths);

        $totalMembershipMonths = $this->getTotalMembershipMonths();
        $unpaidMonths = array_diff($totalMembershipMonths, $paidMembershipMonths);
        return array_reverse($unpaidMonths);
    }

    protected function wallet(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }

    public function scopeSearch(Builder $builder, $search): Builder
    {
        return $builder->whereLike('name', $search)
            ->orWhereLike('address', $search)
            ->orWhere('military_number', $search)
            ->orWhere('seniority_number', $search);
    }
}
