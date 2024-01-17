<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'group_id',
        'company_id',
        'expiered_date',
        'lang',
        'stop',
       
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeSelection($query)
    {
        return $query->select('id','name','username','expiered_date',
        'password','group_id','company_id','stop','lang','created_at','updated_at');
    }


    public function scopeStop($query)
    {

        return $query->where('stop', 1);
    }


    public  function group(){

        return $this->belongsTo('App\Models\Group', 'group_id','id');
    }
    public  function company(){

        return $this->belongsTo('App\Models\Company', 'company_id','companies_id');
    }


}
