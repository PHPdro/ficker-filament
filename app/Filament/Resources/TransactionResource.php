<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Category;
use App\Models\Card;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ToggleButtons::make('type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense'
                    ])
                    ->icons([
                        'income' => 'heroicon-o-arrow-up-circle',
                        'expense' => 'heroicon-o-arrow-down-circle',
                    ])
                    ->colors([
                        'income' => 'success',
                        'expense' => 'danger',
                    ])
                    ->required()
                    ->inline(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->createOptionModalHeading('Create category')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                    ])
                    ->required()
                    ->searchable()
                    ->native(false),
                Forms\Components\Select::make('card_id')
                    ->relationship('card', 'description')
                    ->options(Card::all()->pluck('description', 'id'))
                    ->searchable()
                    ->native(false)
                    ->prefixIcon('heroicon-o-credit-card'),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn(Transaction $record) => match ($record->type) {
                'income' => null,
                'expense' => 'border-l-4 bg-danger-50 !border-l-danger-500 dark:bg-gray-800',
            })
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\IconColumn::make('type')
                    ->icon(fn (string $state): string => match ($state) {
                        'income' => 'heroicon-o-arrow-up-circle',
                        'expense' => 'heroicon-o-arrow-down-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('Type')
                ->options([
                    'income' => 'Income',
                    'expense' => 'Expense'
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->link(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTransactions::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TransactionResource\Widgets\TransactionOverview::class,
        ];
    }
}
