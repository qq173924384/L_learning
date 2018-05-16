<?php
/**
*
*/
class MyExtend
{


	/**
	 * 求组合字符串
	 * @param  [type] $arr [description]
	 * @param  [type] $m   [description]
	 * @return [type]      [description]
	 */
	static function getCombinationToString($arr, $m)
	{
	    $result = array();
	    if ($m == 1) {
	        return $arr;
	    }
	    if ($m == count($arr)) {
	        $result[] = implode(',', $arr);
	        return $result;
	    }
	    $temp_firstelement = $arr[0];
	    unset($arr[0]);
	    $arr        = array_values($arr);
	    $temp_list1 = getCombinationToString($arr, ($m - 1));
	    foreach ($temp_list1 as $s) {
	        $s        = $temp_firstelement . ',' . $s;
	        $result[] = $s;
	    }
	    unset($temp_list1);
	    $temp_list2 = getCombinationToString($arr, $m);
	    foreach ($temp_list2 as $s) {
	        $result[] = $s;
	    }
	    unset($temp_list2);
	    return $result;
	}
	/**
	 * 求平均数
	 * @param  [type] $list [description]
	 * @return [type]       [description]
	 */
	static function getAverage($list)
	{
	    return array_sum($list) / count($list);
	}
	/**
	 * 求方差
	 * @param  [type] $list [description]
	 * @return [type]       [description]
	 */
	static function getVariance($list)
	{
	    $variance = 0;
	    $avg      = getAverage($list);
	    foreach ($list as $lv) {
	        $variance += pow(($lv - $avg), 2);
	    }
	    return sqrt($variance / (count($list)));
	}
	/**
	 * 多样性计分
	 * @param  [type] $list [description]
	 * @return [type]       [description]
	 */
	static function countVariety($list)
	{
	    $has   = [];
	    $count = 0;
	    foreach ($list as $value) {
	        if (in_array($value, $has)) {
	            $count--;
	        } else {
	            $has[] = $value;
	            $count++;
	        }
	    }
	    return $count;
	}
	/**
	 * 性别多样性计分
	 * @param  [type] $list [description]
	 * @return [type]       [description]
	 */
	static function countSex($list)
	{
	    $count = count($list);
	    $f     = 0;
	    $m     = 0;
	    foreach ($list as $value) {
	        switch ($value) {
	            case 'F':
	                $f++;
	                break;
	            case 'M':
	                $m++;
	                break;

	            default:
	                $count--;
	                break;
	        }
	    }
	    if ($f > $m) {
	        $f = $m;
	    }
	    return $f / $count * 2;
	}
	/**
	 * 转化成简单数组
	 * @param  [type] $array [description]
	 * @param  [type] $field [description]
	 * @return [type]        [description]
	 */
	static function toSimplrArray($array, $field)
	{
	    $res = [];
	    foreach ($array as $key => $value) {
	        $res[$key] = $value[$field];
	    }
	    return $res;
	}
}