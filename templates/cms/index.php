<script type="text/javascript" src="/js/cms/jquery-ui.js"></script>
<link type="text/css" href="/js/cms/css/ui-lightness/jquery-ui-1.8.14.custom.css" rel="stylesheet" />	

<style>
	.print-select {
		position: absolute;
		z-index: 15;
		left:0px;
		top:40px;
		max-width:100px;
		display: none;
	}
	
	.promoColor {
		position: absolute;
		display:none;
		z-index: 16;
		left:1px;
		top: 42px;
		max-width:100px;
	}
	
	.promo select{
		min-width:100px;
	}
	
	.promoColor select{
		min-width:100px;
	}

	.drop{		
		/*padding:5px;
		background-color:#FFFFFF;*/
		padding:10px;
		margin-top:20px;
		min-height:16px;
		/*background: #FFFFFF url(/i/ruler.png) 10px 5px no-repeat;*/
		background-color:#FFFFFF;
	}

	.drop.last{
		padding-top:0px;
	}

	.drop.last li{
		margin-top:0px;
	}

	ul{
		width:990px;
		overflow:hidden;
		margin:0;
		padding:0;
	}

	.drag-image{
		position:absolute!important;
		top:0px;
		left:0px;
		z-index:0;
	}

	ul li{
		list-style-type:none;
		background-color:#F1F1F1;
		width:180px;
		height:180px;
		float:left;
		margin:10px;
		position:relative;
		overflow:hidden;
	}

	.clear{
		clear:both;
	}
	.add-block, .remove-row{
		margin-top:5px;

	}
	.one-row{
		width:990px;
		position:relative;
	}

	li .color
	{
		float:left;
		width:10px;
		height:10px;
		border:1px solid #000000;
		margin-right:5px;
		margin-top:3px;
	}

	li .descr .pad{
		margin:5px 5px 5px 5px;
	}

	li .descr{
		left:0px;
		bottom:-5px;
		position:absolute;
		z-index:10;
		color:#FFFFFF;
		opacity: .8;
		width:100%;
		height:43px;
		display:none;
	}

	li .descr .text1{
		text-align:left;
		font-size: 17px;
		font-weight: bold;
		color:#FFFFFF;
		opacity: 1;
	}
	/*.href span{
		margin-left:3px;
	}*/
	li .descr .text1 input, li .descr .text2 input, .href input{
		font-size: 17px;
		font-weight: bold;
		color:#FFFFFF;
		background:none;
		border:none;
		outline: none;
		padding:0;
		margin:0;
		width:100%;
	}
	li .descr .text2 input{
		font-size: 10px;
	}
	li .descr .text2
	{
		text-align:left;
		color:#FFFFFF;
		opacity: 1;
	}

	li .delete-addimage{
		top:42px;
		right:0px;
		position:absolute;
		z-index:10;
		font-size:10px;
		color:#AAAAAA;
		background-color: #000000;
		opacity: .8;
		padding:3px;
		visibility: hidden;
	}
	
	li .delete-bg{
		top:22px;
		right:0px;
		position:absolute;
		z-index:10;
		font-size:10px;
		color:#AAAAAA;
		background-color: #000000;
		opacity: .8;
		padding:3px;
		visibility: hidden;
	}
	li .sizes{
		top:22px;
		left:2px;
		position:absolute;
		font-size:10px;
		color:#AAAAAA;
		display:none;
		z-index:10;
		background-color: #000000;
		opacity: .8;
		padding:3px;
	}
	li .href{
		top:0px;
		left:2px;
		position:absolute;
		color:#FFFFFF;
		z-index:10;
		width:100%;
		padding:3px;
		background-color:#000000;
		opacity: .8;
		display:none;
	}
	li .href input{
		font-size:10px;
		font-weight: normal;
		opacity: 1;
	}
	li .nav{
		z-index:10;
		display:none;
		bottom:5px;
		position:absolute;
		right:10px;
	}

	.add-objects img{
		z-index:900;
	}

	.over-image{
		width:100%;
		height:100%;
		background-repeat: no-repeat;
		z-index:10!important;
		position:absolute!important;
	}

	.one-row .resizerow
	{
		position:absolute;
		right:-22px;
		top:-15px;
	}
