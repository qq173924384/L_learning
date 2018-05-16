<link href="assets/css/dataTables.bootstrap.css" rel="stylesheet" />
<div class="row">
	<div class="col-xs-12 col-md-12">
		<div class="widget">
			<div class="widget-header ">
				<span class="widget-caption">角色列表</span>
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
					<a id="editabledatatable_new" href="javascript:void(0);" class="btn btn-default" data-id="0">
						添加角色
					</a>
				</div>
				<table class="table table-striped table-hover table-bordered" id="editabledatatable">
					<thead>
						<tr role="row">
							<th>
								ID
							</th>
							<th>
								角色名
							</th>
							<th>
								操作
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($role as $value): ?>
							<tr>
								<td><?= $value['id']; ?></td>
								<td><?= $value['name']; ?></td>
								<td>
									<a href="javascript:void(0);" class="btn btn-info btn-xs edit" data-id="<?= $value['id']; ?>" data-name="<?= $value['name']; ?>" data-rights='<?= json_encode($value['rights']); ?>'>
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
					添加/编辑角色
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label for="firstname" class="col-sm-2 control-label">角色名称</label>
						<div class="col-sm-10">
							<input type="text" name="name" class="form-control" placeholder="请输入角色名称"/>
							<input type="hidden" name="id"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">权限</label>
						<div class="col-sm-10">
							<div class="table-responsive">
								<table class="table table-striped table-hover table-bordered">
									<thead>
										<tr>
											<th>
												控制器类名/方法
											</th>
											<th>
												标题
											</th>
											<th>
												权限
											</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
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
       var load = function(id, name, data) {
           $('#myModal form input[name="id"]').val(id);
           $('#myModal form input[name="name"]').val(name);
           html = '';
           console.log(data)
           for (var i in sidebar) {
               var item = sidebar[i];
               html += '<tr><td>[' + i + ']</td><td>' + item.title + '</td><td><label>';
               if (data[i] == 'true') {
                   html += '<input type="checkbox" name="rights" data-key="' + i + '" checked="checked"/>';
               } else {
                   html += '<input type="checkbox" name="rights" data-key="' + i + '"/>';
               }
               html += '<span class="text">允许访问</span></label></td></tr>';
               for (var j in item.menu) {
                   var jtem = item.menu[j];
                   html += '<tr><td>└[' + j + ']</td><td>' + jtem.title + '</td><td><label>';
                   if (data[i + '-' + j] == 'true') {
                       html += '<input type="checkbox" name="rights" data-key="' + i + '-' + j + '" checked="checked"/>';
                   } else {
                       html += '<input type="checkbox" name="rights" data-key="' + i + '-' + j + '"/>';
                   }
                   html += '<span class="text">允许访问</span></label></td></tr>';
               }
           }
           $('#myModal tbody').html(html);
           $('#myModal').modal();
       },
       sidebar = <?= json_encode($sidebar); ?>,
       ajax_url = '<?= self::url("roleAjax"); ?>';
       $('#editabledatatable_new').on('click', function() {
           load(0, '', []);
       })
       $('#editabledatatable a.edit').on('click', function() {
           load($(this).data('id'), $(this).data('name'), $.parseJSON($.parseJSON($(this).data('rights'))));
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
               rights: {}
           };
           form.find('input[name="rights"]').each(function() {
               data.rights[$(this).data('key')] = $(this).prop('checked');
           });
	        $.myPost(ajax_url, data);
       })
   })
</script>
