<?php
namespace lotterythinkphp\lottery\Models;
use think\Model;
class LotteryPrize extends Model
{	 
	/**
	* @数据库表名
	*/   
	protected $table = 'db_lott_prize';
	/**
	* 获取某个活动奖品列表(对外展示的奖品字段)
	* @param $activityId int 活动id
	*/ 
    public function getList($activityId){

    	return LotteryPrize::where('activityId',$activityId)->select();
    }
	/**
	* 获取某个活动奖品列表
	* @param $activityId int 活动id
	*/ 
    public function getAllList($activityId){

    	return LotteryPrize::where('activityId',$activityId)->select();
    }
	/**
	* 获取单个活动奖品
	* @param $id int 奖品id
	*/ 
	public function getInfo($id){

    	return  LotteryPrize::where('id',$id)->find();
    }
	/**
	* 增加单个活动奖品
	* @param $data array 增加字段值
	*/ 
    public function add($data){

    	
    }
	/**
	* 修改单个活动奖品
	* @param $id int 奖品id
	* @param $data array 修改的内容
	*/ 
    public function edit($id,$data){


    }
	/**
	* 删除单个活动奖品
	* @param $id int 奖品id
	*/ 
    public function del($id){


    }

}