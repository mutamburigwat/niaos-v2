<?php

namespace App\Console\Commands;

use App\Enums\BillingStatus;
use App\Enums\Plan;
use App\Enums\WorkspaceStatus;
use App\Enums\WorkspaceType;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Retainer;
use App\Models\Service;
use App\Models\SupportRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SeedAdpWorkspace extends Command
{
    protected $signature = 'niaos:seed-adp-workspace';
    protected $description = 'Seed Akudzwe Digital Partners workspace with real operating data';

    private const WORKSPACE_NAME = 'Akudzwe Digital Partners';

    private array $services = [
        [
            'name' => 'Digital Audit',
            'category' => 'Digital Presence',
            'description' => 'Comprehensive audit of current digital presence, website, social media, and online reputation.',
            'pricing_type' => 'once_off',
            'base_price' => 25,
            'notes' => 'Range: USD 25-50. Initial assessment and recommendations report.',
        ],
        [
            'name' => 'SME Digital Foundation',
            'category' => 'Digital Presence',
            'description' => 'Foundational digital package for small businesses: website setup, social media profiles, Google Business Profile.',
            'pricing_type' => 'once_off',
            'base_price' => 250,
            'notes' => 'Setup fee range: USD 250-500. Monthly support available at USD 20-35. Positioned as entry-level SME package.',
        ],
        [
            'name' => 'SME Operations System',
            'category' => 'Business Systems',
            'description' => 'Operational systems including CRM setup, workflow automation, and business process digitization.',
            'pricing_type' => 'once_off',
            'base_price' => 500,
            'notes' => 'Setup fee range: USD 500-1500. Monthly support available at USD 50-100. Core operations package for growing SMEs.',
        ],
        [
            'name' => 'SME Growth System',
            'category' => 'Growth Infrastructure',
            'description' => 'Growth-focused package including funnel setup, lead generation systems, and conversion optimization.',
            'pricing_type' => 'once_off',
            'base_price' => 300,
            'notes' => 'Setup fee range: USD 300-800. Monthly support available at USD 50-150. For businesses ready to scale.',
        ],
        [
            'name' => 'Managed Growth Partner',
            'category' => 'Growth Infrastructure',
            'description' => 'Ongoing managed partnership for continuous digital growth, strategy, and execution.',
            'pricing_type' => 'monthly',
            'base_price' => 100,
            'notes' => 'Monthly retainer range: USD 100-300. Full-service growth partnership.',
        ],
        [
            'name' => 'Strategic Build Partner',
            'category' => 'Growth Infrastructure',
            'description' => 'Enterprise-level strategic partnership for complex digital transformations and custom builds.',
            'pricing_type' => 'custom',
            'base_price' => 2000,
            'notes' => 'Custom pricing, minimum USD 2000+. Tailored to enterprise requirements.',
        ],
        [
            'name' => 'Website Maintenance',
            'category' => 'Website Maintenance',
            'description' => 'Ongoing website maintenance, updates, security patches, and content changes.',
            'pricing_type' => 'monthly',
            'base_price' => 20,
            'notes' => 'Monthly retainer for website upkeep and minor content updates.',
        ],
        [
            'name' => 'Business Email Setup',
            'category' => 'Business Email Setup',
            'description' => 'Professional business email configuration, Google Workspace or Microsoft 365 setup.',
            'pricing_type' => 'once_off',
            'base_price' => 30,
            'notes' => 'One-time setup fee per mailbox. Includes configuration and training.',
        ],
        [
            'name' => 'CRM Setup',
            'category' => 'CRM Setup',
            'description' => 'CRM system setup, configuration, and team training.',
            'pricing_type' => 'once_off',
            'base_price' => 100,
            'notes' => 'Setup fee range: USD 100-300 depending on complexity and number of users.',
        ],
        [
            'name' => 'Social Media Design',
            'category' => 'Social Media Design',
            'description' => 'Social media graphics, content templates, and brand-consistent visual design.',
            'pricing_type' => 'monthly',
            'base_price' => 30,
            'notes' => 'Monthly retainer for ongoing social media content design.',
        ],
        [
            'name' => 'Company Profile Design',
            'category' => 'Social Media Design',
            'description' => 'Professional company profile, brochure, and capability statement design.',
            'pricing_type' => 'once_off',
            'base_price' => 50,
            'notes' => 'One-time design fee. Range: USD 50-150 depending on complexity.',
        ],
        [
            'name' => 'Domain, Hosting & Email Package',
            'category' => 'Digital Presence',
            'description' => 'Domain registration, web hosting, and business email bundle.',
            'pricing_type' => 'yearly',
            'base_price' => 80,
            'notes' => 'Annual package. Domain + hosting + email support.',
        ],
        [
            'name' => 'Monthly Digital Support',
            'category' => 'Monthly Digital Support',
            'description' => 'Ongoing digital support retainer covering general digital maintenance and quick turnaround tasks.',
            'pricing_type' => 'monthly',
            'base_price' => 20,
            'notes' => 'Flexible monthly retainer for ad-hoc digital support needs.',
        ],
    ];

    private array $customers = [
        [
            'name' => 'Vorx Prints',
            'company_name' => 'Vorx Prints',
            'phone' => '+263 78 391 9206',
            'status' => 'customer',
            'source_channel' => 'referral',
        ],
        [
            'name' => 'Smiling Hearts Care',
            'company_name' => 'Smiling Hearts Care',
            'phone' => '+263 77 125 2328',
            'status' => 'customer',
            'source_channel' => 'referral',
        ],
        [
            'name' => 'Harvest Horizon',
            'company_name' => 'Harvest Horizon',
            'phone' => '+263 77 946 5194',
            'status' => 'customer',
            'source_channel' => 'referral',
        ],
        [
            'name' => 'F&C Cleaning Services',
            'company_name' => 'F&C Cleaning Services',
            'phone' => '+263 77 130 3879',
            'status' => 'customer',
            'source_channel' => 'referral',
        ],
        [
            'name' => 'Lucky',
            'company_name' => null,
            'phone' => '+263 77 503 4756',
            'status' => 'lead',
            'source_channel' => 'referral',
        ],
        [
            'name' => 'Malz Closet',
            'company_name' => 'Malz Closet',
            'phone' => '+263 77 287 5616',
            'status' => 'customer',
            'source_channel' => 'referral',
        ],
    ];

    public function handle(): int
    {
        $workspace = $this->ensureWorkspace();
        if (! $workspace) {
            return self::FAILURE;
        }

        $admin = $this->ensureAdminUser($workspace);

        $this->newLine();
        $this->line('── Seeding Services ────────────────────');
        $serviceRecords = $this->seedServices($workspace);

        $this->newLine();
        $this->line('── Seeding Customers ───────────────────');
        $customerRecords = $this->seedCustomers($workspace);

        $this->newLine();
        $this->line('── Seeding Retainers ───────────────────');
        $this->seedRetainers($workspace, $customerRecords, $serviceRecords);

        $this->newLine();
        $this->line('── Seeding Follow-up Tasks ─────────────');
        $this->seedTasks($workspace, $customerRecords, $admin);

        $this->newLine();
        $this->line('── Seeding Support Requests ────────────');
        $this->seedSupportRequests($workspace, $customerRecords);

        $this->newLine();
        $this->line('── Seeding Customer Contacts ───────────');
        $this->seedContacts($workspace, $customerRecords);

        $this->newLine();
        $this->line('── Notes ───────────────────────────────');
        $this->outputNotes();

        $this->newLine();
        $this->line('── Summary ─────────────────────────────');
        $this->line('  Workspace: ' . $workspace->business_name);
        $this->line('  Services seeded: ' . count($serviceRecords));
        $this->line('  Customers seeded: ' . count($customerRecords));
        $this->line('  Retainers seeded: 4');
        $this->line('  Tasks seeded: 5');
        $this->line('  Support requests seeded: 3');
        $this->line('  Contacts seeded: 11');
        $this->newLine();

        return self::SUCCESS;
    }

    private function ensureWorkspace(): ?Workspace
    {
        $workspace = Workspace::where('business_name', self::WORKSPACE_NAME)->first();

        if ($workspace) {
            $workspace->update([
                'workspace_type' => WorkspaceType::Internal,
                'billing_status' => BillingStatus::Free,
                'plan' => Plan::FreeInternal,
                'status' => WorkspaceStatus::Active,
            ]);
            $this->line('  ✓ Workspace "' . self::WORKSPACE_NAME . '" already exists (updated)');
        } else {
            $workspace = Workspace::create([
                'business_name' => self::WORKSPACE_NAME,
                'business_type' => 'digital_agency',
                'workspace_type' => WorkspaceType::Internal,
                'billing_status' => BillingStatus::Free,
                'plan' => Plan::FreeInternal,
                'status' => WorkspaceStatus::Active,
                'base_currency' => 'USD',
                'timezone' => 'Africa/Harare',
            ]);
            $this->line('  ✓ Workspace "' . self::WORKSPACE_NAME . '" created');
        }

        return $workspace;
    }

    private function ensureAdminUser(Workspace $workspace): User
    {
        $user = User::where('email', 'tmutamburigwa@akudzwe.co.zw')->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Takudzwa Mutamburigwa',
                'email' => 'tmutamburigwa@akudzwe.co.zw',
                'password' => Hash::make('Admin123!'),
                'is_platform_admin' => true,
                'active_workspace_id' => $workspace->id,
            ]);

            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'status' => 'active',
                'joined_at' => now(),
            ]);

            $this->line('  ✓ Admin user created and added to workspace');
        } else {
            $exists = WorkspaceMember::where('workspace_id', $workspace->id)
                ->where('user_id', $user->id)
                ->exists();

            if (! $exists) {
                WorkspaceMember::create([
                    'workspace_id' => $workspace->id,
                    'user_id' => $user->id,
                    'role' => 'owner',
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
                $this->line('  ✓ Admin user added to workspace membership');
            }
        }

        return $user;
    }

    private function seedServices(Workspace $workspace): array
    {
        $records = [];

        foreach ($this->services as $data) {
            $service = Service::updateOrCreate(
                [
                    'workspace_id' => $workspace->id,
                    'name' => $data['name'],
                ],
                [
                    'category' => $data['category'],
                    'description' => $data['description'],
                    'pricing_type' => $data['pricing_type'],
                    'base_price' => $data['base_price'],
                    'currency' => 'USD',
                    'status' => 'active',
                    'notes' => $data['notes'],
                ]
            );

            $records[$data['name']] = $service;
            $this->line('  ✓ Service: ' . $data['name']);
        }

        return $records;
    }

    private function seedCustomers(Workspace $workspace): array
    {
        $records = [];

        foreach ($this->customers as $data) {
            $customer = Customer::updateOrCreate(
                [
                    'workspace_id' => $workspace->id,
                    'name' => $data['name'],
                ],
                [
                    'company_name' => $data['company_name'],
                    'phone' => $data['phone'],
                    'status' => $data['status'],
                    'source_channel' => $data['source_channel'],
                ]
            );

            $records[$data['name']] = $customer;
            $this->line('  ✓ Customer: ' . $data['name'] . ' (' . $data['status'] . ')');
        }

        return $records;
    }

    private function seedRetainers(Workspace $workspace, array $customers, array $services): void
    {
        $retainers = [
            [
                'customer' => 'Malz Closet',
                'service' => 'Monthly Digital Support',
                'title' => 'Monthly Digital Support Retainer',
                'amount' => 150,
                'notes' => 'Reposition as formal Akudzwe retainer. Currently perceived like employee work rather than a professional service retainer.',
            ],
            [
                'customer' => 'Vorx Prints',
                'service' => 'Social Media Design',
                'title' => 'Creative and Website Support Retainer',
                'amount' => 50,
                'notes' => 'Creative and website support retainer. Covers social media design and general digital support.',
            ],
            [
                'customer' => 'Smiling Hearts Care',
                'service' => 'SME Digital Foundation',
                'title' => 'Digital Foundation Support Retainer',
                'amount' => 20,
                'notes' => 'Setup fee: USD 150. Paid: USD 75. Balance: USD 75. Flagship case study candidate for SME Digital Foundation package.',
            ],
            [
                'customer' => 'Lucky',
                'service' => 'SME Digital Foundation',
                'title' => 'SME Digital Foundation Retainer (Proposed)',
                'amount' => 10,
                'notes' => 'Proposed setup fee: USD 250. Payment plan: collect USD 100 first payment, then USD 50 for three months, then USD 10 monthly retainer.',
            ],
        ];

        foreach ($retainers as $data) {
            $customer = $customers[$data['customer']] ?? null;
            $service = $services[$data['service']] ?? null;

            if (! $customer) {
                $this->line('  ✗ Skipping retainer for "' . $data['customer'] . '" — customer not found');
                continue;
            }

            Retainer::updateOrCreate(
                [
                    'workspace_id' => $workspace->id,
                    'customer_id' => $customer->id,
                    'title' => $data['title'],
                ],
                [
                    'service_id' => $service?->id,
                    'amount' => $data['amount'],
                    'currency' => 'USD',
                    'billing_cycle' => 'monthly',
                    'start_date' => now()->subMonths(3),
                    'next_billing_date' => now()->addMonth(),
                    'status' => 'active',
                    'notes' => $data['notes'],
                ]
            );

            $this->line('  ✓ Retainer: ' . $data['customer'] . ' — $' . $data['amount'] . '/month');
        }
    }

    private function seedTasks(Workspace $workspace, array $customers, User $admin): void
    {
        $tasks = [
            [
                'customer' => 'Smiling Hearts Care',
                'title' => 'Collect remaining $75 from Smiling Hearts Care',
                'description' => 'Follow up on outstanding balance. Setup fee was $150, client paid $75. Collect remaining $75.',
                'priority' => 'high',
            ],
            [
                'customer' => 'Malz Closet',
                'title' => 'Reposition Malz Closet as formal Akudzwe retainer',
                'description' => 'Restructure the relationship from employee-style work to a formal retainer agreement. Present scope document and retainer terms.',
                'priority' => 'high',
            ],
            [
                'customer' => 'Vorx Prints',
                'title' => 'Reposition Vorx Prints as formal Akudzwe support retainer',
                'description' => 'Formalize the creative and website support retainer. Prepare and present a retainer agreement.',
                'priority' => 'medium',
            ],
            [
                'customer' => 'Lucky',
                'title' => 'Close Lucky package and collect first payment',
                'description' => 'Proposed SME Digital Foundation package at $250 setup. Collect $100 first payment, then $50 for three months, then $10 monthly retainer.',
                'priority' => 'high',
            ],
            [
                'customer' => null,
                'title' => 'Prepare formal scope documents for active retainers',
                'description' => 'Create standardized scope of work documents for all active retainer clients including Malz Closet, Vorx Prints, Smiling Hearts Care, and Lucky.',
                'priority' => 'medium',
            ],
        ];

        foreach ($tasks as $data) {
            $customerId = null;
            if ($data['customer'] && isset($customers[$data['customer']])) {
                $customerId = $customers[$data['customer']]->id;
            }

            Task::updateOrCreate(
                [
                    'workspace_id' => $workspace->id,
                    'title' => $data['title'],
                ],
                [
                    'customer_id' => $customerId,
                    'description' => $data['description'],
                    'assigned_to' => $admin->id,
                    'priority' => $data['priority'],
                    'status' => 'pending',
                    'due_date' => now()->addDays(14),
                ]
            );

            $this->line('  ✓ Task: ' . $data['title']);
        }
    }

    private function seedSupportRequests(Workspace $workspace, array $customers): void
    {
        $requests = [
            [
                'customer' => 'Smiling Hearts Care',
                'title' => 'Website and content update support',
                'description' => 'Client needs ongoing support for website content updates, social media posting, and general digital maintenance.',
                'priority' => 'normal',
            ],
            [
                'customer' => 'Malz Closet',
                'title' => 'Digital operations support structure',
                'description' => 'Client needs a structured support system for digital operations including content creation, scheduling, and performance reporting.',
                'priority' => 'normal',
            ],
            [
                'customer' => 'Vorx Prints',
                'title' => 'Creative and website support structure',
                'description' => 'Client needs a defined support structure for creative design requests and website maintenance.',
                'priority' => 'low',
            ],
        ];

        foreach ($requests as $data) {
            $customer = $customers[$data['customer']] ?? null;
            if (! $customer) {
                continue;
            }

            SupportRequest::updateOrCreate(
                [
                    'workspace_id' => $workspace->id,
                    'customer_id' => $customer->id,
                    'title' => $data['title'],
                ],
                [
                    'description' => $data['description'],
                    'priority' => $data['priority'],
                    'status' => 'open',
                ]
            );

            $this->line('  ✓ Support request: ' . $data['customer'] . ' — ' . $data['title']);
        }
    }

    private function outputNotes(): void
    {
        $notes = [
            'Revenue Allocation Rule:',
            '  - Tax: 15%',
            '  - Operations: 35%',
            '  - Business Reserves: 25%',
            '  - Owner Pay: 25%',
            '',
            'Current Active MRR (Monthly Recurring Revenue): ~USD 220',
            '',
            'Payments Received:',
            '  - Smiling Hearts Care: USD 75 (partial setup fee payment)',
            '',
            'Current Debt Tracker Balance: USD 800',
            '',
            'Note: These figures are reference data only until a Finance module is implemented.',
            'No financial transactions should be recorded outside the finance module.',
        ];

        foreach ($notes as $line) {
            $this->line('  ' . $line);
        }
    }

    private function seedContacts(Workspace $workspace, array $customers): void
    {
        $contacts = [
            [
                'customer' => 'Vorx Prints',
                'name' => 'Ellah Kareko Gwazvo',
                'role_title' => 'Manager',
                'phone' => '+263 78 391 9206',
                'is_primary' => true,
            ],
            [
                'customer' => 'Vorx Prints',
                'name' => 'Royal Denhere',
                'role_title' => 'CEO',
                'phone' => '+263 77 591 9620',
                'is_primary' => false,
            ],
            [
                'customer' => 'Smiling Hearts Care',
                'name' => 'LeslyAnn Jijita',
                'role_title' => 'Head of Care',
                'phone' => '+263 77 125 2328',
                'is_primary' => true,
            ],
            [
                'customer' => 'Harvest Horizon',
                'name' => 'Donald Choto',
                'role_title' => 'Technical Advisor',
                'phone' => '+263 77 946 5194',
                'is_primary' => true,
            ],
            [
                'customer' => 'Harvest Horizon',
                'name' => 'Nyasha Machingura',
                'role_title' => 'Technical Advisor',
                'phone' => '+263 77 776 6991',
                'is_primary' => false,
            ],
            [
                'customer' => 'F&C Cleaning Services',
                'name' => 'Faith Tapfumanei',
                'role_title' => 'CEO',
                'phone' => '+263 77 130 3879',
                'is_primary' => true,
            ],
            [
                'customer' => 'Lucky',
                'name' => 'Lucky',
                'role_title' => 'Unknown / To be confirmed',
                'phone' => '+263 77 503 4756',
                'is_primary' => true,
                'notes' => 'No company name or full business information confirmed yet.',
            ],
            [
                'customer' => 'Malz Closet',
                'name' => 'Malone Tawanda Manhanha',
                'role_title' => 'CEO',
                'phone' => '+263 77 287 5616',
                'is_primary' => true,
            ],
        ];

        foreach ($contacts as $data) {
            $customer = $customers[$data['customer']] ?? null;
            if (! $customer) {
                $this->line('  ✗ Skipping contact for "' . $data['customer'] . '" — customer not found');
                continue;
            }

            CustomerContact::updateOrCreate(
                [
                    'workspace_id' => $workspace->id,
                    'customer_id' => $customer->id,
                    'name' => $data['name'],
                ],
                [
                    'role_title' => $data['role_title'],
                    'phone' => $data['phone'],
                    'is_primary' => $data['is_primary'],
                    'notes' => $data['notes'] ?? null,
                ]
            );

            $this->line('  ✓ Contact: ' . $data['name'] . ' (' . $data['customer'] . ')');
        }
    }
}
