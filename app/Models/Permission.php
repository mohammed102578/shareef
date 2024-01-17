<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission  extends Model
{
    use HasFactory;


    protected $table = 'permissions';

    protected $fillable = [
        'group_id','page_id','company_id','created_at','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id', 'group_id','page_id','company_id','created_at','updated_at');
    }



    public  function group(){

        return $this->belongsTo('App\Models\Group', 'group_id','id');
    }

    public  function page(){

        return $this->belongsTo('App\Models\Page', 'page_id','id');
    }


}
