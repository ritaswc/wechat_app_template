<?php
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;

$this->title = '数据统计';
?>
<style>
	.panel-1,.panel-2,.panel-3,.panel-4{
		height: 15rem;
	}

	.col-md-3{
		padding-left: 0;
	}

	.col-md-3:first-child{
		padding-left: 15px;
	}

	.col-md-6:nth-child(6){
		padding-left: 0;
	}

	.panel-header{
		height: 4rem;
		line-height: 4rem;
	}
	
	.panel-header .nav-item .nav-link{
		height: 4rem;
		line-height: 4rem;
		padding: 0;
		color: #B8B8B8;
		display: block;
		/* width: 2rem; */
		text-align: center;
	}

	.data-num{
		font-size: 30px;
		text-align: center;
		margin-top: 1rem;
	}

	.trend{
		font-size: 24px;
		text-align: center;
		color: #F1D225;
		margin-bottom: 2rem;
		display: none;
	}

	.trend-up{
		color: #F1202F;
		font-size: 18px;
		text-align: center;
		margin-bottom: 2rem;
		display: none;
	}

	.trend-down{
		color: green;
		font-size: 18px;
		text-align: center;
		margin-bottom: 2rem;
		display: none;
	}

	.form-group{
		margin-left: 0rem;
	}

	.new-day{
		border: 1px solid #028BE3;
		border-radius: 0.3rem;
		margin-right: 1rem;
	}

	.submit{
		margin-left: 1rem;
	}

	.category{
		margin: 1rem;
	}
	
	.panel-header>.nav>.nav-item>.active{
		color: #3d3d3d;
	}

	.hide{
		display: none;
	}
	
	.page-item a{
		color: #0275d8;
		cursor: pointer;
		text-decoration: none;
	}

	.loading{
		margin: 4rem auto;
		width: 3rem;
		height: 3rem;
		border-radius: 50%;
		border: 5px solid #BEBEBE;
		border-left: 5px solid #498aca;
		animation: load 1s linear infinite;
		-moz-animation:load 1s linear infinite;
		-webkit-animation: load 1s linear infinite;
		-o-animation:load 1s linear infinite;
	}

	.loading-2{
		margin: 4rem auto;
		width: 3rem;
		height: 3rem;
		border-radius: 50%;
		border: 5px solid #BEBEBE;
		border-left: 5px solid #498aca;
		animation: load 1s linear infinite;
		-moz-animation:load 1s linear infinite;
		-webkit-animation: load 1s linear infinite;
		-o-animation:load 1s linear infinite;
	}

	.loading-3{
		margin: 4rem auto;
		width: 3rem;
		height: 3rem;
		border-radius: 50%;
		border: 5px solid #BEBEBE;
		border-left: 5px solid #498aca;
		animation: load 1s linear infinite;
		-moz-animation:load 1s linear infinite;
		-webkit-animation: load 1s linear infinite;
		-o-animation:load 1s linear infinite;
	}

	.loading-4{
		margin: 4rem auto;
		width: 3rem;
		height: 3rem;
		border-radius: 50%;
		border: 5px solid #BEBEBE;
		border-left: 5px solid #498aca;
		animation: load 1s linear infinite;
		-moz-animation:load 1s linear infinite;
		-webkit-animation: load 1s linear infinite;
		-o-animation:load 1s linear infinite;
	}

	.loading-8{
		margin: 12rem auto;
		width: 3rem;
		height: 3rem;
		border-radius: 50%;
		border: 5px solid #BEBEBE;
		border-left: 5px solid #498aca;
		animation: load 1s linear infinite;
		-moz-animation:load 1s linear infinite;
		-webkit-animation: load 1s linear infinite;
		-o-animation:load 1s linear infinite;
	}
	@-webkit-keyframes load
	{
		from{-webkit-transform:rotate(0deg);}
		to{-webkit-transform:rotate(360deg);}
	}
	@-moz-keyframes load
	{
		from{-moz-transform:rotate(0deg);}
		to{-moz-transform:rotate(360deg);}
	}
	@-o-keyframes load
	{
		from{-o-transform:rotate(0deg);}
		to{-o-transform:rotate(360deg);}
	}
	.toggle{
		display: none;
	}

	.date-pick{
		margin: 1rem 0;
	}

	.pagination .hold a {
		background: #0275d8;
		color: #fff;
		border-color: #0275d8;
	}

	.error{
		font-size: 24px;
		text-align: center;
		margin: 10rem 0;
	}
</style>
	<div id="load" class="col-sm-12 text-center" style="margin-top: 20rem;">
		<svg width='50px' height='50px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
			 preserveAspectRatio="xMidYMid" class="uil-default">
			<rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect>
			<rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
				  transform='rotate(0 50 50) translate(0 -30)'>
				<animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.8s' repeatCount='indefinite'/>
			</rect>
			<rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
				  transform='rotate(45 50 50) translate(0 -30)'>
				<animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.7000000000000001s'
						 repeatCount='indefinite'/>
			</rect>
			<rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
				  transform='rotate(90 50 50) translate(0 -30)'>
				<animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.6000000000000001s'
						 repeatCount='indefinite'/>
			</rect>
			<rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
				  transform='rotate(135 50 50) translate(0 -30)'>
				<animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.5s' repeatCount='indefinite'/>
			</rect>
			<rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
				  transform='rotate(180 50 50) translate(0 -30)'>
				<animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.4s' repeatCount='indefinite'/>
			</rect>
			<rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
				  transform='rotate(225 50 50) translate(0 -30)'>
				<animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.30000000000000004s'
						 repeatCount='indefinite'/>
			</rect>
			<rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
				  transform='rotate(270 50 50) translate(0 -30)'>
				<animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.2s' repeatCount='indefinite'/>
			</rect>
			<rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
				  transform='rotate(315 50 50) translate(0 -30)'>
				<animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.1s' repeatCount='indefinite'/>
			</rect>
		</svg>
		<div class="text-muted">数据加载中</div>
	</div>

