<?php

namespace App\Filament\Pages;

use App\Models\Plan;
use App\Models\PlatformSetting;
use App\Services\WorkspaceProvisioningService;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CreateClientWorkspace extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-sparkles';
    protected string $view = 'filament.pages.create-client-workspace';
    protected static ?string $slug = 'create-client-workspace';
    protected static ?string $title = 'Create Client Workspace';

    public ?string $business_name = null;
    public ?string $workspace_type = 'paid_client';
    public ?string $plan = null;
    public ?string $billing_status = null;
    public ?string $owner_name = null;
    public ?string $owner_email = null;
    public ?string $owner_password = null;

    public bool $created = false;
    public ?array $summary = null;

    public static function canAccess(): bool
    {
        return Auth::user()?->isPlatformAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $this->plan = PlatformSetting::getValue('default_paid_client_plan', 'starter');
        $this->billing_status = PlatformSetting::getValue('default_paid_client_billing_status', 'trial');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Workspace Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('business_name')
                            ->label('Business / Workspace Name')
                            ->required()
                            ->maxLength(255),
                        Select::make('workspace_type')
                            ->label('Workspace Type')
                            ->options([
                                'paid_client' => 'Paid Client',
                                'demo' => 'Demo',
                                'partner' => 'Partner',
                                'internal' => 'Internal',
                            ])
                            ->default('paid_client')
                            ->required(),
                        Select::make('plan')
                            ->label('Plan')
                            ->options(fn () => Plan::active()->ordered()->pluck('name', 'key'))
                            ->default(fn () => PlatformSetting::getValue('default_paid_client_plan', 'starter'))
                            ->required(),
                        Select::make('billing_status')
                            ->label('Billing Status')
                            ->options([
                                'trial' => 'Trial',
                                'active' => 'Active',
                                'overdue' => 'Overdue',
                                'suspended' => 'Suspended',
                                'cancelled' => 'Cancelled',
                                'free' => 'Free',
                            ])
                            ->default(fn () => PlatformSetting::getValue('default_paid_client_billing_status', 'trial'))
                            ->required(),
                    ]),
                Section::make('Owner Account')
                    ->description('The workspace owner will receive login credentials for this account.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('owner_name')
                            ->label('Owner Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('owner_email')
                            ->label('Owner Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique('users', 'email', ignoreRecord: true),
                        TextInput::make('owner_password')
                            ->label('Temporary Password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function create(): void
    {
        $summary = app(WorkspaceProvisioningService::class)->provision([
            'business_name' => $this->business_name,
            'workspace_type' => $this->workspace_type,
            'plan' => $this->plan,
            'billing_status' => $this->billing_status,
            'owner_name' => $this->owner_name,
            'owner_email' => $this->owner_email,
            'owner_password' => $this->owner_password,
            'created_by' => Auth::id(),
        ]);

        $this->summary = $summary;
        $this->created = true;
    }

    public function resetForm(): void
    {
        $this->reset([
            'business_name', 'workspace_type', 'plan', 'billing_status',
            'owner_name', 'owner_email', 'owner_password',
            'created', 'summary',
        ]);
        $this->workspace_type = 'paid_client';
        $this->plan = PlatformSetting::getValue('default_paid_client_plan', 'starter');
        $this->billing_status = PlatformSetting::getValue('default_paid_client_billing_status', 'trial');
    }
}
