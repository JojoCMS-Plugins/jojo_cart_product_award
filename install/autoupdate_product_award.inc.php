<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2008 Jojo CMS
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Michael Cochrane <mikec@jojocms.org>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

if (!defined('_MULTILANGUAGE')) {
    define('_MULTILANGUAGE', Jojo::getOption('multilanguage', 'no') == 'yes');
}


/* Product Reviews */

$table = 'product_award';
$o = 0;


$default_td[$table] = array(
        'td_name' => "product_award",
        'td_displayname' => "Review",
        'td_primarykey' => "productawardid",
        'td_displayfield' => "pa_name",
        'td_categorytable' => "product",
        'td_categoryfield' => "productid",
        'td_filter' => "yes",
        'td_orderbyfields' => "pa_date",
        'td_topsubmit' => "yes",
        'td_deleteoption' => "yes",
        'td_menutype' => "tree",
        'td_help' => "Reviews and Awards are managed from here. Depending on the exact configuration, the most recent 5 reviews may be shown on the homepage or sidebar, or they may be listed only on the reviews page. The system will comfortably take many hundreds of reviews, but you may want to manually delete anything that is no longer relevant, or correct.",
        'td_golivefield' => "pa_livedate",
        'td_expiryfield' => "pa_expirydate",
    );



/* Content Tab */

// Productid Field
$default_fd[$table]['productawardid'] = array(
        'fd_name' => "ID",
        'fd_type' => "readonly",
        'fd_help' => "A unique ID, automatically assigned by the system",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
        'fd_mode' => "advanced",
    );

// Product Field
$default_fd[$table]['productid'] = array(
        'fd_name' => "Product",
        'fd_type' => "dblist",
        'fd_options' => "product",
        'fd_help' => "The product this award is for.",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
        'fd_mode' => "advanced",
    );

// title Field
$default_fd[$table]['pa_name'] = array(
        'fd_name' => "Title",
        'fd_type' => "text",
        'fd_required' => "yes",
        'fd_size' => "50",
        'fd_help' => "Award title.",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
    );


// Rating Field
$default_fd[$table]['pa_rating'] = array(
        'fd_name' => "Rating (out of 5)",
        'fd_type' => "decimal",
        'fd_required' => "no",
        'fd_size' => "2",
        'fd_help' => "Star rating",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
    );


// Full Description Field
$default_fd[$table]['pa_body_code'] = array(
        'fd_name' => "Full Description",
        'fd_type' => "texteditor",
        'fd_options' => "pa_body",
        'fd_rows' => "10",
        'fd_cols' => "50",
        'fd_help' => "The award/review description",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
    );

// Body Field
$default_fd[$table]['pa_body'] = array(
        'fd_name' => "Body",
        'fd_type' => "hidden",
        'fd_rows' => "10",
        'fd_cols' => "50",
        'fd_help' => "The award/review description",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
        'fd_mode' => "advanced",
    );


// Image Field
$default_fd[$table]['pa_image'] = array(
        'fd_name' => "Image",
        'fd_type' => "fileupload",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
        'fd_mode' => "standard",
    );

// Date Field
$default_fd[$table]['pa_date'] = array(
        'fd_name' => "Date",
        'fd_type' => "date",
        'fd_default' => "NOW()",
        'fd_help' => "Date the product was published (defaults to Today)",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
        'fd_mode' => "standard",
    );

// Language Field
$default_fd[$table]['pa_language'] = array(
        'fd_name' => "Language",
        'fd_type' => "dblist",
        'fd_options' => "language",
        'fd_default' => "en",
        'fd_size' => "20",
        'fd_help' => "The language section this product will appear in. Only used in multilanguage sites.",
        'fd_order' => $o++,
        'fd_tabname' => "Content",
        'fd_mode' => "advanced",
    );



/* Scheduling Tab */

// Go Live Date Field
$default_fd[$table]['pa_livedate'] = array(
        'fd_name' => "Go Live Date",
        'fd_type' => "unixdate",
        'fd_default' => "NOW()",
        'fd_help' => "The award will not appear on the site until this date",
        'fd_order' => "1",
        'fd_tabname' => "Scheduling",
        'fd_mode' => "standard",
    );

// Expiry Date Field
$default_fd[$table]['pa_expirydate'] = array(
        'fd_name' => "Expiry Date",
        'fd_type' => "unixdate",
        'fd_default' => "NOW()",
        'fd_help' => "The award will be removed from view after this date",
        'fd_order' => "2",
        'fd_tabname' => "Scheduling",
        'fd_mode' => "standard",
    );
