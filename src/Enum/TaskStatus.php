<?php

namespace App\Enum;

enum TaskStatus: string
{
    case to_do = 'To Do';
    case in_progress = 'In Progress';
    case completed = 'Completed';
}
