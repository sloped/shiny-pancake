<?php

namespace App;

use App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function shifts ( )
    {
        return $this->hasMany( 'App\Shift', 'employee_id', 'id' );
    }

    public function role ( )
    {
        return $this->belongsTo('App\Role', 'role');
    }

    public function getRoleAttribute($value)
    {
        return Role::find($value)->name;
    }
}
