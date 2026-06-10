<?php

namespace App\Enums;

enum LeadStage: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Qualified = 'qualified';
    case QuotationSent = 'quotation_sent';
    case FollowUp = 'followup';
    case Won = 'won';
    case Lost = 'lost';
}
