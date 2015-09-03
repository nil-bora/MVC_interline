<?xml version="1.0" encoding="utf-8"?>
<price date="{date('Y-m-d H:i')}">
<name>InterLine</name>
<currency code="USD" rate="{$course}" />
<catalog>
	{foreach $catalog one}
	<category id="{$one.id}" {if $one.tree_id!=0}parentId="{$one.tree_id}"{/if}>{$one.name}</category>
	{/foreach}
</catalog>
<items>
	{foreach $products one}
	<item id="{$one.id}">
		<name>{$one.name}</name>
		<categoryId>{$one.catalog.id}</categoryId>
		<priceuah>{$one.priceUAH}</priceuah>
		<url>http://{$dwoo.server.HTTP_HOST}/products/{$one.href}/</url>
		<image>http://{$dwoo.server.HTTP_HOST}{$one.image.filename}</image>
		<vendor>InterLine</vendor>
		<description><![CDATA[{strip_tags($one.description_addition)}]]></description>
		<warranty>{$one.warranty}</warranty>
	</item>
	{/foreach}
</items>
</price>