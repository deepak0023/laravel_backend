<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'cr_id';
    /**
     *  Created at column
     */
    const CREATED_AT = 'cr_created_at';

    /**
     *  Updated at column
     */
    const UPDATED_AT = 'cr_updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cr_title',
        'cr_description',
    ];

    public function user() {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id');
    }
}
