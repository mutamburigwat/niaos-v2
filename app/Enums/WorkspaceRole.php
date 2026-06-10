<?php

namespace App\Enums;

enum WorkspaceRole: string
{
    case Owner = 'owner';
    case Admin = 'admin';
    case Manager = 'manager';
    case Sales = 'sales';
    case Support = 'support';
    case Accounts = 'accounts';
    case Procurement = 'procurement';
    case Viewer = 'viewer';
}
