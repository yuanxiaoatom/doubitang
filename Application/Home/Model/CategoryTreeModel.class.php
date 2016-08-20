<?php
namespace Home\Model;

use Think\Model;

class CategoryTreeModel extends Model
{

    /**
     * 根据pid获取id列表
     * 
     * @param number $pid            
     */
    public function getIdByPid($pid = 0)
    {
        return $this->field('id')
            ->where('pid = ' . $pid)
            ->select();
    }
    public function getTitleById($id,$pid) {
        return $this->field('title')
        ->where('id = '.$id)
        ->where('pid = '.$pid)
        ->find();
    }
}