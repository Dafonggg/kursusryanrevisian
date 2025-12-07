<?php

namespace App\Enums;

enum ExamStatus: string
{
    case Pending = 'pending';
    case Graded = 'graded';
    case Passed = 'passed';
    case Failed = 'failed';
}
