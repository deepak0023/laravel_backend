<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $primaryKey = 'td_id';
    /**
     *  Created at column
     */
    const CREATED_AT = 'td_created_at';

    /**
     *  Updated at column
     */
    const UPDATED_AT = 'td_updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'td_title',
        'td_description',
        'td_status',
        'td_user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'td_user_id', 'id');
    }
}