</style>
<script>
	function initDragAndResize(row)
	{
		row.find("ul.drop").sortable({
			connectWith: "ul",
			handle: ".sort-handler"
		});

		row.find("ul.drop li").resizable({
			grid: 5,
			maxWidth: 970,
			minWidth: 180,
			minHeight: 90,
			resize: function(event, ui) {
				$(this).find(".sizes").html($(this).width()+"x"+$(this).height());
			}
		});
	}
	

	function funcAddRow(e){
		e.preventDefault();

			$(".rows").append($("#blank-row").html());
			$(".one-row:last .add-block").click(funcAddBlock);
		
		$(".one-row:last .resizerow").click(function(e){
			e.preventDefault();
		})

		$(".one-row:last .remove-row").click(funcDeleteRow);
	}

	function funcInitColorClick(e)
	{
		e.preventDefault();

		$(this).closest("li").find(".delete-bg").css("visibility", "visible");

		var tmp=$(this).closest("li").find(".descr");
		
		if(tmp.css("background-color")==$(this).css("background-color"))
			tmp.css("background-color", "transparent").hide();
		else
		{
			tmp.data("color", $(this).data("color"));
			tmp.show().css("background-color", $(this).css("background-color"));
		}
	}

	function funcAddBlock(e){
		e.preventDefault();

		$(this).closest(".one-row").find("ul.drop").append($("#blank-block").html());

		var tmp=$(this).closest(".one-row").find("li:last");

		tmp.find(".nav .basket").click(funcDeleteBlock);

		initDroppable(tmp);
		initLiMouseEnter(tmp);
		
		tmp.find("a.color").each(function(){
			$(this).data("color", $(this).attr("rel"));
			$(this).css("background-color", "#"+$(this).attr("rel"));
		})
		
		tmp.find("a.color").click(funcInitColorClick);

		initDragAndResize($(this).closest(".one-row"));
	}	

	function funcDeleteBlock(e){
		e.preventDefault();
		if(window.confirm("удалить блок?"))
			$(this).closest("li").remove();
	}
	
	function funcDeleteRow(e){
		e.preventDefault();
		if(window.confirm("удалить ряд?"))
			$(this).closest(".one-row").remove();
	}

	function funcClearBlock(e){
		e.preventDefault();

		if(window.confirm("очистить блок?"))
		{
			$(this).css("visibility", "hidden");
			$(this).closest("li").find(".drag-image").remove();
			$(this).closest("li").find(".descr").css("background-color", "transparent").hide();
		}
	}
	
	function funcClearAddImage(e){
		e.preventDefault();

		if(window.confirm("очистить дополнительную картинку?")){
			$(this).closest("li").find(".addimage").remove();
			$(this).css("visibility", "hidden");
		}
	}
	
	$(function(){
		
		initDragAndResize($(".one-row"));
		
		
        $(".date-time-date").mask("99.99.9999");
        $(".date-time-time").mask("99:99");

		$(".save-all").click(function(e){
			e.preventDefault();
			
			$rows=[];

			$(".rows .one-row ul.drop").each(function(i){
				$row=[];

				$(this).find("li").each(function(){
					$li=$(this);

					$sizes=$li.find(".sizes").text().split("x");
					$descr=$li.find(".descr");
	
					$descr_value=null;
	
					if($descr.is(":visible"))
					{
						$descr_value={
							color: $descr.data("color"),
							text1: $li.find(".text1 input").val(),
							text2: $li.find(".text2 input").val()
						}
					}
					
					$addimage=null;
					$mainimage=null;
	
					$li.find(".drag-image").each(function(){
						if($(this).css("z-index")==5)
						{
							$addimage={
								src: $(this).attr("src"),
								top: parseInt($(this).css("top")),
								left: parseInt($(this).css("left"))
							}
						}
						else
						{
							$mainimage={
								src: $(this).attr("src"),
								top: parseInt($(this).css("top")),
								left: parseInt($(this).css("left"))
							}
						}
					});

					$block={
						sizes: $sizes,
						descr: $descr_value,
						mainimage: $mainimage,
						addimage: $addimage,
						href: $li.find(".href input").val(),
						print: $li.find(".print-select select").val()
					};

					$row.push($block);
				});

				if($(this).find(".info-row").length>0)
					$row=$(this).find(".info-row").attr("type");

				$rows.push($row);
			})

			//if($rows.length>0)
			//{
				$params = {	"startDate" : $("#dateStart").val(), 
						"endDate" : $("#dateEnd").val(),
						"startTime" : $("#timeStart").val(), 
						"endTime" : $("#timeEnd").val(),
						"type" : 1,
						"id" : $("#schedule_id").val(),
						"name" : $("#name").val()
				};
				
				showLoading();
				$.post("/cms/index-save", { data: $rows, params: $params }, function(data)
				{
					hideLoading();
					if(data.ok)
						showNotice("Сохранено");
					else
						showNotice("Ошибка: "+data.error, "bad");
					
				}, "json")
			//}
			//else
			//	showNotice("Нечего сохранять", "bad");
		});

		$(".add-row").click(funcAddRow);
		$(".add-block").click(funcAddBlock);
		$(".index-page .nav .basket").click(funcDeleteBlock)
		$(".remove-row").click(funcDeleteRow);

		initLiMouseEnter($(".drop li"));
		$(".delete-bg").live("click", funcClearBlock);
		$(".delete-addimage").live("click", funcClearAddImage);

		$("a.color").each(function(){
			$(this).data("color", $(this).attr("rel"));

			if($(this).closest("li").find(".descr").css("background-color")!="transparent")
				$(this).closest("li").find(".descr").data("color", $(this).attr("rel"));

			$(this).css("background-color", "#"+$(this).attr("rel"));
		});
		
		$('.descr').each(function() {
			$(this).data('color', $(this).attr('rel'));
		});
		
		$('.descr').mouseenter(function() {
			console.log($(this).data('color'));
		})
		
		$("a.color").click(funcInitColorClick);

		$(".drop .drag-image").draggable({
			zIndex:0,
			snap: ".drop li",
			snapMode: "inner",
			snapTolerance: 10
		});

		initDroppable($(".drop li"));

		$(".add-objects img").draggable({
			revert: true,
			snap: ".drop li",
			snapMode: "inner",
			snapTolerance: 10
		})

		$(".add-objects div").disableSelection();
		
		$(".info-row").each(function(){
			$(this).text($("#row-type option[value='"+$(this).text()+"']").text());
		})

		$(".rows").sortable({
			connectWith: ".one-row",
			handle: ".sort-handler"
		});

		$(".resizerow").click(function(e){
			e.preventDefault();
		})
	})

	function initLiMouseEnter(selector)
	{
		selector.mouseenter(function(){
			$(this).find(".nav, .delete-addimage, .delete-bg, .sizes, .href, .promoColor, .print-select").show();
		}).mouseleave(function(){
			$(this).find(".nav, .delete-addimage, .delete-bg, .sizes, .href, .promoColor, .print-select").hide();
		})
	}


	function initDroppable(selector)
	{
		selector.droppable({
			drop:function(event, ui){
				if(ui.helper.is(".drag-from-gallery"))
				{
					var li=$(this).closest("li");
					
					if(li.find(".mainimage").length>0)
						li.find(".mainimage").remove();

					li.append('<img src="'+ui.helper.find("img").attr("title")+'" class="drag-image mainimage">');
					li.find(".delete-bg").css("visibility", "visible");
					li.find(".delete-addimage").css("visibility", "visible");

					li.find(".drag-image").draggable({
						zIndex:0,
						snap: ".drop li",
						snapMode: "inner",
						snapTolerance: 10
					});
				}
				
				if(ui.helper.is("img.addimageone"))
				{
					var dpos=ui.draggable.offset();
					var tpos=$(this).offset();

					var li=$(this).closest("li");

					if(li.find(".addimage").length>0)
						li.find(".addimage").remove();

					li.find(".delete-bg").css("visibility", "visible");
					li.find(".delete-addimage").css("visibility", "visible");

					li.append('<img src="'+ui.draggable.attr("src")+'" class="drag-image addimage" style="z-index:5;top:'+(dpos.top-tpos.top)+'px;left:'+(dpos.left-tpos.left)+'px">');

					li.find(".drag-image").draggable({
						zIndex:0,
						snap: ".drop li",
						snapMode: "inner",
						snapTolerance: 10
					});
				}
			}
		});		
	}
