<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $title = 'Home';

    protected static ?string $navigationLabel = 'Home';

    public function getHeading(): string
    {
        return __('Welcome, '.auth()->user()->name.'!');
    }
}
