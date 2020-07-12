<?php
namespace app\validate\back;
use think\Validate;
class Vcode extends Validate
{

    //写规则
    protected $rule = [
        'price' => 'require|integer',
        'amount' => 'require|integer',
        'indate'=>'require|date'
    ];
    //写报错返回信息
    protected $message = [
        'price.require' => '面值必须填写',
        'price.integer' => '面值为整数',
        'amount.require' => '数量必须填写',
        'amount.integer' => '数量为整数',
        'indate.require'=>'有效期必须填写',
        'indate.date'=>'有效期必须为日期类型',
    ];
    protected $scene = [
        'add' => ['username','userpwd','indate'],
        'edit' => ['username','userpwd','indate'],
    ];
}