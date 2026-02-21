<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique; // Penting untuk validasi custom

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pertanyaan')
                    ->description('Kelola detail pertanyaan survei dan kategorinya.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        TextInput::make('text')
                            ->label('Isi Pertanyaan')
                            ->placeholder('Tuliskan bunyi pertanyaan di sini...')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('dimension')
                            ->label('Dimensi / Kategori')
                            ->options(static::getDimensionOptions())
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->live(), // Menjadikan field ini reaktif agar validasi sort_order terupdate otomatis

                        TextInput::make('sort_order')
                            ->label('Nomor Urut')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(1)
                            /** * Logika Unique Bersyarat:
                             * Nomor urut unik hanya jika dimensinya sama.
                             */
                            ->unique(
                                table: 'questions', 
                                column: 'sort_order', 
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where('dimension', $get('dimension'));
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Nomor urut ini sudah digunakan dalam kategori dimensi yang dipilih.',
                            ]),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Hanya pertanyaan aktif yang muncul di form publik.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getDimensionOptions(): array
    {
        return [
            'Materi'   => '📚 Materi Konsultasi',
            'Standar'  => '📋 Standar Pelayanan',
            'SDM'      => '👨‍💼 Profesionalisme SDM',
            'Dukungan' => '🛠️ Dukungan & Pengelolaan',
        ];
    }
}