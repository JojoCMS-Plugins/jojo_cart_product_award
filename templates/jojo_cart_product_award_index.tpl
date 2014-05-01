{if $pg_body && $pagenum==1}{$pg_body}
{/if}{assign var=prevproduct value=''}
{foreach from=$productawards item=a}{if $prevproduct!=$a.productid || $prevproduct==''}
    <h3><a href = "{$a.product.url}" title="More about {$a.product.title}">{$a.product.title}</a></h3>{assign var=prevproduct value=$a.productid}
{/if}
    <h5>{$a.title}{if $a.award} - {$a.award}{/if}</h5>{if $a.date}
    <p class="date">{$a.date}</p>
    {/if}{if $a.pa_rating!=0}<div class="rating" style="width:{$a.pa_rating*10}px;"><img src="{$SITEURL}/images/stars-trans.png" /></div>
    {/if}{if $a.bodyplain}<p>{$a.bodyplain}</p>
    {/if}
{/foreach}

<div class="pagination">
{$pagination}
</div>
