<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="{date('Y-m-d H:i:s')}">
<shop>
	<name>InterLine</name>
	<company>InterLine</company>
	<url>http://interline.ua/</url>
	<currencies>
		<currency id="UAH" rate="1"/>
		<currency id="USD" rate="{$course}"/>
	</currencies>
	<categories>
		{foreach $catalog one}
			<category id="{$one.id}" {if $one.tree_id!=0}parentId="{$one.tree_id}"{/if}>{$one.name}</category>
			{/foreach}
	</categories>	
	<offers>
		{foreach $products one}
		<offer id="{$one.id}" type="vendor.model" available="true">
		  <url>http://{$dwoo.server.HTTP_HOST}/products/{$one.href}/</url>
		  <price>{$one.priceUAH}</price>
		  <currencyId>UAH</currencyId>
		  <categoryId>{$one.catalog.id}</categoryId>
		  <picture>http://{$dwoo.server.HTTP_HOST}{$one.image.filename}</picture>
		  <vendor>InterLine</vendor>
		  <model>{$one.catalog.name} InterLine {$one.name}</model>
		  <description><![CDATA[{strip_tags($one.description_addition)}]]></description>
		</offer>
		{/foreach}
	</offers>
</shop>
</yml_catalog>