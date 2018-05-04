<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

/**
 * [is_exist 判断是否存在某数据]
 * @param  [string]  $t [表名]
 * @param  [string/array]  $w [条件字符串或数组]
 * @return boolean    [是否存在]
 */
public function is_exist($t, $w)
{
  $this->db->select('count(1) as count');
  $this->db->where($w);
  $this->db->from($t);
  $query = $this->db->get();
  $data = $query->row_array();
  if($data['count'] > 0){
    return true;
  }else{
    return false;
  }
}

/*
|--------------------------------------------------------------------------
| single_sel - 单数据查询
|--------------------------------------------------------------------------
| Desc: 查询单条数据
|
| @param: $t 表名
| @param: $arr 查询的列名
| @param: $w 条件数组/条件字符串
| @param: $con 0 数组/1 字符串 （可选）
|
| @return: arr 单数组
*/
  public function single_sel($t, $arr, $w, $con = 0)
  {
    if($arr != ""){
      $field_str = implode(",", $arr);
      $this->db->select($field_str);
    }
    if($con == 0){
      $query = $this->db->get_where($t, $w, 1, 0);
    }else{
      $this->db->where($w);
      $this->db->limit(1, 0);
      $this->db->from($t);
      $query = $this->db->get();
    }
    return $query->row_array();
  }

/*
|--------------------------------------------------------------------------
| multi_sel - 多数据查询
|--------------------------------------------------------------------------
| Desc: 查询多条数据
|
| @param: $t 表名
| @param: $arr 查询列名
| @param: $w 查询条件数组/条件字符串
| @param: $con 0 数组/ 1 字符串
| @param: $limit 条数
| @param: $offset 偏移量
| @param: $order_str 排序
| @return: 多数组或多维数组
*/
  public function multi_sel($t, $arr, $w, $con = 0, $limit = 5, $offset = 0, $order_str = "")
  {
    if($arr != ""){
      $field_str = implode(",", $arr);
      $this->db->select($field_str); //查询字段
    }
    if($order_str != ""){
      $this->db->order_by($order_str); //排序
    }
    if($con == 0){
      $query = $this->db->get_where($t, $w, $limit, $offset);
    }else{
      $this->db->where($w);
      $this->db->limit($limit, $offset);
      $this->db->from($t);
      $query = $this->db->get();
    }
    return $query->result_array();
  }

/*
|--------------------------------------------------------------------------
| custom_sel - 自定义查询
|--------------------------------------------------------------------------
| Desc: 自定义sql语句查询数据
|
| @param $sql sql语句
| @param $model 0-返回单数据/1-返回多数据/2-更新或者删除
|
| @return 单条数据/多条数据
*/
  public function custom_sel($sql, $model = 0)
  {
    $query = $this->db->query($sql);
    switch ($model) {
      case 0:
        return $query->row_array();
        break;
      case 1:
        return $query->result_array();
        break;
      case 2:
        return $this->db->affected_rows();
        break;
    }
  }

/*
|--------------------------------------------------------------------------
| insert - 新增数据
|--------------------------------------------------------------------------
| Desc: 新增数据
|
| @param: $arr 插入数据数组
| @param: $ref 是否返回数据插入id
| @return 是否成功/新数据id
*/
  public function insert($t, $arr, $ref = 0)
  {
    $query = $this->db->insert($t, $arr);
    if($ref == 0){
      return $query;
    }else{
      $back = $this->db->insert_id();
      return $back;
    }
  }

/*
|--------------------------------------------------------------------------
| update - 更新数据
|--------------------------------------------------------------------------
| Desc: 更新数据
|
| @param $t 表名
| @param $arr 更新数据数组 / 计数中字段
| @param $w 条件数组/条件字符串
| @param $is_calc 是否计数 0 否/ 1 是 （可选参数）
| @param $count 增加的数量 （可选参数）
|
| @return 是否更新成功
*/
  public function update($t, $arr, $w, $is_calc = 0, $count = 1)
  {
    $this->db->where($w);
    if($is_calc > 0){
      if($is_calc == 1){
        $this->db->set("$arr", "$arr + $count", FALSE);
      }else{
        $this->db->set("$arr", "$arr - $count", FALSE);
      }
      $query = $this->db->update($t);
    }else{
      $query = $this->db->update($t, $arr);
    }
    return $query;
  }

/*
|--------------------------------------------------------------------------
| delete - 删除数据
|--------------------------------------------------------------------------
| Desc: 删除数据
|
| @param $t 表名
| @param $w 条件数组/条件字符串
|
| @return 是否删除成功
*/
  public function delete($t, $w)
  {
    $this->db->where($w);
    $query = $this->db->delete($t);
    return $query;
  }

/*
|--------------------------------------------------------------------------
| result_count - 返回数量
|--------------------------------------------------------------------------
| Desc: 根据条件返回数据数量
|
| @param $t 表名
| @param $w 条件
|
| @return Name Introduce
*/
  public function result_count($t, $w)
  {
    $this->db->where($w);
    $this->db->from($t);
    $query = $this->db->count_all_results();
    return $query;
  }

/*
|--------------------------------------------------------------------------
| get_page_count 获取总页数
|--------------------------------------------------------------------------
| Desc: 根据每页数据条数跟总数得出总页数
|
| @param count 总数量
| @param pageSize 每页数量
|
| @return 总页数
*/
  protected function get_page_count($count, $pageSize)
	{
		if($count == 0){
			return 0;
		}else{
			if($count <= $pageSize){
				return 1;
			}else if($count % $pageSize == 0){
				return ($count / $pageSize);
			}else{
				return ((int)($count/$pageSize) + 1);
			}
		}
	}

  /**
   * 伪删除
   */
  protected function flag_delete($t, $w){
    
    $this->db->where($w);
    $arr = array('is_delete' => 1);  
    $query = $this->db->update($t, $arr);
    return $query;
  }

}
