<?php

namespace App\Filament\Resources\SurveyResponses;

use App\Filament\Resources\SurveyResponses\Pages\CreateSurveyResponse;
use App\Filament\Resources\SurveyResponses\Pages\EditSurveyResponse;
use App\Filament\Resources\SurveyResponses\Pages\ViewSurveyResponses;
use App\Filament\Resources\SurveyResponses\Pages\ListSurveyResponses;
use App\Filament\Resources\SurveyResponses\Schemas\SurveyResponseForm;
use App\Filament\Resources\SurveyResponses\Tables\SurveyResponsesTable;
use App\Models\SurveyResponse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\SurveyAnswers\Schemas\SurveyAnswerInfolist;
use Filament\Tables\Table;
use App\Filament\Resources\SurveyResponses\Schemas\SurveyResponseInfolist;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyResponseResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'SureveyResponse';

    public static function infolist(Schema $schema): Schema
{
    // Memanggil konfigurasi dari kelas yang baru dibuat
    return SurveyResponseInfolist::configure($schema);
}


public static function getEloquentQuery(): Builder
{
    $user = auth()->user();
    $query = parent::getEloquentQuery();

    // Debugging: Jika Anda ragu, coba log role user ke file laravel.log
    // \Log::info('User Role: ' . $user->role); 

    // 1. Jika User adalah Admin, berikan SEMUA data tanpa filter
    if ($user->hasRole('super_admin') || $user->is_admin === true) { 
        return $query;
    }

    // 2. Jika bukan admin, baru batasi berdasarkan unit irban-nya
    return $query->where('irban', $user->irban_type);
}

    public static function form(Schema $schema): Schema
    {
        return SurveyResponseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveyResponsesTable::configure($table);
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
            'index' => ListSurveyResponses::route('/'),
            // 'view' => ViewSurveyResponses::route('/{record}'),
            // 'create' => CreateSurveyResponse::route('/create'),
            // 'edit' => EditSurveyResponse::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
