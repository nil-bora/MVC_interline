{literal}
<style>

.button_blue, .button_yellow, .button_red, .button_violet {margin-right:10px;} 
.button_blue, .button_yellow, .button_red, .button_green, .button_save, .button_violet {font-size:11px; cursor: pointer;}
a.hash:hover,a.nounder:hover{text-decoration: none}
.bg {height:27px; text-transform:uppercase; color:#fff; float:left;}
.brd {background:url(/i/border.png) repeat-x; float:left; height:27px; padding:0px 10px 0px 10px;}
.brd div {padding-top:7px; padding-left:25px;}
.brd_r {height:27px; float:left;}
.brd_r div {background:url(/i/border_right.png) no-repeat; width:4px; height:27px;}
.brd_l {background:url(/i/border_left.png) no-repeat; width:3px; height:27px; float:left;}


.button_blue .bg {background:url(/i/bg-blue.gif) 1px 1px repeat-x;}
.button_blue .brd_r {background:url(/i/bg-blue.gif) 0px 1px no-repeat;}
.button_blue .brd div {background:url(/i/replace.png) 0px 7px no-repeat;}

.button_red .bg {background:url(/i/bg-red.gif) 1px 1px repeat-x;}
.button_red .brd_r {background:url(/i/bg-red.gif) 0px 1px no-repeat;}
.button_red .brd div {background:url(/i/delete.png) 0px 8px no-repeat;}
.button_red .brd div {background: url(/i/delete.png) 0px 8px no-repeat;}

.fl {float: left;}
.textinput {height: 18px; margin-bottom: 10px;}
</style>
{/literal}

<div id="import-confirm" style="position:fixed; width:100%; height:60px; background:#d40000; bottom:0px; z-index:1100000;border-top:5px solid #950000" align="center">
	<div style="width:970px; text-align:left; padding:7px 0px 0px 0px;">
		<div style="padding-bottom:5px; color:#FFF;">Вы просматриваете новую версию сайта после импорта</div>
		<div class="button_blue fl" id="import-confirm-ok">
			<div class="bg">
				<div class="brd_l"></div>
				<div class="brd"><div>Подтвердить</div></div>	
			</div>
			<div class="brd_r"><div></div></div>
		</div>
		<div class="button_red fl" id="import-confirm-cancel">
			<div class="bg">
				<div class="brd_l"></div>
				<div class="brd"><div>Отменить</div></div>	
			</div>
			<div class="brd_r"><div></div></div>
		</div>
	</div>	
</div>

<script type="text/javascript">
	$('#import-confirm-ok').click(function(e) {
		e.preventDefault();

			$.post("/user/import/approve/", { operation: 'approve' }, function(data){

				if(data.ok)
				{
					$("#import-confirm").remove();
				}
				else
					alert('Ошибка при подтверждении импорта: '+data);
			}, "json");
		});
		
	$('#import-confirm-cancel').click(function(e) {
		e.preventDefault();

			$.post("/user/import/approve/", { operation: 'cancel' }, function(data){

				if(data.ok)
				{
					document.location.reload(true);
				}
				else
					alert('Ошибка при отмене импорта: '+data);
			}, "json");
		});
</script>