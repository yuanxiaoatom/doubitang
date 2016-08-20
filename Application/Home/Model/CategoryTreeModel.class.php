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
}