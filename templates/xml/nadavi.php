<?xml version="1.0" encoding="utf-8"?>
<yml_catalog date="{date('Y-m-d H:i')}">
	<shop>
		<name>InterLine</name>
		<url>http://{$dwoo.server.HTTP_HOST}/</url>
		<currencies>
			<currency id="UAH" rate="1"/>
			<currency id="USD" rate="{$course}"/>
		</currencies>
		<catalog>
			{foreach $catalog one}
			<category id="{$one.id}" {if $one.tree_id!=0}parentId="{$one.tree_id}"{/if}>{$one.name}</category>
			{/foreach}
		</catalog>
		<items>
			{foreach $products one}
			<item id="5">
			  <name>{$one.name}</name>
			  <url>http://{$dwoo.server.HTTP_HOST}/products/{$one.href}/</url>
			  <price>{$one.price}</price>
			  <categoryId>{$one.catalog.id}</categoryId>
			  <image>http://{$dwoo.server.HTTP_HOST}{$one.image.filename}</image>
			  <vendor>InterLine</vendor>
			  <description><![CDATA[{strip_tags($one.description_addition)}]]></description>
			</item>
			{/foreach}
		</items>
	</shop>
</yml_catalog>