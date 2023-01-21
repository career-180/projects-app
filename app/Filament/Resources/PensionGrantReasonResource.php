<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PensionGrantReasonResource\Pages;
use App\Filament\Resources\PensionGrantReasonResource\RelationManagers;
use App\Models\PensionGrantReason;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use stdClass;

class PensionGrantReasonResource extends Resource
{
    protected static ?string $model = PensionGrantReason::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'قائمة أسباب منحة الزمالة ';
    protected static ?string $label = 'قائمة أسباب منحة الزمالة ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('reason')
                    ->label('السبب')->required()->maxLength(255)
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
                TextColumn::make('reason')->label('السبب'),
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
            ])->defaultSort('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPensionGrantReasons::route('/'),
            'create' => Pages\CreatePensionGrantReason::route('/create'),
            'view' => Pages\ViewPensionGrantReason::route('/{record}'),
            'edit' => Pages\EditPensionGrantReason::route('/{record}/edit'),
        ];
    }    
}