<div class="catalogue">
    <div class="inner">
        {if $catalogActive.baner_image.filename}
        <div class="banner">
            <img src="/thumbs/960x110{$catalogActive.baner_image.filename}">
        </div>
        {/if}
        <div class="left">
            
        </div>
        <div class="right">
            <h1 class="fz48">{$catalogActive.name} InterLine</h1>

            <div class="grid">
                {foreach $catalog one}
                <a class="grid__item" href="{siteUrl(category/$group/$one.href)}">
                    <div class="grid__category">
                        
                    </div>
                    <div class="grid__name">
                       {$one.name}
                    </div>
                    <div class="grid__img">
                        <img src="/thumbs/200x200{$one.image.filename}" alt=""/>
                    </div>                    
                </a>
                {/foreach}
            </div>


            {if $catalogActive.body}
            <div class="grid__seo">
            	{$catalogActive.body}
            </div>
            {/if}
        </div>
    </div>
</div>