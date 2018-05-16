<?php
/**
 *
 */
class cateModel extends Model
{
    public function getListByPid($site_id, $pid, $field = '')
    {
        $pid     = intval($pid);
        $site_id = intval($site_id);
        if ($pid) {
            $parent = $this->field('tree')->selectOne(['id' => $pid, 'site_id' => $site_id]);
            $where  = ['tree' => ['like', $parent['tree'] . '-%'], 'site_id' => $site_id];
        } else {
            $where = ['site_id' => $site_id];
        }
        return $this->field($field)->order('tree')->select($where);
    }
    public function addCate($site_id, $name, $pid)
    {
        $site_id = intval($site_id);
        $pid     = intval($pid);
        $parent  = $pid ? $this->field('tree')->selectOne(['id' => $pid]) : 0;
        $data    = ['name' => $name, 'parent_id' => $pid, 'site_id' => $site_id];
        $id      = $this->insert($data);
        return $this->update(['tree' => $pid ? ($parent['tree'] . '-' . $id) : $id], ['id' => $id]);
    }
    public function editCate($site_id, $id, $name, $pid)
    {
        $id      = intval($id);
        $pid     = intval($pid);
        $site_id = intval($site_id);

        $cate   = $this->field('tree')->selectOne(['id' => $id, '$site_id' => $site_id]);
        $parent = $pid ? $this->field('tree')->selectOne(['id' => $pid, '$site_id' => $site_id]) : 0;

        $where    = ['tree' => ['like', $cate['tree'] . '-%'], '$site_id' => $site_id];
        $old_tree = $cate['tree'] . '-';
        $new_tree = $pid ? ($parent['tree'] . '-' . $id . '-') : ($id . '-');
        $this->update(['tree' => ["REPLACE ( `tree`, '$old_tree', '$new_tree' )"]], $where);

        $data = ['name' => $name, 'parent_id' => $pid, 'tree' => $pid ? ($parent['tree'] . '-' . $id) : $id];
        return $this->update($data, ['id' => $id]);
    }
    public function deleteCate($site_id, $id)
    {
        $id      = intval($id);
        $site_id = intval($site_id);
        $cate    = $this->field('tree')->selectOne(['id' => $id, '$site_id' => $site_id]);
        $this->delete(['tree' => ['like', $cate['tree'] . '-%'], '$site_id' => $site_id]);
        return $this->delete(['id' => $id, '$site_id' => $site_id]);
    }
    public function getTreeByPid($site_id, $pid, $field = '')
    {
        $tree = $this->getListByPid($site_id, $pid, $field);
        return self::getChild($tree, $pid);
    }
    protected static function getChild(&$tree, $pid)
    {
        $data = [];
        foreach ($tree as $value) {
            if ($value['parent_id'] == $pid) {
                $value['child'] = self::getChild($tree, $value['id']);
                $data[]         = $value;
            }
        }
        return $data;
    }
    public function getKeyValue($site_id)
    {
        $data = $this->field('id,name,tree')->order('tree')->select(['site_id' => intval($site_id)]);
        $res  = [];
        foreach ($data as $value) {
            $key       = $value['name'];
            $sub_level = substr_count($value['tree'], '-');
            if ($sub_level) {
                for ($i = 0; $i < $sub_level; $i++) {
                    $key = ($i ? '─' : '└') . $key;
                }
            }
            $res[$key] = $value['id'];
        }
        return $res;
    }
    public function getBreadCrumb($cate = false)
    {
        $cate = $cate ?: [
            ['href' => '#', 'title' => '主页', 'active' => false],
            ['href' => '#', 'title' => '父分类', 'active' => false],
            ['href' => '#', 'title' => '当前分类', 'active' => true],
        ];
        $breadcrumb = '';
        foreach ($cate as $value) {
            if ($value['active']) {
                $breadcrumb .= '<li class="active">' . $value['title'] . '</li>';
            } else {
                $breadcrumb .= '<li><a href="' . $value['href'] . '">' . $value['title'] . '</a></li>';
            }
        }
        return $breadcrumb;
    }
}
