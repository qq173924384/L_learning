<link href="assets/css/dataTables.bootstrap.css" rel="stylesheet" />
<div class="row">
	<div class="col-xs-12 col-md-12">
		<div class="widget">
			<div class="widget-header ">
				<span class="widget-caption">文章列表</span>
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
					<a id="editabledatatable_new" href="<?= self::url('addArticle'); ?>" class="btn btn-success" data-id="0">
						添加文章
					</a>
				</div>
				<table class="table table-striped table-hover table-bordered" id="editabledatatable">
					<thead>
						<tr role="row">
							<th>
								ID
							</th>
							<th>
								文章标题
							</th>
							<th>
								操作
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($article as $value): ?>
							<tr>
								<td><?= $value['id']; ?></td>

								<td>
									<?= $value['title']; ?>
								</td>
								<td>
									<a href="<?= self::url('addArticle',['id'=>$value['id']]); ?>" class="btn btn-info btn-xs edit"><i class="fa fa-edit"></i> 编辑</a>
									<a href="javascript:void(0);" class="btn btn-danger btn-xs delete" data-id="<?= $value['id']; ?>"><i class="fa fa-delete"></i> 删除</a>
								</td>
							</tr>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					添加/编辑站点
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-2 control-label">分类名称</label>
						<div class="col-sm-10">
							<input type="text" name="name" class="form-control" placeholder="请输入站点名称"/>
							<input type="hidden" name="id"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">父分类</label>
						<div class="col-sm-10">
							<select class="form-control" name="parent">
							</select>
						</div>
					</div>
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
<script type="text/javascript">
	$(document).ready(function() {
	    var ajax_url = '<?= self::url("cateAjax"); ?>',
	        load = function(id, name, parent) {
	            $('#myModal form input[name="id"]').val(id);
	            $('#myModal form input[name="name"]').val(name);
	            $.ajax(ajax_url, {
	                type: 'post',
	                dataType: 'json',
	                data: {
	                    get: 1,
	                    id: id
	                },
	                async: false,
	                success: function(data) {
	                    html = '';
	                    if (0 == parent) {
	                        html += '<option value="0" selected="selected">无父分类</option>';
	                    } else {
	                        html += '<option value="0">无父分类</option>';
	                    }
	                    for (var i in data.data) {
	                        var item = data.data[i];
	                        if (item.id == parent) {
	                            html += '<option value="' + item.id + '" selected="selected">' + item.name + '</option>';
	                        } else {
	                            html += '<option value="' + item.id + '">' + item.name + '</option>';
	                        }
	                    }
	                    $('#myModal form select[name="parent"]').html(html);
	                }
	            });
	        };
	    $('#editabledatatable_new').on('click', function() {
	        load(0, '', 0);
	        $('#myModal').modal();
	    })
	    $('#editabledatatable a.edit').on('click', function() {
	        load($(this).data('id'), $(this).data('name'), $(this).data('parent'));
	        $('#myModal').modal();
	    })
	    $('#editabledatatable a.delete').on('click', function() {
	        var id = $(this).data('id');
		    $.myDelAlert(function() {
		    	$.myPost(ajax_url, { id: id, delete: 1});
		    });
	    })
	    $('#commit').on('click', function() {
	        var form = $('#myModal form'),
	            data = {
	                id: form.find('input[name="id"]').val(),
	                name: form.find('input[name="name"]').val(),
	                parent: form.find('select[name="parent"]').val()
	            };
	        $.myPost(ajax_url, data);
	    })
	})
</script>
