<?php

namespace App\Enums;

enum BillingStatus: string
{
    case Free = 'free';
    case Trial = 'trial';
    case Active = 'active';
    case Overdue = 'overdue';
    case Suspended = 'suspended';
    case Cancelled = 'cancelled';
}
