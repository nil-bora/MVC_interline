<div class="black_hor_panel">
	<div class="name">Импорт данных</div>
	<div id="import-queue"></div>
	<div style="position:fixed;top:55px;right:15px">
	
	<input id="import-upload" name="import-upload" type="file" href="{siteUrl(admin/tables/import/$table.id)}" />
	{*<a href="{siteUrl(admin/tables/image_load)}" target="_blank">Загрузить изображение</a>*}
		
	</div>
	<script>
		$(function(){
			$update_obj={
				'uploader'       : '{assetsUrl(modules/admin/upload/uploadify.swf)}',
				'script'         : '{siteUrl(__upload, true)}',
				'cancelImg'      : '{assetsUrl(modules/admin/upload/cancel.png)}',
				'folder'         : '/import/',
				'buttonImg'      : '{assetsUrl(modules/admin/i/update-button.png)}',
				'wmode'          : 'transparent',
				'scriptAccess'	 : 'always',
				'width'          : 110,
				'height'         : 27,
				'queueID'        : 'import-queue',
				'auto'           : true,
				'multi'          : false,
				'fileExt'		 : '*.xls;*.xlsx;*.zip;*.xml;*.csv;*.dbf;*.DBF',
				'fileDesc'		 : 'File types',
				'onError' : function (a, b, c, d) {
					if (d.status == 404)
						showNotice('Upload error: Could not find upload script.', "bad");
					else if (d.type === "HTTP")
						showNotice('Upload error: Error '+d.type+": "+d.status, "bad");
					else if (d.type ==="File Size")
						showNotice('Upload error: '+c.name+' '+d.type+' Limit: '+Math.round(d.sizeLimit/1024)+'KB', "bad");
					else
						showNotice('Upload error: Error '+d.type+": "+d.text, "bad");
				},
				'onSelect': function(){
					$("#formflash").fadeOut("fast", function(){
						$(this).remove();
					});
				},
				'onProgress'	 : function(){
				},
	      		'onComplete'	 : function(event, queueID, fileObj, response, data){
	      			if(response)
	      			{
						//jsonload($("#update-upload").attr("href"), { filename: response })
						
						console.log(response);

						if (response.substr(0, 5) == "Error")
							showNotice(response, "bad");
					}
					else
						showNotice("Upload error", "bad");
	
				},
				'onAllComplete'	 : function(e, data){
					
	           	}				
			};

			$("#update-upload").uploadify($update_obj);

			$import_obj=$update_obj;
			$import_obj.buttonImg='{assetsUrl(modules/admin/i/import-button.png)}';
			$import_obj.width=148;
			$import_obj.onComplete=function(event, queueID, fileObj, response, data)
			{
      			if(response)
      			{
					jsonload('/user/import/load', { filename: response })

					if (response.substr(0, 5) == "Error")
						showNotice(response, "bad");
				}
				else
					showNotice("Upload error", "bad");

			}


			$("#import-upload").uploadify($import_obj);
		})
	</script>

	<br clear="all">
</div>

<div class="content">
	{if sizeof($data)>0}
		<table class="tab" id="table-main">
		<thead>
			<tr class="nohover">{strip}
				<th width="1%" class="nosort nohover"></th>
				<th><div class="dots_container"><div class="dots_name">Файл</div></div></th>
				<th><div class="dots_container"><div class="dots_name">Размер</div></div></th>
				<th><div class="dots_container"><div class="dots_name">Дата создания</div></div></th>
				<th width="1%" class="nosort nohover"></th>
			{/strip}</tr>
		</thead>
		<tbody>
			{foreach $data k one}
				<tr id="{$k}" rel="{substr($one.file, -3)}">{strip}
					<td>&nbsp;</td>
					<td>{substr($one.file, 1)}</td>
					<td>{$one.size}</td>
					<td>{$one.date.ago}</td>
					<td>&nbsp;</td>
				{/strip}</tr>
			{/foreach}
		</tbody>
		</table>
	{else}
	<div class="groups">
		<div class="group">
			<div style="padding:20px 0px 20px 0px">Нет файлов для импорта</div>
		</div>
	</div>
	{/if}
</div>

<script>
	$(function(){
		$.table=$("#table-main");
		$tableactions=true;
	
		$("#table-main th:not(.nohover)").bind("mouseenter", thOver).bind("mouseleave", thOut);
		$("#table-main tr:not(.nohover)").bind("mouseenter", rowOver).bind("mouseleave", rowOut).bind("click", rowImportClick);
		$("#table-main td").bind("mouseenter", thOver).bind("mouseleave", thOut);
	})
	
	function rowImportClick(e)
	{
		e.preventDefault();
	
		var file=$(this).find("td:eq(1)").html();

		if($(this).attr("rel")=="zip")
		{
			if(window.confirm("Распаковать архив?"))
			{
				showLoading();
				$.post("/user/import/zip/", { zip: file }, function(data){
					hideLoading();
					if(data.ok)
						jsonload("/user/import/");
					else
						showNotice("Ошибка: "+data.error, "bad");
				}, "json");
			}
		}
		else if($(this).attr("rel")=="dbf" || $(this).attr("rel")=="DBF")
		{
		
			if(window.confirm("Импортировать файл?"))
			{
				showLoading();
				$.post("/user/import/prepare/", { xml: file }, function(data){
					hideLoading();
					if(data.ok)
					{
						
						$("#ajaxcontent").html(data.ok);
						//jsonload("/user/import/");
					}
					else
						showNotice("Ошибка: Некорректный файл импорта", "bad");
						
				}, "json");
			}
		}
	}
</script>