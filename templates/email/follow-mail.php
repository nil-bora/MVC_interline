{if $products}
	<h2>Упала цена на следующие продукты:</h2>
	<table border="1">
	<tr>
		<td>Название</td>
		<td>Старая цена</td>
		<td>Новая цена</td>
	</tr>
	{foreach $products one}
	<tr>
		<td><a href="http://{$.dwoo.server.SERVER_NAME}/product/{$one.product.href}/">{$one.product.name}</a></td>
		<td>{$one.old_price}</td>
		<td>{$one.new_price}</td>
	</tr>
	{/foreach}
	</table>
{/if}