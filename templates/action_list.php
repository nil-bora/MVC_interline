<div class="sales">
    <div class="inner">
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
		                   {$one.icon_text}
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