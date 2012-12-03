<?php
/*********************************************************************
    config.php

    Static BugBase configuration file. Mainly useful for mysql login info.
    Created during installation process and shouldn't change even on upgrades.
   
    Ross Norvell <ross@rnorvell.com>
    Copyright (c)  2012 rnorvell
    http://www.rnorvell.com
	
**********************************************************************/


# Encrypt/Decrypt secret key - randomly generated during installation.
define('SECRET_SALT','876E6491E7DC07E');

#Default admin email. Used only on db connection issues and related alerts.
define('ADMIN_EMAIL','ross@rnorvell.com');

#Mysql Login info
define('DBTYPE','mysql');//my programming assumes this, but in the future it may change
define('DB_HOST','localhost'); 
define('DB_NAME','db_name');//test database...whoop whoop don't fuck up this time Ross
define('DB_USER','db_user');
define('DB_PASS','db_pass');

#Table prefix
define('TABLE_PREFIX','ost_');

//this is a jery rig for take over
define('ALT_ATTACHMENTS','../support_dev/Attachments');


?>
