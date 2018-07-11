<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [  'name', 'display_name', 'description' ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [  'password', 'remember_token' ];


    public function users(){

        return $this->belongsToMany('App\Models\User');
    }
}
