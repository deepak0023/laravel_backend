<?php

namespace App\Models\TestModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Todo as BaseTodo;

class Todo extends BaseTodo
{
    protected $connection= 'mysql_test';
}
