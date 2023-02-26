<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $primaryKey = 'ar_id';
    /**
     *  Created at column
     */
    const CREATED_AT = 'ar_created_at';

    /**
     *  Updated at column
     */
    const UPDATED_AT = 'ar_updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ar_title',
        'ar_user_id',
        'ar_description',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'ar_user_id', 'id');
    }

    public function comment() {
        return $this->hasMany(Comment::class, 'cm_ar_id', 'ar_id');
    }
}
