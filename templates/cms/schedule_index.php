<div class="content-footer">
	<div class="pane">
		<div id="footer-buttons" class="none">
			<div class="button_blue fl" id="btn-dublicate">
				<div class="bg">
					<div class="brd_l"></div>
					<div class="brd"><div>Дублировать</div></div>	
				</div>
				<div class="brd_r"><div></div></div>
			</div>
			<div class="button_red fl" id="btn-delete">
				<div class="bg">
					<div class="brd_l"></div>
					<div class="brd"><div>Удалить</div></div>	
				</div>
				<div class="brd_r"><div></div></div>
			</div>
		</div>
	</div>
</div>
<div class="black_hor_panel">
	<div class="name">Расписание</div>
	<div class="button_green">
		<div class="bg">
			<div class="brd_l"></div>
			<a href="" class="schedule_add"><div class="brd"><div>добавить</div></div></a>
			
		</div>
		<div class="brd_r"><div></div></div>
	</div>
	<br clear="all">
</div>

<div class="content">
	{if sizeof($data1)>0}
		<table class="tab table-main">
		<thead>
			<tr class="nohover">{strip}
				<th width="1%" class="nosort nohover"></th>
				<th width="25%"><div class="dots_container"><div class="dots_name">Название</div></div></th>
				<th width="25%"><div class="dots_container"><div class="dots_name">Дата начала</div></div></th>
				<th width="25%"><div class="dots_container"><div class="dots_name">Дата завершения</div></div></th>
				<th width="25%"><div class="dots_container"><div class="dots_name">Активность</div></div></th>
				<th width="1%" class="nosort nohover"></th>
			{/strip}</tr>
		</thead>
		<tbody>
			{foreach $data1 k one}
				<tr id="{$one.id}">{strip}
					<td><input type="hidden" value="{$one.id}">&nbsp;</td>
					<td>{$one.name}</td>
					<td>{if $one.idate != 'дата не указана'}{$one.idate.datenamer}, {$one.idate.time}{/if}</td>
					<td>{if $one.idateTo != 'дата не указана'}{$one.idateTo.datenamer} {$one.idateTo.time}{/if}</td>
					<td><div class="{if $one.active==1}bulb_on{else}bulb_off{/if} __yesno_sw row_noselect" rel="active"></div></td>
					<td>&nbsp;</td>
				{/strip}</tr>
			{/foreach}
		</tbody>
		</table>
	{/if}
</div>

<script>
{literal}
	$(function(){
		$.table=$(".table-main");
		$tableactions=true;
	
		$(".table-main th:not(.nohover)").bind("mouseenter", thOver).bind("mouseleave", thOut);
		$(".table-main tr:not(.nohover)").bind("mouseenter", rowOver).bind("mouseleave", rowOut).bind("dblclick", rowIndexdblClick).bind('click', rowClick);
		$(".table-main td").bind("mouseenter", thOver).bind("mouseleave", thOut);
		
		$("a.schedule_add").bind("click", function(e) {
			e.preventDefault();
			showLoading();
			$.post("/cms/index", { id: "new" }, function(data){
				hideLoading();
				if(data.ok)
				{
					$("#ajaxcontent").html(data.ok);
				}
				else
					showNotice("Ошибка: "+data.error, "bad");
			}, "json");
		});
			
		$(".__yesno_sw").click(function(e){
				e.preventDefault();
				$yesnochange=$(this);
				$yesnochange.closest("tr").click();

				showLoading();

				$.post(
					"/admin/yesno/change/"+$(this).attr("rel")+"/51/"+$(this).closest("tr").attr("id"),
					function(data)
					{
						hideLoading();

						if(data.ok)
						{
							if(data.ok==1)
								$yesnochange.removeClass("bulb_off").addClass("bulb_on");
							else
								$yesnochange.removeClass("bulb_on").addClass("bulb_off");

							$yesnochange.removeClass("bulb_hover");
							showNotice("Значение изменено", "changed");
						}
						else
							showNotice("Ошибка: "+data.error, "bad");
					},
					"json"
				);
			}).mouseenter(function(){
				$(this).addClass("bulb_hover");
			}).mouseleave(function(){
				$(this).removeClass("bulb_hover");
			});
		
		$("#btn-dublicate").bind('click', function(e) {
			e.preventDefault();
			showLoading();
			$.post('/cms/dublicate', {id: getSelectedIds()}, function(data){
				hideLoading();
				if(data.ok)
					$("#ajaxcontent").html(data.ok);
				else
					showNotice("Ошибка: "+data.error, "bad");
			}, "json");
		});
		
		$("#btn-delete").bind('click', function(e) {
			e.preventDefault();
			if (confirm("Действительно удалить?")) {
				showLoading();
				$.post('/cms/delete', {id: getSelectedIds()}, function(data){
					hideLoading();
					if(data.ok==1)
					{
						$.post('/cms/schedule', function(data){
							hideLoading();
							if(data.ok)
							{
								$("#ajaxcontent").html(data.ok);
								showNotice("Удалено", "deleted");
							}
							else
								showNotice("Ошибка: "+data.error, "bad");
						}, "json");
					}
					else
						showNotice("Ошибка: "+data.error, "bad");
				}, "json");
			};
		});
		
	});
	
	function rowIndexdblClick(e)
	{
		e.preventDefault();
		var id = $(this).find(":hidden").val();
			//if(window.confirm("Перейти к этому распорядку?"))
			//{
				showLoading();
				$.post("/cms/index", { id: id }, function(data){
					hideLoading();
					if(data.ok)
					{
						$("#ajaxcontent").html(data.ok);
						//jsonload("/user/import/");
					}
					else
						showNotice("Ошибка: "+data.error, "bad");
				}, "json");
			//}
	}
	
	function rowClick() {
		if ($(this).hasClass("r_selected")) {
			$(this).removeClass("r_selected");
		} else {
			$(this).addClass("r_selected");
		}
		if ($(".r_selected").size() > 0) {
			$("#footer-buttons").show();
		} else {
			$("#footer-buttons").hide();
		}
	}
	
	function getSelectedIds()
	{
		$tmp=Array();
		$(".table-main tr.r_selected").each(function(){
			$tmp.push($(this).attr("id"));
		});
	
		return $tmp;
	}
{/literal}
</script>