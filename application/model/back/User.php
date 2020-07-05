<?php
namespace app\model\back;

use think\Model;

class User extends Model
{
    //protected $autoWriteTimestamp = true;
    protected $autoWriteTimestamp = 'datetime';

    public function userinfo()
    {
        return $this->hasOne('userinfo','user_id');
    }
}