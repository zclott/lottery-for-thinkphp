
//'Lotterythinkphp\\Lottery\\' => array($vendorDir . '/lotterythinkphp/lottery/src'),
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<title>大转盘抽奖</title>
<style>
*{
	margin: 0;	
	padding: 0;
}
.lottery-div{
	margin: 0 auto;	
	padding: 0;
	max-width:640px;
}
#lottery-wrap{
    position: relative;
	display: block;
	margin: 40% auto;
	width: 300px;
    border: 8px solid #eac34c;
    border-radius: 50%;
    box-shadow: 3px 0 3px #fffdc9, 0px 3px 3px #fffdc9, -3px 0 3px #fffdc9, 0px -3px 3px #fffdc9;
}
canvas{
    display: block;
}

#lottery-handler{
    position: absolute;
    z-index: 2;
    width: 60px;
    height: 74px;
    left: 50%;
    top: 50%;
    margin: -44px 0 0 -30px;
    background: url(http://www.qiangbus.com/res/images/handler.png) no-repeat 50%;
    background-size: contain;
}
</style>
</head>
<body>
<div class="lottery-div">
	<div id="lottery-wrap">
		<div id="lottery-handler"></div>
		<canvas width="300" height="300" id="lottery"></canvas>
	</div>
</div>
<script src="http://www.qiangbus.com/res/jquery-1.11.1.min.js"></script>
<script src="http://www.qiangbus.com/res//Lottery.js"></script>
<script>
var products = '' ;
/*获取奖品列表*/
function prizeList(){
	$.ajax({
		url:'/lottery/prizeList',
		data: {
			activityId:{{request()->get('activityId',1)}},
		},
		dataType:'json',
		async:false,/*同步,以便全局变量赋值*/
		success: function(response){
			if(response.data){
				products = response.data;
			}
		}
	})
}
/*AJAX执行抽奖方法*/
var page = 1;
function getPrizeRecord(){

	$.ajax({
		url:'/lottery/getPrizeRecord',
		data: {
			activityId:{{request()->get('activityId',1)}},
			limit:20,
			page:1,
		},
		dataType:'json',
		success: function(response){
			if(response.data){
				var prizeRecord = response.data;
				console.log(prizeRecord)
			}
		}
	})
	page = page+1;
}
getPrizeRecord();
prizeList();
console.log(products);
/*AJAX执行抽奖方法*/
function _ajax(callback){
	$.ajax({
		url:'/lottery/index',
		type:'get',
		data: {
			uid:1,
			activityId:{{request()->get('activityId',1)}},
			lotteryLimit:3
		},
		dataType:'json',
		success: function(response){
			/*callback回调*/
			callback && callback(response);
		}
	})
	/*以下是模拟ajax请求数据返回*/
	/*setTimeout(function(){
		var _index   = Math.floor(Math.random()*products.length);
		var response = {id: 1, name: products[_index].name, index: _index};//这里控制礼物
		// var response = {id: 1, name: "控制礼物", index: 1};
		callback && callback(response);
	}, 100);*/
}

new Lottery(document.getElementById('lottery'), {
	handler: document.getElementById('lottery-handler'),
	handlerCallback: function(_interface){
		/*ajax获取中奖结果,callback回调*/
		_ajax(function(response){
			if(response.data){
				/*获得中奖结果，转动转盘*/
				_interface._beginRotate(true);
				/*指定停止的位置:索引*/
				_interface.stop(response.data.index, function(){
					if(response.data.state == 1){ //不为空奖谢谢参与时
						alert('恭喜你中得:' + response.data.name)	
					}else{
						alert(response.data.name)
					}
				});
			}else{
				/*未获得中奖结果，提示信息，不转动转盘*/
				_interface._beginRotate(false);
				alert(response.message)
			}
		});
	},
    images: {
        width: 22,
        height: 29,
        y: '88%',
    },
	font: {
		y: '50%',
		color: '#ee6500',
		/*循环填充字体颜色*/
		//color: ['#f00', '#ee6500'],
		style: 'normal',
		weight: 500,
		size: '10px',
		lineHeight: 1,
		family: 'Arail',
		/*字体摆放 landscape横 portrait竖*/
		lay:'landscape',
	},
	products:products,
});

</script>
</body>
</html>