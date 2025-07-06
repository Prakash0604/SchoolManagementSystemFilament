<?php

namespace App\Filament\Pages;

use App\Models\SchoolInfo;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Models\Setting;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SiteSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    // protected static ?string $navigationGroup = 'Settings';
    protected static string $view = 'filament.pages.site-settings';

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole('super_admin');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            SchoolInfo::first()?->toArray() ?? []
        );
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([

                Group::make()->schema([
                    Forms\Components\TextInput::make('school_name')->label('School Name')->maxLength(255)->required(),
                    Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                ]),

                Group::make()->schema([
                    Forms\Components\TextInput::make('address')->required()->maxLength(255),
                    Forms\Components\TextInput::make('phone')->tel()->required(),
                ]),

                Forms\Components\FileUpload::make('logo')->columnSpanFull(),
                Forms\Components\TextInput::make('website')->columnSpanFull()->maxLength(255),
                Group::make()->schema([
                    Forms\Components\TextInput::make('slogan')->maxLength(255),
                ]),
                Group::make()->schema([
                    Forms\Components\ColorPicker::make('school_theme'),
                ])
            ]),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function submit(): void
    {
        SchoolInfo::updateOrCreate(
            ['id' => 1],
            $this->form->getState()
        );

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
