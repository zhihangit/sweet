<?php /*a:2:{s:59:"D:\wamp64\www\sweet\application\view\back\home\addcode.html";i:1594526358;s:52:"D:\wamp64\www\sweet\application\view\backlayout.html";i:1594519586;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
        <link rel="stylesheet" type="text/css" href="/sweet/public/back/css/bootstrap-clearmin.min.css">
        <link rel="stylesheet" type="text/css" href="/sweet/public/back/css/roboto.css">
        <link rel="stylesheet" type="text/css" href="/sweet/public/back/css/material-design.css">
        <link rel="stylesheet" type="text/css" href="/sweet/public/back/css/small-n-flat.css">
        <link rel="stylesheet" type="text/css" href="/sweet/public/back/css/font-awesome.min.css">

        <title>sweet管理平台</title>
    </head>
    <body class="cm-no-transition cm-1-navbar">
        <div id="cm-menu">
            <nav class="cm-navbar cm-navbar-primary">
                <div class="cm-flex"><a href="index.html" class="cm-logo"></a></div>
                <div class="btn btn-primary md-menu-white" data-toggle="cm-menu"></div>
            </nav>
            <div id="cm-menu-content">
                <div id="cm-menu-items-wrapper">
                    <div id="cm-menu-scroller">
                        <ul class="cm-menu-items">
                            <li class="active"><a href="<?php echo url('back.home/comadmin'); ?>" class="sf-house">系统应用</a></li>
                            <li><a href="dashboard-sales.html" class="sf-dashboard">Dashboard</a></li>
                            <li><a href="components-text.html" class="sf-brick">Components</a></li>
                            <li class="cm-submenu">
                                <a class="sf-window-layout">商家应用<span class="caret"></span></a>
                                <ul>
                                    <li><a href="layouts-breadcrumb1.html">1st nav breadcrumb</a></li>
                                    <li><a href="layouts-breadcrumb2.html">2nd nav breadcrumb</a></li>
                                    <li><a href="layouts-tabs.html">2nd nav tabs</a></li>
                                </ul>
                            </li>
                            <li class="cm-submenu">
                                <a class="sf-cat">Icons <span class="caret"></span></a>
                                <ul>
                                    <li><a href="ico-sf.html">Small-n-flat</a></li>
                                    <li><a href="ico-md.html">Material Design</a></li>
                                    <li><a href="ico-fa.html">Font Awesome</a></li>
                                </ul>
                            </li>
                            <li><a href="notepad.html" class="sf-notepad">Text Editor</a></li>
                            <li><a href="<?php echo url('back.home/loginout'); ?>" class="sf-lock-open">退出登录</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <header id="cm-header">
            <nav class="cm-navbar cm-navbar-primary">
                <div class="btn btn-primary md-menu-white hidden-md hidden-lg" data-toggle="cm-menu"></div>
                <div class="cm-flex">
                    <h1>SWEET管理平台</h1>
                    <form id="cm-search" action="index.html" method="get">
                        <input type="search" name="q" autocomplete="off" placeholder="Search...">
                    </form>
                </div>
                <div class="pull-right">
                    <div id="cm-search-btn" class="btn btn-primary md-search-white" data-toggle="cm-search"></div>
                </div>
                <div class="dropdown pull-right">
                    <button class="btn btn-primary md-notifications-white" data-toggle="dropdown"> <span class="label label-danger">23</span> </button>
                    <div class="popover cm-popover bottom">
                        <div class="arrow"></div>
                        <div class="popover-content">
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    <h4 class="list-group-item-heading text-overflow">
                                        <i class="fa fa-fw fa-envelope"></i> Nunc volutpat aliquet magna.
                                    </h4>
                                    <p class="list-group-item-text text-overflow">Pellentesque tincidunt mollis scelerisque. Praesent vel blandit quam.</p>
                                </a>
                                <a href="#" class="list-group-item">
                                    <h4 class="list-group-item-heading">
                                        <i class="fa fa-fw fa-envelope"></i> Aliquam orci lectus
                                    </h4>
                                    <p class="list-group-item-text">Donec quis arcu non risus sagittis</p>
                                </a>
                                <a href="#" class="list-group-item">
                                    <h4 class="list-group-item-heading">
                                        <i class="fa fa-fw fa-warning"></i> Holy guacamole !
                                    </h4>
                                    <p class="list-group-item-text">Best check yo self, you're not looking too good.</p>
                                </a>
                            </div>
                            <div style="padding:10px"><a class="btn btn-success btn-block" href="#">Show me more...</a></div>
                        </div>
                    </div>
                </div>
                <div class="dropdown pull-right">
                    <button class="btn btn-primary md-account-circle-white" data-toggle="dropdown"></button>
                    <ul class="dropdown-menu">
                        <li class="disabled text-center">
                            <a style="cursor:default;"><strong>John Smith</strong></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-cog"></i> Settings</a>
                        </li>
                        <li>
                            <a href="login.html"><i class="fa fa-fw fa-sign-out"></i> Sign out</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div id="global" style="padding-top: 10px;height: 100%">
            
<div class="container-fluid cm-container-white" style="margin-top:20px;padding-top: 30px;height: 100%">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">生成消费码</div>
                <div class="panel-body" style="min-height: 214px;">
                    <form class="form-horizontal"  enctype="multipart/form-data" action="<?php echo url('back.home/doaddcode'); ?>" method="post" >
                        <div class="form-group">
                            <label for="price" class="col-sm-2 control-label">面值</label>
                            <div class="col-sm-10">
                                <input name="price" type="number" class="form-control" id="price"   placeholder="面值">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="col-sm-2 control-label">数量</label>
                            <div class="col-sm-10">
                                <input name="amount" type="number" class="form-control" id="amount"   placeholder="数量">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company" class="col-sm-2 control-label">有效期（必填）</label>
                            <div class="col-sm-10">
                                <input type="text" name="indate" class="demo-input" placeholder="请选择日期" id="test1">
                            </div>
                        </div>
                        <script src="/sweet/public/plug/layDate/laydate/laydate.js"></script>
                        <script>
                            //执行一个laydate实例
                            laydate.render({
                                elem: '#test1' //指定元素
                            });
                        </script>
                        <div class="form-group">
                            <label for="areaid" class="col-sm-2 control-label">客户名称</label>
                            <div class="col-sm-10">
                                <select name="companyid" id="companyid">



                                    <?php foreach($clientlist as $vo): ?>
                                    <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['companyname']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company" class="col-sm-2 control-label">上传合同</label>
                            <div class="col-sm-10">
                                <input type="file" name="contract" value="" id="contract"/>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <div class="col-sm-offset-2 col-sm-10 text-right">
                                <!--<button type="reset" class="btn btn-default">Cancel</button>-->
                                <button type="submit" class="btn btn-primary">生成消费码</button>
                            </div>
                        </div>
                        <?php echo token(); ?>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>


            <footer class="cm-footer"><span class="pull-left">登录账户：<?php echo htmlentities(app('cookie')->get('user_name')); ?></span></footer>
        </div>
        <script src="/sweet/public/back/js/lib/jquery.min.js"></script>
        <script src="/sweet/public/back/js/jquery.mousewheel.min.js"></script>
        <script src="/sweet/public/back/js/jquery.cookie.min.js"></script>
        <script src="/sweet/public/back/js/fastclick.min.js"></script>
        <script src="/sweet/public/back/js/bootstrap.min.js"></script>
        <script src="/sweet/public/back/js/clearmin.min.js"></script>
        <script src="/sweet/public/back/js/demo/home.js"></script>

    </body>
</html>
