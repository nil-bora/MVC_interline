<div class="service">
    <div class="inner" style="min-height:500px;" data-js="serch_instruction">
		<form method="POST">
			<select name="catalog_search">
				<option>Выбрать тип техники</option>
				{foreach $catalog one}
				<option value="{$one.id}">{if $one._LEVEL==2}&nbsp;&nbsp;&nbsp;&nbsp;{/if}{$one.name}</option>
				{/foreach}
			</select>
			<select name="product_search">
				
			</select>
			<input type="submit" value="Найти" />
		</form>
		{if $post}
			{if $documents && $product}
				<br>
				<div class="product">
					<h3>{$product.name}</h3>
					<ul class="documentation">
					    {foreach $documents one}
					    <li>
					       {$one.name}<a href="{$one.file.filename}">скачать</a>
					    </li>
					    {/foreach}
					</ul>
				</div>
			{else}
				Ни чего не найдено
			{/if}
        {/if}
    </div>
</div>
