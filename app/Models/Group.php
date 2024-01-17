<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;


    protected $table = 'groups';

    protected $fillable = [
        'group_name','company_id','created_at', 'updated_at'
    ];




    public function scopeSelection($query)
    {
        return $query->select('id','group_name','company_id','created_at', 'updated_at');
    }


    public function permission(){
return $this-> hasMany('App\Models\Group','group_id','id');
    }

    public  function user(){
        return $this -> hasMany('App\Models\User','group_id','id');
    }



}
