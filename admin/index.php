<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');

$templates->template('SHCMS: Система');
echo engine::admin($users['group']);

if($users['group'] == 15) {
   header('Location: ../admin.php');
   exit;
}