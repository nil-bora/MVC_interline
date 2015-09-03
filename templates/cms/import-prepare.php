<div class="black_hor_panel">
	<div class="name">Импорт данных</div>
	<div class="button_blue" style="position:absolute;right:265px;top:13px">
		<div class="bg">
			<div class="brd_l"></div>
			<a href="" class="add-row make-import"><div class="brd"><div style="background:none; padding-left: 0px;">Импортировать</div></div></a>
		</div>
		<div class="brd_r"><div></div></div>
	</div>
	<br clear="all">
</div>

<div class="content">
		<input type="hidden" value="{$name}" id="xml" />
		<table class="tab" id="table-main">
		<thead>
			<tr class="nohover">{strip}
				<th width="1%" class="nosort nohover"></th>
				<th><div class="dots_container"><div class="dots_name">Данные</div></div></th>
				<th><div class="dots_container"><div class="dots_name">Количество</div></div></th>
				<th width="1%" class="nosort nohover"></th>
			{/strip}</tr>
		</thead>
		<tbody>
			<tr>{strip}
				<td>&nbsp;</td>
				<td>Товары</td>
				<td>{$products_count}</td>
				<td>&nbsp;</td>
			{/strip}</tr>
		</tbody>
		</table>
</div>

<script>
	$(function(){
		$.table=$("#table-main");
		$tableactions=true;
	
		$("#table-main th:not(.nohover)").bind("mouseenter", thOver).bind("mouseleave", thOut);
		$("#table-main tr:not(.nohover)").bind("mouseenter", rowOver).bind("mouseleave", rowOut).bind("click", rowImportClick);
		$("#table-main td").bind("mouseenter", thOver).bind("mouseleave", thOut);
		
		$('.make-import').click(function(e) {
			e.preventDefault();
				var file=$('#xml').val();
				showLoading();
				$.post("/user/import/make/", { xml: file }, function(data){
					hideLoading();
					if(data.ok)
					{
						$("#ajaxcontent").html(data.ok);
					}
					else
						$("#ajaxcontent").html("<div style='padding:20px 0px 20px 0px'>Импорт прошел успешно, <a target='_blank' class='blue' href='/'>перейдите к сайту</a> для подтверждения</div>");
				}, "json");
			});	
	})
</script>