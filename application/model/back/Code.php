<?php
namespace app\model\back;

use think\Model;

class Code extends Model
{
    //protected $autoWriteTimestamp = true;
    protected $autoWriteTimestamp = 'datetime';

    public function client()
    {
        return $this->hasOne('client','id','companyid')->bind('companyname');
    }
}