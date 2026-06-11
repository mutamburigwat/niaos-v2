<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlansAndPlatformSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPlans();
        $this->seedPlatformSettings();
    }

    protected function seedPlans(): void
    {
        $plans = [
            [
                'key' => 'free_internal',
                'name' => 'Free Internal',
                'description' => 'For internal use by Akudzwe Digital Partners and NiaOS operations. No billing.',
                'price_amount' => null,
                'currency' => 'USD',
                'billing_interval' => 'none',
                'is_active' => true,
                'sort_order' => 0,
                'features' => [
                    'customers' => 'Customer management',
                    'leads' => 'Lead tracking',
                    'tasks' => 'Task management',
                    'quotations' => 'Quotations',
                    'files' => 'File storage',
                    'activity' => 'Activity log',
                ],
                'limits' => [
                    'max_users' => -1,
                    'max_customers' => -1,
                    'max_leads' => -1,
                    'max_tasks' => -1,
                    'max_quotations' => -1,
                ],
            ],
            [
                'key' => 'starter',
                'name' => 'Starter',
                'description' => 'Entry-level plan for small businesses getting started with NiaOS.',
                'price_amount' => 29.00,
                'currency' => 'USD',
                'billing_interval' => 'monthly',
                'is_active' => true,
                'sort_order' => 1,
                'features' => [
                    'customers' => 'Customer management',
                    'leads' => 'Lead tracking',
                    'tasks' => 'Task management',
                    'quotations' => 'Quotations',
                ],
                'limits' => [
                    'max_users' => 2,
                    'max_customers' => 100,
                    'max_leads' => 100,
                    'max_tasks' => 200,
                    'max_quotations' => 50,
                ],
            ],
            [
                'key' => 'growth',
                'name' => 'Growth',
                'description' => 'For growing teams that need more workspace features and capacity.',
                'price_amount' => 79.00,
                'currency' => 'USD',
                'billing_interval' => 'monthly',
                'is_active' => true,
                'sort_order' => 2,
                'features' => [
                    'customers' => 'Customer management',
                    'leads' => 'Lead tracking',
                    'tasks' => 'Task management',
                    'quotations' => 'Quotations',
                    'files' => 'File storage',
                ],
                'limits' => [
                    'max_users' => 5,
                    'max_customers' => 500,
                    'max_leads' => 500,
                    'max_tasks' => 1000,
                    'max_quotations' => 200,
                ],
            ],
            [
                'key' => 'business',
                'name' => 'Business',
                'description' => 'Full-featured plan for established businesses with advanced requirements.',
                'price_amount' => 199.00,
                'currency' => 'USD',
                'billing_interval' => 'monthly',
                'is_active' => true,
                'sort_order' => 3,
                'features' => [
                    'customers' => 'Customer management',
                    'leads' => 'Lead tracking',
                    'tasks' => 'Task management',
                    'quotations' => 'Quotations',
                    'files' => 'File storage',
                    'activity' => 'Activity log',
                ],
                'limits' => [
                    'max_users' => 15,
                    'max_customers' => 2000,
                    'max_leads' => 2000,
                    'max_tasks' => 5000,
                    'max_quotations' => 1000,
                ],
            ],
            [
                'key' => 'custom',
                'name' => 'Custom',
                'description' => 'Tailored plan for enterprise clients with custom needs and dedicated support.',
                'price_amount' => null,
                'currency' => 'USD',
                'billing_interval' => 'custom',
                'is_active' => true,
                'sort_order' => 4,
                'features' => [
                    'customers' => 'Customer management',
                    'leads' => 'Lead tracking',
                    'tasks' => 'Task management',
                    'quotations' => 'Quotations',
                    'files' => 'File storage',
                    'activity' => 'Activity log',
                ],
                'limits' => [
                    'max_users' => 9999,
                    'max_customers' => 99999,
                    'max_leads' => 99999,
                    'max_tasks' => 99999,
                    'max_quotations' => 99999,
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['key' => $plan['key']],
                $plan
            );
        }

        $this->command->info('Seeded ' . count($plans) . ' plans.');
    }

    protected function seedPlatformSettings(): void
    {
        $settings = [
            [
                'key' => 'platform_name',
                'value' => 'NiaOS',
                'type' => 'string',
                'group' => 'general',
                'description' => 'The display name of the platform.',
            ],
            [
                'key' => 'default_paid_client_plan',
                'value' => 'starter',
                'type' => 'string',
                'group' => 'onboarding',
                'description' => 'Default plan assigned to new paid client workspaces.',
            ],
            [
                'key' => 'default_paid_client_billing_status',
                'value' => 'trial',
                'type' => 'string',
                'group' => 'onboarding',
                'description' => 'Default billing status assigned to new paid client workspaces.',
            ],
            [
                'key' => 'support_contact',
                'value' => '',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Support email or contact link for the platform.',
            ],
            [
                'key' => 'system_notes',
                'value' => '',
                'type' => 'text',
                'group' => 'system',
                'description' => 'Internal system notes for platform administrators.',
            ],
        ];

        foreach ($settings as $setting) {
            PlatformSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Seeded ' . count($settings) . ' platform settings.');
    }
}
