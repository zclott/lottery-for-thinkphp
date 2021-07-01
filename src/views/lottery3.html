<div id="app">
  <!-- 大转盘抽奖 -->
  <lucky-wheel
    ref="LuckyWheel"
    width="300px"
    height="300px"
    :prizes="prizes"
    :default-style="defaultStyle"
    :blocks="blocks"
    :buttons="buttons"
    @start="startCallBack"
    @end="endCallBack"
  />

</div>
<script src="https://cdn.bootcdn.net/ajax/libs/vue/2.6.9/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-luck-draw/dist/vue-luck-draw.umd.min.js"></script>
<script src="http://www.qiangbus.com/res/axios.min.js"></script>
<script>
  new Vue({
	  el: '#app',
	  data () {
		return {
		  prizes: [],
		  defaultStyle: {
			fontColor: '#d64737',
			fontSize: '14px'
		  },
		  blocks: [
			{ padding: '13px', background: '#d64737' }
		  ],
		  buttons: [
			{ radius: '50px', background: '#d64737' },
			{ radius: '45px', background: '#fff' },
			{ radius: '41px', background: '#f6c66f', pointer: true },
			{ radius: '35px', background: '#ffdea0'}
		  ],
		}
	  },
	  mounted() {
		this.getPrizesList(); //获取奖品信息列表
	  },
	  methods: {
		getPrizesList () {
			var self = this;
			this.prizes = []
			var activityId = 1; 
			axios.get('/lottery/prizeList/?activityId='+activityId).then(function(res) {
			let data = res.data.data;
				data.forEach((item, index) => {
					self.prizes.push({
						title: item.name,
						background: index % 2 ? '#f9e3bb' : '#f8d384',
						fonts: [{ text: item.name, top: '10%' }],
						imgs:[{ src: item.imgUrl, width: '30%', top: '30%' }],
						state:item.state
					})
				})	
			})
		},
		startCallBack () {
			var self = this;
			var activityId = 1; //获取url参数
			axios.get('/lottery/index/?activityId='+activityId).then(function(res) {
				if(res.data.data){ //中奖结果
					self.$refs.LuckyWheel.play() //转动转盘
					self.$refs.LuckyWheel.stop(res.data.data.index)	//停止位置，中奖结果下标
				}else{ //提示信息
					alert(res.data.message);
				}
			})
		},
		endCallBack (prize) {
			if(prize.state == 1){ 
				alert(`恭喜你获得${prize.title}`)
			}else{	//奖品是为空奖 谢谢参与
				alert(`${prize.title}`)
			}
		},
	  }
  })
</script>
