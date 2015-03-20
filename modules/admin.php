<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');

$templates->template('SHCMS: Система - Главная');
echo engine::admin($users['group']);

header("Location: ../admin.php");
exit;
