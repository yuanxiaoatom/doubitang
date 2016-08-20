<?php
namespace Home\Model;

use Think\Model;

class ChangfangModel extends Model
{

    /**
     * 获取标题与缩略图
     * 
     * @param string $where            
     * @param number $count            
     */
    public function getChangfangTitle($where = '', $order = array('id'=>'desc'), $count = 8)
    {
        return $this->field(array(
            'title',
            'id',
            'thumb'
        ))
            ->where($where)
            ->order($order)
            ->limit($count)
            ->select();
    }

    /**
     * 获取楼宇列表
     * 
     * @param string $where
     *            查询条件
     * @param number $page
     *            页码
     * @param array $order
     *            排序条件
     * @param number $pagesize
     *            每页记录数量
     * @return array
     */
    public function getChangfangList($where = '', $page = 1, $order = array('id'=>'desc'), $pagesize = 10)
    {
        $count = $this->getChangfangCount($where);
        $list['countpage'] = ceil($count / $pagesize);
        $limit = ($page - 1) * $pagesize . ',' . $pagesize;
        if ($page < 1) {
            $page = 1;
        } elseif ($page > $list['countpage']) {
            $page = $list['countpage'];
        }
        $list['list'] = $this->field()
            ->where($where)
            ->order($order)
            ->limit($limit)
            ->select();
        
        return $list;
    }

    /**
     * 通过id获取详情
     * 
     * @param unknown $id            
     * @return mixed|boolean|NULL|string|unknown|object
     */
    public function getChangfangById($id)
    {
        return $this->field()
            ->where()
            ->find($id);
    }

    /**
     * 获取总数
     * 
     * @param string $where            
     */
    private function getChangfangCount($where = '')
    {
        return $this->field()
            ->where($where)
            ->count();
    }
}