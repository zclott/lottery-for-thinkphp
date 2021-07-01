<?php
namespace lotterythinkphp\lottery\Models;
use think\Model;

class LotteryActivity extends Model
{	
	/**
	* @数据库表名
	*/   
	protected $table = 'db_lott_activity';
	/**
	* 获取某个活动列表
	*/ 
    public function getList(){

    	return LotteryActivity::all();
    }
	/**
	* 获取单个活动
	* @param $id int 活动id
	*/ 
	public function getInfo($id){

    	return  LotteryActivity::where('id',$id)->find();
    }
	/**
	* 增加单个活动
	* @param $id int 活动id
	* @param $data array 增加字段值
	*/ 
    public function add($data){
		
    }
	/**
	* 修改单个活动
	* @param $id int 活动id
	* @param $data array 修改的内容
	*/ 
    public function edit($id,$data){
    }
	/**
	* 删除单个活动
	* @param $id int 活动id
	*/ 
    public function del($id){

    }

}