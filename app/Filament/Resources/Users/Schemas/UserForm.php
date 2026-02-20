<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),

                DateTimePicker::make('email_verified_at'),

                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state)) // supaya tidak override password saat edit
                    ->required(fn ($context) => $context === 'create'),

                // ✅ ROLE FIX untuk Filament Shield
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->required(),

                TextInput::make('irban_type'),
            ]);
    }
}
