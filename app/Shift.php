<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Shift extends Model
{
    //
    protected $fillable = [
        'manager_id', 'employee_id', 'break', 'start_time', 'end_time'
    ];

    protected $appends = [
        'employee'
    ];

    protected $dates = ['start_time', 'end_time'];

    public function employee ( )
    {
        return $this->hasOne('App\User', 'id', 'employee_id');
    }

    public function manager ( )
    {
        return $this->hasOne('App\User', 'id', 'manager_id');
    }

    public function getEmployeeAttribute($value)
    {   
        if( array_key_exists('employee_id', $this->attributes) ) {
            return User::find($this->attributes['employee_id']);
        }
        else {
            return null;
        }
        
    }
}
