<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $fillable = ['firstName', 'lastName', 'email', 'phone', 'password', 'otp'];

    protected $attributes = [
        'otp' => '0'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

}