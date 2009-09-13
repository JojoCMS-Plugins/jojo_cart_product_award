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

/* Awards */
$data = Jojo::selectQuery("SELECT * FROM {page}  WHERE pg_link='jojo_plugin_jojo_cart_product_award'");
if (!count($data)) {
    echo "jojo_plugin_jojo_cart_product_award: Adding <b>Awards & Reviews</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Awards & Reviews', pg_link='jojo_plugin_jojo_cart_product_award', pg_url='reviews'");
}

/* Edit Awards */
$data = Jojo::selectQuery("SELECT * FROM {page}  WHERE pg_url='admin/edit/product_award'");
if (!count($data)) {
    echo "jojo_cart_product_award: Adding <b>Product Awards</b> Page to Edit Content menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Awards/Reviews', pg_link='jojo_plugin_Admin_Edit', pg_url='admin/edit/product_award', pg_parent=?, pg_order=3", array($_ADMIN_CONTENT_ID));
}
