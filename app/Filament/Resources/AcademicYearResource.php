<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicYearResource\Pages;
use App\Filament\Resources\AcademicYearResource\RelationManagers;
use App\Models\AcademicYear;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;
    protected static ?string $navigationGroup = 'Academic Setup';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

      public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->columnSpanFull()->label('Academic Year')->unique()->required()->maxLength(255),
                DatePicker::make('start_date')->label('Starting Date')->required(),
                DatePicker::make('end_date')->label('Ending Date')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('start_date'),
                TextColumn::make('end_date'),
                ToggleColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->status === 'Active')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->update([
                            'status' => $state ? 'Active' : 'Inactive',
                        ]);
                    })
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->onColor('success')
                    ->offColor('danger')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalHeading('Edit Category')
                    ->modalSubmitActionLabel('Save Changes'),
            ])
             ->headerActions([
                 Tables\Actions\CreateAction::make()
                    ->modalHeading('Add Academic Year')
                    ->modalSubmitActionLabel('Create Academic Year'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListAcademicYears::route('/'),
            // 'create' => Pages\CreateAcademicYear::route('/create'),
            // 'edit' => Pages\EditAcademicYear::route('/{record}/edit'),
        ];
    }
}
