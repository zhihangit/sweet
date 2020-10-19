<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板引擎类型 支持 php think 支持扩展
    'type'         => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
    'auto_rule'    => 1,
    // 模板路径
    'view_path'    => '',
    // 模板后缀
    'view_suffix'  => 'html',
    // 模板文件名分隔符
    'view_depr'    => DIRECTORY_SEPARATOR,
    // 模板引擎普通标签开始标记
    'tpl_begin'    => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'      => '}',
    // 标签库标签开始标记
    'taglib_begin' => '{',
    // 标签库标签结束标记
    'taglib_end'   => '}',
    // 模板参数
    'tpl_replace_string' => [ // 视图输出字符串内容替换
        '__PUBLIC__' => SITE_URL,
        '__UPLOADS__' =>  SITE_URL.'/uploads',
        //前端
        '__FIMAGES__' =>  SITE_URL.'/front/images',
        '__FUPLOADS__' =>  SITE_URL.'/front/uploads',
        '__FCSS__' =>  SITE_URL.'/front/css',
        '__FJS__' =>  SITE_URL.'/front/js',
        '__FFONTS__' =>  SITE_URL.'/front/fonts',

        //后端
        '__BIMAGES__' =>  SITE_URL.'/back/images',
        '__BUPLOADS__' =>  SITE_URL.'/back/uploads',
        '__BCSS__' =>  SITE_URL.'/back/css',
        '__BJS__' =>  SITE_URL.'/back/js',
        '__BFONTS__' => SITE_URL.'/back/fonts',
        '__BUEDITOR__' =>  SITE_URL.'/static/ueditor',
    ],
];
