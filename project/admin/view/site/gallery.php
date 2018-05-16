<link href="/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .img_title{
        display:block;white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
</style>
<script src="/js/fileinput.min.js" type="text/javascript"></script>
<script src="/js/locales/zh.js" type="text/javascript"></script>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="widget">
            <div class="widget-header ">
                <span class="widget-caption">添加图片</span>
                <div class="widget-buttons">
                    <a href="#" data-toggle="maximize">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a href="#" data-toggle="collapse">
                        <i class="fa fa-minus"></i>
                    </a>
                    <a href="#" data-toggle="dispose">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="widget-body">
                <div class="table-toolbar">
                    <input class="file" id="file-1" multiple="" type="file"></input>
                </div>
            </div>
        </div>
        <div class="widget">
            <div class="widget-header ">
                <span class="widget-caption">图库</span>
                <div class="widget-buttons">
                    <a href="#" data-toggle="maximize">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a href="#" data-toggle="collapse">
                        <i class="fa fa-minus"></i>
                    </a>
                    <a href="#" data-toggle="dispose">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="widget-body">
                <div class="row">
                    <?php if ($gallery): ?>
                        <?php foreach ($gallery as $key => $value): ?>
                            <div class="col-md-2">
                                <div class="thumbnail">
                                    <a href="javascript:void(0);" class="edit" data-id="<?= $value['id']; ?>" data-name="<?= $value['name']; ?>" data-src="<?= $value['src']; ?>">
                                        <img src="<?= $value['src']; ?>" alt="<?= $value['name']; ?>"/>
                                    </a>
                                    <div class="caption">
                                        <p class="img_title"><?= $value['name']; ?></p>
                                        <p>
                                            <a href="javascript:void(0);" class="btn btn-primary edit" role="button" data-id="<?= $value['id']; ?>" data-name="<?= $value['name']; ?>" data-src="<?= $value['src']; ?>">编辑</a>
                                            <a href="javascript:void(0);" class="btn btn-danger delete" role="button" data-id="<?= $value['id']; ?>">删除</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach?>
                    <?php endif?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    编辑图片
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">图片名称</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" placeholder="请输入图片名称"/>
                            <input type="hidden" name="id"/>
                        </div>
                    </div>
                    <img class="img-responsive img-thumbnail" src=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="commit">
                    提交更改
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#file-1").fileinput({
        language: 'zh',
        uploadUrl: '<?= self::url("galleryAjax"); ?>',
        allowedFileExtensions : ['jpg','jpeg','png','gif'],
        overwriteInitial: false,
        maxFileSize: 4096,
        maxFilesNum: 1,
        allowedFileTypes: ['image']
    });
    $("#file-1").on("fileuploaded", function (event, data, previewId, index) {
        swal({
            title: "成功！",
            text: "上传成功",
            type: "success"
        }, function() {
            location.reload();
        });
    });
    $(document).ready(function() {
        var load = function(id, name, src) {
                $('#myModal form input[name="id"]').val(id);
                $('#myModal form input[name="name"]').val(name);
                $('#myModal form img').attr('src', src);
            },
            ajax_url = '<?= self::url("galleryAjax"); ?>';
        $('a.edit').on('click', function() {
            load($(this).data('id'), $(this).data('name'), $(this).data('src'));
            $('#myModal').modal();
        })
        $('a.delete').on('click', function() {
            var id = $(this).data('id');
            $.myDelAlert(function() {
                $.myPost(ajax_url, { id: id, delete: 1});
            });
        })
        $('#commit').on('click', function() {
            var form = $('#myModal form'),
                data = {
                    id: form.find('input[name="id"]').val(),
                    name: form.find('input[name="name"]').val()
                };
            $.myPost(ajax_url, data);
        })
    });
</script>