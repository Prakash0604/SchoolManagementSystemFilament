<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages;
use App\Filament\Resources\SectionResource\RelationManagers;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;
    protected static ?string $navigationGroup = 'Academic Setup';

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

      public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Section Name')->required()->maxLength(255),
                Select::make('grade_id')->relationship('grade','name')->searchable()->preload()->createOptionForm([
                    TextInput::make('name')->required()->unique()->maxLength(255)->label('Grade Name'),
                ])->required(),
                Select::make('academic_year_id')->relationship('academicYear','name')->searchable()->preload()->createOptionForm([
                    TextInput::make('name')->required()->unique()->maxLength(255)->label('AcademicYear Name'),
                    DatePicker::make('start_date')->required(),
                    DatePicker::make('end_date')->required(),
                ])->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('academicYear.name')->searchable(),
                TextColumn::make('grade.name')->searchable(),
                TextColumn::make('name')->searchable(),
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
                SelectFilter::make('academic_year_id')->relationship('academicYear','name'),
            ])
            ->actions([
                 Tables\Actions\EditAction::make()->modalHeading('Edit Section')
                    ->modalSubmitActionLabel('Save Changes'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Add Section')
                    ->modalSubmitActionLabel('Create Section'),
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
            'index' => Pages\ListSections::route('/'),
            // 'create' => Pages\CreateSection::route('/create'),
            // 'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
