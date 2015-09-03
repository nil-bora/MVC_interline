<div class="product" data-js="one_product">
   <div class="inner">
      
       {include('product_cart.php')}

        <div class="product__left">
            <div class="product__img-big">
                {foreach $product.gallery one}
                <div>
                    <img src="/thumbs/440x440{$one.image.filename}" alt=""/>
                </div>
                {/foreach}
            </div>
            <div class="product__gallery" data-js="item__{if $product.vimeo.html || $product.video.html}small{else}full{/if}">
            	{if $product.gallery}
                <div class="product__gallery__box{if !$product.vimeo.html && !$product.video.html} product__gallery__box--novid{/if}">
                    <div class="product__slider">
                    	{foreach $product.gallery one}
                        <div class="product__img">
                            <img src="/thumbs/90x90{$one.image.filename}" alt=""/>
                        </div>
                        {/foreach}
                     
                    </div>
                    <div class="arrows">
                        <div class="arrow__left"></div>
                        <div class="arrow__right"></div>
                    </div>
                </div>
                {/if}
                {if $product.vimeo.html || $product.video.html}
	                <a class="product__video">
	                    <img src="/thumbs/142x90{$product.image.filename}" alt=""/>
	                </a>
                {/if}
                
            </div>
        </div>

        <div class="product__right" data-js="product_right">
            <div class="star">
                <div class="star__container">
	                {section name=i start=0 loop=5}
					  <div class="star__item {if $mean >= $dwoo.section.i.iteration}star__item--active{/if}"></div>
					{/section}

                </div>

                <a href="#" class="star__reviews">{$cnt} {$endWord}</a>
            </div>
			{if $product.catalog.unit_name}
            <div class="product__category">
                {$product.catalog.unit_name}
            </div>
            {/if}
            <div class="product__name">
                {$product.name}
            </div>
            <div class="product__article" data-idproduct="{$product.id}">
                Код товарa: {$product.articul}
            </div>
            <div class="product__quantity">
                {if $product.product_instock == '1'}есть в наличии{else}Нет в наличии{/if}
            </div>
            <div class="product__price">
               {$product.price} <span>грн</span>
            </div>

        	<a href="{siteUrl(cart/order)}" class="product__to-cart js-to-order" {if !$order}style="display:none"{/if}>оформить заказ</a>
        	<a class="product__to-cart js-to-cart" data-id="{$product.id}" {if $order}style="display:none"{/if}>Купить</a>
            
            <a href="#" class="product__to-credit js-installments-show-popup">Купить в рассрочку 0%</a>
            <br/>
            <a href="#" class="product__info js-info-installments">Информация про расскрочку</a>
            <br/>
            <a href="#" class="product__info js-follow_price">следить за ценой</a>
        </div>
        <div class="clear"></div>
        <div class="product__box" data-js="product_box">
            {if is_array($product.hang)}
            <div class="product__box__item">
                <div class="product__box__name">
                    Особенности
                </div>
                <ul>
                	{foreach $product.hang one name="iter"}
                		<li>{$one.name}</li>
                	{/foreach}
                </ul>
            </div>
            {/if}
            {if $action}
	     
            <div class="product__box__item">
                <div class="product__box__name">
                    Акция! <span>{$action.short_name}</span>
                </div>
                <div class="main-slider__small-img">
                     {$action.icon_text}
                </div>
                <a href="{siteUrl(action/$action.href)}" class="product__box__more">Узнать больше</a>
            </div>
            {/if}
            <div class="product__box__item">
                <div class="product__box__name">
                   {translate(delivery_payment)}
                </div>
                {interface(delivery_payment_short)}
                <a href="#" class="product__box__more js-delivery_payment_more">Узнать больше</a>
            </div>
            <div class="product__box__item">
                <div class="product__box__name">
                    Гарания <span>12 месяцев</span>
                </div>
                {interface(guaranty_short)}
                <a href="{siteUrl(guarantee)}" class="product__box__more">Узнать больше</a>
            </div>
        </div>

        <div class="tp" data-js="tp">
            <div class="tabs">
                <div class="tab active">характеристики</div>
                <div class="tab">фукции</div>
                <div class="tab">описание</div>
                <div class="tab">документы</div>
                <div class="tab">отзывы ({if $coments}{sizeof($coments)}{else}0{/if})</div>
                <div class="tab">акции ({sizeof($actions)})</div>
            </div>
            <div class="panes">
                <div class="pane active">
                    {if $product.propertys}
                    <table class="features">
                        {foreach $product.propertys one}
                        {if !empty($one.value) && $one.value!='xx'}
                        <tr>
                            <td>{$one.name_property}</td>
                            <td>{if $one.value==1}есть{else}{$one.value}{/if} {$one.unit_property}</td>
                        </tr>
                        {/if}
                        {/foreach}
                    </table>
                    {/if}
                </div>
                <div class="pane">
                    {if $product.feature}
                    <table class="functions">
                        {foreach $product.feature one}
                        <tr>
                            <td>
                                <img src="{$one.image.filename}" alt=""/>
                            </td>
                            <td>
                                {$one.body}
                            </td>
                        </tr>
                        {/foreach}
                    </table>
                    {/if}
                </div>
                <div class="pane">
                    <div class="description">
                        {$product.description}
                    </div>
                </div>
                <div class="pane">
                	{if $product.documents}
                    <ul class="documentation">
                        {foreach $product.documents one}
                        <li>
                           {$one.name}<a href="{$one.file.filename}">скачать</a>
                        </li>
                        {/foreach}
                    </ul>
                    {/if}
                </div>
                <div class="pane">
                    <table class="reviews">
                        {if $coments}
                        {foreach $coments one}
                        <tr>
                            <td>
                                <div class="star">
                                    <div class="star__container">
                                    	{section name=i start=0 loop=5}
										  <div class="star__item {if $one.mark >= $dwoo.section.i.iteration}star__item--active{/if}"></div>
										{/section} 
                                    </div>
                                </div>
                                <div class="name">
                                    {$one.name}
                                </div>
                                <div class="date">
                                	{$one.idate.datenamer}
                                </div>
                            </td>
                            <td>
                                {nl2br($one.body)}
                            </td>
                        </tr>
                        {/foreach}
                        {/if}
                    </table>

                    <form method="POST" class="add-review" action="{siteUrl(comments/add)}" data-js="comments">
                        <table>
                            <tr>
                                <td>Оцените данную модель</td>
                                <td>
                                    <div class="star">
                                        <div class="star__container js-star">
                                            <div class="star__item"></div>
                                            <div class="star__item"></div>
                                            <div class="star__item"></div>
                                            <div class="star__item"></div>
                                            <div class="star__item"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="review-name">Ваше Имя</label></td>
                                <td><input type="text" id="review-name" name="name"/></td>
                            </tr>
                            <tr>
                                <td><label for="revierw-text">Отзыв</label></td>
                                <td><textarea id="revierw-text" cols="30" rows="10" name="body"></textarea></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                	<input type="hidden" name="id" value="{$product.id}" />
                                	<input type="submit" class="submit js-add-comment" value="оставить отзыв"/>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div class="js-coments-thanks" style="display:none;">Спасибо за оставленный комментарий</div>
                </div>
                <div class="pane">
                    <div class="sales">
                    	{if $actions}
				        	{foreach $actions one}
				        		<div class="sales__item">
						            <div class="sales__img">
						                <img src="{$one.image.filename}" alt=""/>
						                <div class="sales__date">
						                    <span>Период проведения акции</span>
						                    {$one.idate.d} {$one.idate.monthr} - {$one.date_end.d} {$one.date_end.monthr}
						                </div>
						            </div>
						            <div class="sales__caption">
						                <div class="sales__name">
						                    {$one.short_name}
						                </div>
						                <div class="sales__descr">
						                   {$one.short_body}
						                </div>
						                <div class="sales__small-img">
						                	{if $one.icon_text}
						                		{$one.icon_text}
						                	{/if}
						                    {*<img src="/thumbs/34x{$one.imgCatalog1.filename}" alt=""/>+<img src="/thumbs/34x{$one.imgCatalog2.filename}" alt=""/>=<img src="/thumbs/34x{$one.imgCatalog3.filename}" alt=""/>*}
						                </div>
						                <a href="{siteUrl(action/$one.href)}" class="sales__more">
						                    собрать комплект
						                </a>
						            </div>
						        </div>
				        	
				        	{/foreach}
				        {/if}
                    </div>
                </div>
            </div>
        </div>
        <div class="callback">
            <div class="callback__name">
                Осталиcь вопросы? Звоните!<br/>
                <span>0 800 30-33-34</span>
            </div>

            <div class="callback__descr">
                или оставьте заявку на обратный звонок<br/>
                и мы вам перезвоним
            </div>

            <div class="callback__call">
                Обратный звонок
            </div>
        </div>
    </div>
