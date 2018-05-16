<?php
class indexControl extends Control
{
    public function index()
    {
        session_start();
        date_default_timezone_set("Asia/chongqing");
        error_reporting(E_ERROR);
        $config = self::getConf();
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result = json_encode($config);
                break;
            case 'uploadimage':
            case 'uploadscrawl':
            case 'uploadvideo':
            case 'uploadfile':
                $result = self::action_upload($config);
                break;
            case 'listimage':
            case 'listfile':
                $result = self::action_list($config);
                break;
            case 'catchimage':
                $result = self::action_crawler($config);
                break;

            default:
                $result = json_encode(array(
                    'state' => '请求地址出错',
                ));
                break;
        }
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法',
                ));
            }
        } else {
            echo $result;
        }
    }
    protected static function action_crawler($conf)
    {
        set_time_limit(0);
        $config = array(
            "pathFormat" => $conf['catcherPathFormat'],
            "maxSize"    => $conf['catcherMaxSize'],
            "allowFiles" => $conf['catcherAllowFiles'],
            "oriName"    => "remote.png",
        );
        $fieldName = $conf['catcherFieldName'];

        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state"    => $info["state"],
                "url"      => $info["url"],
                "size"     => $info["size"],
                "title"    => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source"   => htmlspecialchars($imgUrl),
            ));
        }
        return json_encode(array(
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list'  => $list,
        ));
    }
    protected static function action_list($conf)
    {
        switch ($_GET['action']) {
            case 'listfile':
                $allowFiles = $conf['fileManagerAllowFiles'];
                $listSize   = $conf['fileManagerListSize'];
                $path       = $conf['fileManagerListPath'];
                break;
            case 'listimage':
            default:
                $allowFiles = $conf['imageManagerAllowFiles'];
                $listSize   = $conf['imageManagerListSize'];
                $path       = $conf['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        $size  = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end   = $start + $size;
        $path  = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "" : "/") . $path;
        $files = self::getfiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list"  => array(),
                "start" => $start,
                "total" => count($files),
            ));
        }
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list"  => $list,
            "start" => $start,
            "total" => count($files),
        ));
        return $result;
    }
    protected static function getfiles($path, $allowFiles, &$files = array())
    {
        if (!is_dir($path)) {
            return null;
        }

        if (substr($path, strlen($path) - 1) != '/') {
            $path .= '/';
        }

        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    self::getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                        $files[] = array(
                            'url'   => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime' => filemtime($path2),
                        );
                    }
                }
            }
        }
        return $files;
    }
    protected static function action_upload($conf)
    {
        $base64 = "upload";
        switch (htmlspecialchars($_GET['action'])) {
            case 'uploadimage':
                $config = array(
                    "pathFormat" => $conf['imagePathFormat'],
                    "maxSize"    => $conf['imageMaxSize'],
                    "allowFiles" => $conf['imageAllowFiles'],
                );
                $fieldName = $conf['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $conf['scrawlPathFormat'],
                    "maxSize"    => $conf['scrawlMaxSize'],
                    "allowFiles" => $conf['scrawlAllowFiles'],
                    "oriName"    => "scrawl.png",
                );
                $fieldName = $conf['scrawlFieldName'];
                $base64    = "base64";
                break;
            case 'uploadvideo':
                $config = array(
                    "pathFormat" => $conf['videoPathFormat'],
                    "maxSize"    => $conf['videoMaxSize'],
                    "allowFiles" => $conf['videoAllowFiles'],
                );
                $fieldName = $conf['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = array(
                    "pathFormat" => $conf['filePathFormat'],
                    "maxSize"    => $conf['fileMaxSize'],
                    "allowFiles" => $conf['fileAllowFiles'],
                );
                $fieldName = $conf['fileFieldName'];
                break;
        }
        $up = new Uploader($fieldName, $config, $base64);
        return json_encode($up->getFileInfo());
    }
    protected static function getConf()
    {
        $id = intval($_SESSION['admin_id']);
        return [
            'imageActionName'         => 'uploadimage',
            'imageFieldName'          => 'upfile',
            'imageMaxSize'            => 2048000,
            'imageAllowFiles'         => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
            'imageCompressEnable'     => true,
            'imageCompressBorder'     => 1600,
            'imageInsertAlign'        => 'none',
            'imageUrlPrefix'          => '',
            'imagePathFormat'         => '/upload/' . $id . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'scrawlActionName'        => 'uploadscrawl',
            'scrawlFieldName'         => 'upfile',
            'scrawlPathFormat'        => '/upload/' . $id . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'scrawlMaxSize'           => 2048000,
            'scrawlUrlPrefix'         => '',
            'scrawlInsertAlign'       => 'none',
            'snapscreenActionName'    => 'uploadimage',
            'snapscreenPathFormat'    => '/upload/' . $id . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'snapscreenUrlPrefix'     => '',
            'snapscreenInsertAlign'   => 'none',
            'catcherLocalDomain'      => ['127.0.0.1', 'localhost', 'img.baidu.com'],
            'catcherActionName'       => 'catchimage',
            'catcherFieldName'        => 'source',
            'catcherPathFormat'       => '/upload/' . $id . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'catcherUrlPrefix'        => '',
            'catcherMaxSize'          => 2048000,
            'catcherAllowFiles'       => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
            'videoActionName'         => 'uploadvideo',
            'videoFieldName'          => 'upfile',
            'videoPathFormat'         => '/upload/' . $id . '/video/{yyyy}{mm}{dd}/{time}{rand:6}',
            'videoUrlPrefix'          => '',
            'videoMaxSize'            => 102400000,
            'videoAllowFiles'         => ['.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'],
            'fileActionName'          => 'uploadfile',
            'fileFieldName'           => 'upfile',
            'filePathFormat'          => '/upload/' . $id . '/file/{yyyy}{mm}{dd}/{time}{rand:6}',
            'fileUrlPrefix'           => '',
            'fileMaxSize'             => 51200000,
            'fileAllowFiles'          => ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid', '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'],
            'imageManagerActionName'  => 'listimage',
            'imageManagerListPath'    => '/upload/' . $id . '/image/',
            'imageManagerListSize'    => 20,
            'imageManagerUrlPrefix'   => '',
            'imageManagerInsertAlign' => 'none',
            'imageManagerAllowFiles'  => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
            'fileManagerActionName'   => 'listfile',
            'fileManagerListPath'     => '/upload/' . $id . '/file/',
            'fileManagerUrlPrefix'    => '',
            'fileManagerListSize'     => 20,
            'fileManagerAllowFiles'   => ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid', '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'],
        ];
    }
}
