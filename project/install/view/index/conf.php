<!DOCTYPE html>
<html>
<head>
    <title>
        配置
    </title>
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <script src="/js/jquery-2.2.4.min.js" type="text/javascript">
    </script>
    <script src="/js/bootstrap.min.js" type="text/javascript">
    </script>
</head>
<body style="background-color: rgba(0, 0, 0, 0.1);">
    <div class="container">
        <div class="row">
            <img src="/img/w-logo-blue.png" style="margin: 50px;"/>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="<?=self::url('init');?>">
                        <p>请在下方填写您的数据库连接信息。如果您不确定，请联系您的服务提供商。</p>
                        <div class="form-group">
                            <label for="dbname" class="col-sm-2 control-label">数据库名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"  name="dbname" size="25" value="wordpress" placeholder="请输入数据库名">
                                <p>将WordPress安装到哪个数据库？</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uname" class="col-sm-2 control-label">用户名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"  name="uname" size="25" value="root" placeholder="请输入用户名">
                                <p>您的数据库用户名。</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pwd" class="col-sm-2 control-label">密码</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control"  name="pwd" size="25" value="" placeholder="请输入密码">
                                <p>您的数据库密码。</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dbhost" class="col-sm-2 control-label">数据库主机</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"  name="dbhost" size="25" value="localhost" placeholder="请输入数据库主机">
                                <p>如果<code>localhost</code>不能用，您通常可以从网站服务提供商处得到正确的信息。</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input name="submit" type="submit" value="提交" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /container -->
</body>
</html>
