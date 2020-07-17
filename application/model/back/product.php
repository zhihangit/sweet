<?php
namespace app\model\back;

use think\Model;

class Product extends Model
{
    //protected $autoWriteTimestamp = true;
    protected $autoWriteTimestamp = 'datetime';

    public function productimage()
    {
        return $this->hasMany('productimage','product_id');
    }
}