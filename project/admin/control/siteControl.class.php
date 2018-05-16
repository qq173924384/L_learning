<?php
class siteControl extends AdminControl
{
    /**
     * 站点列表
     */
    public function index()
    {
        self::checkLogin();
        self::assign('sidebar', self::getClass());

        $mod = Model::build('site');
        self::assign('site', $mod->select());

        self::layout();
    }

    /**
     * 站点编辑
     */
    public function indexAjax()
    {
        self::checkLogin();
        $mod = Model::build('site');
        if (isset($_POST['delete']) && $_POST['id']) {
            self::returnRes($mod->delete(['id' => intval($_POST['id'])]), '删除成功');
        }
        $name  = self::str_trim($_POST['name']);
        $brief = self::str_trim($_POST['brief']);
        $url   = self::str_trim($_POST['url']);
        self::checkParameter($name && $brief && ($url || $_POST['id'] == '1'));
        $data = [
            'name'  => $name,
            'brief' => $brief,
            'url'   => $url,
        ];
        if ($id = intval($_POST['id'])) {
            self::returnRes($mod->update($data, ['id' => $id]), '修改成功');
        } else {
            self::returnRes($mod->insert($data), '添加成功!');
        }
    }

    /**
     * 页面管理
     */
    public function page()
    {
        self::checkLogin();
        $this->assign('sidebar', $this->getClass());

        $mod = Model::build('page');
        self::assign('page', $mod->select());

        $this->layout();
    }
    public function pageAjax()
    {
        self::checkLogin();
        if (isset($_GET['kv']) && $_GET['kv'] == 'cate') {
            $site_id = intval($_SESSION['admin']['site_id']);
            self::checkParameter($site_id);
            self::returnRes(Model::build('cate')->getKeyValue($site_id));
        }
        $mod = Model::build('page');
        if (isset($_POST['delete']) && $_POST['id']) {
            self::returnRes($mod->delete(['id' => intval($_POST['id'])]), '删除成功');
        }
        $name        = self::str_trim($_POST['name']);
        $brief       = self::str_trim($_POST['brief']);
        $url         = self::str_trim($_POST['url']);
        $keywords    = self::str_trim($_POST['keywords']);
        $description = self::str_trim($_POST['description']);
        self::checkParameter($name && $brief && ($url || $_POST['id'] == '1'));
        $data = [
            'name'        => $name,
            'brief'       => $brief,
            'url'         => $url,
            'keywords'    => $keywords,
            'description' => $description,
        ];
        if ($id = intval($_POST['id'])) {
            self::returnRes($mod->update($data, ['id' => $id]), '修改成功');
        } else {
            self::returnRes($mod->insert($data), '添加成功!');
        }
    }
    public function addPage()
    {
        self::checkLogin();
        $page      = isset($_GET['page']) ? $_GET['page'] : 'index';
        $site_mod  = Model::build('site');
        $page_mod  = Model::build('page');
        $site      = $site_mod->field('url')->selectOne(['id' => intval($_SESSION['admin']['site_id'])]);
        $site_name = $site['url'] ?: '';

        switch ($page) {
            case 'cate':
            case 'article':
                $path = PROJECT_PATH . $page . '/view/index/' . ($site_name ?: 'index') . '.php';
                break;

            default:
                $path = ROOT_PATH . 'public/' . $site_name . '/' . $page . '.html';
                break;
        }
        if ($_POST) {
            $title       = self::str_trim($_POST['title']);
            $keywords    = self::str_trim($_POST['keywords']);
            $description = self::str_trim($_POST['description']);
            $html        = self::str_trim($_POST['html']);
            if (in_array($page, ['cate', 'article'])) {
                self::returnRes(file_put_contents($path, $page_mod->phpPage($html, $title)));
            } else {
                self::returnRes(file_put_contents($path, $page_mod->htmlPage($html, $title, '$keywords', '$description')));
            }
        } else {
            $html = file_get_contents($path);
            if (in_array($page, ['cate', 'article'])) {
                $breadcrumb = Model::build('cate')->getBreadCrumb();
                $article    = [
                    'title'       => '文章标题',
                    'content'     => '文章内容',
                    'create_time' => '发布日期',
                    'edit_time'   => '修改日期',
                ];
                $this->assign('article', $article);
                $this->assign('breadcrumb', $breadcrumb);
            } else {
                $this->assign('article', '');
                $this->assign('breadcrumb', '');
            }
            preg_match("/<body.*?>(.*?)<\/body>/is", $html, $html);
            $html = $html[1];
            $this->assign('html', $html);

            $this->display();
        }
    }
    public function gallery()
    {
        self::checkLogin();
        $this->assign('sidebar', $this->getClass());

        $mod   = Model::build('gallery');
        $where = ['admin_id' => intval($_SESSION['admin_id'])];

        self::assign('gallery', $mod->select($where));

        $this->layout();
    }
    public function galleryAjax()
    {
        self::checkLogin();
        $mod = Model::build('gallery');
        if (isset($_POST['delete']) && $_POST['id']) {
            $where = ['id' => intval($_POST['id'])];
            $img   = $mod->selectOne($where);
            $file  = ROOT_PATH . 'public' . $img['src'];
            if (file_exists($file)) {
                unlink($file);
            }
            self::returnRes($mod->delete($where), '删除成功');

        }
        if (isset($_FILES['file_data'])) {
            $mod  = Model::build('gallery');
            $data = ['admin_id' => intval($_SESSION['admin_id'])];
            if (is_array($_FILES['file_data']['tmp_name'])) {
                foreach ($_FILES['file_data']['tmp_name'] as $key => $file) {

                    $name = $_FILES['file_data'][$key]['name'];

                    $file_type = strrchr($name, '.');
                    $file_name = md5(uniqid('',1));
                    $newloc    = '/upload/img/' . $file_name . $file_type;
                    move_uploaded_file($file, ROOT_PATH . 'public' . $newloc);
                    $data['name']      = $name;
                    $data['src']       = $newloc;
                    $data['edit_time'] = date('Y-m-d H:i:s');
                    $mod->insert($data);
                }
            } else {

                $file = $_FILES['file_data']['tmp_name'];
                $name = $_FILES['file_data']['name'];

                $file_type = strrchr($name, '.');
                $file_name = md5(uniqid('',1));
                $newloc    = '/upload/img/' . $file_name . $file_type;
                move_uploaded_file($file, ROOT_PATH . 'public' . $newloc);
                $data['name']      = $name;
                $data['src']       = $newloc;
                $data['edit_time'] = date('Y-m-d H:i:s');
                $mod->insert($data);
            }
            die(json_encode('success'));
        }
        $id   = intval($_POST['id']);
        $name = $_POST['name'];
        self::checkParameter($id && $name);
        $data     = ['name' => $name];
        $where    = ['id' => $id];
        self::returnRes($mod->update($data, $where));
    }
    public function article()
    {
        self::checkLogin();
        self::assign('sidebar', self::getClass());

        $site_id = intval($_SESSION['admin']['site_id']);

        $mod   = Model::build('article');
        $where = ['site_id' => $site_id];
        if (isset($_POST['cate'])) {
            $where['cate_id'] = intval($_POST['cate']);
        }
        self::assign('article', $mod->select($where));
        self::assign('cate', Model::build('cate')->getListByPid($site_id, 0));

        self::layout();
    }
    public function articleAjax()
    {
        self::checkLogin();
        $mod = Model::build('article');
        if (isset($_POST['delete']) && $_POST['id']) {
            self::returnRes($mod->delete(['id' => intval($_POST['id'])]), '删除成功');
        }
        self::checkParameter(false);
    }
    public function addArticle()
    {
        self::checkLogin();
        self::assign('sidebar', self::getClass());

        $id      = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $site_id = intval($_SESSION['admin']['site_id']);

        $mod = Model::build('article');
        self::assign('article', $mod->selectOne(['id' => $id, 'site_id' => $site_id]));
        self::assign('cate', Model::build('cate')->getListByPid($site_id, 0));

        self::layout();
    }
    public function addArticleAjax()
    {
        self::checkLogin();
        $site_id = intval($_SESSION['admin']['site_id']);

        $cate_id     = intval($_POST['cate']);
        $title       = self::str_trim($_POST['title']);
        $keywords    = self::str_trim($_POST['keywords']);
        $description = self::str_trim($_POST['description']);
        $content     = self::str_trim($_POST['content']);
        self::checkParameter($title && $site_id && $cate_id);
        $mod  = Model::build('article');
        $time = date('Y-m-d H:i:s');
        $data = [
            'title'       => $title,
            'cate_id'     => $cate_id,
            'keywords'    => $keywords,
            'description' => $description,
            'content'     => $content,
            'edit_time'   => $time,
        ];
        if ($id = intval($_POST['id'])) {
            self::returnRes($mod->update($data, ['id' => $id, 'site_id' => $site_id]), '修改成功');
        } else {
            $data['create_time'] = $time;
            $data['site_id']     = $site_id;
            self::returnRes($mod->insert($data), '添加成功!');
        }
    }
    public function cate()
    {
        self::checkLogin();
        self::assign('sidebar', self::getClass());

        $site_id = intval($_SESSION['admin']['site_id']);
        self::assign('cate', Model::build('cate')->getListByPid($site_id, 0));

        self::layout();
    }
    public function cateAjax()
    {
        self::checkLogin();
        $site_id = intval($_SESSION['admin']['site_id']);
        $mod     = Model::build('cate');
        if (isset($_POST['get'])) {
            $id    = intval($_POST['id']);
            $cate  = $mod->field('tree')->selectOne(['id' => $id]);
            $where = [
                'id'   => ['<>', $id],
                'tree' => ['not like', $cate['tree'] . '-%'],
            ];
            self::returnRes($mod->field('id,name')->order('tree')->select($where));
        }
        if (isset($_POST['delete']) && $_POST['id']) {
            self::returnRes($mod->deleteCate($site_id, intval($_POST['id'])), '删除成功');
        }
        $name = self::str_trim($_POST['name']);
        $pid  = intval($_POST['parent']);
        self::checkParameter($name);
        if ($id = intval($_POST['id'])) {
            self::returnRes($mod->editCate($site_id, $id, $name, $pid), '修改成功');
        } else {
            self::returnRes($mod->addCate($site_id, $name, $pid), '添加成功!');
        }
    }
}
