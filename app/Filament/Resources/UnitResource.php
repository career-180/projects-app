<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Filament\Resources\UnitResource\RelationManagers;
use App\Filament\Resources\UnitResource\RelationManagers\MembersRelationManager;
use App\Models\FinancialBranch;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Forms\Components\Card;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'الوحدات ';
    protected static ?string $label = 'الوحدات ';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('code')
                        ->label('الكود')->required()->maxLength(255),
                    TextInput::make('name')
                        ->label('الاسم')->required()->maxLength(255),
                    Select::make('financial_branch_id')
                        ->label('الفرع المالي')
                        ->options(FinancialBranch::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('id'),
                TextColumn::make('financialBranch.name')
                    ->searchable()
                    ->sortable()
                    ->label('الفرع المالي'),
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->label('الكود'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('الاسم'),
                TextColumn::make('members_count')
                    ->label('عدد الأعضاء')
                    ->counts('members')
                    ->sortable(),
                TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()->fromTable()->except([
                        '#'
                    ]),
                ])
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            MembersRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'view' => Pages\ViewUnit::route('/{record}'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }    


    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }

    public static function canEdit(Model $record): bool 
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }
    
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }
}
