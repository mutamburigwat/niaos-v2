<?php

namespace App\Enums;

enum Plan: string
{
    case FreeInternal = 'free_internal';
    case Starter = 'starter';
    case Growth = 'growth';
    case Business = 'business';
    case Custom = 'custom';
}
