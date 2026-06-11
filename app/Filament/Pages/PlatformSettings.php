<?php

namespace App\Filament\Pages;

use App\Models\Plan;
use App\Models\PlatformSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PlatformSettings extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.pages.platform-settings';
    protected static ?string $slug = 'settings';
    protected static ?string $title = 'Settings';
    protected static ?string $navigationLabel = 'Settings';
    protected static string | \UnitEnum | null $navigationGroup = 'Platform';

    public ?string $platform_name = null;
    public ?string $default_paid_client_plan = null;
    public ?string $default_paid_client_billing_status = null;
    public ?string $support_contact = null;
    public ?string $system_notes = null;

    public static function canAccess(): bool
    {
        return Auth::user()?->isPlatformAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function mount(): void
    {
        $this->platform_name = PlatformSetting::getValue('platform_name', 'NiaOS');
        $this->default_paid_client_plan = PlatformSetting::getValue('default_paid_client_plan', 'starter');
        $this->default_paid_client_billing_status = PlatformSetting::getValue('default_paid_client_billing_status', 'trial');
        $this->support_contact = PlatformSetting::getValue('support_contact', '');
        $this->system_notes = PlatformSetting::getValue('system_notes', '');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('General')
                    ->description('Platform-wide identity and display settings.')
                    ->schema([
                        TextInput::make('platform_name')
                            ->label('Platform Name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make('Defaults')
                    ->description('Default values applied when onboarding new client workspaces.')
                    ->columns(2)
                    ->schema([
                        Select::make('default_paid_client_plan')
                            ->label('Default Plan')
                            ->options(fn () => Plan::active()->ordered()->pluck('name', 'key'))
                            ->required(),
                        Select::make('default_paid_client_billing_status')
                            ->label('Default Billing Status')
                            ->options([
                                'trial' => 'Trial',
                                'active' => 'Active',
                                'overdue' => 'Overdue',
                                'suspended' => 'Suspended',
                                'cancelled' => 'Cancelled',
                                'free' => 'Free',
                            ])
                            ->required(),
                    ]),
                Section::make('Support')
                    ->description('Contact information displayed to workspace users.')
                    ->schema([
                        TextInput::make('support_contact')
                            ->label('Support Contact')
                            ->maxLength(255),
                    ]),
                Section::make('Notes')
                    ->description('Internal notes visible only to platform administrators.')
                    ->schema([
                        Textarea::make('system_notes')
                            ->label('System Notes')
                            ->rows(4),
                    ]),
            ]);
    }

    public function save(): void
    {
        $this->validate();

        PlatformSetting::setValue('platform_name', $this->platform_name, 'string', 'general');
        PlatformSetting::setValue('default_paid_client_plan', $this->default_paid_client_plan, 'string', 'onboarding');
        PlatformSetting::setValue('default_paid_client_billing_status', $this->default_paid_client_billing_status, 'string', 'onboarding');
        PlatformSetting::setValue('support_contact', $this->support_contact, 'string', 'contact');
        PlatformSetting::setValue('system_notes', $this->system_notes, 'text', 'system');

        $this->sendSuccessNotification();
    }

    protected function sendSuccessNotification(): void
    {
        $this->dispatch('notify', type: 'success', message: 'Platform settings saved successfully.');
    }
}
