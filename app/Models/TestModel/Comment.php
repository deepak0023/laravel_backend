<?php

namespace App\Models\TestModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment as BaseComment;

class Comment extends BaseComment
{
    protected $connection= 'mysql_test';
}
