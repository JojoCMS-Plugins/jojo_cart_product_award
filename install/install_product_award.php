<?php

$table = 'product_award';
$query = "
    CREATE TABLE {product_award} (
        `productawardid` int(11) NOT NULL auto_increment,
        `pa_name` varchar(255) NOT NULL default '',
        `pa_rating` decimal(10,1) default '0',
        `pa_body` text NULL,
        `pa_body_code` text NULL,
        `pa_image` varchar(255) NOT NULL,
        `pa_language` varchar(100) NOT NULL default 'en',
        `pa_date` date default NULL,
        `pa_livedate` int(11) NOT NULL default '0',
        `pa_expirydate` int(11) NOT NULL default '0',
        `productid` int(11) NOT NULL default '0',
         PRIMARY KEY  (`productawardid`),
         FULLTEXT KEY `title` (`pa_name`),
         FULLTEXT KEY `body` (`pa_name`,`pa_body`)
    ) TYPE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_general_ci ;";

/* Check table structure */
$result = Jojo::checkTable($table, $query);

/* Output result */
if (isset($result['created'])) {
    echo sprintf("jojo_cart_products_wine_awards: Table <b>%s</b> Does not exist - created empty table.<br />", $table);
}

if (isset($result['added'])) {
    foreach ($result['added'] as $col => $v) {
        echo sprintf("jojo_cart_products_wine_awards: Table <b>%s</b> column <b>%s</b> Does not exist - added.<br />", $table, $col);
    }
}

if (isset($result['different'])) Jojo::printTableDifference($table,$result['different']);


