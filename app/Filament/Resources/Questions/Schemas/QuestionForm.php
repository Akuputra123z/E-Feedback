<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class QuestionForm
{
    private const TABLE = 'questions';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pertanyaan')
                    ->description('Kelola detail pertanyaan survei dan kategorinya.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        static::textField(),
                        static::dimensionField(),
                        static::sortOrderField(),
                        static::activeToggle(),
                    ])
                    ->columns(2),
            ]);
    }

    protected static function textField(): TextInput
    {
        return TextInput::make('text')
            ->label('Isi Pertanyaan')
            ->placeholder('Tuliskan bunyi pertanyaan di sini...')
            ->required()
            ->maxLength(255)
            ->columnSpanFull();
    }

    protected static function dimensionField(): Select
    {
        return Select::make('dimension')
            ->label('Dimensi / Kategori')
            ->options(static::dimensionOptions())
            ->required()
            ->searchable()
            ->preload()
            ->native(false)
            ->reactive(); // refresh validation saat berubah
    }

    protected static function sortOrderField(): TextInput
    {
        return TextInput::make('sort_order')
            ->label('Nomor Urut')
            ->numeric()
            ->default(1)
            ->required()
            ->minValue(1)
            ->maxValue(999)
            ->rule(fn (callable $get) => static::uniqueSortOrderRule($get))
            ->validationMessages([
                'unique' => 'Nomor urut ini sudah digunakan dalam kategori dimensi yang dipilih.',
            ]);
    }

    protected static function activeToggle(): Toggle
    {
        return Toggle::make('is_active')
            ->label('Status Aktif')
            ->helperText('Hanya pertanyaan aktif yang muncul di form publik.')
            ->default(true)
            ->dehydrated()
            ->required();
    }

    protected static function uniqueSortOrderRule(callable $get): Unique
    {
        return (new Unique(self::TABLE, 'sort_order'))
            ->where('dimension', $get('dimension'))
            ->ignore(request()->route('record'));
    }

    protected static function dimensionOptions(): array
    {
        return [
            'Materi'   => '📚 Materi Konsultasi',
            'Standar'  => '📋 Standar Pelayanan',
            'SDM'      => '👨‍💼 Profesionalisme SDM',
            'Dukungan' => '🛠️ Dukungan & Pengelolaan',
        ];
    }
}