<div class="row hide" id='statistic'>
	<div class="home-col col-md-3">
		<div class="panel panel-1 mb-3">
			<div class="panel-header">
				<div class="nav nav-left">
					<span>访问人数</span>                 
				</div>
				<ul class="nav nav-right visitor_num">
					<li class="nav-item">
						<input hidden value="1">
						<a class="nav-link active" href="javascript:" data-tab=".tab-1">昨日</a>
					</li>
					<li class="nav-item">
						<input hidden value="2">
						<a class="nav-link" href="javascript:" data-tab=".tab-1">上周</a>
					</li>
					<li class="nav-item">
						<input hidden value="3">
						<a class="nav-link" href="javascript:" data-tab=".tab-1">上月</a>
					</li>
				</ul>
			</div>
			<div class="loading toggle"></div>
			<div class="panel-body">
				<div class='data-num'>{{ visitor }}</div>
				<div class="trend-up">
					<span>↑</span>
					<span>{{ visitPct }}</span>
				</div>
				<div class="trend">
					<span>一</span>
				</div>
				<div class="trend-down">
					<span>↓</span>
					<span>{{ visitPct }}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="home-col col-md-3">
		<div class="panel panel-2 mb-3">
			<div class="panel-header">
				<div class="nav nav-left">
					<span>新增访问人数</span>                 
				</div>
				<ul class="nav nav-right new_visitor">
					<li class="nav-item">
						<input hidden value="1">
						<a class="nav-link active" href="javascript:" data-tab=".tab-1">昨日</a>
					</li>
					<li class="nav-item">
						<input hidden value="2">
						<a class="nav-link" href="javascript:" data-tab=".tab-1">上周</a>
					</li>
					<li class="nav-item">
						<input hidden value="3">
						<a class="nav-link" href="javascript:" data-tab=".tab-1">上月</a>
					</li>
				</ul>
			</div>
			<div class="loading-2 toggle"></div>
			<div class="panel-body">
				<div class='data-num'>{{ newVisitor }}</div>
				 <div class="trend-up">
					<span>↑</span>
					<span>{{ newvisitPct }}</span>
				</div>
				<div class="trend">
					<span>一</span>
				</div>
				<div class="trend-down">
					<span>↓</span>
					<span>{{ newvisitPct }}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="home-col col-md-3">
		<div class="panel panel-3 mb-3">
			<div class="panel-header">
				<div class="nav nav-left">
					<span>分享数据</span>                 
				</div>
				<ul class="nav nav-right share_num">
					<li class="nav-item">
						<input hidden value="1">
						<a class="nav-link active" href="javascript:" data-tab=".tab-1">昨日次数</a>
					</li>
					<li class="nav-item">
						<input hidden value="2">
						<a class="nav-link" href="javascript:" data-tab=".tab-1">昨日人数</a>
					</li>
				</ul>
			</div>
			<div class="loading-3 toggle"></div>
			<div class="panel-body">
				<div class='data-num'>{{ share }}</div>
				<div class="trend-up">
					<span>↑</span>
					<span>{{ sharePct }}</span>
				</div>
				<div class="trend">
					<span>一</span>
				</div>
				<div class="trend-down">
					<span>↓</span>
					<span>{{ sharePct }}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="home-col col-md-3">
		<div class="panel panel-4 mb-3">
			<div class="panel-header">
				<div class="nav nav-left">
					<span>商品浏览量</span>                 
				</div>
				<ul class="nav nav-right open_num">
					<li class="nav-item">
						<input hidden value="1">
						<a class="nav-link active" href="javascript:" data-tab=".tab-1">昨日次数</a>
					</li>
					<li class="nav-item">
						<input hidden value="2">
						<a class="nav-link" href="javascript:" data-tab=".tab-1">昨日人数</a>
					</li>
				</ul>
			</div>
			<div class="loading-4 toggle"></div>
			<div class="panel-body">
				<div class='data-num'>{{ open }}</div>
				<div class="trend-up">
					<span>↑</span>
					<span>{{ openPct }}</span>
				</div>
				<div class="trend">
					<span>一</span>
				</div>
				<div class="trend-down">
					<span>↓</span>
					<span>{{ openPct }}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="home-col col-md-6">
		<div class="panel panel-5 mb-3">
			<div class="panel-header">
				<div class="nav nav-left">
					<span>近七日访问趋势</span>                 
				</div>
			</div>
			<div class="panel-body">
				<div id="echarts_1" style="height:18rem;"></div>
			</div>
		</div>
	</div>

	<div class="home-col col-md-6">
		<div class="panel panel-6 mb-3">
			<div class="panel-header">
				<div class="nav nav-left">
					<span>近七日留存趋势</span>                 
				</div>
			</div>
			<div class="panel-body">
				<div id="echarts_2" style="height:18rem;"></div>
			</div>
		</div>
	</div>

	<div class="home-col col-md-12">
		<div class="panel panel-7 mb-3">
			<div class="panel-header">
				<div class="nav nav-left">
					<span>用户画像</span> 
					<div class="category">
						<select class="form-control">
						  <option value="normal">活跃用户</option>
						  <option value="add">新增用户</option>
						</select>                        
					</div>                
				</div>
				<ul class="nav nav-right userPort">
					<li class="nav-item">
						<input hidden value="1">
						<a class="nav-link active" href="javascript:" data-tab=".tab-1">今日</a>
					</li>
					<li class="nav-item">
						<input hidden value="2">
						<a class="nav-link" href="javascript:" data-tab=".tab-1">最近7天</a>
					</li>
					<li class="nav-item">
						<input hidden value="3">
						<a class="nav-link" href="javascript:" data-tab=".tab-1">最近30天</a>
					</li>
				</ul>
			</div>
			<div class="error toggle">
				很抱歉，似乎并没有获取到信息
			</div>
			<div class="panel-body">
				<div class="row">
					<div id="echarts_3" class="col-md-4" style="height:30rem;"></div>
					<div id="echarts_4" class="col-md-4" style="height:30rem;"></div>
					<div id="echarts_5" class="col-md-4" style="height:30rem;"></div>                    
				</div>
			</div>
		</div>
	</div>

	<div class="home-col col-md-12">
		<div class="panel panel-8 mb-3">
			<div class="panel-header">
				<div class="nav nav-left">
					<span>页面访问数据</span>                 
				</div>
			</div>
			<div class="panel-body">
				<div class="date-pick row ml-1">
					<label class="col-form-label">日期：</label>
					<div class="mr-3">
						<div class="input-group">
							<input class="form-control" id="date" :value='chooseDay'>
							<span class="input-group-btn">
										<a class="btn btn-secondary" id="show_date_start" href="javascript:">
											<span class="iconfont icon-daterange"></span>
										</a>
									</span>
							</span>
							<button class="btn btn-primary ml-4 date-submit">确定</button>
						</div>
					</div>
				</div>
				<div class="loading-8 toggle"></div>
				<table class="table table-bordered">
					<col style="width: 20%">
					<col style="width: 15%">
					<col style="width: 15%">
					<col style="width: 15%">
					<col style="width: 15%">
					<col style="width: 15%">
					<thead>
						<tr>
							<th>页面名称</th>
							<th>访问次数</th>
							<th>访问人数</th>
							<th>次均时长（秒）</th>
							<th>分享次数</th>
							<th>分享人数</th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="length==0">
							<td colspan="6" class="text-center">暂无数据</td>
						</tr>
					</tbody>

					<tbody v-for='page in tenPages'>
						<tr>
							<td>{{ page.page_path }}</td>
							<td>{{ page.page_visit_pv }}</td>
							<td>{{ page.page_visit_uv }}</td>
							<td>{{ page.page_staytime_pv }}</td>
							<td>{{ page.page_share_pv }}</td>
							<td>{{ page.page_share_uv }}</td>
						</tr>
					</tbody>
				</table>
				<div class="text-center">
					<ul class="pagination">
						<li class="page-item disabled"><a href="javascript:void(0);">首页</a></li>
						<li class="page-item disabled"><a href="javascript:void(0);">上一页</a></li>
						<li class="page-item hold"><a href="javascript:void(0);">1</a></li>
						<li class="page-item" v-for = 'num in pagination'><a href="javascript:void(0);">{{ num }}</a></li>
						<li class="page-item"><a href="javascript:void(0);">下一页</a></li>
						<li class="page-item"><a href="javascript:void(0);">尾页</a></li>
					</ul>
					<div class="text-muted">共{{ length }}条数据</div>
				</div>
			</div>
		</div>
	</div>

