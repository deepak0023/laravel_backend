<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'rl_id';
    /**
     *  Created at column
     */
    const CREATED_AT = 'rl_created_at';

    /**
     *  Updated at column
     */
    const UPDATED_AT = 'rl_updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rl_name',
    ];

    public function user() {
        return $this->hasMany(User::class, 'user_rl_id', 'rl_id');
    }
}
