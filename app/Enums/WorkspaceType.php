<?php

namespace App\Enums;

enum WorkspaceType: string
{
    case Internal = 'internal';
    case PaidClient = 'paid_client';
    case Demo = 'demo';
    case Partner = 'partner';
}
