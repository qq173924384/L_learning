<?php
class indexControl extends Control
{
    public function index()
    {
        $id = intval($_GET['id']);
        if ($id) {
            $mod  = Model::build('article');
            $cate = [
                ['href' => '#', 'title' => 'Home', 'active' => false],
                ['href' => '#', 'title' => 'Library', 'active' => false],
                ['href' => '#', 'title' => 'Data', 'active' => true],
            ];
            $breadcrumb = '';
            foreach ($cate as $value) {
                if ($value['active']) {
                    $breadcrumb .= '<li class="active">' . $value['title'] . '</li>';
                } else {
                    $breadcrumb .= '<li><a href="' . $value['href'] . '">' . $value['title'] . '</a></li>';
                }
            }
            $article = $mod->field('title,description,content,create_time,edit_time')->selectOne(['id' => $id]);
            $this->assign('article', $article);
            $this->assign('breadcrumb', $breadcrumb);
            $this->display();
        } else {
            var_dump('id');
        }
    }
}
