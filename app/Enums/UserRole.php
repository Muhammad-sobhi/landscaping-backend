<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CREW_LEADER = 'crew_leader';
    case CREW_MEMBER = 'crew_member';
    case SALES = 'sales';
    case CUSTOMER = 'customer';
}
