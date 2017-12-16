<?php
namespace Hiland\Utils\Web\Widget;

    /**
     * File name: TreeTable.class.php
     * Author: run.gao 312854458@qq.com Date: 2012-07-24 23:22 GMT+8
     * Description: 通用的表格无限级分类
     * http://www.jb51.net/article/31101.htm
     */
/**
 * 表格展示无限分类是将无线分类已表格的形式表现出来，更好的能体现出分类的所属关系
 * 使用方法：
 * 1.
 * 实例化分类
 * $treeTable = new TreeTable();
 * 2. 初始化分类，$dataArray必须是一个多维数组且包含 id,parentid,name字段
 * $treeTable->init($dataArray);
 * 3. 获取无限分类HTML代码
 * echo $treeTable->generateTreeTable();
 */
class TreeTable
{

    /**
     * 生成树型结构所需要的2维数组
     *
     * @var array
     *
     */
    private $tableContentArray = array();

    private $tablePropertyArray;

    private $trPropertyArray;

    private $tdPropertyArray;

    /**
     * 表格列数
     *
     * @var int
     *
     */
    private $columnCount = 0;

    /**
     * 表格行数
     *
     * @var int
     *
     */
    private $rowCount = 0;

    /**
     * 初始化TreeTable数据
     *
     * @param array $dataArray
     *            2维数组 array(
     *            1 => array('id'=>'1','parentid'=>0,'name'=>'一级栏目一'),
     *            2 => array('id'=>'2','parentid'=>0,'name'=>'一级栏目二'),
     *            3 => array('id'=>'3','parentid'=>1,'name'=>'二级栏目一'),
     *            4 => array('id'=>'4','parentid'=>1,'name'=>'二级栏目二'),
     *            5 => array('id'=>'5','parentid'=>2,'name'=>'二级栏目三'),
     *            6 => array('id'=>'6','parentid'=>3,'name'=>'三级栏目一'),
     *            7 => array('id'=>'7','parentid'=>3,'name'=>'三级栏目二')
     *            )
     * @param array $tablePropertyArray
     *            在table标签内使用的属性组成的名值对数组
     * @param array $trPropertyArray
     *            在tr标签内使用的属性组成的名值对数组
     * @param array $tdPropertyArray
     *            在td标签内使用的属性组成的名值对数组
     */
    public function init($dataArray = array(), $tablePropertyArray = null, $trPropertyArray = null, $tdPropertyArray = null)
    {
        $this->tdPropertyArray = $tdPropertyArray;
        $this->trPropertyArray = $trPropertyArray;
        $this->tablePropertyArray = $tablePropertyArray;

        if (!is_array($dataArray))
            return false;
        foreach ($dataArray as $k => $v) {
            $this->tableContentArray[$v['id']] = $v;
        }
        foreach ($this->tableContentArray as $k => $v) {
            $this->tableContentArray[$k]['column'] = $this->get_level($v['id']); // Y轴位置
            $this->tableContentArray[$k]['arrchildid'] = $this->get_arrchildid($v['id']); // 所有子节点
            $this->tableContentArray[$k]['arrparentid'] = $this->get_arrparentid($v['id']); // 所有父节点
            $this->tableContentArray[$k]['child_bottom_num'] = $this->get_child_count($v['id']); // 所有底层元素节点
        }
        $this->columnCount = $this->getColumnCount(); // 总行数
        $this->rowCount = $this->getRowCount(); // 总列数
        // 按照arrparentid和id号进行排序
        $this->sort_arr();
        foreach ($this->tableContentArray as $k => $v) {
            $this->tableContentArray[$k]['row'] = $this->get_row_location($v['id']); // X轴位置
            $this->tableContentArray[$k]['rowspan'] = $v['child_bottom_num']; // 行合并数
            $this->tableContentArray[$k]['colspan'] = $v['child_bottom_num'] == 0 ? $this->columnCount - $v['column'] + 1 : 0; // 列合并数
        }
        return $this->get_tree_arr();
    }