</script>
	
<div class="black_hor_panel">
	<div class="name">Настройка главной страницы</div>
	
	<div class="button_blue" style="position:absolute;right:370px;top:13px">
		<div class="bg">
			<div class="brd_l"></div>
			<a href="" class="add-row"><div class="brd"><div style="background:none; padding-left: 0px;">добавить ряд</div></div></a>
		</div>
		<div class="brd_r"><div></div></div>
	</div>
	<div class="button_green">
		<div class="bg">
			<div class="brd_l"></div>
			<a href="" class="save-all"><div class="brd"><div style="background:none; padding-left: 0px;">сохранить</div></div></a>
		</div>
		<div class="brd_r"><div></div></div>
	</div>
	<br clear="all">
</div>

<div id="blank-block" style="display:none">
	<li>
		<div class="sizes">180x180</div>

		<div class="href"><span><input type="text" value="http://..." /></span></div>
		<div class="delete-bg"><a href="" class="grey">очистить блок</a></div>
		<div class="delete-addimage"><a href="" class="grey">очистить доп.картинку</a></div>
		<div class="print-select"><select><option value="-1">--флаер на печать--</option><option value="-1"></option>{foreach $print p}<option value="{$p.id}">{$p.name}</option>{/foreach}</select></div>

		<div class="nav">
			<a href="" class="sort-handler resize fl"></a>
			<a href="" class="basket3 basket fl"></a>
		</div>
	</li>
