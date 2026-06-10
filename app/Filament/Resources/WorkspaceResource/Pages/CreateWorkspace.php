<?php

namespace App\Filament\Resources\WorkspaceResource\Pages;

use App\Enums\WorkspaceRole;
use App\Enums\WorkspaceStatus;
use App\Filament\Resources\WorkspaceResource;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class CreateWorkspace extends CreateRecord
{
    protected static string $resource = WorkspaceResource::class;

    public ?string $owner_option = 'create_new';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Business Information')
                    ->schema([
                        Forms\Components\TextInput::make('business_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_type')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('industry')
                            ->maxLength(255),
                        Forms\Components\Select::make('plan')
                            ->options([
                                'starter' => 'Starter',
                                'growth' => 'Growth',
                                'business' => 'Business',
                                'custom' => 'Custom',
                            ])
                            ->default('starter'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'onboarding' => 'Onboarding',
                                'suspended' => 'Suspended',
                            ])
                            ->default('onboarding'),
                    ])->columns(2),
                Schemas\Components\Section::make('Owner Account')
                    ->description('Create or select the workspace owner')
                    ->schema([
                        Forms\Components\Radio::make('owner_option')
                            ->label('Owner Type')
                            ->options([
                                'create_new' => 'Create New User',
                                'select_existing' => 'Select Existing User',
                            ])
                            ->default('create_new')
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('owner_user_id', null)),
                        Forms\Components\TextInput::make('owner_name')
                            ->label('Owner Name')
                            ->required(fn (callable $get) => $get('owner_option') === 'create_new')
                            ->visible(fn (callable $get) => $get('owner_option') === 'create_new')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('owner_email')
                            ->label('Owner Email')
                            ->email()
                            ->required(fn (callable $get) => $get('owner_option') === 'create_new')
                            ->visible(fn (callable $get) => $get('owner_option') === 'create_new')
                            ->unique('users', 'email', ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('owner_password')
                            ->label('Owner Password')
                            ->password()
                            ->required(fn (callable $get) => $get('owner_option') === 'create_new')
                            ->visible(fn (callable $get) => $get('owner_option') === 'create_new')
                            ->minLength(8),
                        Forms\Components\Select::make('owner_user_id')
                            ->label('Select Existing User')
                            ->options(User::where('is_platform_admin', false)->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required(fn (callable $get) => $get('owner_option') === 'select_existing')
                            ->visible(fn (callable $get) => $get('owner_option') === 'select_existing'),
                    ]),
            ]);
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();

        if (($data['owner_option'] ?? 'create_new') === 'create_new') {
            $owner = User::create([
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'password' => Hash::make($data['owner_password']),
            ]);
        } else {
            $owner = User::find($data['owner_user_id']);
        }

        $this->record->members()->create([
            'user_id' => $owner->id,
            'role' => WorkspaceRole::Owner->value,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $owner->active_workspace_id = $this->record->id;
        $owner->save();
    }
}
