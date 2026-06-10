<?php

namespace App\Console\Commands;

use App\Enums\BillingStatus;
use App\Enums\Plan;
use App\Enums\WorkspaceStatus;
use App\Enums\WorkspaceType;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EnsurePlatformAdmin extends Command
{
    protected $signature = 'niaos:ensure-platform-admin';
    protected $description = 'Ensure the platform admin user and workspace exist for development';

    private const ADMIN_EMAIL = 'tmutamburigwa@akudzwe.co.zw';
    private const ADMIN_PASSWORD = 'Admin123!';
    private const ADMIN_NAME = 'Takudzwa Mutamburigwa';
    private const WORKSPACE_NAME = 'Akudzwe Digital Partners';

    public function handle(): int
    {
        $report = [
            'workspace' => false,
            'user' => false,
            'password_valid' => false,
            'membership' => false,
            'active_workspace_set' => false,
        ];

        $workspace = Workspace::where('business_name', self::WORKSPACE_NAME)->first();
        if ($workspace) {
            $report['workspace'] = true;
            $this->line('  ✓ Workspace "' . self::WORKSPACE_NAME . '" already exists');
        } else {
            $workspace = Workspace::create([
                'business_name' => self::WORKSPACE_NAME,
                'business_type' => 'digital_agency',
                'status' => WorkspaceStatus::Active,
                'plan' => Plan::FreeInternal,
                'workspace_type' => WorkspaceType::Internal,
                'billing_status' => BillingStatus::Free,
                'base_currency' => 'USD',
                'timezone' => 'Africa/Harare',
            ]);
            $this->line('  ✓ Workspace "' . self::WORKSPACE_NAME . '" created');
        }

        $user = User::where('email', self::ADMIN_EMAIL)->first();
        if ($user) {
            $report['user'] = true;
            if ($user->name !== self::ADMIN_NAME) {
                $user->update(['name' => self::ADMIN_NAME]);
                $this->line('  ✓ User name updated to "' . self::ADMIN_NAME . '"');
            }
            $this->line('  ✓ User ' . self::ADMIN_EMAIL . ' already exists');
        } else {
            $user = User::create([
                'name' => self::ADMIN_NAME,
                'email' => self::ADMIN_EMAIL,
                'password' => Hash::make(self::ADMIN_PASSWORD),
                'is_platform_admin' => true,
            ]);
            $this->line('  ✓ User ' . self::ADMIN_EMAIL . ' created');
        }

        if (! $user->is_platform_admin) {
            $user->update(['is_platform_admin' => true]);
            $this->line('  ✓ User promoted to platform_admin');
        }

        if ($user->active_workspace_id !== $workspace->id) {
            $user->update(['active_workspace_id' => $workspace->id]);
            $report['active_workspace_set'] = true;
            $this->line('  ✓ active_workspace_id set to "' . self::WORKSPACE_NAME . '"');
        } else {
            $report['active_workspace_set'] = true;
            $this->line('  ✓ active_workspace_id already points to "' . self::WORKSPACE_NAME . '"');
        }

        if (! $workspace->creator) {
            $workspace->update(['created_by' => $user->id]);
            $this->line('  ✓ Workspace creator set');
        }

        if ($workspace->workspace_type !== WorkspaceType::Internal) {
            $workspace->update(['workspace_type' => WorkspaceType::Internal]);
            $this->line('  ✓ workspace_type set to internal');
        }

        if ($workspace->billing_status !== BillingStatus::Free) {
            $workspace->update(['billing_status' => BillingStatus::Free]);
            $this->line('  ✓ billing_status set to free');
        }

        if ($workspace->plan !== Plan::FreeInternal) {
            $workspace->update(['plan' => Plan::FreeInternal]);
            $this->line('  ✓ plan set to free_internal');
        }

        $membership = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', $user->id)
            ->first();

        if ($membership) {
            $report['membership'] = true;
            $this->line('  ✓ Membership record already exists');
        } else {
            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'status' => 'active',
                'joined_at' => now(),
            ]);
            $this->line('  ✓ Membership record created');
        }

        $report['password_valid'] = Hash::check(self::ADMIN_PASSWORD, $user->password);
        if ($report['password_valid']) {
            $this->line('  ✓ Password is valid');
        } else {
            $user->update(['password' => Hash::make(self::ADMIN_PASSWORD)]);
            $report['password_valid'] = true;
            $this->line('  ✓ Password reset to default');
        }

        $this->newLine();
        $this->line('── Report ──────────────────────────────');
        $this->line('  workspace exists:        ' . ($report['workspace'] ? 'yes' : 'created'));
        $this->line('  user exists:             ' . ($report['user'] ? 'yes' : 'created'));
        $this->line('  password valid:          ' . ($report['password_valid'] ? 'yes' : 'reset'));
        $this->line('  membership exists:       ' . ($report['membership'] ? 'yes' : 'created'));
        $this->line('  active workspace set:    ' . ($report['active_workspace_set'] ? 'yes' : 'no'));
        $this->newLine();
        $this->line('  Login: ' . self::ADMIN_EMAIL . ' / ' . self::ADMIN_PASSWORD);
        $this->newLine();

        return self::SUCCESS;
    }
}
