<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2008 Harvey Kane <code@ragepank.com>
 * Copyright 2008 Michael Holt <code@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

class Jojo_Plugin_Jojo_cart_product_award extends JOJO_Plugin
{
    /**
     * Store the class name of the product handler
     */



    /* Gets $num awards/reviews sorted by date (desc) for use on homepages and sidebars */
    function getProductAwards($num=false, $start = 0, $productid = false, $order = false) {
        global $page, $cart;
        if (_MULTILANGUAGE) $language = !empty($page->page['pg_language']) ? $page->page['pg_language'] : Jojo::getOption('multilanguage-default', 'en');
        $limit = ($num) ? " LIMIT $start,$num" : '';

        $now    = time();
        $query  = "SELECT * FROM {product_award} AS productaward";
        $query .= " LEFT JOIN {product} AS product ON (productaward.productid = product.productid) ";
        $query .= " WHERE pa_livedate<$now AND (pa_expirydate<=0 OR pa_expirydate>$now)";
        $query .= (_MULTILANGUAGE) ? " AND (pa_language = '$language')" : '';
        $query .= ($productid) ? " AND (productaward.productid = '$productid')" : '';
        $query .= ($order=='product') ? " ORDER BY product.pr_display_order, product.productid, pa_date DESC $limit" : " ORDER BY pa_date DESC $limit" ;
        $productawards = Jojo::selectQuery($query);
        foreach ($productawards as $i => $a){
            $productawards[$i]['productid']    = $productawards[$i]['productid'];
            $productawards[$i]['title']        = $productawards[$i]['pa_name'];
            $productawards[$i]['bodyplain']    = strip_tags($productawards[$i]['pa_body']);
            $productawards[$i]['date']         = Jojo::strToTimeUK($productawards[$i]['pa_date']);
            $productawards[$i]['datefriendly'] = Jojo::mysql2date($productawards[$i]['pa_date'], "medium");
            $productawards[$i]['current'] = $productawards[$i]['pr_livedate']<$now && ($productawards[$i]['pr_expirydate']<=0 || $productawards[$i]['pr_expirydate']>$now) ? true : false;
        }
        return $productawards;
    }


    function _getContent()
    {
        global $smarty, $_USERGROUPS, $_USERID;
        $content = array();
        $language = !empty($this->page['pg_language']) ? $this->page['pg_language'] : Jojo::getOption('multilanguage-default', 'en');
        $mldata = Jojo::getMultiLanguageData();
        $lclanguage = $mldata['longcodes'][$language];


            /* Award index section */

            $pagenum = Jojo::getFormData('pagenum', 1);
            if ($pagenum[0] == 'p') {
                $pagenum = substr($pagenum, 1);
            }

            /* Get category url and id if needed */
            $pg_url = $this->page['pg_url'];

            $smarty->assign('productaward','');
            $productawardsperpage = Jojo::getOption('productaward_perpage', 40);
            $start = ($productawardsperpage * ($pagenum-1));

            /* get number of products for pagination */
            $now = strtotime('now');
           /* Get number of products for pagination */
            $countquery =  "SELECT COUNT(*) AS numproductawards FROM {product_award} WHERE pa_livedate<$now AND (pa_expirydate<=0 OR pa_expirydate>$now)";
            $countquery .= (_MULTILANGUAGE) ? " AND (pa_language = '$language')" : '';
            $productawardscount = Jojo::selectQuery($countquery);
            $numproductawards = $productawardscount[0]['numproductawards'];
            $numpages = ceil($numproductawards / $productawardsperpage);
            /* calculate pagination */
            if ($numpages == 1) {
                $pagination = '';
            } elseif ($numpages == 2 && $pagenum == 2) {
                $pagination = sprintf('<a href="%s/p1/">previous...</a>', (_MULTILANGUAGE ? $lclanguage . '/' : '') . Jojo_Plugin_Jojo_cart_product_award::_getPrefix('productaward', (_MULTILANGUAGE ? $language : '')) );
            } elseif ($numpages == 2 && $pagenum == 1) {
                $pagination = sprintf('<a href="%s/p2/">more...</a>', (_MULTILANGUAGE ? $lclanguage . '/' : '') . Jojo_Plugin_Jojo_cart_product_award::_getPrefix('productaward', (_MULTILANGUAGE ? $language : '')) );
            } else {
                $pagination = '<ul>';
                for ($p=1;$p<=$numpages;$p++) {
                    $url = (_MULTILANGUAGE ? $lclanguage . '/' : '') . Jojo_Plugin_Jojo_cart_product_award::_getPrefix('productaward', (_MULTILANGUAGE ? $language : '')) . '/';
                    if ($p > 1) {
                        $url .= 'p' . $p . '/';
                    }
                    if ($p == $pagenum) {
                        $pagination .= '<li>&gt; Page '.$p.'</li>'. "\n";
                    } else {
                        $pagination .= '<li>&gt; <a href="'.$url.'">Page '.$p.'</a></li>'. "\n";
                    }
                }
                $pagination .= '</ul>';
            }
            $smarty->assign('pagination',$pagination);
            $smarty->assign('pagenum',$pagenum);

            /* clear the meta description to avoid duplicate content issues */
             $content['metadescription'] = '';

            /* get product content and assign to Smarty */
            $smarty->assign('productawards', Jojo_Plugin_Jojo_cart_product_award::getProductAwards($productawardsperpage, $start, '', 'product'));

            $content['content'] = $smarty->fetch('jojo_cart_product_award_index.tpl');
            return $content;

    }


    /**
     * Get the url prefix for a particular part of this plugin
     */
    static function _getPrefix($for='productaward', $language=false) {
        static $_cache;
        $language = !empty($language) ? $language : Jojo::getOption('multilanguage-default', 'en');
        if (!isset($_cache[$for.$language])) {
            $query = "SELECT pageid, pg_title, pg_url FROM {page} WHERE pg_link = ?";
            $query .= (_MULTILANGUAGE) ? " AND pg_language = '$language'" : '';
            $values = array('Jojo_Plugin_Jojo_cart_product_award');

            if ($values) {
                $res = Jojo::selectQuery($query, $values);
                if (isset($res[0])) {
                    $_cache[$for.$language] = !empty($res[0]['pg_url']) ? $res[0]['pg_url'] : $res[0]['pageid'] . '/' . strtolower($res[0]['pg_title']);
                    return $_cache[$for.$language];
                }
            }
            $_cache[$for.$language] = '';

        }

        return $_cache[$for.$language];
    }

    function getCorrectUrl()
    {
        global $page;
        $language  = $page->page['pg_language'];
        $pg_url    = $page->page['pg_url'];
        $productawardid = Jojo::getFormData('id',     0);
        $url       = Jojo::getFormData('url',    '');
        $action    = Jojo::getFormData('action', '');
        $pagenum   = Jojo::getFormData('pagenum', 1);

        if ($pagenum[0] == 'p') {
            $pagenum = substr($pagenum, 1);
        }

        /* product index with pagination */
        if ($pagenum > 1) return parent::getCorrectUrl() . 'p' . $pagenum . '/';

        /* product index - default */
        return parent::getCorrectUrl();
    }
}
