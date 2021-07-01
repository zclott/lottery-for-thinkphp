# lottery-for-thinkphp5

> ##### thinkphp5版composer抽奖程序插件，包含服务端和前端html(vue和js版大转盘)

#### 程序流程

1. 创建活动
2. 添加活动的奖品
3. 前端展示
4. 抽奖
5. 记录抽奖

- 这些可以简单在自己的cms系统增加个抽奖模块，管理抽奖模块数据，本插件只涉及抽奖行为业务，数据部分直插数据库表。

#### 运行环境

- php >= 5.4
- composer
- thinkphp5
- mysql
- redis

#### 安装

```
$ composer require lotterythinkphp/lottery
```

#### 视图

![image](http://www.qiangbus.com/res/images/draw.png)

tips:静态资源和视图模板放在views下，vue版大转盘前端模板参考：https://100px.net/

#### 抽奖主类 Lottery.php

- 对外方法

| method | 描述 |
| :----: | :-----: |
| lottery | 抽奖方法 |
| prizeList | 奖品列表 | 
| getPrizeRecord | 获奖记录 |  
| activityInfo | 活动详情 |

```php
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
```

#### 抽奖算法

```php
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
```

#### 抽奖数据模型目录Models

- 所有的数据模型都在Models目录。包含三部分，抽奖活动模型（Activity），抽奖奖品模型（Prize），抽奖记录模型（Record）

#### 业务逻辑举例目录example（Lottery）


```php

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
```

#### 备注

- 这里所有的模型和控制器都给出来了，使用的时候可以不以插件服务提供者方式使用，可将Models和example内代码直接在自己项目的app目录包含应用程序的核心代码内。
- MYSQL创建语句在sql目录下
- 交流可发至943826443#qq.com,把#换成@. qq:943826443








