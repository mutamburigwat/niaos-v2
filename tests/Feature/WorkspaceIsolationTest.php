<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use App\Models\Workspace;
use App\Services\WorkspaceContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_context_returns_null_when_unauthenticated(): void
    {
        $this->assertNull(WorkspaceContext::activeWorkspaceId());
    }

    public function test_workspace_context_returns_active_workspace_id(): void
    {
        $workspace = Workspace::create(['business_name' => 'Test Workspace', 'subscription_plan' => 'free']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'active_workspace_id' => $workspace->id,
        ]);

        $this->actingAs($user);

        $this->assertEquals($workspace->id, WorkspaceContext::activeWorkspaceId());
    }

    public function test_user_belongs_to_workspace_check(): void
    {
        $workspace = Workspace::create(['business_name' => 'Workspace A', 'subscription_plan' => 'free']);
        $otherWorkspace = Workspace::create(['business_name' => 'Workspace B', 'subscription_plan' => 'free']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->workspaces()->attach($workspace->id, ['role' => 'member', 'status' => 'active']);

        $this->actingAs($user);

        $this->assertTrue(WorkspaceContext::userBelongsToWorkspace($workspace->id));
        $this->assertFalse(WorkspaceContext::userBelongsToWorkspace($otherWorkspace->id));
    }

    public function test_scope_only_shows_current_workspace_records(): void
    {
        $workspaceA = Workspace::create(['business_name' => 'Workspace A', 'subscription_plan' => 'free']);
        $workspaceB = Workspace::create(['business_name' => 'Workspace B', 'subscription_plan' => 'free']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
            'active_workspace_id' => $workspaceA->id,
        ]);

        $user->workspaces()->attach($workspaceA->id, ['role' => 'owner', 'status' => 'active']);
        $user->workspaces()->attach($workspaceB->id, ['role' => 'member', 'status' => 'active']);

        Customer::create(['workspace_id' => $workspaceA->id, 'name' => 'Record A', 'phone' => '111']);
        Customer::create(['workspace_id' => $workspaceB->id, 'name' => 'Record B', 'phone' => '222']);

        $this->actingAs($user);

        $customers = Customer::query()->currentWorkspace()->get();

        $this->assertCount(1, $customers);
        $this->assertEquals('Record A', $customers->first()->name);
    }

    public function test_scope_shows_all_when_no_active_workspace(): void
    {
        $workspaceA = Workspace::create(['business_name' => 'Workspace A', 'subscription_plan' => 'free']);
        $workspaceB = Workspace::create(['business_name' => 'Workspace B', 'subscription_plan' => 'free']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test4@example.com',
            'password' => bcrypt('password'),
            'active_workspace_id' => null,
        ]);

        Customer::create(['workspace_id' => $workspaceA->id, 'name' => 'Record A', 'phone' => '111']);
        Customer::create(['workspace_id' => $workspaceB->id, 'name' => 'Record B', 'phone' => '222']);

        $this->actingAs($user);

        $customers = Customer::query()->currentWorkspace()->get();

        $this->assertCount(2, $customers);
    }

    public function test_invalid_active_workspace_is_cleared(): void
    {
        $workspace = Workspace::create(['business_name' => 'Workspace', 'subscription_plan' => 'free']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test5@example.com',
            'password' => bcrypt('password'),
            'active_workspace_id' => $workspace->id,
        ]);

        $this->actingAs($user);

        $this->assertNotNull($user->active_workspace_id);

        $this->assertFalse(WorkspaceContext::userBelongsToWorkspace($user->active_workspace_id));
    }
}