    /**
     * 获取数组
     */
    private function get_tree_arr()
    {
        return is_array($this->tableContentArray) ? $this->tableContentArray : false;
    }

    /**
     * 按arrparentid/id号依次重新排序数组
     */
    private function sort_arr()
    {
        // 要进行排序的字段
        foreach ($this->tableContentArray as $k => $v) {
            $order_pid_arr[$k] = $v['arrparentid'];
            $order_iscost[] = $v['sort'];
            $order_id_arr[$k] = $v['id'];
        }
        // 先根据arrparentid排序，再根据排序,id号排序
        array_multisort($order_pid_arr, SORT_ASC, SORT_STRING, $order_iscost, SORT_DESC, SORT_NUMERIC, $order_id_arr, SORT_ASC, SORT_NUMERIC, $this->tableContentArray);
        // 获取每一个节点层次
        for ($column = 1; $column <= $this->columnCount; $column++) {
            $row_level = 0;
            foreach ($this->tableContentArray as $key => $node) {
                if ($node['column'] == $column) {
                    $row_level++;
                    $this->tableContentArray[$key]['column_level'] = $row_level;
                }
            }
        }
        // 重新计算以ID作为键名
        foreach ($this->tableContentArray as $k => $v) {
            $arr[$v['id']] = $v;
        }
        $this->tableContentArray = $arr;
    }

    /**
     * 得到父级数组
     *
     * @param
     *            int
     * @return array
     *
     */
    private function get_parent($myid)
    {
        $newarr = array();
        if (!isset($this->tableContentArray[$myid]))
            return false;
        $pid = $this->tableContentArray[$myid]['parentid'];
        $pid = $this->tableContentArray[$pid]['parentid'];
        if (is_array($this->tableContentArray)) {
            foreach ($this->tableContentArray as $id => $a) {
                if ($a['parentid'] == $pid)
                    $newarr[$id] = $a;
            }
        }
        return $newarr;
    }

    /**
     * 得到子级数组
     *
     * @param
     *            int
     * @return array
     *
     */
    private function get_child($myid)
    {
        $a = $newarr = array();
        if (is_array($this->tableContentArray)) {
            foreach ($this->tableContentArray as $id => $a) {
                if ($a['parentid'] == $myid)
                    $newarr[$id] = $a;
            }
        }
        return $newarr ? $newarr : false;
    }

    /**
     * 获取当前节点所在的层级
     *
     * @param $myid 当前节点ID号
     *
     */
    private function get_level($myid, $init = true)
    {
        static $level = 1;
        if ($init)
            $level = 1;
        if ($this->tableContentArray[$myid]['parentid']) {
            $level++;
            $this->get_level($this->tableContentArray[$myid]['parentid'], false);
        }
        return $level;
    }

    /**
     * 获取当前节点所有底层节点（没有子节点的节点）的数量
     *
     * @param $myid 节点ID号
     * @param $init 第一次加载将情况static变量
     *
     */
    private function get_child_count($myid, $init = true)
    {
        static $count = 0;
        if ($init)
            $count = 0;
        if (!$this->get_child($myid) && $init)
            return 0;

        $childarr = $this->get_child($myid);
        if (!empty($childarr)) {
            foreach ($childarr as $v) {
                $this->get_child_count($v['id'], false);
            }
        } else {
            $count++;
        }
        return $count;
    }

    /**
     * 获取节点所有子节点ID号
     *
     * @param $catid 节点ID号
     * @param $init 第一次加载将情况static初始化
     *
     */
    private function get_arrchildid($myid, $init = true)
    {
        static $childid;
        if ($init)
            $childid = '';
        if (!is_array($this->tableContentArray))
            return false;
        foreach ($this->tableContentArray as $id => $a) {
            if ($a['parentid'] == $myid) {
                $childid = $childid ? $childid . ',' . $a['id'] : $a['id'];
                $this->get_arrchildid($a['id'], false);
            }
        }
        return $childid;
    }

