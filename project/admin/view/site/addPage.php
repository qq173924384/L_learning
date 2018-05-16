<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <title>
        页面编辑
    </title>
    <link href="/css/jquery-ui.min.css" rel="stylesheet"/>
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/sweetalert.min.css" rel="stylesheet"/>
    <link href="/css/myControl.css" rel="stylesheet" type="text/css"/>
    <link href="/css/myExtend.css" rel="stylesheet" type="text/css"/>
    <script src="/js/jquery-2.2.4.min.js">
    </script>
    <script src="/js/jquery-ui.min.js">
    </script>
    <script src="/js/bootstrap.min.js" type="text/javascript">
    </script>
    <script src="/js/sweetalert.min.js">
    </script>
    <script src="/js/myControl.js" type="text/javascript">
    </script>
    <script src="/js/myExtend.js">
    </script>
    <script type="text/javascript" id='json_article'><?=json_encode($article);?></script>
    <script type="text/javascript" id='json_breadcrumb'><?=json_encode($breadcrumb);?></script>
    <script>
        $(function() {
            $('#show').on('click', function() {
                $('.sidebar').toggle();
                $('#main').toggleClass('col-md-10 col-md-12 editor jumbotron');
            })
            $('#save').on('click', function() {
                var data = {
                    title:'test',
                    html:$('#main').html()
                };
                $.myPost('', data);
            })

            $.myControlInit('#catalog div.panel-collapse>ul>li.list-group-item', '.editor>div', '.myControl', '#myModal', '#myModalDel', '#myModalSave');
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" class="navbar-toggle collapsed" data-target="#navbar" data-toggle="collapse" type="button">
                    <span class="sr-only">
                        Toggle navigation
                    </span>
                    <span class="icon-bar">
                    </span>
                    <span class="icon-bar">
                    </span>
                    <span class="icon-bar">
                    </span>
                </button>
                <a class="navbar-brand" href="#">
                    Project name
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <div class="navbar-form navbar-left">
                    <button class="btn btn-primary" id="show">
                        预览/编辑
                    </button>
                    <button class="btn btn-primary" id="save">
                        保存
                    </button>
                </div>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 no-padding sidebar">
                <div class="panel-group" id="catalog">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a data-parent="#catalog" data-toggle="collapse" href="#collapseOne">
                                <h3 class="text-primary">
                                    简单静态控件
                                </h3>
                            </a>
                        </div>
                        <div class="panel-collapse collapse" id="collapseOne">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&IMG">
                                            <img class="img-responsive img-thumbnail" src="/img/photo-1417128281290-30a42da46277.jpg"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            图片
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&A&IMG">
                                            <a href="javascript:void(0);">
                                                <img class="img-responsive img-thumbnail" src="/img/photo-1417128281290-30a42da46277.jpg"/>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            带链接的图片
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&P">
                                            <p>
                                                Git是一个分布式的版本控制系统，最初由Linus Torvalds编写，用作Linux内核代码的管理。在推出后，Git在其它项目中也取得了很大成功，尤其是在Ruby社区中。
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            段落
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&A.btn">
                                            <a class="btn btn-primary btn-lg btn-block" href="javascript:void(0);" role="button">
                                                链接按钮
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            链接按钮
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&H1">
                                            <h1>
                                                h1. 这是一套可视化布局系统.
                                            </h1>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            一级标题
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&H2">
                                            <h2>
                                                h2. 这是一套可视化布局系统.
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            二级标题
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&H3">
                                            <h3>
                                                h3. 这是一套可视化布局系统.
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            三级标题
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&H4">
                                            <h4>
                                                h4. 这是一套可视化布局系统.
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            四级标题
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&H5">
                                            <h5>
                                                h5. 这是一套可视化布局系统.
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            五级标题
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&H6">
                                            <h6>
                                                h6. 这是一套可视化布局系统.
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            六级标题
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="text-primary">
                                <a data-parent="#catalog" data-toggle="collapse" href="#collapseOne2">
                                    组合静态控件
                                </a>
                            </h3>
                        </div>
                        <div class="panel-collapse collapse" id="collapseOne2">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&DIV.carousel">
                                            <div class="carousel slide" data-ride="carousel" id="carousel_id">
                                                <!-- Wrapper for slides -->
                                                <div class="carousel-inner" role="listbox">
                                                    <div class="item active" data-type="A&A IMG&P.carousel-caption">
                                                        <a href="javascript:void(0);" target="_blank">
                                                            <img src="/img/photo-1417128281290-30a42da46277.jpg"/>
                                                            <p class="carousel-caption">
                                                                文字说明
                                                            </p>
                                                        </a>
                                                    </div>
                                                    <div class="item" data-type="A&A IMG&P.carousel-caption">
                                                        <a href="javascript:void(0);" target="_blank">
                                                            <img src="/img/photo-1417128281290-30a42da46277.jpg"/>
                                                            <p class="carousel-caption">
                                                                文字说明
                                                            </p>
                                                        </a>
                                                    </div>
                                                    <div class="item" data-type="A&A IMG&P.carousel-caption">
                                                        <a href="javascript:void(0);" target="_blank">
                                                            <img src="/img/photo-1417128281290-30a42da46277.jpg"/>
                                                            <p class="carousel-caption">
                                                                文字说明
                                                            </p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <a class="left carousel-control" data-slide="prev" href="#carousel_id" role="button">
                                                    <span aria-hidden="true" class="glyphicon glyphicon-chevron-left">
                                                    </span>
                                                    <span class="sr-only">
                                                        Previous
                                                    </span>
                                                </a>
                                                <a class="right carousel-control" data-slide="next" href="#carousel_id" role="button">
                                                    <span aria-hidden="true" class="glyphicon glyphicon-chevron-right">
                                                    </span>
                                                    <span class="sr-only">
                                                        Next
                                                    </span>
                                                </a>
                                                <ol class="carousel-indicators">
                                                    <li class="active" data-slide-to="0" data-target="#carousel_id">
                                                    </li>
                                                    <li data-slide-to="1" data-target="#carousel_id">
                                                    </li>
                                                    <li data-slide-to="2" data-target="#carousel_id">
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            轮播图片
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&A&IMG&P">
                                            <a href="javascript:void(0);">
                                                <div class="thumbnail">
                                                    <img src="/img/photo-1417128281290-30a42da46277.jpg"/>
                                                    <div class="caption">
                                                        <p>Git是一个分布式的版本控制系统，最初由Linus Torvalds编写，用作Linux内核代码的管理。在推出后，Git在其它项目中也取得了很大成功，尤其是在Ruby社区中。</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            缩略图文
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="myControl col-md-12" data-type="DIV&A.navbar-brand&UL.nav">
                                            <nav class="navbar navbar-default">
                                                <div class="container-fluid">
                                                    <div class="navbar-header">
                                                        <button aria-expanded="false" class="navbar-toggle collapsed" data-target="#bs-example-navbar-collapse" data-toggle="collapse" type="button">
                                                            <span class="sr-only">
                                                                菜单
                                                            </span>
                                                            <span class="icon-bar">
                                                            </span>
                                                            <span class="icon-bar">
                                                            </span>
                                                            <span class="icon-bar">
                                                            </span>
                                                        </button>
                                                        <a class="navbar-brand" href="javascript:void(0);">
                                                            主站
                                                        </a>
                                                    </div>
                                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse">
                                                        <ul class="nav navbar-nav navbar-right">
                                                            <li class="active">
                                                                <a href="javascript:void(0);">
                                                                    激活链接
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);">
                                                                    链接
                                                                </a>
                                                            </li>
                                                            <li class="dropdown">
                                                                <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                                                                    下拉菜单
                                                                    <span class="caret">
                                                                    </span>
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a href="#">
                                                                            下拉链接
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">
                                                                            下拉链接
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </nav>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            导航条
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="text-primary">
                                <a data-parent="#catalog" data-toggle="collapse" href="#collapseOne3">
                                    动态数据组件
                                </a>
                            </h3>
                        </div>
                        <div class="panel-collapse collapse in" id="collapseOne3">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-12 myControl ui-sortable-handle" data-cate="14" data-limit="5" data-source="/article-cate.json" data-target="_blank" data-type="DBAC&DIV" style="position: relative;">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <a href="http://l.test.com/cate/id/14.html" target="_blank">
                                                        <h4>
                                                            <strong>
                                                                默认分类
                                                            </strong>
                                                        </h4>
                                                    </a>
                                                </div>
                                                <ul class="list-group">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="text-primary text-center">
                                            文章列表
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 editor jumbotron" id="main">
                <?= $html; ?>
            </div>
        </div>
    </div>
    <div class="modal container fade" id="myModal" tabindex="-1">
        <div class="modal-header">
            <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                ×
            </button>
            <h4 class="modal-title">
                控件编辑
            </h4>
        </div>
        <div class="modal-body">
            <form>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal" type="button">
                关闭
            </button>
            <button class="btn btn-danger" id="myModalDel" type="button">
                删除
            </button>
            <button class="btn btn-primary" id="myModalSave" type="button">
                修改
            </button>
        </div>
    </div>
    <div class="modal container fade" id="controlModal" tabindex="-1">
        <div class="modal-header">
            <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                ×
            </button>
            <h4 class="modal-title">
                子控件编辑
            </h4>
        </div>
        <div class="modal-body">
            <form>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal" type="button">
                关闭
            </button>
            <button class="btn btn-danger" id="controlModalDel" type="button">
                删除
            </button>
            <button class="btn btn-primary" id="controlModalSave" type="button">
                修改
            </button>
        </div>
    </div>
</body>
</html>
