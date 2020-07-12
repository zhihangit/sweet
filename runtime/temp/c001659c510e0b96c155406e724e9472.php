<?php /*a:2:{s:64:"D:\wamp64\www\sweet\application\view\back\home\vendermanage.html";i:1594435642;s:52:"D:\wamp64\www\sweet\application\view\backlayout.html";i:1594519586;}*/ ?>
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
            
<script src="/sweet/public/back/js/jquery.min.js"></script>
<script type="text/javascript">

    $(function() {
        // 删除大修模态框的确定按钮的点击事件
        $("#deleteHaulBtn").click(function() {
            // ajax异步删除
            deleteHaulinfo();
        });

    })

 function deleteHaulinfo() {
        //alert("你即将删除的ID" + $("#deleteHaulId").val());
       window.location.href ="<?php echo url('back.home/delevender','',''); ?>"+"/id/"+ $("#deleteHaulId").val()+".html";
       //window.location.href="<?php echo url('table/index'); ?>?id="+id;
    }
    // 打开询问是否删除的模态框并设置需要删除的的ID
 function showDeleteModal(obj) {
        var $tds = $(obj).parent().parent().children();// 获取到所有列
        var delete_id = $($tds[0]).children("input").val();// 获取隐藏的ID
        $("#deleteHaulId").val(delete_id);// 将模态框中需要删除的ID设为需要删除的ID
        $("#delcfmOverhaul").modal({
            backdrop : 'static',
            keyboard : false
        });
    }

</script>
<div class="container-fluid cm-container-white" style="margin-top:20px;padding-top: 30px;height: 100%">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>商家列表</h3></div>
                <h4 style="padding-left: 25px;margin: 5px 0px;">
                    <a href="<?php echo url('back.home/addvender'); ?>" class="btn btn-primary" role="button">新增商家</a>
                </h4>
                <div class="panel-body" style="padding-top: 0px;">
                    <table class="table table-bordered table-hover table-condensed" style="text-align: center">
                        <thead  >
                        <tr>
                            <th  style="text-align: center">序号</th>
                            <th  style="text-align: center">名称</th>
                            <th  style="text-align: center">地址</th>
                            <th style="text-align: center" >形象图</th>
                            <th style="text-align: center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td scope="row"><input type="hidden" value="<?php echo htmlentities($vo['id']); ?>"> <?php echo htmlentities($k); ?></td>
                            <td><?php echo htmlentities($vo['userinfo']['company']); ?></td>
                            <td><?php echo htmlentities($vo['userinfo']['address']); ?></td>
                            <td>@mdo</td>
                            <td><a href="javascript:void(0)" onclick="showDeleteModal(this)">删除</a><a href="<?php echo url('back.home/modivender','',''); ?>/id/<?php echo htmlentities($vo['id']); ?>.html">/修改</td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>



<div class="modal fade" id="delcfmOverhaul" style="margin-top: 150px;">
    <div class="modal-dialog">
        <div class="modal-content message_align">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">提示</h4>
            </div>
            <div class="modal-body">
                <!-- 隐藏需要删除的id -->
                <input type="hidden" id="deleteHaulId" />
                <p>您确认要删除该条信息吗？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary"
                        id="deleteHaulBtn">确认</button>
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
