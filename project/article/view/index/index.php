<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="<?php echo $article['description']; ?>" name="description"/>
    <title>
        <?=$article['title'];?>
    </title>
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/myExtend.css" rel="stylesheet" type="text/css"/>
    <script src="/js/jquery-2.2.4.min.js"></script>
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/js/myControl.js" type="text/javascript"></script>
    <script type="text/javascript" id='json_article'><?=json_encode($article);?></script>
    <script type="text/javascript" id='json_breadcrumb'><?=json_encode($breadcrumb);?></script>
</head>
<body>
    <div class="container">
        <ol class="breadcrumb myControl" id="breadcrumb" data-type="">
        </ol>
        <div class="panel panel-default myControl" data-type="">
            <div class="panel-heading" id="title"></div>
            <div class="panel-body" id="content"></div>
            <div class="panel-footer" id="footer"></div>
        </div>
    </div>
</body>
</html>
