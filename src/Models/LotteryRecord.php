<?php
namespace lotterythinkphp\lottery\Models;
use lotterythinkphp\lottery\Models\LotteryPrize;
use think\Model;
class LotteryRecord extends Model
{	
	/**
	* @数据库表名
	*/   
	protected $table = 'db_lott_record';

	/**
	* 获取某个活动所有获奖记录
	* @param $activityId int 活动id
	* @param $limit 分页限制
	*/ 
    public function getPrizeList($activityId,$page,$limit){
		/*state为1代表中奖 0代表未中奖*/
    	return LotteryRecord::where('activityId',$activityId)->where('state',1)->paginate($limit);
    }
	/**
	* 获取某个活动某个用户所有获奖记录
	* @param $activityId int 活动id
	* @param $uid 用户标识
	* @param $limit 分页限制
	*/ 
	public function userRecord($activityId,$uid,$page,$limit){
		
		return LotteryRecord::where('activityId',$activityId)->where('state',1)->where('uid',$uid)->paginate($limit);
    }
	/**
	* 获取某个活动某个奖品的已抽中数量
	* @param $pid 奖品标识id
	*/ 
	public function prizeCount($pid){

    	return LotteryRecord::where('prizeId',$pid)->count();
    }
	/**
	* 增加用户抽奖记录（中与不中都记录）
	* @param $data array 增加字段值
	*/ 
    public function addonly($data){

		$this->data($data) ;
		return $this->save();
    }
	/**
	* 增加用户抽奖记录到记录表（中与不中都记录），并且将已中奖奖品数量在奖品表中+1
	* @param $data array 增加字段值
	* @param $lott_num int 奖品已中奖数量
	*/ 
    public function addwith($data,$lott_num){
		
		$prizeModel = LotteryPrize::get(['id'=>$data['prizeId']]);
		$prizeModel->lott_num = $lott_num;
		$prizeModel->save();			
		$this->data($data) ;
		return $this->save();

    }	

}