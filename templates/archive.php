<div class="sales">
    <div class="inner">
    	{if $archive}
    	<div class="product" style="height:500px;">

		<ul class="documentation">
		    {foreach $archive one}
		    <li>
		       {$one.name}<a href="{$one.file.filename}">скачать</a>
		    </li>
		    {/foreach}
		</ul>
        
        </div>
        {/if}
    </div>
</div>
