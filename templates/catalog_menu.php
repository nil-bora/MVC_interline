{if $catalog}
<div class="inner">
    <nav class="main-menu">
        <menu class="main-menu__container inner">
            {foreach $catalog one}
            <li class="main-menu__item {if 'category/`$one.href`' == $.url || 'category/`$one.href`/`$subgroup`' == $.url || $href == $one.href}current{/if}">
                <a href="{siteUrl(category/$one.href)}" class="main-menu__link">{$one.name}</a>
            </li>
            {/foreach}
        </menu>
    </nav>
</div>
{/if}
