<?php

namespace App\Filament\Pages;

use App\Exports\FinancialBranchVsDepartmentReportExport;
use App\Models\Department;
use App\Models\FinancialBranch;
use Filament\Pages\Page;
use App\Models\Member;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Filament\Pages\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Actions\Reports\GetFinancialBranchVsDepartmentExportAction;
use App\Models\Permission;


class NcoPensionFinancialBranchVsDepartmentReport extends Page
{
    // use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.nco-pension-financial-branch-vs-department-report';

    protected static ?string $navigationGroup = 'التقارير';

    protected static ?string $title = 'يومية عددية معاش - شرفيين';

    protected static array | string $middlewares = ['can:'.Permission::CAN_SEE_ON_PENSION_NCO_MEMBERS_REPORT];

    protected static ?int $navigationSort = 2;
    public $data;
    public $isNco = true;
    public $onPension = true;
    public function __construct()
    {
        $data = (new GetFinancialBranchVsDepartmentExportAction(isNco: $this->isNco, onPension: $this->onPension))->execute();
        $this->data = $data;
    }

    public function getActions(): array
    {
        return [
            Action::make('export')
                ->action(function() {
                    return Excel::download(new FinancialBranchVsDepartmentReportExport($this->isNco, $this->onPension), 'users.xlsx');
                })
            ];
    }
}
