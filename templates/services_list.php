<div class="service">
    <div class="inner">
        <h1>{$.page.menuname}</h1>

        <div class="service__grid">
            {if $array}
            	{foreach $array one}
            		 <a href="{siteUrl($one.href)}" class="service__item">
		                <div class="service__name">
		                    {$one.name}
		                </div>
						{if $one.short_body}
		                <div class="service__descr">
		                   {$one.short_body}
		                </div>
		                {/if}
		            </a>
		          {/foreach}
            {/if}
        </div>
    </div>
</div>