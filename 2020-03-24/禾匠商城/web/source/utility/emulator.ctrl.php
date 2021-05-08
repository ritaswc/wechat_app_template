<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
$_W['page']['title'] = '模拟测试';
$development = 1;
$accounts = uni_owned(0, false);
define('FRAME', '');
template('common/header');

?>
<style type="text/css">
.chatPanel .left{float:left;}
.chatPanel .right{float:right;}
.chatPanel .media a{display:block;}
.chatPanel .media{border:1px solid #cdcdcd;box-shadow:0 3px 6px #999999;-webkit-border-radius:12px;-moz-border-radius:12px;border-radius:12px;width:285px;background-color:#FFFFFF;background:-webkit-gradient(linear,left top,left bottom,from(#FFFFFF),to(#FFFFFF));background-image:-moz-linear-gradient(top,#FFFFFF 0%,#FFFFFF 100%);margin:0px auto;}
.chatPanel .media .mediaPanel{padding:0px;margin:0px;}
.chatPanel .media .mediaImg{margin:25px 15px 15px;width:255px;position:relative;}
.chatPanel .media .mediaImg .mediaImgPanel{position:relative;padding:0px;margin:0px;max-height:164px;overflow:hidden;}
.chatPanel .media .mediaImg img{/* width:100%;height:164px;position:absolute;left:0px;*/width:255px;}
.chatPanel .media .mediaImg .mediaImgFooter{position:absolute;bottom:0;height:29px;background-color:#000;background-color:rgba(0,0,0,0.4);text-shadow:none;color:#FFF;text-align:left;padding:0px 11px;line-height:29px;width:233px;}
.chatPanel .media .mediaImg .mediaImgFooter a:hover p{color:#B8B3B3;}
.chatPanel .media .mediaImg .mediaImgFooter .mesgTitleTitle{line-height:28px;color:#FFF;max-width:240px;height:26px;white-space:nowrap;text-overflow:ellipsis;-o-text-overflow:ellipsis;overflow:hidden;width:240px;}
.chatPanel .media .mesgIcon{display:inline-block;height:19px;width:13px;margin:8px 0px -2px 4px;}
.chatPanel .media .mediaContent{margin:0px;padding:0px;}
.chatPanel .media .mediaContent .mediaMesg{border-top:1px solid #D7D7D7;padding:10px;}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgDot{display:block;position:relative;top:-3px;left:20px;height:6px;width:6px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgTitle:hover p{color:#1A1717;}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgTitle a{color:#707577;}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgTitle a:hover p{color:#444440;}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgIcon{}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgTitle p{line-height:1.5em;max-height:45px;max-width:220px;min-width:176px;margin-top:2px;color:#5D6265;text-overflow:ellipsis;-o-text-overflow:ellipsis;overflow:hidden;text-align:left;text-overflow:ellipsis;}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgIcon img{height:45px;width:45px;}
/*media mesg detail*/
.chatPanel .media .mediaHead{/*height:48px;*/padding:0px 15px 4px;border-bottom:0px solid #D3D8DC;color:#000000;font-size:20px;}
.chatPanel .media .mediaHead .title{line-height:1.2em;margin-top:22px;display:block;max-width:312px;text-align:left;/*height:25px;white-space:nowrap;text-overflow:ellipsis;-o-text-overflow:ellipsis;overflow:hidden;*/}
.chatPanel .mediaFullText .mediaImg{width:255px;padding:0;margin:0 15px;overflow:hidden;max-height:164px;}
.chatPanel .mediaFullText .mediaImg img{/*margin-top:17px;position:absolute;*/}
.chatPanel .mediaFullText .mediaContent{padding:0 0 8px;font-size:16px;line-height:1.5em;text-align:left;color:#222222;}
.chatPanel .mediaFullText .mediaContentP{margin:12px 15px 0px; word-break: break-all;}
.chatPanel .media .mediaHead .time{margin:0px;margin-top:21px;color:#8C8C8C;background:none;width:auto;font-size:12px;}
.chatPanel .media .mediaFooter{-webkit-border-radius:0px 0px 12px 12px;-moz-border-radius:0px 0px 12px 12px;border-radius:0px 0px 12px 12px;padding:0px;}
.chatPanel .media .mediaFooter a{color:#222222;font-size:16px;padding:0;}
.chatPanel .media .mediaFooter .mesgIcon{margin:15px 3px 0px 0px;}
.chatPanel .media .mediaFooterbox{border-top:1px #CCC solid;}
.chatPanel .media a:hover{cursor:pointer;}
.chatPanel .media a:hover .mesgIcon{}
.mediaContent a:hover{background-color:#F6F6F6;}
.mediaContent .last:hover{-webkit-border-radius:0px 0px 12px 12px;-moz-border-radius:0px 0px 12px 12px;border-radius:0px 0px 12px 12px;background-color:#F6F6F6;}
.mediaFullText:hover{background-color:#F6F6F6;background:-webkit-gradient(linear,left top,left bottom,from(#F6F6F6),to(#F6F6F6));background-image:-moz-linear-gradient(top,#F6F6F6 0%,#F6F6F6 100%);}
.chatItem a{text-decoration:none;}.chatItem a:hover{text-decoration:none;}.mediaFooterbox{cursor:pointer; padding:0 15px;}
#svinfolist{display:none;}#svinfolist p{border-top:1px #CCC solid; padding:4px 6px;word-break:break-all; white-space:pre; margin:2px; cursor:pointer;}
#svinfolist p img{width:50px;height:50px;}
.btn{white-space:normal;box-sizing:content-box;}
</style>
<div class="panel panel-content">
		<div class="content-head panel-heading">
			<span class="font-lg">模拟测试</span>					
		</div>
		<div class="panel-body">
			<div class="col-sm-8">
				<form action="" method="get" class="form-horizontal form">
					<div class="page-header">
						<h4>模拟测试</h4>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label"></label>
						<div class="col-sm-10 col-xs-12">
							<input name="submit" type="button" onclick="submitform()" value="发送" class="btn btn-primary" style="margin-right:15px">
							<input name="submit" type="button" onclick="submitprocess()" value="查看触发过程" class="btn btn-success">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">公众号</label>
						<div class="col-sm-10 col-xs-12">
							<select name="account" id="account" class="form-control">
							<?php
								foreach ($accounts as $account) {
									?>
								<?php
									if (!empty($account)) {
										$timestamp = TIMESTAMP;
										$nonce = random(5);
										$token = $account['token'];
										$signkey = array($token, TIMESTAMP, $nonce);
										sort($signkey, SORT_STRING);
										$signString = implode($signkey);
										$signString = sha1($signString); ?>
								<?php if (1 == $development) {
											?>
									<option <?php if ($_W['uniacid'] == $account['uniacid']) {
												?>selected<?php
											} ?> value="<?php echo '../api.php?id=' . $account['acid']; ?>&timestamp=<?php echo $timestamp; ?>&nonce=<?php echo $nonce; ?>&signature=<?php echo $signString; ?>"><?php echo $account['name']; ?></option>
								<?php
										} else {
											?>
									<option <?php if ($_W['uniacid'] == $account['uniacid']) {
												?>selected<?php
											} ?> value="<?php echo $account['uniacid']; ?>"><?php echo $account['name']; ?></option>
								<?php
										} ?>
								<?php
									} ?>
							<?php
								}
							?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">消息类型</label>
						<div class="col-sm-10 col-xs-12">
							<div class="radio-inline"><input type="radio" name="type" value="text" id="type_text" onclick="toggle('text')" checked="checked" /><label for="type_text">&nbsp;文本</label></div>
							<div class="radio-inline"><input type="radio" name="type" value="image" id="type_image" onclick="toggle('image')" /><label for="type_image">&nbsp;图片</label></div>
							<div class="radio-inline"><input type="radio" name="type" value="location" id="type_location" onclick="toggle('location')" /><label for="type_location">&nbsp;位置</label></div>
							<div class="radio-inline"><input type="radio" name="type" value="link" id="type_link" onclick="toggle('link')" /><label for="type_link">&nbsp;链接</label></div>
							<div class="radio-inline"><input type="radio" name="type" value="event" id="type_event" onclick="toggle('event')" /><label for="type_event">&nbsp;菜单</label></div>
							<div class="radio-inline"><input type="radio" name="type" value="subscribe" id="type_subscribe" onclick="toggle('subscribe')" /><label for="type_subscribe">&nbsp;模拟关注</label></div>
							<div class="radio-inline"><input type="radio" name="type" value="unsubscribe" id="type_unsubscribe" onclick="toggle('unsubscribe')" /><label for="type_unsubscribe">&nbsp;取消关注</label></div>
							<div class="radio-inline"><input type="radio" name="type" value="other" id="type_other" onclick="toggle('other')" /><label for="type_other">&nbsp;其他</label></div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">发送用户</label>
						<div class="col-sm-10 col-xs-12">
							<input type="text" id="fromuser" value="fromUser" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">接收用户</label>
						<div class="col-sm-10 col-xs-12">
							<input type="text" id="touser" value="toUser" class="form-control" />
						</div>
					</div>
					<div class="form-group content_type" id="text">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">内容</label>
						<div class="col-sm-10 col-xs-12">
							<textarea id="contentvalue" rows="5" cols="50" class="form-control">测试内容</textarea>
						</div>
					</div>
					<div class="form-group content_type" id="image">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">图片</label>
						<div class="col-sm-10 col-xs-12">
							<input type="text" id="picurl" value="http://www.baidu.com/img/bdlogo.gif" class="form-control" />
						</div>
					</div>
					<div id="location" class="content_type">
						<div class="form-group">
							<label class="col-xs-12 col-sm-2 col-md-2 control-label">X坐标</label>
							<div class="col-sm-10 col-xs-12">
								<input type="text" id="location_x" class="form-control" value="23.134521" />
							</div>
						</div>
						<div class="form-group" >
							<label class="col-xs-12 col-sm-2 col-md-2 control-label">Y坐标</label>
							<div class="col-sm-10 col-xs-12">
								<input type="text" id="location_y" class="form-control" value="113.358803" />
							</div>
						</div>
					</div>
					<div class="form-group content_type" id="link">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">链接</label>
						<div class="col-sm-10 col-xs-12">
							<input type="text" id="url" class="form-control" value="http://baidu.com" />
						</div>
					</div>
					<div class="form-group content_type" id="event">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">EventKey</label>
						<div class="col-sm-10 col-xs-12">
							<input type="text" id="event_key" class="form-control" value="EVENTKEY" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">发送消息</label>
						<div class="col-sm-10 col-xs-12">
							<textarea id="sendxml" rows="10" cols="50" class="form-control" readonly="readonly"></textarea>
						</div>
					</div>
					<div class="form-group" style="display:none" id="process">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">处理过程</label>
						<div class="col-sm-10 col-xs-12 table-responsive">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 control-label">接收消息</label>
						<div class="col-sm-10 col-xs-12">
							<pre id="receive"></pre>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-4" id="demoSendBox">
				<div class="chatPanel form" style="width:300px;">
					<div class="page-header">
						<h4>预览效果</h4>
					</div>
					<div id="svposttext" style="text-align:left; padding-bottom:10px;display:none;">
						<img src="./resource/images/noavatar_middle.gif" style="width:34px;height:34px;margin-right:6px;float:right;" class="img-rounded">
						<div id="svpostinfo" class="btn btn-success" style="margin-right: 4px;float: right;max-width: 184px;text-align:left;">发送内容</div>
						<div style="clear:both;"></div>
					</div>
		
					<div class="chatItem you">
						<div id="svtext" style="text-align:left; padding-bottom:10px;display:none;">
							<img src="./resource/images/noavatar_middle.gif" style="width:34px;height:34px;margin-left:6px; float:left;" class="img-rounded">
							<div class="btn btn-success" style="margin-left: 4px;float: left;max-width: 184px;text-align:left;">回复内容</div>
							<div style="clear:both;"></div>
						</div>
		
						<div id="svurlbox" style="display:none;">
							<div class="media mediaFullText">
								<div class="mediaPanel">
									<a href="javascript:;" id="svurl" target="_blank">
										<div class="mediaHead"><span class="title" id="svtitle">标题</span><span class="time"><?php echo date('m月d日'); ?></span>
											<div class="clr"></div>
										</div>
										<div class="mediaImg"><img id="svpic" src=""></div>
										<div class="mediaContent mediaContentP"><p id="svinfo"></p></div>
									</a>
									<div id="svinfolist"></div>
									<div class="mediaFooter">
										<div class="mediaFooterbox clearfix" onclick="opensvurl();">
											<span class="mesgIcon right">&gt;</span>
											<span style="line-height:50px;" class="left">查看全文</span>
										</div>
										<div class="clr"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
<?php
	if (1 == $development) {
		?>
		<script type="text/javascript">
			require(['jquery'], function($){
			$('.content_type').hide();
			$('#text').show();
			toggle('text');
		});
		function opensvurl(){
			var href=$("#svurl").attr("href");
			window.open(href, "_blank");
		}
		function popensvurl(href){
			if(href)window.open(href, "_blank");
		}
		function toggle(type) {
			curtype = type;
			$('.content_type').hide();
			$("#" + curtype).show();

			buildRequest(type);
			$('#receive').text('');
			$('#svposttext').hide();
			$('#svtext').hide();
			$('#svurlbox').hide();
			$('#sendxml').attr('readonly', 'readonly');
			if(type == 'other') {
				$('#sendxml').removeAttr('readonly');
			}
		}

		function getxml(xmlstring){
			var xmlobject = null;
			try{
				if(window.ActiveXObject){
					xmlobject =new ActiveXObject("Microsoft.XMLDOM");
					xmlobject.async="false";
					xmlobject.loadXML(xmlstring);
				}else{// 用于 Mozilla, Firefox, Opera, 等浏览器的代码：
					var parser=new DOMParser();
					xmlobject =parser.parseFromString(xmlstring,"text/xml");
				}
			}catch(e){alert("您的浏览器不支持模拟测试");}
			return xmlobject;
		}

		function buildRequest(type) {
			var $demoSendBox = $('#demoSendBox');
			$('span.time', $demoSendBox).show();
			$('div.mediaImg', $demoSendBox).show();
			$('div.mediaContent', $demoSendBox).show();
			$('div.mediaFooterbox', $demoSendBox).show();
			var time = Math.round(new Date().getTime()/1000);
			xml = 	"<xml>\n"+
				"<ToUserName><![CDATA["+$('#touser').val()+"]]></ToUserName>\n"+
				"<FromUserName><![CDATA["+$('#fromuser').val()+"]]></FromUserName>\n"+
				"<CreateTime>"+time+"</CreateTime>\n";
			if (type == 'text') {
				xml += "<MsgType><![CDATA[text]]></MsgType>\n";
				xml += "<Content><![CDATA["+$('#contentvalue').val()+"]]></Content>\n";
				$('#svpostinfo').text($('#contentvalue').val());
			} else if (type == 'image') {
				xml += "<MsgType><![CDATA[image]]></MsgType>\n";
				xml += "<PicUrl><![CDATA["+$('#picurl').val()+"]]></PicUrl>";
				$('#svpostinfo').html('<img src="'+$('#picurl').val()+'">');
			} else if (type == 'location') {
				xml += "<MsgType><![CDATA[location]]></MsgType>\n";
				xml += "<Location_X>"+parseFloat($('#location_x').val())+"</Location_X>\n";
				xml += "<Location_Y>"+parseFloat($('#location_y').val())+"</Location_Y>\n";
				xml += "<Scale>20</Scale>\n";
				xml += "<Label><![CDATA[位置信息]]></Label>\n";
				$('span.time', $demoSendBox).hide();
				$('div.mediaImg', $demoSendBox).hide();
				$('div.mediaContent', $demoSendBox).hide();
				$('#svpostinfo').html('<img src="data:image/jpg;base64,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">');
			} else if (type == 'link') {
				xml += "<MsgType><![CDATA[link]]></MsgType>\n";
				xml += "<Title><![CDATA[测试链接]]></Title>\n";
				xml += "<Description><![CDATA[测试链接描述]]></Description>\n";
				xml += "<Url><![CDATA["+$('#url').val()+"]]></Url>\n";
			} else if (type == 'subscribe') {
				xml += "<MsgType><![CDATA[event]]></MsgType>\n";
				xml += "<Event><![CDATA[subscribe]]></Event>\n";
				xml += "<EventKey><![CDATA[]]></EventKey>\n";
			} else if (type == 'unsubscribe') {
				xml += "<MsgType><![CDATA[event]]></MsgType>\n";
				xml += "<Event><![CDATA[unsubscribe]]></Event>\n";
				xml += "<EventKey><![CDATA[]]></EventKey>\n";
			} else if (type == 'event') {
				xml += "<MsgType><![CDATA[event]]></MsgType>\n";
				xml += "<Event><![CDATA[CLICK]]></Event>\n";
				xml += "<EventKey><![CDATA["+$('#event_key').val()+"]]></EventKey>\n";
			}
			xml +=  "<MsgId>1234567890123456</MsgId>\n"+
				"</xml>";
			if(type == 'other') {
				xml = $('#sendxml').val();
			}
			$('#sendxml').val(xml);
		}
		function submitprocess() {
			buildRequest(curtype);
			$('#svtext').hide();$('#svurlbox').hide();$('#svinfolist').hide();
			$('div.mediaFooterbox', $('#demoSendBox')).show();
			var url = $('#account').val() + '&debug=1';
			$.ajax(url, {
				type : 'POST',
				headers : {"Content-type" : "text/xml"},
				data : $('#sendxml').val().replace(/[\r\n]/g,""),
				beforeSend : function(){
					if(curtype!='subscribe' && curtype!='unsubscribe'){
						if(curtype=='text' || curtype=='image' || curtype == 'location'){
							$('#svposttext').show();
						}
					}
					$('#receive').text('加载中。。。');
				}
			}).done(function(data){
				var data = $.parseJSON(data);
				var s = data.resp;
				var p = data.process;
				if(1 || curtype!='unsubscribe'){
					var xmlobject = getxml(s);
					if(xmlobject){
						var xmlobj = xmlobject.getElementsByTagName("xml");
						if(xmlobj.length){
							var xmls = xmlobj.item(0);
							var xml = xmls;
							if (xml.getElementsByTagName("FromUserName").length > 0) {
								var FromUserName = xml.getElementsByTagName("FromUserName")[0].firstChild.nodeValue;
								var ToUserName = xml.getElementsByTagName("ToUserName")[0].firstChild.nodeValue;
								var MsgType = xml.getElementsByTagName("MsgType")[0].firstChild.nodeValue;
							} else {
								var MsgType = 'text';
								var Content = '';
							}

							if(MsgType=='text'){
								if (xml.getElementsByTagName("FromUserName").length > 0) {
									var Content = xml.getElementsByTagName("Content")[0].firstChild.nodeValue;
								} else {
									var Content = '';
								}
								Content = nl2br(Content);
								Content = Content.replace('./index', "../app/index");

								Content && $('#svtext').show().find('div.btn').html(Content);
							}else if(MsgType == 'news'){
								var Title = xml.getElementsByTagName("Title")[0].firstChild.nodeValue;
								var Description = xml.getElementsByTagName("Description")[0].firstChild.nodeValue;
								var PicUrl = xml.getElementsByTagName("PicUrl")[0].firstChild.nodeValue;
								var Url = xml.getElementsByTagName("Url")[0].firstChild.nodeValue;
								if(Url.indexOf('http://') == -1 && Url.indexOf('https://') == -1) {
									Url = '../app/' + Url;
								}
								$('#svtitle').html(Title);
								$('#svinfo').html(Description);
								$('#svpic').attr('src', PicUrl);
								$('#svurlbox').show().find('a#svurl').attr('href', Url);
								var titleObj = xml.getElementsByTagName("Title");
								if(titleObj.length>1){
									var svinfolist = imghtml = '';
									var UrlObj = xml.getElementsByTagName("Url");
									var PicUrlObj = xml.getElementsByTagName("PicUrl");
									for(var ti=1;ti<titleObj.length;ti++){
										imghtml = PicUrlObj[ti].firstChild.nodeValue ? '<img align="right" src="'+PicUrlObj[ti].firstChild.nodeValue+'">' : '';
										svinfolist += '<p class="clearfix" onclick="popensvurl(\''+UrlObj[ti].firstChild.nodeValue+'\')">'+titleObj[ti].firstChild.nodeValue+imghtml+'</p>';
									}
									$('div.mediaFooterbox', $('#demoSendBox')).hide();
									$('#svinfolist').show().html(svinfolist);
								}
							}
						}
					}
					$('#receive').text(s);
				}else{
					$('#receive').text('模拟取消关注成功');
				}
				$('#process').find('div').html(p);
				$('#process').show();
			});
		}

		function submitform() {
			$('#process').hide();
			buildRequest(curtype);
			$('#svtext').hide();$('#svurlbox').hide();$('#svinfolist').hide();
			$('div.mediaFooterbox', $('#demoSendBox')).show();
			$.ajax($('#account').val(), {
				type : 'POST',
				headers : {"Content-type" : "text/xml"},
				data : $('#sendxml').val().replace(/[\r\n]/g,""),
				beforeSend : function(){
				if(curtype!='subscribe' && curtype!='unsubscribe'){
					if(curtype=='text' || curtype=='image' || curtype == 'location'){
						$('#svposttext').show();
					}
				}
				$('#receive').text('加载中。。。');
			}
			}).done(function(s){
				if(1 || curtype!='unsubscribe'){
					var xmlobject = getxml(s);
					if(xmlobject){
						var xmlobj = xmlobject.getElementsByTagName("xml");
						if(xmlobj.length){
							var xmls = xmlobj.item(0);
							var xml = xmls;
							/* var FromUserName = xml.getElementsByTagName("FromUserName")[0].firstChild.nodeValue;
							 var ToUserName = xml.getElementsByTagName("ToUserName")[0].firstChild.nodeValue;
							 var MsgType = xml.getElementsByTagName("MsgType")[0].firstChild.nodeValue; */
							if (xml.getElementsByTagName("FromUserName").length > 0) {
								var FromUserName = xml.getElementsByTagName("FromUserName")[0].firstChild.nodeValue;
								var ToUserName = xml.getElementsByTagName("ToUserName")[0].firstChild.nodeValue;
								var MsgType = xml.getElementsByTagName("MsgType")[0].firstChild.nodeValue;
							} else {
								var MsgType = 'text';
								var Content = '';
							}

							if(MsgType=='text'){
								if (xml.getElementsByTagName("FromUserName").length > 0) {
									var Content = xml.getElementsByTagName("Content")[0].firstChild.nodeValue;
								} else {
									var Content = '';
								}
								Content = nl2br(Content);
								Content = Content.replace('./index', "../app/index");

								Content && $('#svtext').show().find('div.btn').html(Content);
							}else if(MsgType == 'news'){
								var Title = xml.getElementsByTagName("Title")[0].firstChild.nodeValue;
								var Description = xml.getElementsByTagName("Description")[0].firstChild.nodeValue;
								var PicUrl = xml.getElementsByTagName("PicUrl")[0].firstChild.nodeValue;
								var Url = xml.getElementsByTagName("Url")[0].firstChild.nodeValue;
								if(Url.indexOf('http://') == -1 && Url.indexOf('https://') == -1) {
									Url = '../app/' + Url;
								}
								$('#svtitle').html(Title);
								$('#svinfo').html(Description);
								$('#svpic').attr('src', PicUrl);
								$('#svurlbox').show().find('a#svurl').attr('href', Url);
								var titleObj = xml.getElementsByTagName("Title");
								if(titleObj.length>1){
									var svinfolist = imghtml = '';
									var UrlObj = xml.getElementsByTagName("Url");
									var PicUrlObj = xml.getElementsByTagName("PicUrl");
									for(var ti=1;ti<titleObj.length;ti++){
										imghtml = PicUrlObj[ti].firstChild.nodeValue ? '<img align="right" src="'+PicUrlObj[ti].firstChild.nodeValue+'">' : '';
										svinfolist += '<p class="clearfix" onclick="popensvurl(\''+UrlObj[ti].firstChild.nodeValue+'\')">'+titleObj[ti].firstChild.nodeValue+imghtml+'</p>';
									}
									$('div.mediaFooterbox', $('#demoSendBox')).hide();
									$('#svinfolist').show().html(svinfolist);
								}
							}
						}
					}
					$('#receive').text(s);
				}else{
					$('#receive').text('模拟取消关注成功');
				}
			})
		}

		function nl2br(str, is_xhtml) {
			var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
			return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
		}
	</script>
	<?php
	} else {
		?>
		<script type="text/javascript">
		require(['jquery'], function($){
			$('.content_type').hide();
			$('#text').show();
			toggle('text');
		});
		function opensvurl(){
			var href=$("#svurl").attr("href");
			window.open(href, "_blank");
		}
		function popensvurl(href){
			if(href)window.open(href, "_blank");
		}
		function toggle(type) {
			curtype = type;
			$('.content_type').hide();
			$("#" + curtype).show();

			buildRequest(type);
			$('#receive').text('');
			$('#svposttext').hide();
			$('#svtext').hide();
			$('#svurlbox').hide();
			$('#sendxml').attr('readonly', 'readonly');
			if(type == 'other') {
				$('#sendxml').removeAttr('readonly');
			}
		}

		function getxml(xmlstring){
			var xmlobject = null;
			try{
				if(window.ActiveXObject){
					xmlobject =new ActiveXObject("Microsoft.XMLDOM");
					xmlobject.async="false";
					xmlobject.loadXML(xmlstring);
				}else{// 用于 Mozilla, Firefox, Opera, 等浏览器的代码：
					var parser=new DOMParser();
					xmlobject =parser.parseFromString(xmlstring,"text/xml");
				}
			}catch(e){alert("您的浏览器不支持模拟测试");}
			return xmlobject;
		}

		function buildRequest(type) {
			var $demoSendBox = $('#demoSendBox');
			$('span.time', $demoSendBox).show();
			$('div.mediaImg', $demoSendBox).show();
			$('div.mediaContent', $demoSendBox).show();
			$('div.mediaFooterbox', $demoSendBox).show();
			var time = Math.round(new Date().getTime()/1000);
			xml = 	"<xml>\n"+
				"<ToUserName><![CDATA["+$('#touser').val()+"]]></ToUserName>\n"+
				"<FromUserName><![CDATA["+$('#fromuser').val()+"]]></FromUserName>\n"+
				"<CreateTime>"+time+"</CreateTime>\n";
			if (type == 'text') {
				xml += "<MsgType><![CDATA[text]]></MsgType>\n";
				xml += "<Content><![CDATA["+$('#contentvalue').val()+"]]></Content>\n";
				$('#svpostinfo').text($('#contentvalue').val());
			} else if (type == 'image') {
				xml += "<MsgType><![CDATA[image]]></MsgType>\n";
				xml += "<PicUrl><![CDATA["+$('#picurl').val()+"]]></PicUrl>";
				$('#svpostinfo').html('<img src="'+$('#picurl').val()+'">');
			} else if (type == 'location') {
				xml += "<MsgType><![CDATA[location]]></MsgType>\n";
				xml += "<Location_X>"+parseFloat($('#location_x').val())+"</Location_X>\n";
				xml += "<Location_Y>"+parseFloat($('#location_y').val())+"</Location_Y>\n";
				xml += "<Scale>20</Scale>\n";
				xml += "<Label><![CDATA[位置信息]]></Label>\n";
				$('span.time', $demoSendBox).hide();
				$('div.mediaImg', $demoSendBox).hide();
				$('div.mediaContent', $demoSendBox).hide();
				$('#svpostinfo').html('<img src="data:image/jpg;base64,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">');
			} else if (type == 'link') {
				xml += "<MsgType><![CDATA[link]]></MsgType>\n";
				xml += "<Title><![CDATA[测试链接]]></Title>\n";
				xml += "<Description><![CDATA[测试链接描述]]></Description>\n";
				xml += "<Url><![CDATA["+$('#url').val()+"]]></Url>\n";
			} else if (type == 'subscribe') {
				xml += "<MsgType><![CDATA[event]]></MsgType>\n";
				xml += "<Event><![CDATA[subscribe]]></Event>\n";
				xml += "<EventKey><![CDATA[]]></EventKey>\n";
			} else if (type == 'unsubscribe') {
				xml += "<MsgType><![CDATA[event]]></MsgType>\n";
				xml += "<Event><![CDATA[unsubscribe]]></Event>\n";
				xml += "<EventKey><![CDATA[]]></EventKey>\n";
			} else if (type == 'event') {
				xml += "<MsgType><![CDATA[event]]></MsgType>\n";
				xml += "<Event><![CDATA[CLICK]]></Event>\n";
				xml += "<EventKey><![CDATA["+$('#event_key').val()+"]]></EventKey>\n";
			}
			xml +=  "<MsgId>1234567890123456</MsgId>\n"+
				"</xml>";
			if(type == 'other') {
				xml = $('#sendxml').val();
			}
			$('#sendxml').val(xml);
		}
		function submitform() {
			buildRequest(curtype);
			var id = $('#account').val();
			$.ajax('<?php echo $_W['siteroot']; ?>api.php?flag=1&id=' + id, {
				type : 'POST',
				headers : {"Content-type" : "text/xml"},
				data : $('#sendxml').val().replace(/[\r\n]/g,""),
				success : function(data) {
					var data = $.parseJSON(data);
					var url = '<?php echo $_W['siteroot']; ?>api.php?encrypt_type=' + data.encrypt_type + '&id=' + id + '&timestamp=' + data.timestamp + '&nonce=' + data.nonce + '&signature=' + data.signature + '&msg_signature=' + data.msg_signature;
					if(data.encrypt_type == 'aes') {
						var xml = "";
						xml += "<xml>\n";
						xml += "<ToUserName><![CDATA["+$('#touser').val()+"]]></ToUserName>\n";
						xml += "<Encrypt><![CDATA["+data.msg+"]]></Encrypt>\n";
						xml += "</xml>";
					} else {
						var xml = $('#sendxml').val();
					}
					$('#svtext').hide();$('#svurlbox').hide();$('#svinfolist').hide();
					$('div.mediaFooterbox', $('#demoSendBox')).show();
					$.ajax(url, {
						type : 'POST',
						headers : {"Content-type" : "text/xml"},
						data : xml.replace(/[\r\n]/g,""),
						beforeSend : function(){
							if(curtype!='subscribe' && curtype!='unsubscribe'){
								if(curtype=='text' || curtype=='image' || curtype == 'location'){
									$('#svposttext').show();
								}
							}
							$('#receive').text('加载中。。。');
						}
					}).done(function(s){
						if(1 || curtype!='unsubscribe'){
							$.ajax('<?php echo $_W['siteroot']; ?>api.php?flag=2&id=' + id, {
								type : 'POST',
								headers : {"Content-type" : "text/xml"},
								data : s.replace(/[\r\n]/g,""),
								success : function(s) {
									var xmlobject = getxml(s);
									if(xmlobject){
										var xmlobj = xmlobject.getElementsByTagName("xml");
										if(xmlobj.length){
											var xmls = xmlobj.item(0);
											var xml = xmls;

											/* var FromUserName = xml.getElementsByTagName("FromUserName")[0].firstChild.nodeValue;
											 var ToUserName = xml.getElementsByTagName("ToUserName")[0].firstChild.nodeValue;
											 var MsgType = xml.getElementsByTagName("MsgType")[0].firstChild.nodeValue; */

											if (xml.getElementsByTagName("FromUserName").length > 0) {
												var FromUserName = xml.getElementsByTagName("FromUserName")[0].firstChild.nodeValue;
												var ToUserName = xml.getElementsByTagName("ToUserName")[0].firstChild.nodeValue;
												var MsgType = xml.getElementsByTagName("MsgType")[0].firstChild.nodeValue;
											} else {
												var MsgType = 'text';
												var Content = '';
											}
											if(MsgType=='text'){
												if (xml.getElementsByTagName("FromUserName").length > 0) {
													var Content = xml.getElementsByTagName("Content")[0].firstChild.nodeValue;
												} else {
													var Content = '';
												}
												Content = nl2br(Content);
												Content = Content.replace('./index', "../app/index");

												Content && $('#svtext').show().find('div.btn').html(Content);
											}else if(MsgType == 'news'){
												var Title = xml.getElementsByTagName("Title")[0].firstChild.nodeValue;
												var Description = xml.getElementsByTagName("Description")[0].firstChild.nodeValue;
												var PicUrl = xml.getElementsByTagName("PicUrl")[0].firstChild.nodeValue;
												var Url = xml.getElementsByTagName("Url")[0].firstChild.nodeValue;
												if(Url.indexOf('http://') == -1 && Url.indexOf('https://') == -1) {
													Url = '../app/' + Url;
												}
												$('#svtitle').html(Title);
												$('#svinfo').html(Description);
												$('#svpic').attr('src', PicUrl);
												$('#svurlbox').show().find('a#svurl').attr('href', Url);
												var titleObj = xml.getElementsByTagName("Title");
												if(titleObj.length>1){
													var svinfolist = imghtml = '';
													var UrlObj = xml.getElementsByTagName("Url");
													var PicUrlObj = xml.getElementsByTagName("PicUrl");
													for(var ti=1;ti<titleObj.length;ti++){
														imghtml = PicUrlObj[ti].firstChild.nodeValue ? '<img align="right" src="'+PicUrlObj[ti].firstChild.nodeValue+'">' : '';
														svinfolist += '<p class="clearfix" onclick="popensvurl(\''+UrlObj[ti].firstChild.nodeValue+'\')">'+titleObj[ti].firstChild.nodeValue+imghtml+'</p>';
													}
													$('div.mediaFooterbox', $('#demoSendBox')).hide();
													$('#svinfolist').show().html(svinfolist);
												}
											}
										}
									}
									$('#receive').text(s);
								}
							});
						}else{
							$('#receive').text('模拟取消关注成功');
						}
					})
				}
			});
		}

		function nl2br(str, is_xhtml) {
			var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
			return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
		}
		</script>
	<?php
	}
?>
<?php template('common/footer'); ?>