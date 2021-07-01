<?php
namespace lotterythinkphp\lottery;
use lotterythinkphp\lottery\Models\LotteryPrize;
use lotterythinkphp\lottery\Models\LotteryActivity;
use lotterythinkphp\lottery\Models\LotteryRecord;

class Lottery 
{
	/**
     * Lottery  lottery. 抽奖主方法
	 * @param $uid int 用户id
	 * @param $activityId int 活动id
	 * @param $lotteryLimit int 抽奖次数
    */
	public static function lottery($activityId,$uid=null,$lotteryLimit=0)
	{
		$activityInfo =self::activityInfo($activityId);
		if(!$activityInfo){
		
			return self::returnOut(0,'活动不存在');
		}
		if(!self::checkActivitydate($activityInfo)){
		
			return self::returnOut(0,'今天不在活动时间范围哦');
		}
		$prize = new LotteryPrize;
		$prize = $prize->getAllList($activityId);
		if(!$prize){

			return self::returnOut(0,'奖品不存在');
		}
		$redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->auth('');
		//$lotteryLimit == 0时，不限抽奖次数。
		if($uid && $lotteryLimit!=0){
			
			//获取用户已抽奖次数，默认$lotteryTimes为0
			$lotteryTimes = $redis->hget('lotterytimes', 'lotterytimes_'.$activityId.'_'.$uid)?$redis->hget('lotterytimes', 'lotterytimes_'.$activityId.'_'.$uid):0 ;
			if( $lotteryTimes > $lotteryLimit-1){
				return self::returnOut(0,'抽奖次数已用完');
			}
			$redis->hincrby('lotterytimes','lotterytimes_'.$activityId.'_'.$uid,1); //记录用户抽奖次数
			//如果是每日限制$lotteryLimit次，则设置第二天0点过期
			//$redis->expire('lotterytimes','lotterytimes_'.$activityId.'_'.$uid,strtotime(date("Y-m-d",time()))+3600*24-time());
		}
		$proArr = []; //所有奖品概率基数数组
		foreach ($prize as $obj) {
			if( $obj->num != 0 && $obj->basenumber!=0) { //奖品数量有限,概率基数不为0时
				//检查奖品数量是否达到抽取上限,达到则设置该奖项的中奖率为0。
				if(!self::checkPrizeCount($obj)){
					$obj->basenumber = 0;
				}				
			}
			$proArr[] = $obj->basenumber;
		}
		$prizeIndex = self::getRand($proArr); //根据概率获取奖品的索引
		$result = $prize[$prizeIndex]; //中奖奖品
		$record = new LotteryRecord;
		$addData = ['uid'=>$uid,'prizeId'=>$result->id,'prizename'=>$result->name,'activityId'=>$activityInfo->id,'activitytitle'=>$activityInfo->title,'state'=>$result->state,'lotterytime'=>time()];
		if($result->num == 0){ //奖品数量无限制时
			if(!$record->addonly($addData) ){ //中奖纪录写入数据库表,写入失败时，回退中奖次数
				if($uid && $lotteryLimit!=0){			
					$redis->hincrby('lotterytimes','lotterytimes_'.$activityId.'_'.$uid,-1); 
				}
			}
		}else{ //奖品数量有限制时
			$lott_num = $result->lott_num + 1 ;//已中奖数量+1
			if(!$record->addwith($addData,$lott_num) ){ //中奖纪录写入中奖记录表并更新奖品表，失败时，回退中奖次数
				if($uid && $lotteryLimit!=0){			
					$redis->hincrby('lotterytimes','lotterytimes_'.$activityId.'_'.$uid,-1); 
				}
			}		
			
		}
		$data['id'] = $result->id; //中奖奖品id
		$data['name'] = $result->name; //中奖奖品名称
		$data['state'] = $result->state; //是否为空奖谢谢参与
		$data['index'] = $prizeIndex; //中奖奖品的索引
		return self::returnOut(0,'',$data);
	}
	/**
     * Lottery  prizeList. 抽奖奖品列表
	 * @param $activityId int 活动id
    */
	public static function prizeList( $activityId)
	{
		$prize = new LotteryPrize;
		$prize = $prize->getList($activityId);
		if(!$prize){

			return false;
		}
		return $prize;
	}
	/**
     * Lottery  getPrizeRecord. 中奖纪录
     * @param $page int 页码
	 * @param $limit int 条数
	 * @param $activityId int 活动id
    */
	public static function getPrizeRecord($activityId,$page=1,$limit=20)
	{
		
		$record = new LotteryRecord;
		$getPrize = $record->getPrizeList($activityId,$page,$limit);	
		if(!$getPrize){

			return false;
		}
		return $getPrize;
	}
	/**
	 * Lottery  checkActivitydate. 检查活动时间
	 * @param $activityInfo object 活动内容
	*/
	public static function checkActivitydate($activityInfo)
	{
	
		$timestamp = time();
		if($activityInfo->starttime > $timestamp){
			
			return false;
		}
		if($activityInfo->endtime < $timestamp){
			
			return false;
		}
		return true;
	}
	/**
	 * Lottery  checkPrizeCount. 检查活动奖品数量,
	 * @param $prizeInfo object 奖品
	*/
	public static function checkPrizeCount($prizeInfo)
	{
		if($prizeInfo->lott_num >= $prizeInfo->num){	//超出数量设置该奖品的概率基数为0
			$prize = new LotteryPrize;
			$prize->edit($prizeInfo->id,['basenumber'=>0]);
			return false;
		}
		return true;
	}
	/**
	 * Lottery  activityInfo. 活动详情
	 * @param $activityId int 活动id
	*/
	public static function activityInfo($activityId)
	{
		$activity = new LotteryActivity;
		$activityInfo = $activity->getInfo($activityId);
		if(!$activityInfo){
		
			return false;
		}
		return $activityInfo;
	}
	/**
     * Lottery  getRand. 抽奖算法
     * @param array $proArr 所有奖品概率基数数组
     */
	protected static function getRand( $proArr ) 
	{
		$result = '';
		//$proSum抽奖概率基数之和
		$proSum = array_sum( $proArr ); 

		foreach ($proArr as $k => $v) { 
			//获取当次抽奖概率基数
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $v) { //$randNum在当前奖品概率基数（$v）内，直接返回结果
				$result = $k;
				break;
			} else { //减去当前概率基数，继续寻找，直到满足$randNum在$v内
				$proSum -= $v;
			}
		}
		//删除$proArr，防止影响下次抽奖
		unset($proArr);

		return $result;
	}
	/**
     * Lottery  returnOut. 处理所有返回值
	 * @param int code 状态码
     * @param object $result 结果
	 * @param string $message 提示信息
     */
	protected static function returnOut($code=0,$message='',$result=null) 
	{
		return ['code'=>0,'message'=>$message,'result'=>$result];
	}
}