</div>

<div id="blank-row" style="display:none">
	<div class="one-row">
		<a href="" class="sort-handler resize fl resizerow"></a>
		<ul class="drop"></ul>
		<div class="clear"></div>
		<div class="button_blue add-block"><div class="bg"><div class="brd_l"></div><a href=""><div class="brd"><div>добавить блок</div></div></a></div><div class="brd_r"><div></div></div></div>
		<div class="button_red remove-row"><div class="bg"><div class="brd_l"></div><a href=""><div class="brd"><div>удалить ряд</div></div></a></div><div class="brd_r"><div></div></div></div>
		<div class="clear"></div>
	</div>
</div>

<div id="blank-sys-row" style="display:none">
	<div class="one-row">
		<ul class="drop info"></ul>
		<div class="clear"></div>
		<div class="button_red remove-row"><div class="bg"><div class="brd_l"></div><a href=""><div class="brd"><div>удалить ряд</div></div></a></div><div class="brd_r"><div></div></div></div>
		<div class="clear"></div>
	</div>
</div>


<input id="schedule_id" type="hidden" value="{if $info.id}{$info.id}{else}new{/if}">
<div class="groups">
	<div class="group">
		<br>
		<div class="fl title">Страница</div>
		<br clear="all">
		<br />
		<div class="fl title">Название</div>
		<br clear="all">
		<div class="field"><input id="name" type="text" class="standart" value="{if $info.name}{$info.name}{/if}"></div>
		
		<div class="fl title">Дата начала</div>
		<br clear="all">
		<div class="field"><input id="dateStart" type="text" class="standart date-time-date" value="{if $info.idate != 'дата не указана'}{$info.idate.date}{/if}" style="width:100px;min-width:inherit" maxlength="10">&nbsp;<input id="timeStart" type="text" class="standart date-time-time" value="{if $info.idate != 'дата не указана'}{$info.idate.time}{/if}" maxlength="5" style="width:50px;min-width:inherit"></div>
		
		<div class="fl title">Дата завершения</div>
		<br clear="all">
		<div class="field"><input id="dateEnd" type="text" class="standart date-time-date" value="{if $info.idateTo != 'дата не указана'}{$info.idateTo.date}{/if}" style="width:100px;min-width:inherit" maxlength="10">&nbsp;<input id="timeEnd" type="text" class="standart date-time-time" value="{if $info.idateTo != 'дата не указана'}{$info.idateTo.time}{/if}"  maxlength="5" style="width:50px;min-width:inherit">
	</div>
