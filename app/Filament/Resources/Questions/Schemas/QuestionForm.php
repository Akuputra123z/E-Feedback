<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pertanyaan')
                    ->description('Kelola detail pertanyaan survei dan kategorinya.')
                    ->schema([
                        TextInput::make('text')
                            ->label('Isi Pertanyaan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('dimension')
                            ->label('Dimensi / Kategori')
                            ->options([
                                'Materi' => 'Materi Konsultasi',
                                'Standar' => 'Standar Pelayanan',
                                'SDM' => 'Profesionalisme SDM',
                                'Dukungan' => 'Dukungan & Pengelolaan',
                            ])
                            ->required()
                            ->searchable()
                            ->native(false),

                        TextInput::make('sort_order')
                            ->label('Nomor Urut')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
