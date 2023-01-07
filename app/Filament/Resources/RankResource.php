<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RankResource\Pages;
use App\Filament\Resources\RankResource\RelationManagers;
use App\Models\Rank;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;

class RankResource extends Resource
{
    protected static ?string $model = Rank::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'الرتب و الدرجات ';
    protected static ?string $label = 'الرتب و الدرجات ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                    ->label('الاسم')->required()->maxLength(255)
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
                TextColumn::make('name')->label('الاسم'),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListRanks::route('/'),
            'create' => Pages\CreateRank::route('/create'),
            'edit' => Pages\EditRank::route('/{record}/edit'),
        ];
    }    
}
