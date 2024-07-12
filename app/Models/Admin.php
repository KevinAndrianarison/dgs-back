<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_admin', 'admin_id', 'role_id');
    }
}
