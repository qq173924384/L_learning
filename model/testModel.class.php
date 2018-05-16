<?php
/**
 *
 */
class testModel extends Model
{
    public function testString()
    {
        return $this->field('id a,name b')->order('id desc,name')->limit('0,3')->select(['id' => '2']);
    }
    public function testObject()
    {
        return $this->field('id a', 'name b')->order('id desc', 'name')->limit(0, 3)->select(['id' => 2]);
    }
    public function testArray()
    {
        return $this->field(['id a', 'name b'])->order(['id desc', 'name'])->limit([0, 3])->select([['id' => ['=', 2]]]);
    }
    public function testLikeString()
    {
        return $this->field(['id a', 'name b'])->order(['id desc', 'name'])->limit([0, 3])->select([['id' => ['in', '2']]]);
    }
    public function testLikeArray()
    {
        return $this->field(['id a', 'name b'])->order(['id desc', 'name'])->limit([0, 3])->select([['id' => ['in', [2]]]]);
    }
}
