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
                'features' => json_encode(['Unlimited workspaces', 'All core features', 'Community support']),
                'limits' => json_encode(['users' => 999, 'storage_mb' => 10240]),
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
                'features' => json_encode(['Up to 5 users', 'CRM core', 'Task management', 'Email support']),
                'limits' => json_encode(['users' => 5, 'storage_mb' => 500]),
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
                'features' => json_encode(['Up to 25 users', 'Advanced CRM', 'Quotations', 'File storage', 'Priority support']),
                'limits' => json_encode(['users' => 25, 'storage_mb' => 5000]),
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
                'features' => json_encode(['Unlimited users', 'Full CRM suite', 'API access', 'Custom fields', 'Dedicated support']),
                'limits' => json_encode(['users' => 999, 'storage_mb' => 50000]),
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
                'features' => json_encode(['Everything in Business', 'Custom integrations', 'SLA guarantee', 'Account manager', 'On-premise option']),
                'limits' => json_encode(['users' => 9999, 'storage_mb' => 999999]),
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