</div>

<div class="modal none js-delivery_payment">
    <div class="modal__box modal__box--border">
        <div class="modal__close"></div>
            {interface(delivery-payment)}
        <div class="btn btn--close">Закрыть окно</div>
    </div>
</div>

<div class="modal none js-payment_installments">
    <div class="modal__box modal__box--border">
        <div class="modal__close"></div>
            {interface(payment-by-installments)}
        <div class="btn btn--close">Закрыть окно</div>
    </div>
</div>

<div class="modal none js-callback-popup" data-js="callback">
    <div class="modal__box modal__box--border modal__box--form">
        <div class="modal__close"></div>
       {interface(callbeck-popap)}
        <div>
            <form action="{siteUrl(callback/send)}" method="POST" id="callback">
                <label><span>Ваше имя</span><input type="text" name="name_callback"/></label>
                <label><span>Ваш номер телефона</span><input type="tel" name="phone_callback"/></label>

                <input type="submit" class="btn js-callbask-send" value="OK">
            </form>
        </div>
    </div>
</div>

<div class="modal none js-follow_price_popup" data-js="follow">
    <div class="modal__box modal__box--border modal__box--form">
        <div class="modal__close"></div>
        <h2>Прислать письмо
            <span>когда снизится цена</span>
        </h2>
        <div>
            <form action="" id="follow_form">
                <label><span>Ваше Email</span><input type="email" name="email_follow"/></label>

                <input type="submit" class="btn js-follow_send" value="OK">
            </form>
        </div>
    </div>
