<!DOCTYPE html>
<html>
    <head>
        <title>
            安装
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
                        <h3>
                            欢迎使用WordPress。在开始前，我们需要您数据库的一些信息。请准备好如下信息。
                        </h3>
                        <ol>
                            <li>数据库名</li>
                            <li>数据库用户名</li>
                            <li>数据库密码</li>
                            <li>数据库主机</li>
                            <li>数据表前缀（table prefix，特别是当您要在一个数据库中安装多个WordPress时）</li>
                        </ol>
                        <p>
                            我们会使用这些信息来创建一个
                            <code>
                                wp-config.php
                            </code>
                            文件。
                            <strong>
                                如果自动创建未能成功，不用担心，您要做的只是将数据库信息填入配置文件。您也可以在文本编辑器中打开
                                <code>
                                    wp-config-sample.php
                                </code>
                                ，填入您的信息，并将其另存为
                                <code>
                                    wp-config.php
                                </code>
                                。
                            </strong>
                            需要更多帮助？
                            <a href="https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php">
                                看这里
                            </a>
                            。
                        </p>
                        <p>
                            绝大多数时候，您的网站服务提供商会给您这些信息。如果您没有这些信息，在继续之前您将需要联系他们。如果您准备好了…
                        </p>
                        <p class="step">
                            <a class="btn btn-primary" href="<?= self::url('conf'); ?>">
                                现在就开始！
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /container -->
    </body>
</html>
