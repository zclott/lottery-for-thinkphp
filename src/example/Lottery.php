<?php
namespace app\index\controller;
use think\Controller;
use lotterythinkphp\lottery\Lottery as LotteryFuc;
class Lottery extends Controller
{
	/**
     * Lottery  index. 抽奖方法
	 * @param $uid int 用户id
	 * @param $activityId int 活动id
	 * @param $lotteryLimit int 抽奖次数
    */
    public function Index()
    {
    	$activityId = input('get.activityId');
    	$uid = input('get.uid');
    	$lotteryLimit = input('get.lotteryLimit');
		$activityId = $activityId?$activityId:1;
		$uid = $uid?$uid:1;
		$lotteryLimit = $lotteryLimit?$lotteryLimit:3;
		$result = LotteryFuc::lottery($activityId,$uid,$lotteryLimit);
		if($result['result']){
			echo json_encode(['code'=>0,'data'=>$result['result']]);
		}else{
			echo json_encode(['code'=>0,'message'=>$result['message']]);
		}
		
    }
	/**
     * Lottery  activityInfo. 活动详情
	 * @param $activityId int 活动id
    */
	public function Activityinfo()
	{
		$activityId = input('get.activityId');
		$activityId = $activityId?$activityId:1;
		$activity =  LotteryFuc::activityInfo($activityId);
		if($activity){
			echo json_encode(['code'=>0,'data'=>$activity]);
		}else{
			echo json_encode(['code'=>0,'message'=>'无数据']);
		}
		
	}
	/**
     * Lottery  prizeList. 奖品列表
	 * @param $activityId int 活动id
    */
	public function Prizelist()
	{
		$activityId = input('get.activityId');
		$activityId = $activityId?$activityId:1;
		$prize =  LotteryFuc::prizeList($activityId);
		if($prize){
			echo json_encode(['code'=>0,'data'=>$prize]);
		}else{
			echo json_encode(['code'=>0,'message'=>'无数据']);
		}
		
	}
	/**
     * Lottery  getPrizeRecord. 获奖记录
	 * @param $activityId int 活动id
	 * @param $page int 页码
	 * @param $limit int 条数
    */
	public function Getprizerecord()
	{
		$activityId = input('get.activityId');
		$limit = input('get.limit');
		$page = input('get.page');
		$getPrize = LotteryFuc::getPrizeRecord($activityId,$page,$limit);
		if( $getPrize){
			echo json_encode(['code'=>0,'data'=>$getPrize]);
		}else{
			echo json_encode(['code'=>0,'message'=>'无数据']);
		}
	
	}
	/* Lottery  show. html展示
	 * https://100px.net/ 抽奖前端开源项目vue-luck-draw插件
    */
	public function Show()
	{
		
		//js版本大转盘 http://xxx.com/lottery/show?activityId=1
		return $this->fetch('lottery1');
		//vue版本 vue-luck-draw插件 通过script 标签引入 http://xxx.com/lottery/show?activityId=1
		//return $this->fetch('lottery3');
		
	}
}
