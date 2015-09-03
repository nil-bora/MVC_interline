<?xml version="1.0" encoding="utf-8"?>
<price>
<firmName>InterLine</firmName>
<firmId></firmId>
<rate>{$course}</rate>
<categories>
{foreach $catalog one}
	<category>
		<id>{$one.id}</id>
{if $one.tree_id!=0}<parentId>{$one.tree_id}</parentId>{/if}
		<name>{$one.name}</name>
	</category>
{/foreach}
</categories>
<items>
	{foreach $products one}
	<item>
		<id>{$one.id}</id>
		<categoryId>{$one.catalog.id}</categoryId>
		<vendor>InterLine</vendor>
		<name>{$one.name}</name>
		<description><![CDATA[{strip_tags($one.description_addition)}]]></description>
		<url>http://{$dwoo.server.HTTP_HOST}/products/{$one.href}/</url>
		<image>http://{$dwoo.server.HTTP_HOST}{$one.image.filename}</image>
		<priceRUAH>{$one.priceUAH}</priceRUAH>
		<priceRUSD>{$one.price}</priceRUSD>
		<priceOUSD></priceOUSD>
		<stock>На складе</stock>
		<guarantee>{$one.warranty}</guarantee>
	</item>
	{/foreach}
</items>
</price>