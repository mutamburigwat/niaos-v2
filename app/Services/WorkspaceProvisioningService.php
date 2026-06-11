<?php

namespace App\Services;

use App\Enums\WorkspaceRole;
use App\Enums\WorkspaceStatus;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Support\Facades\Hash;

class WorkspaceProvisioningService
{
    public function provision(array $data): array
    {
        $workspace = Workspace::create([
            'business_name' => $data['business_name'],
            'workspace_type' => $data['workspace_type'],
            'plan' => $data['plan'],
            'billing_status' => $data['billing_status'],
            'status' => WorkspaceStatus::Active,
            'created_by' => $data['created_by'],
        ]);

        $user = User::create([
            'name' => $data['owner_name'],
            'email' => $data['owner_email'],
            'password' => Hash::make($data['owner_password']),
        ]);

        WorkspaceMember::create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'role' => WorkspaceRole::Owner->value,
            'status' => 'active',
            'invited_by' => $data['created_by'],
            'joined_at' => now(),
        ]);

        $user->active_workspace_id = $workspace->id;
        $user->save();

        return [
            'workspace' => $workspace,
            'user' => $user,
            'workspace_name' => $workspace->business_name,
            'workspace_type' => $workspace->workspace_type->value,
            'plan' => $workspace->plan->value,
            'billing_status' => $workspace->billing_status->value,
            'owner_email' => $user->email,
            'temporary_password' => $data['owner_password'],
            'login_url' => url('/app'),
        ];
    }
}
