<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPurchase extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'app_id', 'package_id', 'status', 'token', 'expires_at'];

}
