<link href="assets/css/dataTables.bootstrap.css" rel="stylesheet" />
<div class="row">
	<div class="col-xs-12 col-md-12">
		<div class="widget">
			<div class="widget-header ">
				<span class="widget-caption">管理员列表</span>
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
					<a id="editabledatatable_new" href="javascript:void(0);" class="btn btn-default">
						添加管理员
					</a>
				</div>
				<table class="table table-striped table-hover table-bordered" id="editabledatatable">
					<thead>
						<tr role="row">
							<th>
								ID
							</th>
							<th>
								账号
							</th>
							<th>
								操作
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($admin as $value): ?>
							<tr>
								<td><?= $value['id']; ?></td>
								<td><?= $value['login']; ?></td>
								<td>
									<a href="javascript:void(0);" class="btn btn-info btn-xs edit" data-id="<?= $value['id']; ?>" data-login="<?= $value['login']; ?>" data-site="<?= $value['site_id']; ?>" data-role="<?= $value['role_id']; ?>">
										<i class="fa fa-edit"></i> 编辑
									</a>
									<a href="javascript:void(0);" class="btn btn-danger btn-xs delete" data-id="<?= $value['id']; ?>">
										<i class="fa fa-delete"></i> 删除
									</a>
								</td>
							</tr>
						<?php endforeach;?>
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
					添加/编辑管理员
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-2 control-label">账号</label>
						<div class="col-sm-10">
							<input type="text" name="login" class="form-control" placeholder="请输入角色名称"/>
							<input type="hidden" name="id"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">所属站点</label>
						<div class="col-sm-10">
							<select class="form-control" name="site">
								<?php foreach ($site as $key => $value): ?>
									<option value="<?= $value['id']; ?>">
										<?= $value['name']; ?>
									</option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">角色</label>
						<div class="col-sm-10">
							<select class="form-control" name="role">
								<?php foreach ($role as $key => $value): ?>
									<option value="<?= $value['id']; ?>">
										<?= $value['name']; ?>
									</option>
								<?php endforeach ?>
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
	    var load = function(id, login, site_id, role_id) {
	        $('#myModal form input[name="id"]').val(id);
	        $('#myModal form input[name="login"]').val(login);
	        $('#myModal form option').prop('selected', false);
	        $('#myModal form select[name="site"] option[value="' + site_id + '"]').prop('selected', true);
	        $('#myModal form select[name="role"] option[value="' + role_id + '"]').prop('selected', true);
	    },ajax_url = '<?= self::url("indexAjax"); ?>';
	    $('#editabledatatable_new').on('click', function() {
	    	load(0,'',0,0);
	        $('#myModal').modal();
	    })
	    $('#editabledatatable a.edit').on('click', function() {
	        load($(this).data('id'), $(this).data('login'), $(this).data('site'), $(this).data('role'));
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
	            login: form.find('input[name="login"]').val(),
	            site_id: form.find('select[name="site"]').val(),
	            role_id: form.find('select[name="role"]').val()
	        };
	        $.myPost(ajax_url, data);
	    })
	})
</script>