</div>

<div class="modal none js-product-popap">
    <div class="modal__box modal__box--border modal__box--cart">
        <div class="modal__close"></div>
        <div class="modal__item">
            <div class="modal__img">
                <img src="/thumbs/130x130{$product.image.filename}" alt="">
            </div>
            <div class="modal__half">
                <div class="modal__name">
                    {if $product.catalog.unit_name}
                    <span>
                        {$product.catalog.unit_name}
                    </span>
                    {/if}
                    {$product.name}
                </div>
                <div class="modal__price">
                    {$product.price} <span>грн</span>
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

<div class="modal none js-modal-video">
    <div class="modal__box modal__box--border modal__box--video">
        <div class="modal__close"></div>
        {if $product.vimeo.html}
        	{$product.vimeo.html}
        {elseif $product.video.html}
        	{$product.video.html}
        {/if}
        {*<iframe src="" frameborder="0"></iframe>*}
    </div>
</div>

<div class="modal none js-payment_installments-popup" data-js="installments" data-idinst="{$product.id}">
    <div class="modal__box modal__box--border">
        <div class="modal__close"></div>
        <h2>Оформление рассрочки
            <span>Купите технику в рассрочку без переплаты! Оплачивайте покупку равными частями на срок до 11 месяцев</span>
        </h2>
        <h3>{if $product.catalog.unit_name}{$product.catalog.unit_name} {/if}{$product.name}</h3>
        {$product.description}
        <br>
<strong>11 платежей по <span>{ceil($product.price/11)}</span> грн</strong>
        <div>
            <form action="" id="installments_form" method="POST">
                <label><span>Фамилия</span><input type="input" name="surname"/></label>
                <label><span>Имя</span><input type="input" name="name"/></label>
                <label><span>Отчество</span><input type="input" name="lastname"/></label>
                <label><span>ИНН</span><input type="input" name="inn"/></label>
                <label><span>Серия и номер паспорта</span><input type="input" name="identity_doc" id="identity_doc" /></label>
                <label><span>Номер телефона (моб)</span><input type="input" name="phone" id="phone_installments"/></label>
                <label><span>Email</span><input type="input" name="email"/></label>
                <label><span>Дата рождения</span><input type="input" placeholder="ГГГГ-MM-ДД" name="birthday" id="birthday_installments"/></label>
                <label><span>Город</span><input type="input" name="city"/></label>
                <label><span>Адрес</span><input type="input" name="address"/></label>
                <label><span>С условиями согласен</span><input type="checkbox" name="conditions"/></label>
                <div class="installments_error" style="display:none; color:red;"></div>
                <input type="submit" class="btn js-installments_send" value="OK">
            </form>
            
        </div>
    </div>
</div>
