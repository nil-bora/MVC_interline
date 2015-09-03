<div class="sales-one">
    <div class="inner">
        <h1>
            <span>{$action.name}</span>
            {$action.short_name}
        </h1>

        <div class="descr">
           {$action.body}
        </div>


        <div class="action" data-js="one_actions">
            <div class="action__title">
                Чтобы оформить заказ на комплект или узнать стоимость – <br/>
                выберите интерисующую вас технику
            </div>

            <div class="grid">

                <div class="grid__item grid__item--step1 js-step1">
                    <div class="step1">
                        <div class="grid__img">
                        	{if $node1.icon.filename}
                        		<img src="{$node1.icon.filename}" width="105px" height="105px" alt=""/>
                        	{else}
                          	  <img src="{$node1.image.filename}" alt=""/>
                            {/if}
                        </div>

                        <div class="grid__check">
                            Выберите {$node1.accusative_name}
                        </div>

                        <div class="ol">
                            <div class="btn js-open-modal" data-modal="1">Выбрать</div>
                        </div>
                    </div>
                    <div class="step2">
                        <div class="ol">
                            <div class="btn">Выбрать другую</div>
                        </div>
                    </div>
                    <div class="step3">
                        <div class="grid__category">
                            духовка
                        </div>
                        <div class="grid__name" data-articul="" data-id="">
                            DONNA AV/BRA A/60 PB
                        </div>
                        <div class="grid__img">
                            <img src="/img/grid__item.png" alt=""/>
                        </div>
                        <div class="grid__price">
                            13 244 <span>грн</span>
                        </div>
                        <div class="ol">
                            <div class="btn js-open-modal" data-modal="1">Выбрать</div>
                        </div>
                    </div>
                </div>
                
                 <div class="grid__item grid__item--step1 js-step2">
                    <div class="step1">
                        <div class="grid__img">
                        	{if $node2.icon.filename}
                        		<img src="{$node2.icon.filename}" width="105px" height="105px" alt=""/>
                        	{else}
                            <img src="/thumbs/109x105{$node2.image.filename}" alt=""/>
                            {/if}
                        </div>

                        <div class="grid__check">
                            Выберите {$node2.accusative_name}
                        </div>

                        <div class="ol">
                            <div class="btn js-open-modal" data-modal="2">Выбрать</div>
                        </div>
                    </div>
                    <div class="step2">
                        <div class="ol">
                            <div class="btn">Выбрать другую</div>
                        </div>
                    </div>
                    <div class="step3">
                        <div class="grid__category">
                            духовка
                        </div>
                        <div class="grid__name" data-articul="" data-id="">
                            DONNA AV/BRA A/60 PB
                        </div>
                        <div class="grid__img">
                            <img src="/img/grid__item.png" alt=""/>
                        </div>
                        <div class="grid__price">
                            13 244 <span>грн</span>
                        </div>
                        <div class="ol">
                            <div class="btn js-open-modal" data-modal="2">Выбрать</div>
                        </div>
                    </div>
                </div>
                
                <div class="grid__item grid__item--step1 js-step3">
                    <div class="step1">
                        <div class="prize">
                            {$action.short_name}
                        </div>
                        <div class="grid__img">
                        	{if $node3.icon.filename}
                        		<img src="{$node3.icon.filename}" width="105px" height="105px" alt=""/>
                        	{else}
                          	  <img src="{$node3.image.filename}" alt=""/>
                            {/if}
                        </div>

                        <div class="grid__check">
                            Выберите {$node3.accusative_name}
                        </div>

                        <div class="ol">
                            <div class="btn js-open-modal" data-modal="3">Выбрать</div>
                        </div>
                    </div>
                    <div class="step2">

                    </div>
                    <div class="step3">
                        <div class="grid__category">
                            духовка
                        </div>
                        <div class="grid__name" data-articul="" data-id="">
                            DONNA AV/BRA A/60 PB
                        </div>
                        <div class="grid__img">
                            <img src="/img/grid__item.png" alt=""/>
                        </div>
                        <div class="grid__price">
                            <div class="old">
                                33 3333 <span>грн</span>
                            </div>
                            13 244 <span>грн</span>
                        </div>
                        <div class="ol">
                            <div class="btn js-open-modal" data-modal="3">Выбрать</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action__legend">
                <table>
                    <tr>
                        <td>Осталось выбрать:</td>
                        <td>
                            <span class="cb js-checked-1">{$node1.accusative_name}</span>
                            <span class="cb js-checked-2">{$node2.accusative_name}</span>
                            <span class="cb js-checked-3">{$node3.accusative_name}</span>
                        </td>
                    </tr>
                </table>
                <div class="summ none" data-js="one_action_summ">
                    <div class="row">
                        <div class="summ__name">
                            Итого:
                        </div>
                        <div class="summ__value">
                            <div class="old">
                                <span class="js-sum-old" >25 560</span> грн
                            </div>
                            <label class="js-sum-new">19 244</label> <span>грн</span>
                        </div>
                    </div>

                    <div class="btn js-by_complect">Купить комплект</div>

                    <div class="summ__economy">
                        Экономия: <b class="js-sum-ec">5 367 грн</b>
                    </div>
                </div>
            </div>
        </div>


        <div class="text">
            {$.page.body}
        </div>
    </div>
