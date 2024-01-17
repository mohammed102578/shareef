<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $table = 'products';

    protected $fillable = [
        'product_name','packing_id','barcode','company_id','created_at','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id', 'product_name','packing_id','barcode','created_at','updated_at');
    }



    public function determining_price()
    {
       return $this->hasOne('App\Models\Determining_price','product_id','id');
    }


    public function purchase()
    {
       return $this->hasOne('App\Models\Purchase','product_id','id');
    }



    public function packing()
    {
        return  $this->belongsTo('App\Models\Packing','packing_id','id');
    }

    

    

}
?>