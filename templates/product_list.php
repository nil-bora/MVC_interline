{if $isAjax}
{foreach $products one}
<div class="grid__item" data-id="{$one.id}">
	{if $select_catalog.unit_name}
    <div class="grid__category">
      	{$select_catalog.unit_name}
    </div>
    {/if}
    <a class="grid__name" href="{siteUrl(product/$one.href)}" data-unit_name="{$select_catalog.unit_name}">
       {$one.name}
    </a>
    <a class="grid__img" href="{siteUrl(product/$one.href)}" data-image="/thumbs/130x130{$one.image.filename}">
    	{if $one.image.filename}
        <img src="/thumbs/200x200x1{$one.image.filename}" alt=""/>
        {/if}
    </a>
    
    
    <div class="grid__price">
        {$one.price} <span>грн</span>
    </div>
    {if $one.product_instock == '1'}
	    {if !in_array($one.id, $idCartProd)}
		    <div class="grid__add">
		        купить
		    </div>
		    {else}
			<div class="grid__none">
		   		 Товар в корзине
		    </div>		                    	
	    {/if}
   {else}
   <div class="grid__none">
        нет в наличии
   </div>
   {/if}
</div>
{/foreach}
<input type="hidden" name="hb" id="hb" value="{$hb}" />
{else}
<div class="catalogue" data-js="product_list">
    <div class="inner">
        {if $treeNode.baner_image.filename}
        <div class="banner">
            <img src="/thumbs/960x110{$treeNode.baner_image.filename}">
        </div>
        {/if}
        <div class="left" data-js="filter">
            <div class="filter">
                <div class="filter__name">
                    Цена (грн)
                </div>
                <div class="filter__box">
                    <div class="filter__half">
                        <label for="from">От</label>
                        <input type="text" name="from" id="from" value="{$min_price_filter}"/>
                    </div>
                    <div class="filter__half">
                        <label for="to">До</label>
                        <input type="text" name="to" id="to" value="{$max_price_filter}"/>
                    </div>
                    <input type="submit" class="filter__btn" value="OK"/>
                </div>
            </div>
            {if $prop_names}
            	{foreach $prop_names one}
            		<div class="filter">
		                <div class="filter__name">
		                    {$one.name}
		                </div>
		                <div class="filter__box">
		                    {foreach $one.values two}
		                    <div class="filter__item {if in_array($two, $where_arr)}filter__item--active{/if}" data-filter="{$one.sys_name}" data-value="{$two}">
		                        {$two}
		                    </div>
		                    {/foreach}
		                </div>
		            </div>
            	{/foreach}
            {/if}
            
            {*
            {if $material_fasada}
            <div class="filter">
                <div class="filter__name">
                    Материал фасада
                </div>
                <div class="filter__box">
                    {foreach $material_fasada one}
                    <div class="filter__item {if $one==$curent_material}filter__item--active{/if}" data-filter="material" data-value="{$one}">
                        {$one}
                    </div>
                    {/foreach}
                </div>
            </div>
            {/if}
            {if $cvet}
            <div class="filter">
                <div class="filter__name">
                    Цвет
                </div>
               
                <div class="filter__box">
                	{foreach $cvet one}
                    <div class="filter__item {if $one==$curent_color}filter__item--active{/if}" data-filter="cvet" data-value="{$one}">
                        {$one}
                    </div>
                    {/foreach}
                </div>
            </div>
            {/if}
            {if $taymer}
            <div class="filter">
                <div class="filter__name">
                    Таймер
                </div>
                <div class="filter__box">
                    {foreach $taymer one}
                    <div class="filter__item {if $one==$curent_timer}filter__item--active{/if}" data-filter="timer" data-value="{$one}">
                        {$one}
                    </div>
                    {/foreach}
                </div>
            </div>
			{/if}
			
			*}
            <a href="{siteUrl($.url)}" class="filter__clear">очистить фильтр</a>
        </div>
        <div class="right">

            <h1 class="fz48">{$catalogActive.name} InterLine</h1>
            {if $products}
            <div class="grid">
                {foreach $products one}
	                <div class="grid__item" data-id="{$one.id}">
	                	{if $one.actions.name && $one.actions.href}
	                	<div class="grid__category grid__category--sale" >
	                        <a  href="{siteUrl(action/$one.actions.href)}">АКЦИЯ! {$one.actions.name}</a>
	                    </div>
	                    {elseif $select_catalog.unit_name}
	                    <div class="grid__category">
	                      	{$select_catalog.unit_name}
	                    </div>
	                    {/if}
	                    <a class="grid__name" href="{siteUrl(product/$one.href)}" data-unit_name="{$select_catalog.unit_name}">
	                       {$one.name}
	                    </a>
	                    <a class="grid__img" href="{siteUrl(product/$one.href)}" data-image="/thumbs/130x130{$one.image.filename}">
	                    	{if $one.image.filename}
	                        <img src="/thumbs/200x200x1{$one.image.filename}" alt=""/>
	                        {/if}
	                    </a>
	                    
	                    
	                    <div class="grid__price">
	                        {$one.price} <span>грн</span>
	                    </div>
	                    {if $one.product_instock == '1'}
		                    {if !in_array($one.id, $idCartProd)}
		                    <div class="grid__add">
		                        купить
		                    </div>
		                    {else}
							<div class="grid__none">
		                    Товар в корзине
		                    </div>		                    	
		                    {/if}
	                   {else}
		               <div class="grid__none">
		                    нет в наличии
		               </div>
	                   {/if}
	                </div>
                {/foreach}
            </div>
            {else}
            	Нет товаров для отображения
            {/if}
            <input type="hidden" value="{$first}" id="first">
            <div class="grid__more" {if $hb==1}style="display:none" data-hb="{$hb}"{/if}>
                <span>Показать больше техники</span>
            </div>
			{if $catalogActive.body}
            <div class="grid__seo">
            	{$catalogActive.body}
            </div>
            {/if}
        </div>
    </div>
</div>
<div class="modal none js-callback-popup">
    <div class="modal__box modal__box--border modal__box--cart">
        <div class="modal__close"></div>
        <div class="modal__item">
            <div class="modal__img">
                <img src="/img/cart.png" alt="">
            </div>
            <div class="modal__half">
                <div class="modal__name">
                    <span>
                        духовка
                    </span>
                    DONNA AV/BRA A/60 PB
                </div>
                <div class="modal__price">
                    13 244 <span>грн</span>
                </div>
            </div>
        </div>

        <div class="modal__footer">
            <div class="modal__l">
                <a href="#" class="modal__back btn--close">Продолжить покупки</a><br>
                Товар сохранится в корзине
            </div>
            <div class="modal__r">
                <a class="btn" href="{siteUrl(cart/order)}">Оформить заказ</a>
            </div>
        </div>
    </div>
</div>
{/if}
