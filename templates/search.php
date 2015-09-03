<div class="search" data-js="product_list">
    <div class="inner">
        <div class="right">
            {if $array}
            <h1>Результат поиска по запросу "{$search}"</h1>
            <div class="grid">
                {foreach $array one}
                <div class="grid__item" data-id="{$one.id}">
                    <div class="grid__category {*grid__category--sale*}">
                        {$one.catalog.unit_name}
                    </div>
                    <a class="grid__name" href="{siteUrl(product/$one.href)}" data-unit_name="{$one.catalog.unit_name}">
                       {$one.name}
                    </a>
                    <div class="grid__img" data-image="/thumbs/130x130{$one.image.filename}">
                        <img src="/thumbs/200x200x1{$one.image.filename}" alt=""/>
                    </div>
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
            	 <h1>По запросу "{$search}" ни чего не найдено</h1>
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