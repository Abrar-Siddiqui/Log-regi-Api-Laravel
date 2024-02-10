<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    protected $table = 'password_reset_tokens';
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const UPDATED_AT = null;
    protected $fillable = [
        'email',
        'token'
    ];

}
