<link href="assets/css/dataTables.bootstrap.css" rel="stylesheet" />
<div class="row">
	<div class="col-xs-12 col-md-12">
		<div class="widget">
			<div class="widget-header ">
				<span class="widget-caption">页面列表</span>
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
					<a href="/admin-site-addPage/page/index.html" target="_blank" class="btn btn-primary">
						主页编辑
					</a>
					<a href="/admin-site-addPage/page/cate.html" target="_blank" class="btn btn-primary">
						分类页编辑
					</a>
					<a href="/admin-site-addPage/page/article.html" target="_blank" class="btn btn-primary">
						文章页编辑
					</a>
					<a id="editabledatatable_new" href="javascript:void(0);" class="btn btn-success" data-id="0">
						添加页面
					</a>
				</div>
				<table class="table table-striped table-hover table-bordered" id="editabledatatable">
					<thead>
						<tr role="row">
							<th>
								ID
							</th>
							<th>
								页面名称
							</th>
							<th>
								描述
							</th>
							<th>
								页面路径
							</th>
							<th>
								操作
							</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($page as $value): ?>
							<tr>
								<td><?= $value['id']; ?></td>
								<td><?= $value['name']; ?></td>
								<td><?= $value['brief']; ?></td>
								<td><?= $value['url']; ?></td>
								<td>
									<a href="javascript:void(0);" class="btn btn-info btn-xs edit" data-id="<?= $value['id']; ?>" data-name="<?= $value['name']; ?>" data-brief="<?= $value['brief']; ?>" data-url="<?= $value['url']; ?>" data-keywords="<?= $value['keywords']; ?>" data-description="<?= $value['description']; ?>"><i class="fa fa-edit"></i> 编辑</a>
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
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					添加/编辑页面
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-2 control-label">页面名称</label>
						<div class="col-sm-10">
							<input type="text" name="name" class="form-control" placeholder="请输入页面名称"/>
							<input type="hidden" name="id"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">简介</label>
						<div class="col-sm-10">
							<input type="text" name="brief" class="form-control" placeholder="请输入简介"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">页面路径</label>
						<div class="col-sm-10">
							<input type="text" name="url" class="form-control" placeholder="请输入页面路径"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">关键词</label>
						<div class="col-sm-10">
							<input type="text" name="keywords" class="form-control" placeholder="请输入关键词，以,分隔"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">描述</label>
						<div class="col-sm-10">
							<input type="text" name="description" class="form-control" placeholder="请输入描述"/>
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
	$(document).ready(function () {
		var load = function(id, name, brief, url) {
			$('#myModal form input[name="id"]').val(id);
			$('#myModal form input[name="name"]').val(name);
			$('#myModal form input[name="brief"]').val(brief);
			$('#myModal form input[name="url"]').val(url);
		},ajax_url = '<?= self::url("pageAjax"); ?>';
		$('#editabledatatable_new').on('click', function() {
			load(0,'','','new_site');
			$('#myModal').modal();
		})
		$('#editabledatatable a.edit').on('click', function() {
			load($(this).data('id'), $(this).data('name'), $(this).data('brief'), $(this).data('url'));
			$('#myModal').modal();
		})
		$('#editabledatatable a.delete').on('click', function() {
			var id =$(this).data('id');
			$.myDelAlert(function() {
				$.myPost(ajax_url, { id: id, delete: 1});
			});
		})
		$('#commit').on('click', function() {
			var form = $('#myModal form'),
			data = {
				id: form.find('input[name="id"]').val(),
				name: form.find('input[name="name"]').val(),
				brief: form.find('input[name="brief"]').val(),
				url: form.find('input[name="url"]').val(),
				keywords: form.find('input[name="keywords"]').val(),
				description: form.find('input[name="description"]').val()
			};
			$.myPost(ajax_url, data);
		})
	})
</script>
