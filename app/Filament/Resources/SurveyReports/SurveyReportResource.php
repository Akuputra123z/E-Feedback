<?php

namespace App\Filament\Resources\SurveyReports;

use App\Filament\Resources\SurveyReports\Pages\CreateSurveyReport;
use App\Filament\Resources\SurveyReports\Pages\EditSurveyReport;
use App\Filament\Resources\SurveyReports\Pages\ListSurveyReports;
use App\Filament\Resources\SurveyReports\Schemas\SurveyReportForm;
use App\Filament\Resources\SurveyReports\Tables\SurveyReportsTable;
use App\Models\SurveyResponse;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyReportResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'SurveyResponse';
    

    protected static string | UnitEnum | null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Laporan';

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
        return SurveyReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveyReportsTable::configure($table);
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
            'index' => ListSurveyReports::route('/'),
            // 'create' => CreateSurveyReport::route('/create'),
            // 'edit' => EditSurveyReport::route('/{record}/edit'),
        ];
    }
   public static function canView(Model $record): bool
{
    $user = auth()->user();

    if (!$user) return false;

    // Admin bebas akses
    if ($user->role === 'admin' || $user->is_admin === true) {
        return true;
    }

    // Irban hanya bisa melihat jika nilai kolom 'irban' sama dengan milik user
    return $record->irban === $user->irban;
}

 

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


}
