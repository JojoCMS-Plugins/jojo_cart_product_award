{if $pg_body && $pagenum==1}
    {$pg_body}
{/if}

    <table> 
      <tbody> 
{assign var=prevproduct value=''}
{foreach from=$productawards item=wineaward}
{if $prevproduct!='' && $prevproduct!=$wineaward.productid }
          </td>
        </tr>
{/if}
{if $prevproduct!=$wineaward.productid || $prevproduct==''}
        <tr> 
          <td class="awards-leftcolumn"><strong>{if $wineaward.current}<a href = "wines/{$wineaward.pr_url}" title="More about {$wineaward.pr_variety} {$wineaward.pr_vintage}">{$wineaward.pr_variety} {$wineaward.pr_vintage}</a>{else}{$wineaward.pr_variety} {$wineaward.pr_vintage}{/if}:</strong>
          </td>
          <td>
          {if $wineaward.pa_rating !=0}<div class="rating" style="width:{$wineaward.pa_rating*10}px; margin-bottom: 3px;"><img src="images/stars-trans.png" /></div>{/if}
                        <p>&ldquo;{$wineaward.bodyplain}&rdquo;</p>
                        <p class="credit">{$wineaward.title}</p>
{assign var=prevproduct value=$wineaward.productid}
{else}
          {if $wineaward.pa_rating!=0}<div class="rating" style="width:{$wineaward.pa_rating*10}px;"><img src="images/stars-trans.png" /></div>{/if}
                        <p>&ldquo;{$wineaward.bodyplain}&rdquo;</p>
                        <p class="credit">{$wineaward.title}</p>
{/if}

{/foreach}
      </td>
      </tr>
      </tbody>
      </table>

<div class="product-pagination">
{$pagination}
</div>
