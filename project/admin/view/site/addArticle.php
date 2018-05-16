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
					<a id="editabledatatable_new" href="javascript:void(0);" class="btn btn-primary" data-id="0">
						提交
					</a>
				</div>
				<form class="form" role="form">
					<div class="form-group">
						<label class="control-label">文章标题</label>
						<input type="text" name="title" class="form-control" placeholder="请输入文章标题" value="<?= $article ? $article['title'] : ''; ?>" />
						<input type="hidden" name="id" value="<?= $article ? $article['id'] : ''; ?>"/>
					</div>
					<div class="form-group">
						<label class="control-label">关键词</label>
						<input type="text" name="keywords" class="form-control" placeholder="请输入关键词，以逗号（,）分隔" value="<?= $article ? $article['keywords'] : ''; ?>" />
					</div>
					<div class="form-group">
						<label class="control-label">描述</label>
						<input type="text" name="description" class="form-control" placeholder="请输入描述" value="<?= $article ? $article['description'] : ''; ?>" />
					</div>
					<div class="form-group">
						<label class="control-label">文章分类</label>
						<select class="form-control" name="cate">
						<?php foreach ($cate as $value): ?>
							<option value="<?= $value['id']; ?>"<?php if ($value['id'] == ($article ? $article['cate_id'] : '')): ?> selected="selected"<?php endif?>>
								<?php
								$sub_level = substr_count($value['tree'], '-');
								if ($sub_level) {
									for ($i = 0; $i < $sub_level; $i++) {
										echo $i ? '─' : '└';
									}
								}
								?>
								[<?= $value['name']; ?>]
							</option>
						<?php endforeach?>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">内容</label>
						<script id="editor" type="text/plain" style="width:100%;height:500px;"><?= $article ? $article['content'] : ''; ?></script>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" charset="utf-8" src="/js/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/js/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var ue = UE.getEditor('editor',{
			toolbars: [['fullscreen', 'source', '|', 'undo', 'redo', '|', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|', 'rowspacingtop', 'rowspacingbottom', 'lineheight', '|', 'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|', 'directionalityltr', 'directionalityrtl', 'indent', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|', 'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|', 'simpleupload', 'insertimage', 'emotion', 'scrawl', 'map', 'insertframe', 'insertcode', 'pagebreak', '|', 'horizontal', 'date', 'time', 'spechars', 'snapscreen', '|', 'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|', 'print', 'preview', 'searchreplace', 'help']]
		});
		ajax_url = '<?= self::url("addArticleAjax"); ?>';
		$('#editabledatatable_new').on('click', function() {
			var data = {
					id:$('form input[name="id"]').val(),
					cate:$('form select[name="cate"]').val(),
					title:$('form input[name="title"]').val(),
					keywords:$('form input[name="keywords"]').val(),
					description:$('form input[name="description"]').val(),
					content:UE.getEditor('editor').getContent()
				};
			$.myPost(ajax_url, data);
		});
	})
</script>
