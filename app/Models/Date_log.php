<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date_log  extends Model
{
    use HasFactory;


    protected $table = 'date_logs';

    protected $fillable = [
        'date'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id',  'date');
    }


   

}
