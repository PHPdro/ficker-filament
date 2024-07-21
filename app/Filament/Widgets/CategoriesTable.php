<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CategoryResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Forms;

class CategoriesTable extends BaseWidget
{
    protected static ?string $heading = 'Categories';
    
    protected int | string | array $columnSpan = '1';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(CategoryResource::getEloquentQuery())
            ->striped()
            ->paginated([4, 8, 12, 'all'])
            ->queryStringIdentifier('categories')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\ColorPicker::make('color')
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('description')
                            ->columnSpan(1)
                    ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->link(),
            ])
            ->headerActions([
                    Tables\Actions\CreateAction::make()
                        ->form([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\ColorPicker::make('color'),
                            Forms\Components\Textarea::make('description')
                        ])
            ]);
    }
}
