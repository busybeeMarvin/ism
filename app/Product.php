<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function  isLimited($product_id)
    {
        $count = self::query()
                ->where('type','limited')
                ->where('id', $product_id)
                ->get()
                ->count();
        
        return $count == 1 ? true : false;
    }

    public static function isUnLimited($product_id)
    {
        $count = self::query()
            ->where('type','limited')
            ->where('id', $product_id)
            ->get()
            ->count();
        
        return $count == 0 ? true : false;
    }
}
