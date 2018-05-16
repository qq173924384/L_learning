<link href="assets/css/dataTables.bootstrap.css" rel="stylesheet" />

<div class="row">
	<div class="col-xs-12 col-md-12">
		<div class="widget">
			<div class="widget-header ">
				<span class="widget-caption">目录标题</span>
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
				<table class="table table-striped table-hover table-bordered" id="editabledatatable">
					<thead>
						<tr role="row">
							<th>
								控制器类名/方法
							</th>
							<th>
								标题
							</th>
							<th>
								参数
							</th>
							<th>
								操作
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($sidebar as $key => $value): ?>
							<tr>
								<td>[<?= $key; ?>]</td>
								<td><input name="title" type="text" value="<?= $value['title']; ?>" /></td>
								<td><input name="extend" type="text" value="<?= $value['icon']; ?>" /></td>
								<td>
									<a href="javascript:void(0);" class="btn btn-success btn-xs save" data-key="<?= $key; ?>" data-value="<?= $value['title']; ?>" data-type="control"><i class="fa fa-save"></i> 保存</a>
								</td>
							</tr>
							<?php foreach ($value['menu'] as $k => $v): ?>
								<tr>
									<td>└[<?= $k; ?>]</td>
									<td><input name="title" type="text" value="<?= $v['title']; ?>" /></td>
									<td><input name="extend" type="text" value="<?= $v['target']; ?>" /></td>
									<td>
										<a href="javascript:void(0);" class="btn btn-success btn-xs save" data-key="<?= "$key-$k"; ?>" data-value="<?= $value['title']; ?>" data-type="method"><i class="fa fa-save"></i> 保存</a>
									</td>
								</tr>
							<?php endforeach?>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('#editabledatatable a.save').on('click', function () {
			$.myPost("<?= self::url('setMenuAjax'); ?>", {
				key:$(this).data('key'),
				type:$(this).data('type'),
				value:$(this).parents('tr').find('input[name="title"]').val(),
				extend:$(this).parents('tr').find('input[name="extend"]').val()
			});
		})
	})
</script>
