<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public $transformer = UserTransformer::class;

    protected $fillable = [
        'name',
        'email', 
        'password'
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'remember_token'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}