</div>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/echarts/echarts.min.js"></script>
<script>
	var statistic = new Vue({
		el: '#statistic',
		data: {
            data: null,
			user: null,
			status: 'normal',
			statistic: false,
			day: null,
			chooseDay:null,
			visitor: null,
			visitPct:null,
			newVisitor: null,
			newvisitPct: null,
			share: null,
			sharePct: null,
			open: null,
			openPct: 0,
			user: null,
			pages: [],
			pagination: null,
			length: 0,
			tenPages:[],
			now: 1
		},
		created:function(){
			let myDate = new Date;
			let year = myDate.getFullYear();
			let yue = myDate.getMonth()+1;
			let date = myDate.getDate()-1;
			if (date < 10){
				date = '0'+date.toString();
			}
			if (yue < 10){
				yue = '0'+yue.toString();
			}        
			this.day = year.toString() + yue.toString() + date.toString();
			this.chooseDay = year.toString() +'-'+ yue.toString() +'-'+ date.toString();
		}
	})
</script>
<script>

	$.ajax({
		url: '<?= $urlManager->createUrl('mch/store/analytics')?>',
		method: 'get',
		dataType: 'json',
		data: {
			_csrf: _csrf,
		},
		success: function (res) {
			statistic.data = res;
            var echarts_1 = echarts.init(document.getElementById('echarts_1'));
            var echarts_2 = echarts.init(document.getElementById('echarts_2'));
            echarts_1.showLoading({
                text:'正在拼命加载中...'
            });
            echarts_2.showLoading({
                text:'正在拼命加载中...'
            });
			// 访问人数
			statistic.visitor = res.data.dailyvisittrend.list[0].visit_uv;
			let lastvisit = res.data.lastdailyvisittrend.list[0].visit_uv;
            let visitList = [res.data.lastdailyvisittrend.list[0],
                            res.data.dailyvisittrend.list[0]];
            let retainList = res.data.lastdailyretaininfo.visit_uv;
			if(lastvisit == 0){
				if(lastvisit > statistic.visitor){
					$('.panel-1').find('.trend-down').show();
					statistic.visitPct = parseInt(((lastvisit - statistic.visitor) / 1) * 100) + '%';
				}else if(lastvisit < statistic.visitor){
					$('.panel-1').find('.trend-up').show();
					statistic.visitPct = parseInt(((statistic.visitor - lastvisit) / 1) * 100) + '%';
				}else{
					$('.panel-1').find('.trend').show();
				}
			}else{
				if(lastvisit > statistic.visitor){
					$('.panel-1').find('.trend-down').show();
					statistic.visitPct = parseInt(((lastvisit - statistic.visitor) / lastvisit) * 100) + '%';
				}else if(lastvisit < statistic.visitor){
					$('.panel-1').find('.trend-up').show();
					statistic.visitPct = parseInt(((statistic.visitor - lastvisit) / lastvisit) * 100) + '%';
				}else{
					$('.panel-1').find('.trend').show();
				}                
			}

			// 新增访问人数
			statistic.newVisitor = res.data.dailyvisittrend.list[0].visit_uv_new;
			let addVisit = res.data.lastdailyvisittrend.list[0].visit_uv_new;
			if(addVisit == 0){
				if(addVisit > statistic.newVisitor){
					$('.panel-2').find('.trend-down').show();
					statistic.newvisitPct = parseInt(((addVisit - statistic.newVisitor) / 1) * 100) + '%';
				}else if(addVisit < statistic.newVisitor){
					$('.panel-2').find('.trend-up').show();
					statistic.newvisitPct = parseInt(((statistic.newVisitor - addVisit) / 1) * 100) + '%';
				}else{
					$('.panel-2').find('.trend').show();
				}
			}else{
				if(addVisit > statistic.newVisitor){
					$('.panel-2').find('.trend-down').show();
					statistic.newvisitPct = parseInt(((addVisit - statistic.newVisitor) / addVisit) * 100) + '%';
				}else if(addVisit < statistic.newVisitor){
					$('.panel-2').find('.trend-up').show();
					statistic.newvisitPct = parseInt(((statistic.newVisitor - addVisit) / addVisit) * 100) + '%';
				}else{
					$('.panel-2').find('.trend').show();
				}                
			}

			// 分享次数
			statistic.share = res.data.dailysummarytrend.list[0].share_pv;
			let lastShare = res.data.lastsummarytrend.list[0].share_pv;
			if(lastShare == 0){
				if(lastShare > statistic.share){
					$('.panel-3').find('.trend-down').show();
					statistic.sharePct = parseInt(((lastShare - statistic.share) / 1) * 100) + '%';
				}else if(lastShare < statistic.share){
					$('.panel-3').find('.trend-up').show();
					statistic.sharePct = parseInt(((statistic.share - lastShare) / 1) * 100) + '%';
				}else{
					$('.panel-3').find('.trend').show();
				}
			}else{
				if(lastShare > statistic.share){
					$('.panel-3').find('.trend-down').show();
					statistic.sharePct = parseInt(((lastShare - statistic.share) / lastShare) * 100) + '%';
				}else if(lastShare < statistic.share){
					$('.panel-3').find('.trend-up').show();
					statistic.sharePct = parseInt(((statistic.share - lastShare) / lastShare) * 100) + '%';
				}else{
					$('.panel-3').find('.trend').show();
				}                
			}
            $.ajax({
                url: '<?= $urlManager->createUrl('mch/store/analytics-two')?>',
                method: 'get',
                dataType: 'json',
                data: {
                    _csrf: _csrf,
                },
                success: function (e) {
        			// 访问趋势
        			let visitData = [
        							e.data.seventhvisittrend.list[0],
        							e.data.sixthvisittrend.list[0],
        							e.data.fifthvisittrend.list[0],
        							e.data.fourthvisittrend.list[0],
        							e.data.thirdvisittrend.list[0]
        							];
        			let visitDate = [];
        			let visitPerson = [];
        			visitData.forEach(function(e){
        				visitDate.push(e.ref_date);
        				visitPerson.push(e.visit_uv);
        			});
                    visitList.forEach(function(e){
                        visitDate.push(e.ref_date);
                        visitPerson.push(e.visit_uv);
                    });
        			echarts1(echarts_1,visitDate,visitPerson);
                    echarts_1.hideLoading();
        			// 留存趋势
        			let retainData = [
        							e.data.seventhretaininfo.visit_uv,
        							e.data.sixthretaininfo.visit_uv,
        							e.data.fifthretaininfo.visit_uv,
        							e.data.fourthretaininfo.visit_uv,
        							e.data.thirdretaininfo.visit_uv,
        							];
                    retainData.push(retainList)
        			let data1 = [];
        			for(i = 0;i < retainData.length;i++){
        				data1.push(retainData[i][1].value);
        			}
        			data1[6] = 0;
        			let data2 = [];
        			for(i = 0;i < retainData.length-2;i++){
        				data2.push(retainData[i][3].value);
        			}
        			data2.push(0,0,0);
                    console.log(visitDate,data1,data2);
        			echarts2(echarts_2,visitDate,data1,data2);
                    echarts_2.hideLoading();
            }
        });
	}
	});

    $.ajax({
        url: '<?= $urlManager->createUrl('mch/store/analytics-one')?>',
        method: 'get',
        dataType: 'json',
        data: {
            _csrf: _csrf,
        },
        success: function (obj) {
            $("#load").hide();
            $("#statistic").removeClass('hide');
            statistic.user = obj;
        // 用户画像
        let user = obj.data.userportraitone.visit_uv.province;
         if(user.length != 0){
            user.sort(function(a,b){
                return b.value - a.value;
            })
            // 用户画像柱状图
            let userlist = [];
            for (i = 0;i< 10;i++) {
                userlist.unshift(user[i])
            }
            let province =  [];
            let person = [];
            userlist.forEach(function(e){
                province.push(e.name);
                person.push(e.value);
            });
            echarts3(province,person);
            // 用户画像饼状图
            let echarts_4 = echarts.init(document.getElementById('echarts_4'));
            let temp = [];
            let otherProvince = [];
            user.forEach(function(e){
                temp.push(e.value);
            });
            let num = 0;
            for (i = 3;i< temp.length-1;i++) {
                num += temp[i];
            }
            other = [{name: '其他', value: num}]
            other.unshift(user[0],user[1],user[2])
            other.forEach(function(e){
                otherProvince.push(e.name);
            });
            echarts4(echarts_4,otherProvince,other)
            // 用户性别饼状图
            let echarts_5 = echarts.init(document.getElementById('echarts_5'));
            let userSex = obj.data.userportraitone.visit_uv.genders;
            userSex.push(userSex[0]);
            userSex.shift();
            let sexName = [];
            userSex.forEach(function(e){
                sexName.push(e.name);
            });       
            echarts4(echarts_5,sexName,userSex);                
        }else{
            $('.error').show();
            $('.panel-7').find('.row').hide();
        }
        }
    })
	$.ajax({
		url: '<?= $urlManager->createUrl('mch/store/analytics')?>',
		method: 'post',
		dataType: 'json',
		data: {
			_csrf: _csrf,
			time: statistic.day
		},
		success: function (res) {
			// 页面访问数据
			statistic.pages = res.data.visitdistribution.list;
			if(statistic.pages != undefined){
				statistic.length = res.data.visitdistribution.list.length;
				statistic.pagination = [Math.ceil(statistic.length/10)];
				if(statistic.pagination > 1){
				for (i = 2;i < 10;i++) {
					if(i <= statistic.pagination[0]){
						statistic.pagination.push(i)
					}
				}
				statistic.pagination.shift();
				}else{
					statistic.pagination = [];
					$('.page-item').addClass('disabled');
				}
				for (i = 0;i < 10;i++) {
					statistic.tenPages.push(statistic.pages[i]);
				}                
			}

			// 商品浏览量

			let path = [];
			let visit_pv = [];
			let goods = [
						'pages/goods/goods',
						'pages/miaosha/details/details',
						'pages/pt/details/details',
						'pages/book/details/details',
						'pages/integral-mall/goods-info/index',
						'bargain/goods/goods',
						'lottery/goods/goods'
						];
			statistic.pages.forEach(function(e){
				path.push(e.page_path)
				visit_pv.push(e.page_visit_pv)
			});
			for(i = 0;i < goods.length;i++){           
				if(Number(visit_pv[path.indexOf(goods[i])])){
					statistic.open += visit_pv[path.indexOf(goods[i])]
				}
			}
			let lastpath = [];
			let last_visit_pv = [];
			let lastOpen = 0;
			res.data.lastvisitdistribution.list.forEach(function(e){
				lastpath.push(e.page_path)
				last_visit_pv.push(e.page_visit_pv)
			});

			for(i = 0;i < goods.length;i++){           
				if(Number(last_visit_pv[lastpath.indexOf(goods[i])])){
					lastOpen += last_visit_pv[lastpath.indexOf(goods[i])]
				}
			}
			if(lastOpen == 0){
				if(lastOpen > statistic.open){
					$('.panel-4').find('.trend-down').show();
					statistic.openPct = parseInt(((lastOpen - statistic.open) / 1) * 100) + '%';
				}else if(lastOpen < statistic.open){
					$('.panel-4').find('.trend-up').show();
					statistic.openPct = parseInt(((statistic.open - lastOpen) / 1) * 100) + '%';
				}else{
					$('.panel-4').find('.trend').show();
				}                
			}else{
				if(lastOpen > statistic.open){
					$('.panel-4').find('.trend-down').show();
					statistic.openPct = parseInt(((lastOpen - statistic.open) / lastOpen) * 100) + '%';
				}else if(lastOpen < statistic.open){
					$('.panel-4').find('.trend-up').show();
					statistic.openPct = parseInt(((statistic.open - lastOpen) / lastOpen) * 100) + '%';
				}else{
					$('.panel-4').find('.trend').show();
				}
			}

	}
	});

	$(document).on('click', '.panel .panel-header .nav-link', function () {
		$(this).parents('.panel').find('.nav-link').removeClass('active');
		$(this).parents('.panel').find('.tab-body').removeClass('active');
		var target = $(this).attr('data-tab');
		$(this).addClass('active');
		$(this).parents('.panel').find(target).addClass('active');
	});

	$(document).on('click', '.visitor_num .nav-link', function () {
		$('.panel-1').find('.trend-down').hide();
		$('.panel-1').find('.trend-up').hide();
		$('.panel-1').find('.trend').hide();
		var val = $('.visitor_num .active').prev().val();
		$('.loading').removeClass('toggle');
		$('.panel-1').children('.panel-body').addClass('toggle');
		setTimeout(function(){
			let lastvisit;
			if(val == 1){
				statistic.visitor = statistic.data.data.dailyvisittrend.list[0].visit_uv;
				lastvisit = statistic.data.data.lastdailyvisittrend.list[0].visit_uv;
			}else if(val == 2){
				statistic.visitor = statistic.data.data.weeklyvisittrend.list[0].visit_uv;
				lastvisit = statistic.data.data.lastweeklyvisittrend.list[0].visit_uv;
			}else if(val == 3){
				statistic.visitor = statistic.data.data.monthlyvisittrend.list[0].visit_uv;
				lastvisit = statistic.data.data.lastmonthlyvisittrend.list[0].visit_uv;
			}
			if(lastvisit == 0){
				if(lastvisit > statistic.visitor){
					$('.panel-1').find('.trend-down').show();
					statistic.visitPct = parseInt(((lastvisit - statistic.visitor) / 1) * 100) + '%';
				}else if(lastvisit < statistic.visitor){
					$('.panel-1').find('.trend-up').show();
					statistic.visitPct = parseInt(((statistic.visitor - lastvisit) / 1) * 100) + '%';
				}else{
					$('.panel-1').find('.trend').show();
				}
			}else{
				if(lastvisit > statistic.visitor){
					$('.panel-1').find('.trend-down').show();
					statistic.visitPct = parseInt(((lastvisit - statistic.visitor) / lastvisit) * 100) + '%';
				}else if(lastvisit < statistic.visitor){
					$('.panel-1').find('.trend-up').show();
					statistic.visitPct = parseInt(((statistic.visitor - lastvisit) / lastvisit) * 100) + '%';
				}else{
					$('.panel-1').find('.trend').show();
				}                
			}
			$('.loading').addClass('toggle');
			$('.panel-1').children('.panel-body').removeClass('toggle');
		},500)
	});

	$(document).on('click', '.new_visitor .nav-link', function () {
		$('.panel-2').find('.trend-down').hide();
		$('.panel-2').find('.trend-up').hide();
		$('.panel-2').find('.trend').hide();
		var val = $('.new_visitor .active').prev().val();
		$('.loading-2').removeClass('toggle');
		$('.panel-2').children('.panel-body').addClass('toggle');
		setTimeout(function(){
			let addvisit;
			if(val == 1){
				statistic.newVisitor = statistic.data.data.dailyvisittrend.list[0].visit_uv_new;
				addvisit = statistic.data.data.lastdailyvisittrend.list[0].visit_uv_new;
			}else if(val == 2){
				statistic.newVisitor = statistic.data.data.weeklyvisittrend.list[0].visit_uv_new;
				addvisit = statistic.data.data.lastweeklyvisittrend.list[0].visit_uv_new;
			}else if(val == 3){
				statistic.newVisitor = statistic.data.data.monthlyvisittrend.list[0].visit_uv_new;
				addvisit = statistic.data.data.lastmonthlyvisittrend.list[0].visit_uv_new;
			}
			if(addvisit == 0){
				if(addvisit > statistic.newVisitor){
					$('.panel-2').find('.trend-down').show();
					statistic.newvisitPct = parseInt(((addvisit - statistic.newVisitor) / 1) * 100) + '%';
				}else if(addvisit < statistic.newVisitor){
					$('.panel-2').find('.trend-up').show();
					statistic.newvisitPct = parseInt(((statistic.newVisitor - addvisit) / 1) * 100) + '%';
				}else{
					$('.panel-2').find('.trend').show();
				}
			}else{
				if(addvisit > statistic.newVisitor){
					$('.panel-2').find('.trend-down').show();
					statistic.newvisitPct = parseInt(((addvisit - statistic.newVisitor) / addvisit) * 100) + '%';
				}else if(addvisit < statistic.newVisitor){
					$('.panel-2').find('.trend-up').show();
					statistic.newvisitPct = parseInt(((statistic.newVisitor - addvisit) / addvisit) * 100) + '%';
				}else{
					$('.panel-2').find('.trend').show();
				}                
			}
			$('.loading-2').addClass('toggle');
			$('.panel-2').children('.panel-body').removeClass('toggle');
		},500);          
	});

	$(document).on('click', '.share_num .nav-link', function () {
		$('.panel-3').find('.trend-down').hide();
		$('.panel-3').find('.trend-up').hide();
		$('.panel-3').find('.trend').hide();
		var val = $('.share_num .active').prev().val();
		$('.loading-3').removeClass('toggle');
		$('.panel-3').children('.panel-body').addClass('toggle');
		setTimeout(function(){
			let lastShare;
			if(val == 1){
				statistic.share = statistic.data.data.dailysummarytrend.list[0].share_pv;
				lastShare = statistic.data.data.lastsummarytrend.list[0].share_pv;
			}else if(val == 2){
				statistic.share = statistic.data.data.dailysummarytrend.list[0].share_uv;
				lastShare = statistic.data.data.lastsummarytrend.list[0].share_uv;
			}
			if(lastShare == 0){
				if(lastShare > statistic.share){
					$('.panel-3').find('.trend-down').show();
					statistic.sharePct = parseInt(((lastShare - statistic.share) / 1) * 100) + '%';
				}else if(lastShare < statistic.share){
					$('.panel-3').find('.trend-up').show();
					statistic.sharePct = parseInt(((statistic.share - lastShare) / 1) * 100) + '%';
				}else{
					$('.panel-3').find('.trend').show();
				}
			}else{
				if(lastShare > statistic.share){
					$('.panel-3').find('.trend-down').show();
					statistic.sharePct = parseInt(((lastShare - statistic.share) / lastShare) * 100) + '%';
				}else if(lastShare < statistic.share){
					$('.panel-3').find('.trend-up').show();
					statistic.sharePct = parseInt(((statistic.share - lastShare) / lastShare) * 100) + '%';
				}else{
					$('.panel-3').find('.trend').show();
				}                
			}

			$('.loading-3').addClass('toggle');
			$('.panel-3').children('.panel-body').removeClass('toggle');
		},500);          
	});

	$(document).on('click', '.open_num .nav-link', function () {
		statistic.open = 0;
		$('.panel-4').find('.trend-down').hide();
		$('.panel-4').find('.trend-up').hide();
		$('.panel-4').find('.trend').hide();
		var val = $('.open_num .active').prev().val();
		$('.loading-4').removeClass('toggle');
		$('.panel-4').children('.panel-body').addClass('toggle');
		$.ajax({
			url: '<?= $urlManager->createUrl('mch/store/analytics')?>',
			method: 'post',
			dataType: 'json',
			data: {
				_csrf: _csrf,
				time: statistic.day
			},
			success: function (res) {
				let path = [];
				let page_visit_uv = [];
				let visit_pv = [];
				let lastOpen = 0;
				let lastpath = [];
				let last_visit_pv = [];
				let last_page_visit_uv = [];
				let goods = [
							'pages/goods/goods',
							'pages/miaosha/details/details',
							'pages/pt/details/details',
							'pages/book/details/details',
							'pages/integral-mall/goods-info/index',
							'bargain/goods/goods',
							'lottery/goods/goods'
							];
				if(val == 1){
					statistic.pages.forEach(function(e){
						path.push(e.page_path)
						visit_pv.push(e.page_visit_pv)
					});
					for(i = 0;i < goods.length;i++){           
						if(Number(visit_pv[path.indexOf(goods[i])])){
							statistic.open += visit_pv[path.indexOf(goods[i])]
						}
					}
					res.data.lastvisitdistribution.list.forEach(function(e){
						lastpath.push(e.page_path)
						last_visit_pv.push(e.page_visit_pv)
					});
					for(i = 0;i < goods.length;i++){           
						if(Number(last_visit_pv[lastpath.indexOf(goods[i])])){
							lastOpen += last_visit_pv[lastpath.indexOf(goods[i])]
						}
					}
				}else if(val == 2){
					statistic.pages.forEach(function(e){
						path.push(e.page_path)
						page_visit_uv.push(e.page_visit_uv)
					});  
					for(i = 0;i < goods.length;i++){           
						if(Number(page_visit_uv[path.indexOf(goods[i])])){
							statistic.open += page_visit_uv[path.indexOf(goods[i])]
						}
					}
					res.data.lastvisitdistribution.list.forEach(function(e){
						lastpath.push(e.page_path)
						last_page_visit_uv.push(e.page_visit_uv)
					});
					for(i = 0;i < goods.length;i++){           
						if(Number(last_page_visit_uv[lastpath.indexOf(goods[i])])){
							lastOpen += last_page_visit_uv[lastpath.indexOf(goods[i])]
						}
					}                  
				}
				if(lastOpen == 0){
					if(lastOpen > statistic.open){
						$('.panel-4').find('.trend-down').show();
						statistic.openPct = parseInt(((lastOpen - statistic.open) / 1) * 100) + '%';
					}else if(lastOpen < statistic.open){
						$('.panel-4').find('.trend-up').show();
						statistic.openPct = parseInt(((statistic.open - lastOpen) / 1) * 100) + '%';
					}else{
						$('.panel-4').find('.trend').show();
					}                
				}else{
					if(lastOpen > statistic.open){
						$('.panel-4').find('.trend-down').show();
						statistic.openPct = parseInt(((lastOpen - statistic.open) / lastOpen) * 100) + '%';
					}else if(lastOpen < statistic.open){
						$('.panel-4').find('.trend-up').show();
						statistic.openPct = parseInt(((statistic.open - lastOpen) / lastOpen) * 100) + '%';
					}else{
						$('.panel-4').find('.trend').show();
					}
				}
				$('.loading-4').addClass('toggle');
				$('.panel-4').children('.panel-body').removeClass('toggle');
		}
		});          
	});

	$(document).on('change', '.category', function (){
		statistic.status = $(this).find('select').val();
		let user = statistic.user.data.userportraitone;
		$('.userPort').find('.active').removeClass('active');
		$('.userPort').children('.nav-item:first').children('.nav-link').addClass('active');
		if(statistic.status == 'normal'){
			user.visit_uv.province.sort(function(a,b){
				return b.value - a.value;
			})
			// 用户画像柱状图
			let userlist = [];
			for (i = 0;i< 10;i++) {
				userlist.unshift(user.visit_uv.province[i])
			}
			let province =  [];
			let person = [];
			userlist.forEach(function(e){
				province.push(e.name);
				person.push(e.value);
			});
			echarts3(province,person);
			// 用户画像饼状图
			let echarts_4 = echarts.init(document.getElementById('echarts_4'));
			let temp = [];
			let otherProvince = [];
			user.visit_uv.province.forEach(function(e){
				temp.push(e.value);
			});
			let num = 0;
			for (i = 3;i< temp.length-1;i++) {
				num += temp[i];
			}
			other = [{name: '其他', value: num}]
			other.unshift(user.visit_uv.province[0],user.visit_uv.province[1],user.visit_uv.province[2])
			other.forEach(function(e){
				otherProvince.push(e.name);
			});
			echarts4(echarts_4,otherProvince,other)
			// 用户性别饼状图
			let echarts_5 = echarts.init(document.getElementById('echarts_5'));
			let userSex = user.visit_uv.genders;
			userSex.push(userSex[0]);
			userSex.shift();
			let sexName = [];
			userSex.forEach(function(e){
				sexName.push(e.name);
			});       
			echarts4(echarts_5,sexName,userSex);
		}else if(statistic.status == 'add'){
			user.visit_uv_new.province.sort(function(a,b){
				return b.value - a.value;
			})
			// 用户画像柱状图
			let userlist = [];
			for (i = 0;i< 10;i++) {
				userlist.unshift(user.visit_uv_new.province[i])
			}
			let province =  [];
			let person = [];
			userlist.forEach(function(e){
				province.push(e.name);
				person.push(e.value);
			});
			echarts3(province,person);
			// 用户画像饼状图
			let echarts_4 = echarts.init(document.getElementById('echarts_4'));
			let temp = [];
			let otherProvince = [];
			user.visit_uv_new.province.forEach(function(e){
				temp.push(e.value);
			});
			let num = 0;
			for (i = 3;i< temp.length-1;i++) {
				num += temp[i];
			}
			other = [{name: '其他', value: num}]
			other.unshift(user.visit_uv_new.province[0],user.visit_uv_new.province[1],user.visit_uv_new.province[2])
			other.forEach(function(e){
				otherProvince.push(e.name);
			});
			echarts4(echarts_4,otherProvince,other)
			// 用户性别饼状图
			let echarts_5 = echarts.init(document.getElementById('echarts_5'));
			let userSex = user.visit_uv_new.genders;
			userSex.push(userSex[0]);
			userSex.shift();
			let sexName = [];
			userSex.forEach(function(e){
				sexName.push(e.name);
			});       
			echarts4(echarts_5,sexName,userSex);            
		}
	})

	$(document).on('click', '.userPort .nav-link', function () {
		var val = $('.userPort .active').prev().val();
		let user;
		if(statistic.status == 'normal'){
			if(val == 1){
				user = statistic.user.data.userportraitone.visit_uv;
			}else if(val == 2){
				user = statistic.user.data.userportraitseven.visit_uv;
			}else if(val == 3){
				user = statistic.user.data.userportraitthirty.visit_uv;
			}
		}else if(statistic.status == 'add'){
			if(val == 1){
				user = statistic.user.data.userportraitone.visit_uv_new;
			}else if(val == 2){
				user = statistic.user.data.userportraitseven.visit_uv_new;
			}else if(val == 3){
				user = statistic.user.data.userportraitthirty.visit_uv_new;
			}
		}

		user.province.sort(function(a,b){
			return b.value - a.value;
		})
		// 用户画像柱状图
		let userlist = [];

		for (i = 0;i< 10;i++) {
			userlist.unshift(user.province[i])
		}
		let province =  [];
		let person = [];
		userlist.forEach(function(e){
			province.push(e.name);
			person.push(e.value);
		});
		echarts3(province,person);
		// 用户画像饼状图
		let echarts_4 = echarts.init(document.getElementById('echarts_4'));
		let temp = [];
		let otherProvince = [];
		user.province.forEach(function(e){
			temp.push(e.value);
		});
		let num = 0;
		for (i = 3;i< temp.length-1;i++) {
			num += temp[i];
		}
		other = [{name: '其他', value: num}]
		other.unshift(user.province[0],user.province[1],user.province[2])
		other.forEach(function(e){
			otherProvince.push(e.name);
		});
		echarts4(echarts_4,otherProvince,other)
		// 用户性别饼状图
		let echarts_5 = echarts.init(document.getElementById('echarts_5'));
		let userSex = user.genders;
		userSex.push(userSex[0]);
		userSex.shift();
		let sexName = [];
		userSex.forEach(function(e){
			sexName.push(e.name);
		});       
		echarts4(echarts_5,sexName,userSex);
	});

	$(document).on('click', '.pagination .page-item', function (e){
		statistic.tenPages = [];
		let text = e.target.text;
		$(this).siblings('.hold').removeClass('hold');
		if(Number(text)){
			statistic.now = +text;
			$(this).siblings('.disabled').removeClass('disabled');
			$(this).addClass('hold');
			if(text == 1){
				$(this).prevAll().addClass('disabled')
			}else if(text == statistic.pagination[statistic.pagination.length - 1]){
				$(this).nextAll().addClass('disabled')
			}
		}else if(text == '首页'){
			statistic.now = 1;
			$('.page-item:gt(2)').removeClass('disabled');
			$('.page-item:lt(2)').addClass('disabled');
		}else if(text == '尾页'){
			 statistic.now = statistic.pagination.length + 1;
			$(`.page-item:lt(${statistic.pagination.length + 2})`).removeClass('disabled');
			$(`.page-item:gt(${statistic.pagination.length + 2})`).addClass('disabled');
		}else if(text == '上一页'){
			statistic.now--;
			$('.page-item:gt(2)').removeClass('disabled');
			if(statistic.now == 1){
				$('.page-item:lt(2)').addClass('disabled');

			}
		}else if(text == '下一页'){
			statistic.now++;
			$(`.page-item:lt(${statistic.pagination.length + 2})`).removeClass('disabled');
			if(statistic.now == statistic.pagination.length + 1){
				$(`.page-item:gt(${statistic.pagination.length + 2})`).addClass('disabled');
			}
		}
		$(`.page-item:nth-child(${statistic.now + 2})`).addClass('hold');
		statistic.tenPages = statistic.pages.slice((statistic.now-1)*10 , (statistic.now-1)*10 + 10)

	});

	$(document).on('click', '.date-submit', function (){
		statistic.chooseDay = $('#date').val();
		let day = statistic.chooseDay.split("-").join("");
		statistic.pages = [],
		statistic.pagination = null,
		statistic.length = 0,
		statistic.tenPages = [],
		statistic.now = 1;
		$('.loading-8').removeClass('toggle');
		$('.panel-8').find('table').addClass('toggle');
		$('.panel-8').find('.text-center').addClass('toggle');
		$.ajax({
			url: '<?= $urlManager->createUrl('mch/store/analytics')?>',
			method: 'post',
			dataType: 'json',
			data: {
				_csrf: _csrf,
				time: day
			},
			success: function (res) {
				$('.page-item:nth-child(3)').prevAll().addClass('disabled');
				$('.page-item:nth-child(3)').addClass('hold');
				$('.page-item:nth-child(3)').siblings().removeClass('hold');
				$('.loading-8').addClass('toggle');
				$('.panel-8').find('table').removeClass('toggle');
				$('.panel-8').find('.text-center').removeClass('toggle');
				statistic.pages = res.data.visitdistribution.list;
				if(statistic.pages != undefined){
					statistic.length = res.data.visitdistribution.list.length;
					statistic.pagination = [Math.ceil(statistic.length/10)];
					if(statistic.pagination > 1){
						$('.hold').nextAll('.disabled').removeClass('disabled');
						for (i = 2;i < 10;i++) {
							if(i <= statistic.pagination[0]){
								statistic.pagination.push(i)
							}
					}
					statistic.pagination.shift();
					}else{
						$(this).siblings('.disabled').removeClass('disabled');
						statistic.pagination = [];
						$('.page-item').addClass('disabled');
					}
					for (i = 0;i < 10;i++) {
						statistic.tenPages.push(statistic.pages[i]);
					}                    
				}

		}
		});
	});

	$(document).on('click', '#show_date_start', function (){
		$('#date').focus();
	});

