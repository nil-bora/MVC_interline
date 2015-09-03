{if $mainmenu}
<div class="top-menu">
	<menu class="inner">
		{foreach $mainmenu one name='iter'}
	    <li class="top-menu__item {if $.foreach.iter.iteration>3}top-menu__item--right{/if}{if $one.href == str_replace('/','',$dwoo.server.REQUEST_URI)} top-menu__item--active{/if}">
	        <a href="{siteUrl($one.href)}" class="top-menu__link">{$one.menuname}</a>
	    </li>
	    {/foreach}
	    
		    <li class="top-menu__item top-menu__item--right js-count-header" {if !$cart}style="display:none"{/if}>
		        <a href="{siteUrl(cart/order)}" class="top-menu__link">Корзина(<span>{$cart.count}</span>)</a>
		    </li>
	   
	</menu>
</div>
{/if}