    /**
     * 获取该节点所有父节点ID号
     *
     * @param $id 节点ID号
     *
     */
    private function get_arrparentid($id, $arrparentid = '')
    {
        if (!is_array($this->tableContentArray))
            return false;
        $parentid = $this->tableContentArray[$id]['parentid'];
        if ($parentid > 0)
            $arrparentid = $arrparentid ? $parentid . ',' . $arrparentid : $parentid;
        if ($parentid)
            $arrparentid = $this->get_arrparentid($parentid, $arrparentid);
        return $arrparentid;
    }

    /**
     * 获取节点所在地行定位
     *
     * @param $myid 节点ID号
     *
     */
    private function get_row_location($myid)
    {
        $nodearr = $this->tableContentArray;
        // 获取每一个节点所在行的位置
        foreach ($nodearr as $key => $node) {
            if ($myid == $node['id']) {
                $node_row_count = 0;
                $arrparentid = explode(',', $node['arrparentid']);
                // 所有父节点小于当前节点层次的底层节点等于0的元素
                foreach ($arrparentid as $pid) {
                    foreach ($nodearr as $node_row) {
                        if ($node_row['column'] == $nodearr[$pid]['column'] && $nodearr[$pid]['column_level'] > $node_row['column_level'] && $node_row['child_bottom_num'] == 0) {
                            $node_row_count++;
                        }
                    }
                }
                // 所有当前节点并且节点层次（rowid_level）小于当前节点层次的个数
                foreach ($nodearr as $node_row) {
                    if ($node['column'] == $node_row['column'] && $node_row['column_level'] < $node['column_level']) {
                        $node_row_count += $node_row['child_bottom_num'] ? $node_row['child_bottom_num'] : 1;
                    }
                }
                $node_row_count++;
                break;
            }
        }
        return $node_row_count;
    }

    /**
     * 获取表格的行数
     */
    private function getRowCount()
    {
        $rowcount = 0;
        foreach ($this->tableContentArray as $key => $node) {
            if ($node['child_bottom_num'] == 0) {
                $rowcount++; // 总行数
            }
        }
        return $rowcount;
    }

    /**
     * 获取表格的列数
     */
    private function getColumnCount()
    {
        $columncount = 0;
        foreach ($this->tableContentArray as $key => $node) {
            if ($node['column'] > $columncount) {
                $columncount = $node['column']; // 总列数
            }
        }
        return $columncount;
    }

    /**
     * 获取分类的表格展现形式(不包含表头)
     */
    public function generateTreeTable()
    {
        $tablePropertyString = '';
        if (!empty($this->tablePropertyArray) && is_array($this->tablePropertyArray)) {
            foreach ($this->tablePropertyArray as $k => $v) {
                $tablePropertyString .= " $k=\"$v\"";
            }
        }

        $trPropertyString = '';
        if (!empty($this->trPropertyArray) && is_array($this->trPropertyArray)) {
            foreach ($this->trPropertyArray as $k => $v) {
                $trPropertyString .= " $k=\"$v\"";
            }
        }

        $tdPropertyString = '';
        if (!empty($this->tdPropertyArray) && is_array($this->tdPropertyArray)) {
            foreach ($this->tdPropertyArray as $k => $v) {
                $tdPropertyString .= " $k=\"$v\"";
            }
        }

        $table_string = "<table $tablePropertyString>";
        for ($row = 1; $row <= $this->rowCount; $row++) {
            $table_string .= "\r\t<tr $trPropertyString>";
            foreach ($this->tableContentArray as $v) {
                if ($v['row'] == $row) {
                    $rowspan = $v['rowspan'] ? "rowspan=\"{$v['rowspan']}\"" : "";
                    $colspan = $v['colspan'] ? "colspan=\"{$v['colspan']}\"" : "";
                    $table_string .= "\r\t\t<td {$rowspan} {$colspan} $tdPropertyString>
{$v['name']}
</td>";
                }
            }
            $table_string .= "\r\t</tr>";
        }
        $table_string .= "</table>";
        return $table_string;
    }
}

?>