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

$_provides['pluginClasses'] = array(
        'jojo_plugin_jojo_cart_product_award' => 'Products - Awards Listing',
        );

/* Register URI patterns */

$languages = Jojo::selectQuery("SELECT languageid FROM {language} WHERE active = 'yes'");
if (Jojo::getOption('product_enable_categories', 'no') == 'yes') $categories = Jojo::selectQuery("SELECT productcategoryid FROM {productcategory}");


foreach ($languages as $k => $v){
    $language = !empty($languages[$k]['languageid']) ? $languages[$k]['languageid'] : Jojo::getOption('multilanguage-default', 'en');
    $prefix = jojo_plugin_jojo_cart_product_award::_getPrefix('productaward', $language );
    if (empty($prefix)) continue;
    Jojo::registerURI("$prefix/p[pagenum:([0-9]+)]",   'jojo_plugin_jojo_cart_product_award'); // "productawards/p2/" for pagination of product awards
}

$_options[] = array(
    'id'          => 'productaward_perpage',
    'category'    => 'Products',
    'label'       => 'Products Awards per page on index',
    'description' => 'The number of products awards/reviews to show on the Awards index page before paginating',
    'type'        => 'integer',
    'default'     => '40',
    'options'     => '',
    'plugin'      => 'jojo_cart_product_award'
);

$_options[] = array(
    'id'          => 'productaward_num_sidebar',
    'category'    => 'Products',
    'label'       => 'Number of award/review teasers to show in the sidebar',
    'description' => 'The number of product awards/reviews to be displayed as snippets in a teaser box on other pages)',
    'type'        => 'integer',
    'default'     => '6',
    'options'     => '',
    'plugin'      => 'jojo_cart_product_award'
);

$_options[] = array(
    'id'          => 'productaward_currentonly',
    'category'    => 'Products',
    'label'       => 'Current Product Awards only',
    'description' => 'Limit awards/reviews on index page to those for currently available products only.',
    'type'        => 'radio',
    'default'     => 'yes',
    'options'     => 'yes,no'
);

