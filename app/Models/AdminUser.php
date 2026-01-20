<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'admin_users';
    protected $primaryKey = 'admin_id';
    
    protected $fillable = [
        'admin_name',
        'admin_email',
        'admin_password',
        'admin_phone',
        'admin_role',
        'is_active',
    ];

    protected $hidden = [
        'admin_password',
    ];

    /**
     * Get the name of the "password" column.
     */
    public function getAuthPassword()
    {
        return $this->admin_password;
    }
}
