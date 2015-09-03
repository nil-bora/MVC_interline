{if $array}
<div class="breadcrumbs">
    <div class="inner">
    	{foreach $array one name='iter'}
    		{if !$dwoo.foreach.iter.last}
    			 <a href="{siteUrl($one.href)}">{$one.menuname}</a> >
    		{else}
    			<span>{$one.menuname}</span>
    		{/if}
    	{/foreach}
    </div>
</div>
{/if}