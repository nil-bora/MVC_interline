<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE e-shop SYSTEM "http://market.meta.ua/market.dtd">
<e-shop name="InterLine">
	<currencies>
		<currency id="UAH" rate="1.00" />
		<currency id="USD" rate="{$course}" />
	</currencies>
	<categories>
		{foreach $catalog one}
	 	<category id="{$one.id}" parent="{$one.tree_id}">
	 		<![CDATA[{$one.name}]]>
	 	</category>
	 	{/foreach}
	</categories>
	<itemlist>
		{foreach $products one}
		<item id="5" category="4" priority="0">
            <link img="http://{$dwoo.server.HTTP_HOST}{$one.image.filename}" click="http://{$dwoo.server.HTTP_HOST}/products/{$one.href}/" />
            <type><![CDATA[{$one.catalog.name}]]></type>
            <vendor><![CDATA[InterLine]]></vendor>
            <name><![CDATA[{$one.name}]]></name>
            <price cid="USD">{$one.price}</price>
            <description><![CDATA[{strip_tags($one.description_addition)}]]></description>
         </item>
         {/foreach}
	</itemlist>                       
</e-shop>