</div>

<div style="background-color:#CCCCCC;min-height:1000px;min-width:1140px;" align="center" class="index-page groups">



	<br>

	<div class="add-objects"{* style="overflow:auto; width:100%; height:150px;white-space:nowrap"*}>
		{foreach $images onee}
			<img src="{$onee.image.filename}" width="{$onee.image.size.width}" height="{$onee.image.size.height}" class="addimageone">
		{/foreach}
	</div>

	<br>

	<div style="border-bottom:1px dashed #AAAAAA"></div>

	<br>

	<div class="rows">
		{foreach $array row}
		<div class="one-row">
			<a href="" class="sort-handler resize fl resizerow"></a>
			<ul class="drop">
			{if !is_array($row)}
				<div style="text-align:left" class="info-row" type="{$row}">{$row}</div>
			{else}
				{foreach $row one}
					<li{if $one.width && $one.height} style="width:{$one.width}px;height:{$one.height}px"{/if}>
						
						<div class="href"><span><input type="text" value="{if $one.href}{$one.href}{else}http://...{/if}" /></span></div>
						<div class="sizes">{$one.width}x{$one.height}</div>
						<div class="delete-bg"{if $one.color!="" || $one.addimage.filename || $one.image.filename} style="visibility:visible;display:none"{/if}><a href="" class="grey">очистить блок</a></div>
						<div class="delete-addimage"{if $one.addimage.filename} style="visibility:visible;display:none"{/if}><a href="" class="grey">очистить доп.картинку</a></div>
						<div class="print-select"><select><option value="-1">--флаер на печать--</option><option value="-1"></option>{foreach $print p}<option value="{$p.id}"{if $p.id==$one.print_id.id} selected{/if}>{$p.name}</option>{/foreach}</select></div>
						<div class="nav">

							<a href="" class="sort-handler resize fl"></a>
							<a href="" class="basket3 basket fl"></a>
						</div>
						{if $one.addimage.filename}<img src="{$one.addimage.filename}" class="drag-image addimage" style="z-index:5;top:{$one.addimage_top}px;left:{$one.addimage_left}px;">{/if}
						{if $one.image.filename}<img src="{$one.image.filename}" class="drag-image mainimage" style="top:{$one.image_top}px;left:{$one.image_left}px;">{/if}
					</li>
				{/foreach}
			{/if}
			</ul>
			<div class="clear"></div>
			{if is_array($row)}<div class="button_blue add-block"><div class="bg"><div class="brd_l"></div><a href=""><div class="brd"><div>добавить блок</div></div></a></div><div class="brd_r"><div></div></div></div>{/if}
			<div class="button_red remove-row"><div class="bg"><div class="brd_l"></div><a href=""><div class="brd"><div>удалить ряд</div></div></a></div><div class="brd_r"><div></div></div></div>
			<div class="clear"></div>
		</div>			
		{/foreach}
	</div>
	<div class="clear"></div>
	<br>
</div>