</script>

<script>
	var echarts1 = function(echarts,date,number){
			setTimeout(function () {
				var echarts_1_option = {
					tooltip: {
						trigger: 'axis'
					},
					legend: {
						data: ['访问量']
					},
					grid: {
						left: '0%',
						right: '0%',
						bottom: '5%',
						containLabel: true
					},
					xAxis: {
						data: date,
					},
					yAxis: {
						type: 'value'
					},
					series: [
						{
							name: '访问量',
							type: 'line',
							data: number,
						}
					]
				};;
				echarts.setOption(echarts_1_option);
			}, 500);    
	};
	var echarts2 = function(echarts,date,number1,number2){
			setTimeout(function () {
				var echarts_1_option = {
					tooltip: {
						trigger: 'axis'
					},
					legend: {
						data: ['1日后','3日后']
					},
					grid: {
						left: '0%',
						right: '0%',
						bottom: '5%',
						containLabel: true
					},
					xAxis: {
						data: date,
					},
					yAxis: {
						type: 'value'
					},
					series: [
						{
							name: '1日后',
							type: 'line',
							data: number1,
						},{
							name: '3日后',
							type: 'line',
							data: number2,
						}
					]
				};;
				echarts.setOption(echarts_1_option);
			}, 500);    
	};
	var echarts3 = function(city,number){
			var echarts_3 = echarts.init(document.getElementById('echarts_3'));
			setTimeout(function () {
				var echarts_3_option = {
					color: ['#63aef6'],
					tooltip: {
						trigger: 'axis',
						axisPointer: {
							type: 'shadow'
						}
					},
					grid: {
						left: '3%',
						right: '4%',
						bottom: '3%',
						top: '5%',
						containLabel: true
					},
					xAxis: {
						type: 'value',
						boundaryGap: [0, 0.01]
					},
					yAxis: {
						type: 'category',
						data: city
					},
					series: [
						{
							name: '用户数',
							type: 'bar',
							label: {
								normal: {
									show: true,
									position: 'insideRight'
								}
							},
							barWidth : 20,
							data: number
						}
					]
					};
				echarts_3.setOption(echarts_3_option);
			}, 500);    
	};

	var echarts4 = function(echarts,province,user){
			setTimeout(function () {
				var echarts_4_option = {
					tooltip: {
						trigger: 'item',
						formatter: "{a} <br/>{b}: {c} ({d}%)"
					},
					legend: {
						orient: 'vertical',
						x: 'left',
						data: province
					},
					series: [
						{
							name:'用户数',
							type:'pie',
							radius: ['50%', '70%'],
							avoidLabelOverlap: false,
							label: {
								normal: {
									show: false,
									position: 'center'
								},
								emphasis: {
									show: true,
									textStyle: {
										fontSize: '30',
										fontWeight: 'bold'
									}
								}
							},
							label: {
								normal: {
									show: false
								}
							},
							data: user
						}
					]                    
				};
				echarts.setOption(echarts_4_option);
			}, 500);    
	};

	jQuery.datetimepicker.setLocale('zh');
	jQuery('#date').datetimepicker({
		datepicker: true,
		timepicker: false,
		format: 'Y-m-d',
		dayOfWeekStart: 1,
		scrollMonth: false,
		scrollTime: false,
		scrollInput: false,
	});
</script>