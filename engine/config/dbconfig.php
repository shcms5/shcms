<?php

define ("DBHOST", "localhost"); 
define ("DBNAME", "shcms");
define ("DBUSER", "shcms");
define ("DBPASS", "111222");  
define ("COLLATE", "utf8");
define ("SHCMS_PROJECT", "SHCMS Engine (version: 5.x)");

$db = new Shcms\Options\Database\MYSQLi\MYSQLi;