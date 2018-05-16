<?php
class cateControl extends AjaxControl
{
    public function index()
    {
        $cate_id = intval($_POST['cate']);
        $limit   = intval($_POST['limit']);

        $cate_mod    = Model::build('cate');
        $cate        = $cate_mod->field('name')->selectOne(['id' => $cate_id]);
        $article_mod = Model::build('article');
        $article     = $article_mod->field('id,title,create_time')->order('create_time desc')->limit($limit)->select(['cate_id' => $cate_id]);
        foreach ($article as $key => $value) {
            $article[$key]['date'] = date('Y-m-d', strtotime($value['create_time']));
            $article[$key]['url']  = self::url('article-index-index', ['id' => $value['id']]);
        }
        self::returnRes(['title' => $cate['name'], 'title_url' => self::url('cate-index-index', ['id' => $cate_id]), 'list' => $article]);
    }
}
