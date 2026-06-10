<?php

namespace Tests\Feature;

use App\Enums\Plan;
use App\Enums\WorkspaceStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\Workspace;
use App\Services\WorkspaceContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaaSTenantManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_admin_can_view_all_workspaces(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'is_platform_admin' => true]);
        $ws1 = Workspace::create(['business_name' => 'WS1', 'plan' => Plan::Starter, 'status' => WorkspaceStatus::Active]);
        $ws2 = Workspace::create(['business_name' => 'WS2', 'plan' => Plan::Growth, 'status' => WorkspaceStatus::Active]);

        $this->actingAs($admin);

        $workspaces = Workspace::query()->get();
        $this->assertCount(2, $workspaces);
    }

    public function test_workspace_user_only_sees_their_workspaces(): void
    {
        $user = User::create(['name' => 'User', 'email' => 'user@test.com', 'password' => bcrypt('password')]);
        $ws1 = Workspace::create(['business_name' => 'WS1']);
        $ws2 = Workspace::create(['business_name' => 'WS2']);
        $ws3 = Workspace::create(['business_name' => 'WS3']);

        $user->workspaces()->attach($ws1->id, ['role' => 'owner', 'status' => 'active']);
        $user->workspaces()->attach($ws2->id, ['role' => 'member', 'status' => 'active']);

        $this->actingAs($user);

        $workspaces = $user->workspaces;
        $this->assertCount(2, $workspaces);
        $this->assertTrue($workspaces->contains('id', $ws1->id));
        $this->assertTrue($workspaces->contains('id', $ws2->id));
        $this->assertFalse($workspaces->contains('id', $ws3->id));
    }

    public function test_workspace_has_status_and_plan_defaults(): void
    {
        $ws = Workspace::create(['business_name' => 'Test']);
        $ws = $ws->fresh();
        $this->assertEquals('onboarding', $ws->status->value);
        $this->assertEquals('starter', $ws->plan->value);
    }

    public function test_suspended_workspace_is_detected(): void
    {
        $user = User::create(['name' => 'User', 'email' => 'u@test.com', 'password' => bcrypt('password')]);
        $ws = Workspace::create(['business_name' => 'Suspended WS', 'status' => WorkspaceStatus::Suspended]);
        $user->workspaces()->attach($ws->id, ['role' => 'member', 'status' => 'active']);
        $user->active_workspace_id = $ws->id;
        $user->save();

        $this->actingAs($user);

        $this->assertTrue(WorkspaceContext::activeWorkspaceIsSuspended());
    }

    public function test_active_workspace_allows_access(): void
    {
        $user = User::create(['name' => 'User', 'email' => 'u2@test.com', 'password' => bcrypt('password')]);
        $ws = Workspace::create(['business_name' => 'Active WS', 'status' => WorkspaceStatus::Active]);
        $user->workspaces()->attach($ws->id, ['role' => 'member', 'status' => 'active']);
        $user->active_workspace_id = $ws->id;
        $user->save();

        $this->actingAs($user);

        $this->assertFalse(WorkspaceContext::activeWorkspaceIsSuspended());
        $this->assertEquals($ws->id, WorkspaceContext::activeWorkspaceId());
    }

    public function test_workspace_creation_sets_owner_and_active_workspace(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'a@test.com', 'password' => bcrypt('password'), 'is_platform_admin' => true]);
        $this->actingAs($admin);

        $ws = Workspace::create([
            'business_name' => 'New WS',
            'plan' => Plan::Growth,
            'status' => WorkspaceStatus::Active,
            'created_by' => $admin->id,
        ]);

        $owner = User::create(['name' => 'Owner', 'email' => 'o@test.com', 'password' => bcrypt('password')]);
        $ws->members()->create([
            'user_id' => $owner->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);
        $owner->active_workspace_id = $ws->id;
        $owner->save();

        $this->assertEquals('New WS', $ws->business_name);
        $this->assertEquals('growth', $ws->plan->value);
        $this->assertEquals('active', $ws->status->value);
        $this->assertCount(1, $ws->members);
        $this->assertEquals($owner->id, $ws->members->first()->user_id);
        $this->assertEquals($ws->id, $owner->fresh()->active_workspace_id);
    }

    public function test_platform_admin_detection(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin2@test.com', 'password' => bcrypt('password'), 'is_platform_admin' => true]);
        $user = User::create(['name' => 'User', 'email' => 'user2@test.com', 'password' => bcrypt('password'), 'is_platform_admin' => false]);

        $this->assertTrue($admin->isPlatformAdmin());
        $this->assertFalse($user->isPlatformAdmin());
    }

    public function test_workspace_scoped_policy_denies_for_non_member(): void
    {
        $user = User::create(['name' => 'User', 'email' => 'u3@test.com', 'password' => bcrypt('password')]);
        $ws = Workspace::create(['business_name' => 'WS', 'status' => WorkspaceStatus::Active]);
        $user->workspaces()->attach($ws->id, ['role' => 'member', 'status' => 'active']);
        $user->active_workspace_id = $ws->id;
        $user->save();

        $otherWs = Workspace::create(['business_name' => 'Other WS']);

        $this->actingAs($user);

        $membership = $user->workspaces()->where('workspace_id', $otherWs->id)->exists();
        $this->assertFalse($membership);
    }
}
