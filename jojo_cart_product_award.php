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

    /* Gets $num awards/reviews sorted by date (desc) for use on homepages and sidebars */
    public static function getProductAwards($num=false, $start = 0, $productid = false, $order = false) {

        global $page, $cart;
        if (_MULTILANGUAGE) $language = !empty($page->page['pg_language']) ? $page->page['pg_language'] : Jojo::getOption('multilanguage-default', 'en');
        $limit = ($num) ? " LIMIT $start,$num" : '';

        $now    = time();
        $query  = "SELECT * FROM {product_award}";
        $query .= " WHERE productid AND pa_livedate<$now AND (pa_expirydate<=0 OR pa_expirydate>$now)";
        $query .= (_MULTILANGUAGE) ? " AND (pa_language = '$language')" : '';
        $query .= ($productid) ? " AND (productid = '$productid')" : '';
        $query .= ($order=='product') ? " ORDER BY productid DESC, pa_date DESC $limit" : " ORDER BY pa_date DESC $limit" ;
        $productawards = Jojo::selectQuery($query);
        foreach ($productawards as $k => &$a){
            $a['reviewer']     = htmlspecialchars($a['pa_author'], ENT_COMPAT, 'UTF-8', false);
            $a['title']     = htmlspecialchars($a['pa_organisation'], ENT_COMPAT, 'UTF-8', false);
            $a['award']        = htmlspecialchars($a['pa_name'], ENT_COMPAT, 'UTF-8', false);
            $a['link']        = !empty($a['pa_organisationlink']) ? $a['pa_organisationlink'] : '';
            $a['rating']     = htmlspecialchars($a['pa_rating'], ENT_COMPAT, 'UTF-8', false);
            $a['bodyplain']    = strip_tags($a['pa_body']);
            unset($a['pa_body_code']);
            $a['date']         = $a['pa_date'];
            if ($a['productid'] && class_exists('JOJO_Plugin_Jojo_cart')) {
                foreach (JOJO_Plugin_Jojo_cart::getProductHandlers() as $productHandler) {
                    if (method_exists($productHandler, 'getItemsbyId')) {
                        $item = call_user_func($productHandler .'::getItemsById', $a['productid']);
                        if ($item) {
                            $a['product'] = $item;
                            break;
                        }
                    }
                }
            }
            if (!($productid || Jojo::getOption('productaward_currentonly', 'yes')=='no' || (isset($a['product']['pr_price']) && $a['product']['pr_price']!=0) )) {
                unset($productawards[$k]);
            }
        }
        return $productawards;
    }


    function _getContent()
    {
        global $smarty, $_USERGROUPS, $_USERID;
        $content = array();
        $pageid = $this->page['pageid'];
        $pageprefix = Jojo::getPageUrlPrefix($pageid);
        $smarty->assign('multilangstring', $pageprefix);

        $pagenum = Jojo::getFormData('pagenum', 1);
        if ($pagenum[0] == 'p') {
            $pagenum = substr($pagenum, 1);
        }
        $smarty->assign('pagenum',$pagenum);

        /* Get category url and id if needed */
        $pg_url = $this->page['pg_url'];
        $productawards = self::getProductAwards('', '', '', 'product');

        $perpage = Jojo::getOption('productaward_perpage', 40);
        $start = ($perpage * ($pagenum-1));

        /* get number of products for pagination */
        $now = strtotime('now');
       /* Get number of products for pagination */
        $total = count($productawards);
        $numpages = ceil($total / $perpage);
        /* calculate pagination */
        if ($numpages == 1) {
            $pagination = '';
        } else {
            $smarty->assign('numpages', $numpages);
            $smarty->assign('pageurl', $pageprefix . self::_getPrefix());
            $pagination = $smarty->fetch('pagination.tpl');
        }
        $smarty->assign('pagination',$pagination);

        /* clear the meta description to avoid duplicate content issues */
         $content['metadescription'] = '';

        /* get product content and assign to Smarty */
        $productawards = array_slice($productawards, $start, $perpage);
        $smarty->assign('productawards', $productawards);

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
