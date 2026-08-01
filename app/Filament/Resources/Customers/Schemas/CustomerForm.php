<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('surname')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefono')
                    ->tel()
                    ->maxLength(15),
                TextInput::make('dni')
                    ->label('DNI')
                    ->required()
                    ->maxLength(20),
            ]);
    }
}
