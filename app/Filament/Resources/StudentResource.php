<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Infolist;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Academic Setup';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Student Information')
                    ->schema([
                        TextInput::make('full_name')->required()->maxLength(length: 5255),
                        Select::make('gender')
                            ->required()
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Other' => 'Other',
                            ]),
                        DatePicker::make('date_of_birth')->required(),
                        DatePicker::make('admission_date')->required(),
                        TextInput::make('nationality')->required(),
                        TextInput::make('previous_school')->maxLength(255),
                        FileUpload::make('profile')->columnSpanFull()->disk('public')->directory('uploads/students')->image()->imageEditor(),
                    ])->columns(2),

                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('email')->email()->required(),
                        TextInput::make('phone')->tel()->required(),
                        Textarea::make('address')->required()->columnSpanFull(),
                        TextInput::make('city')->required(),
                        TextInput::make('country')->required(),
                    ])->columns(2),


                Section::make('Academic Information')
                    ->schema([
                        Select::make('academic.academic_year_id')
                            ->label('Academic Year')
                            ->relationship('academic.academicYear', 'name')
                            ->required()
                            ->reactive(),

                        Select::make('academic.grade_id')
                            ->label('Grade')
                            ->relationship('academic.grade', 'name')
                            ->required()
                            ->reactive(),

                        Select::make('academic.section_id')
                            ->label('Section')
                            ->options(function (callable $get) {
                                return \App\Models\Section::where('academic_year_id', $get('academic.academic_year_id'))
                                    ->where('grade_id', $get('academic.grade_id'))
                                    ->pluck('name', 'id');
                            })
                            ->required()
                            ->disabled(fn(callable $get) => !$get('academic.academic_year_id') || !$get('academic.grade_id')),
                    ])->columns(3),

                Section::make('Parent / Guardian Information')
                    ->schema([
                        TextInput::make('parent.parent_name')->label('Guardian Name')->required(),
                        TextInput::make('parent.parent_relation')->label('Relation')->required(),
                        TextInput::make('parent.parent_phone')->label('Guardian Phone')->required(),
                        TextInput::make('parent.parent_email')->label('Guardian Email')->email(),
                    ])->columns(2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile')->label('Profile Image')->circular()->size(40),
                TextColumn::make('full_name')->label('Full name')->searchable(),
                TextColumn::make('academic.academicYear.name')->label('Academic Year'),
                TextColumn::make('academic.grade.name')->label('Grade name'),
                TextColumn::make('academic.section.name')->label('Section Name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Student Info
                ComponentsSection::make('Student Information')
                    ->schema([
                        ImageEntry::make('profile')->label('Photo'),
                        TextEntry::make('full_name'),
                        TextEntry::make('admission_date'),
                        TextEntry::make('gender'),
                        TextEntry::make('date_of_birth')->date(),
                        TextEntry::make('nationality'),
                        TextEntry::make('email'),
                        TextEntry::make('phone'),
                        TextEntry::make('address')->columnSpanFull(),
                        TextEntry::make('previous_school'),
                        TextEntry::make('city'),
                        TextEntry::make('country'),
                    ])
                    ->columns(2),

                // Parent Info
                ComponentsSection::make('Parent / Guardian Information')
                    ->schema([
                        TextEntry::make('parent.parent_name'),
                        TextEntry::make('parent.parent_relation'),
                        TextEntry::make('parent.parent_phone'),
                        TextEntry::make('parent.parent_email'),
                    ])
                    ->columns(2),

                // Academic Info
                ComponentsSection::make('Academic Information')
                    ->schema([
                        TextEntry::make('academic.academicYear.name')->label('Academic Year'),
                        TextEntry::make('academic.grade.name')->label('Grade'),
                        TextEntry::make('academic.section.name')->label('Section'),
                    ])
                    ->columns(2),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}'),
        ];
    }
}
