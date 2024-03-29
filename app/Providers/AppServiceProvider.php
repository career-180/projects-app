<?php

namespace App\Providers;

use App\Models\Membership;
use App\Observers\MembershipSheetImportObserver;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Filament\Navigation\NavigationGroup;
use App\Models\MembershipSheetImport;
use App\Models\MemberWallet;
use App\Models\RefundForm;
use App\Observers\MembershipObserver;
use App\Observers\MemberWalletObserver;
use App\Observers\RefundFormObserver;
use App\Models\Member;
use App\Models\Permission;
use App\Observers\MemberObserver;
use Opcodes\LogViewer\Facades\LogViewer;
use App\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');
        });

        // Filament Navigation Groups
        Filament::registerNavigationGroups([
            NavigationGroup::make()
                ->label('اشتراكات الأعضاء')
                ->collapsed(),
            NavigationGroup::make()
                ->label('المذكرات')
                ->collapsed(),
            NavigationGroup::make()
                ->label('البيانات الأساسية')
                ->collapsed(),
            NavigationGroup::make()
                ->label('Authentication')
                ->collapsed(),
            NavigationGroup::make()
                ->label('Settings')
                ->collapsed(),
            NavigationGroup::make()
                ->label('System')
                ->collapsed(),
            NavigationGroup::make()
                ->label('إعدادات')
                ->collapsed(),
            NavigationGroup::make()
                ->label('إدارة النظام')
                ->collapsed(),
            NavigationGroup::make()
                ->label('الحساب')
                ->collapsed(),
        ]);

        Carbon::setWeekStartsAt(Carbon::SATURDAY);
        Carbon::setWeekEndsAt(Carbon::FRIDAY);

        // Macro on builder to use where like in shorter way in the code
        Builder::macro('whereLike', function (string $column, string $search) {
            return $this->orWhere($column, 'LIKE', '%'.$search.'%');
        });
        
        Builder::macro('orWhereLike', function (string $column, string $search) {
            return $this->orWhere($column, 'LIKE', '%'.$search.'%');
        });

        // Observers
        MembershipSheetImport::observe(MembershipSheetImportObserver::class);
        MemberWallet::observe(MemberWalletObserver::class);
        Membership::observe(MembershipObserver::class);
        RefundForm::observe(RefundFormObserver::class);

        // LogViewer::auth(function ($request) {
        //     return array_intersect( $request->user()->getRoleNames()->toArray(), [Role::ROOT_ADMIN, Role::SUPER_ADMIN]);
        // });

    }
}
