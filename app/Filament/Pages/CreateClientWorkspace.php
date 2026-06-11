<?php

namespace App\Filament\Pages;

use App\Services\WorkspaceProvisioningService;
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
    public ?string $plan = 'starter';
    public ?string $billing_status = 'trial';
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

    public function form(Schema $schema): Schema
    {
        return $schema
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
                    ->options([
                        'starter' => 'Starter',
                        'growth' => 'Growth',
                        'business' => 'Business',
                        'custom' => 'Custom',
                    ])
                    ->default('starter')
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
                    ->default('trial')
                    ->required(),
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
                    ->minLength(8),
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
        $this->plan = 'starter';
        $this->billing_status = 'trial';
    }
}
