<?php
/**
 *
 */
class AjaxControl extends Control
{
    /**
     * ajax返回
     * @param  [type] $data   [description]
     * @param  [type] $info   [description]
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    protected static function ajaxReturn($data, $info, $status)
    {
        header("Content-Type:text/html; charset=utf-8");
        $return = json_encode(['data' => $data, 'info' => $info, 'status' => intval($status)]);
        if (isset($_GET['callback'])) {
            exit("\$callback($return)");
        } else {
            exit($return);
        }
    }
    /**
     * 错误返回
     * @param  [type]  $info   [description]
     * @param  integer $status [description]
     * @return [type]          [description]
     */
    protected static function returnErr($info, $status = 400)
    {
        self::ajaxReturn(false, $info, $status);
    }
    /**
     * 结果返回
     * @param  [type]  $data       [description]
     * @param  string  $info       [description]
     * @param  integer $err_status [description]
     * @return [type]              [description]
     */
    protected static function returnRes($data, $info = '操作成功', $err_status = 400)
    {
        if ($data === false) {
            self::returnErr('操作失败', $err_status);
        } else {
            self::ajaxReturn($data, $info, 200);
        }
    }
    /**
     * 参数过滤
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    protected static function checkParameter()
    {
        $res = true;
        foreach (func_get_args() as $value) {
            $res = $res && $value;
        }
    }
}
