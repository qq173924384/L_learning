<?php
/**
 *
 */
class pageModel extends Model
{
    public function htmlPage($html, $title, $keywords = '', $description = '')
    {
        return '<!DOCTYPE html><html><head><meta charset="utf-8"/><meta content="width=device-width, initial-scale=1" name="viewport"/><meta content="' . $keywords . '" name="description"/><meta content="' . $description . '" name="author"/><title>' . $title . '</title><link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"/><link href="/css/myExtend.css" rel="stylesheet" type="text/css"/><script src="/js/jquery-2.2.4.min.js"></script><script src="/js/bootstrap.min.js" type="text/javascript"></script><script src="/js/myControl.js" type="text/javascript"></script></head><body>' . self::trimHTML($html) . '</body></html>';
    }
    public function phpPage($html, $title)
    {
        return '<!DOCTYPE html><html><head><meta charset="utf-8"/><meta content="width=device-width, initial-scale=1" name="viewport"/><meta content="<?php echo $article[\'description\']; ?>" name="description"/><title><?php echo $article[\'title\']; ?></title><link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"/><link href="/css/myExtend.css" rel="stylesheet" type="text/css"/><script src="/js/jquery-2.2.4.min.js"></script><script src="/js/bootstrap.min.js" type="text/javascript"></script><script src="/js/myControl.js" type="text/javascript"></script><script type="text/javascript" id=\'json_article\'><?=json_encode($article);?></script><script type="text/javascript" id=\'json_breadcrumb\'><?=json_encode($breadcrumb);?></script>' . self::trimHTML($html) . '</body></html>';
    }
    protected static function trimHTML($html)
    {
        return preg_replace([
            "/<!--[^!]*-->/",
            "/>([\s]+)</",
            "/[\s]+/",
            "/> /",
            "/ </",
        ], [
            '',
            '><',
            ' ',
            '>',
            '<',
        ], trim($html));
    }
}