</div>

<div class="modal js-product1" style="display:none;">
    <div class="modal__box">
        <div class="modal__close"></div>
        <h1>Выберите {$node1.accusative_name}</h1>
        <div class="grid">
        	{if $products1}
        		{foreach $products1 one}
        			<div class="grid__item">
		                <div class="grid__category grid__category--sale">
		                    {$one.catalog.unit_name}
		                </div>
		                <div class="grid__name" data-articul="{$one.articul}" data-id="{$one.id}">
		                    {$one.name}
		                </div>
		                <div class="grid__img">
		                    <img src="/thumbs/200x200x1{$one.image.filename}" alt=""/>
		                </div>
		                <div class="grid__price" >
		                	<span class='js-price' data-oldprice="{$one.price}"></span>
		                    {$one.price} <span>грн</span>
		                </div>
		                {if !in_array($one.id, $cartProdId)}
			                <div class="grid__add js-add-to-step" data-step="1">
			                    Выбрать
			                </div>
		                {else}
		              		<div data-step="1">
			                    Товар уже в корзине
			                </div>
		              	{/if}
		            </div>
        		{/foreach}
        	{/if}
        </div>
    </div>
</div>


<div class="modal js-product2" style="display:none;">
    <div class="modal__box">
        <div class="modal__close"></div>
        <h1>Выберите {$node2.accusative_name}</h1>
        <div class="grid">
        	{if $products2}
        		{foreach $products2 one}
        			<div class="grid__item">
		                <div class="grid__category grid__category--sale">
		                    {$one.catalog.unit_name}
		                </div>
		                <div class="grid__name" data-articul="{$one.articul}" data-id="{$one.id}">
		                    {$one.name}
		                </div>
		                <div class="grid__img">
		                    <img src="/thumbs/200x200x1{$one.image.filename}" alt=""/>
		                </div>
		                <div class="grid__price">
		                	<span class='js-price' data-oldprice="{$one.price}"></span>
		                    {$one.price} <span>грн</span>
		                </div>
		                {if !in_array($one.id, $cartProdId)}
		                <div class="grid__add js-add-to-step" data-step="2">
		                    Выбрать
		                </div>
		                {else}
		                <div data-step="2">
		                     Товар уже в корзине
		                </div>
		                {/if}
		            </div>
        		{/foreach}
        	{/if}
        </div>
    </div>
</div>
<div class="modal js-product3" style="display:none;">
    <div class="modal__box">
        <div class="modal__close"></div>
        <h1>Выберите {$node3.accusative_name}</h1>
        <div class="grid">
        	{if $products3}
        		{foreach $products3 one}
        			<div class="grid__item sales-one">
		                <div class="grid__category grid__category--sale">
		                    {$one.catalog.unit_name}
		                </div>
		                <div class="grid__name" data-articul="{$one.articul}" data-id="{$one.id}">
		                    {$one.name}
		                </div>
		                <div class="grid__img js-last-img" data-img="/thumbs/130x130{$one.image.filename}">
		                    <img src="/thumbs/200x200x1{$one.image.filename}" alt=""/>
		                </div>
		                <div class="grid__price">
		                	<span class='js-price' data-oldprice="{$one.price}" data-newprice="{$one.price2}"></span>
		                	<div class="old">
		                    	{$one.price} <span>грн</span>
		                    </div>
		                     {$one.price2} <span>грн</span>
		                </div>
		                {if !in_array($one.id, $cartProdId)}
		                <div class="grid__add js-add-to-step" data-step="3">
		                    Выбрать
		                </div>
		                {else}
		                 <div data-step="3">
		                    Товар уже в корзине
		                </div>	
		                {/if}
		            </div>
        		{/foreach}
        	{/if}
        </div>
    </div>
</div>

<div class="modal none js-action-popap">
    <div class="modal__box modal__box--border modal__box--cart">
        <div class="modal__close"></div>
        <div class="modal__item">
            <div class="modal__img">
                <img src="" alt="">
            </div>
            <div class="modal__half">
                <div class="modal__name">
                    <span>
                      {$node1.unit_name}+{$node2.unit_name}+{$node3.unit_name}
                    </span>
                </div>
                <div class="modal__price">
                   <span>грн</span>
                </div>
            </div>
        </div>

        <div class="modal__footer">
            <div class="modal__l">
                <a class="modal__back btn--close">Продолжить покупки</a><br>
                Товар сохранится в корзине
            </div>
            <div class="modal__r">
                <a class="btn" href="{siteUrl(cart/order)}">Оформить заказ</a>
            </div>
        </div>
    </div>
</div>