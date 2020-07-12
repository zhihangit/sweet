<?php /*a:2:{s:62:"D:\wamp64\www\sweet\application\view\back\home\modivender.html";i:1594433451;s:52:"D:\wamp64\www\sweet\application\view\backlayout.html";i:1594483611;}*/ ?>
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
                <div class="panel-heading">修改商家信息</div>
                <div class="panel-body" style="min-height: 214px;">
                    <form class="form-horizontal"  enctype="multipart/form-data" action="<?php echo url('back.home/domodivender'); ?>" method="post" >
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">用户名（必填）</label>
                            <div class="col-sm-10">
                                <input name="userid" type="hidden" value="<?php echo htmlentities($user['id']); ?>">
                                <input type="text" name="username" class="form-control" id="username" placeholder="用户名" value="<?php echo htmlentities($user['username']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pass_word" class="col-sm-2 control-label">登录密码（必填）</label>
                            <div class="col-sm-10">
                                <input name="userpwd" type="password" class="form-control" id="user_pwd" placeholder="密码(不填则保留原密码)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="limite" class="col-sm-2 control-label">类型（必选）</label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                    <input type="radio" name="limite" id="limiteRadio1" value="1"  <?php if(( $user['limite'] == 1)): ?> checked <?php endif; ?>> 1系统管理员
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="limite" id="limiteRadio2" value="2" <?php if(( $user['limite'] == 2)): ?> checked <?php endif; ?>> 2商家管理员
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="limite" id="limiteRadio3" value="3" <?php if(( $user['limite'] == 3)): ?> checked <?php endif; ?>> 3门店管理员
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company" class="col-sm-2 control-label">商家名称（必填）</label>
                            <div class="col-sm-10">
                                <input type="text" name="company" class="form-control" id="company" placeholder="商家名称" value="<?php echo htmlentities($user['userinfo']['company']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company" class="col-sm-2 control-label">商家形象图</label>
                            <div class="col-sm-10">
                              <!--<input type="text" name="company" class="form-control" id="company" placeholder="商家形象图">
                         -->
                                 <!--   <img id="plsod" style="display:none; border-color:#333; border-style:solid; border-width:1px;" width="80px" height="60px">-->
                                <input type="file" name="info_photo" value="<?php echo htmlentities($user['userinfo']['image']); ?>" id="info_photo"
                                       onchange='PreviewImage(this)' />

                                <div id="photo_info" class="photo_info" ><img id='img1' width='120px' height='100px'  style='border:black solid 1px;margin-top:5px;' src='/sweet/uploads/<?php echo htmlentities($user['userinfo']['image']); ?>'/></div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="areaid" class="col-sm-2 control-label">地域归属（必填）</label>
                            <div class="col-sm-10">
                                <select name="pro" id="pro">
                                    <option>--请选择--</option>
                                   <?php foreach($region as $vo): if($vo['id'] == $pp['id']): ?>
                                    <option value=<?php echo htmlentities($vo['id']); ?> selected><?php echo htmlentities($vo['name']); ?></option>
                                    <?php else: ?>
                                    <option value=<?php echo htmlentities($vo['id']); ?> ><?php echo htmlentities($vo['name']); ?></option>
                                    <?php endif; ?>



                                    <?php endforeach; ?>
                                </select>


                                <!-- 城市 -->
                                <select name="city" id="city">
                                    <option value="<?php echo htmlentities($p['id']); ?>"><?php echo htmlentities($p['name']); ?></option>
                                </select>




                                <!-- 区县 -->
                                <select name="area" id="area">
                                    <option value="<?php echo htmlentities($user['id']); ?>"><?php echo htmlentities($userareaname); ?></option>
                                </select>

                            </div>
                        </div>

                        <!--<script src="https://cdn.bootcss.com/jquery/3.3.0/jquery.min.js"></script>-->
                        <script src="/sweet/public/back/js/jquery.min.js"></script>
                        <script>

                            $('#pro').change(function(){
                                    $.ajax({
                                        type:"post",
                                        url:"<?php echo url('back.home/addvender'); ?>",
                                        data:'pro_id='+$('#pro').val(),
                                        dataType:"json",
                                        success:function(data){
                                            console.log(data);
                                            $('#city').html(data);
                                            $('#area').html('<option>--请选择市--</option>');
                                        }
                                    });
                            });

                            $('#city').change(function(){
                                $.ajax({
                                    type:"post",
                                    url:"<?php echo url('back.home/addvender'); ?>",
                                    data:'pro_id='+$('#city').val(),
                                    dataType:"json",
                                    success:function(data){
                                        console.log(data);
                                        $('#area').html(data);
                                    }
                                });
                            });


                            function PreviewImage(imgFile) {
                                var filextension = imgFile.value.substring(imgFile.value
                                    .lastIndexOf("."), imgFile.value.length);
                                filextension = filextension.toLowerCase();
                                if ((filextension != '.jpg') && (filextension != '.gif')
                                    && (filextension != '.jpeg') && (filextension != '.png')
                                    && (filextension != '.bmp')) {
                                    alert("对不起，系统仅支持标准格式的照片，请您调整格式后重新上传，谢谢 !");
                                    imgFile.focus();
                                } else {
                                    var path;
                                    if (document.all)//IE
                                    {
                                        imgFile.select();
                                        path = document.selection.createRange().text;
                                        document.getElementById("photo_info").innerHTML = "";
                                        document.getElementById("photo_info").style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled='true',sizingMethod='scale',src=\""
                                            + path + "\")";//使用滤镜效果
                                    } else//FF
                                    {
                                        path = window.URL.createObjectURL(imgFile.files[0]);// FF 7.0以上
                                        //path = imgFile.files[0].getAsDataURL();// FF 3.0
                                        document.getElementById("photo_info").innerHTML = "<img id='img1' width='120px' height='100px'  style='border:black solid 1px;margin-top:5px;' src='"+path+"'/>";
                                        //document.getElementById("img1").src = path;
                                    }
                                }
                            }

                        </script>

                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">email（必填）</label>
                            <div class="col-sm-10">
                                <input type="email"  name="email" class="form-control" id="areaid" placeholder="email" value="<?php echo htmlentities($user['userinfo']['email']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-sm-2 control-label">地址（必填）</label>
                            <div class="col-sm-10">
                                <input type="text"  name="address" class="form-control" id="address" placeholder="地址" value="<?php echo htmlentities($user['userinfo']['address']); ?>">
                            </div>
                        </div>


                        <div class="form-group" style="margin-bottom:0">
                            <div class="col-sm-offset-2 col-sm-10 text-right">
                                <!--<button type="reset" class="btn btn-default">Cancel</button>-->
                                <button type="submit" class="btn btn-primary">修改</button>
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
