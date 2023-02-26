<?php

namespace App\Models\TestModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course as BaseCourse;

class Course extends BaseCourse
{
    protected $connection= 'mysql_test';
}
