<?php /*a:2:{s:60:"D:\wamp64\www\sweet\application\view\back\home\comadmin.html";i:1593615425;s:52:"D:\wamp64\www\sweet\application\view\backlayout.html";i:1594519586;}*/ ?>
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
<div class="row cm-fix-height" >
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
    <a href="<?php echo url('back.home/vendermanage'); ?>" class="panel panel-default thumbnail cm-thumbnail">
      <div class="panel-body text-center" style="min-height: 187px;">
                                <span class="svg-48">
                                    <img src="/sweet/public/back/images/sf/profile-group.svg" alt="dashboard">
                                </span>
        <h4>商家管理</h4> <small>商家进驻 </small>

      </div>
    </a>
  </div>
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
    <a href="<?php echo url('back.home/codemanage'); ?>" class="panel panel-default thumbnail cm-thumbnail">
      <div class="panel-body text-center" style="min-height: 187px;">
                                <span class="svg-48">
                                    <img src="/sweet/public/back/images/sf/notepad.svg" alt="notepad">
                                </span>
        <h4>消费码管理</h4> <small>消费码生成 消费码激活等</small>

      </div>
    </a>
  </div>
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
    <a href="components-text.html" class="panel panel-default thumbnail cm-thumbnail">
      <div class="panel-body text-center" style="min-height: 187px;">
                                <span class="svg-48">
                                    <img src="/sweet/public/back/images/sf/user-male-alt.svg" alt="brick">
                                </span>
        <h4>客户管理</h4> <small>消费客户信息维护等</small>

      </div>
    </a>
  </div>
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
    <a href="layouts-breadcrumb1.html" class="panel panel-default thumbnail cm-thumbnail">
      <div class="panel-body text-center" style="min-height: 187px;">
                                <span class="svg-48">
                                    <img src="/sweet/public/back/images/sf/window-layout.svg" alt="window-layout">
                                </span>
        <h4>扩展一</h4> <small> </small>

      </div>
    </a>
  </div>
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
    <a href="ico-sf.html" class="panel panel-default thumbnail cm-thumbnail">
      <div class="panel-body text-center" style="min-height: 187px;">
                                <span class="svg-48">
                                    <img src="/sweet/public/back/images/sf/cat.svg" alt="cat">
                                </span>
        <h4>扩展二</h4> <small> </small>

      </div>
    </a>
  </div>
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
    <a href="login.html" class="panel panel-default thumbnail cm-thumbnail">
      <div class="panel-body text-center" style="min-height: 187px;">
                                <span class="svg-48">
                                    <img src="/sweet/public/back/images/sf/lock-open.svg" alt="lock-open">
                                </span>
        <h4>扩展三</h4> <small> </small>

      </div>
    </a>
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
