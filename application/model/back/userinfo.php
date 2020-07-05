<?php
namespace app\model\back;

use think\Model;

class Userinfo extends Model
{
    //protected $autoWriteTimestamp = true;
    //protected $autoWriteTimestamp = 'datetime';

    public function user()
    {
        return $this->belongsTo('User');
    }
}