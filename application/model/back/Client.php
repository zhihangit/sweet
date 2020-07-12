<?php
namespace app\model\back;

use think\Model;

class Client extends Model
{
    //protected $autoWriteTimestamp = true;
    protected $autoWriteTimestamp = 'datetime';

    public function code()
    {
        return $this->belongsTo('Code');
    }
}