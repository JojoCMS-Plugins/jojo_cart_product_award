<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007-2008 Harvey Kane <code@ragepank.com>
 * Copyright 2007-2008 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @author  Michael Cochrane <mikec@jojocms.org>
 * @author  Melanie Schulz <mel@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 * @package jojo_article
 */



/** Example usage in theme template:
                {if $reviews}
                <div id='reviews' class="sidebarbox">
                    <h2>Latest Awards &amp; Reviews</h2>

                    {foreach from=$reviews key=key item=review}
                        <h4 class="clear">{$review.pa_name}</h4>
                        <p><a href = "{$review.producturl}" title="More about {$review.productname}">{$review.productname}</a></p>
                        <p class='news-content'>
                            {$review.bodyplain}
                        </p>
                    {/foreach}
                    <p class="links">&gt; <a href='{$SITEURL}/{if _MULTILANGUAGE}{$lclanguage}/{/if}{$reviewhome}/'>See all reviews</a></p>
                </div>
                {/if}
*/

$num = Jojo::getOption('productaward_num_sidebar', 3);

if ($num) {
    if ($page->getValue('pg_link') == 'jojo_plugin_jojo_cart_products_wine') {
        $productid = Jojo::getFormData('id',     0);
        $url       = Jojo::getFormData('url',    '');
        if (!empty($url)) {
             $product = Jojo::selectRow("SELECT productid FROM {product} WHERE pr_url = ? and status = 'active'", array($url));
             $productid = $product['productid'];
        }
        if (!empty($productid)) {
            $productreviews = Jojo_Plugin_Jojo_cart_product_award::getProductAwards('', '', $productid);
            $smarty->assign('productreviews', $productreviews );
            $smarty->assign('productname', ($productreviews ? $productreviews[0]['productname'] : false ) );
        } else {
            $reviews = Jojo_Plugin_Jojo_cart_product_award::getProductAwards($num * 2, 0, '', 'date');

            $smarty->assign('reviews', $reviews);
            $smarty->assign('reviewhome', Jojo_Plugin_Jojo_cart_product_award::_getPrefix('', $page->getValue('pg_language')) );
        }
    } else {
        /* Create latest Reviews/Awards array for sidebar: getProductAwards(x, start, categoryid) = list x# of articles */
        $reviews = Jojo_Plugin_Jojo_cart_product_award::getProductAwards($num * 2, 0, '', 'date');
        shuffle($reviews);
        $reviews = array_slice($reviews, 0, $num);
        $smarty->assign('reviews', $reviews);

        /* Get the prefix for reviews (can vary for multiple installs) for use in the theme template instead of hard coding it */
        $smarty->assign('reviewhome', Jojo_Plugin_Jojo_cart_product_award::_getPrefix('', $page->getValue('pg_language')) );
    }
}