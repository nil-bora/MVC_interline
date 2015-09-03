{if $main_slider}
<div class="main-slider" data-js="main__slider">
    <div class="inner">
        <div class="main-slider__container">
            {foreach $main_slider one}
            <div class="main-slider__item">
                <div class="main-slider__caption">
                    <div class="main-slider__name">
                        {nl2br($one.name)}
                    </div>
                    <div class="main-slider__descr">
                       {$one.body}
                    </div>
                    <a href="{$one.href.href}" class="main-slider__more btn-hover">
                        <span>{$one.button}</span>
                    </a>
                </div>

                <div class="main-slider__img">
                    <img src="{$one.image.filename}" alt=""/>
                </div>
            </div>
            {/foreach}
        </div>
        <div class="arrows">
            <div class="arrow__left"></div>
            <div class="arrow__right"></div>
        </div>
    </div>
</div>
{/if}
<div class="m-categories">
    <div class="inner">
    	{if $catalog}
    		{foreach $catalog one}
			<a class="m-categories__item" href="{siteUrl(category/$one.href)}">
				<img src="{$one.icon.filename}" alt=""  width="80%" height="80%"/>
				<div class="m-categories__name">
				    {$one.name}
				</div>
			</a>
    		{/foreach}
    	{/if}       
    </div>
</div><div class="m-sale">
    <div class="inner">
        <div class="m-sale__container">
            <div class="m-sale__item">
                <div class="m-sale__img">
                    <img src="img/m-sale__item.png" alt="" />
                </div>
                <div class="m-sale__caption">
                    <div class="m-sale__name">
                        Рассрочка 0%
                    </div>
                    <div class="m-sale__descr">
                        Купите технику в рассрочку без переплаты!<br/>
                        Оплачивайте покупку равными частями на срок до<br/>11 месяцев
                    </div>
                    <a href="#" class="m-sale__more btn-hover btn-hover--dark">
                        <span>узнать подробнее</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{if $slide_bottom}
<div class="m-special" data-js="special">
    <div class="inner">
        <div class="m-special__heading">
            Особенности техники
        </div>
        <div class="arrows">
            <div class="arrow__left"></div>
            <div class="arrow__right"></div>
        </div>
        <div class="m-special__container">
            {foreach $slide_bottom one}
            <div class="m-special__item">
                <div class="m-special__img" href="{$one.href.href}">
                    <img src="/thumbs/187x187{$one.image.filename}" alt=""/>
                </div>
                <div class="m-special__name">
                    <a {if $one.href.title}href="{$one.href.href}"{/if}>{$one.name}</a>
                </div>
                <div class="m-special__descr">
                   {$one.body}
                </div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
{/if}
{if $action}
<div class="m-sale m-sale--no-bg">
    <div class="inner">
        <div class="m-sale__container">
            <div class="m-sale__item">
                <div class="m-sale__img">
                    <img src="{$action.image.filename}" alt=""/>
                </div>
                <div class="m-sale__caption">
                    <div class="m-sale__name">
                      {$action.short_name}
                    </div>
                    <div class="m-sale__descr">
                        {$action.short_body}
                    </div>
                    <div class="m-sale__small-img">
                        {$action.icon_text}
                    </div>
                    <a href="{siteUrl(action/$action.href)}" class="m-sale__more btn-hover">
                        <span>узнать подробнее</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
