<?php

$table = 'product_award';
$query = "
    CREATE TABLE {product_award} (
        `productawardid` int(11) NOT NULL auto_increment,
        `pa_name` varchar(255) NOT NULL default '',
        `pa_rating` decimal(10,1) default '0.0',
        `pa_body` text NULL,
        `pa_body_code` text NULL,
        `pa_author` varchar(100) NOT NULL default '',
        `pa_organisation` varchar(100) NOT NULL default '',
        `pa_organisationlink` varchar(100) NOT NULL default '',
        `pa_date` varchar(100) NOT NULL default '',
        `pa_image` varchar(255) NOT NULL,
        `pa_language` varchar(100) NOT NULL default 'en',
        `pa_dateadded` int(11) NOT NULL default '0',
        `pa_livedate` int(11) NOT NULL default '0',
        `pa_expirydate` int(11) NOT NULL default '0',
        `productid` int(11) NOT NULL default '0',
         PRIMARY KEY  (`productawardid`)
    ) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci ;";

/* Convert mysql date format to unix timestamps and copy existing dates to */
if (Jojo::tableExists($table) && Jojo::getMySQLType($table, 'pa_date') == 'date') {
    date_default_timezone_set(Jojo::getOption('sitetimezone', 'Pacific/Auckland'));
    $data = Jojo::selectQuery("SELECT productawardid, pa_date FROM {product_award}");
    Jojo::structureQuery("ALTER TABLE  {product_award} CHANGE  `pa_date`  `pa_date` VARCHAR(100) NOT NULL default ''");
    foreach ($data as $k => $a) {
        if ($a['pa_date']!='0000-00-00') {
            $time = strtotime($a['pa_date']);
            $time = strftime('%e %b %Y', $time);
       } else {
            $time = '';
        }
       Jojo::updateQuery("UPDATE {product_award} SET pa_date=? WHERE productawardid=?", array($time, $a['productawardid']));
    }
}
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


