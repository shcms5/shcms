<?
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
$templates->template('Покинуть страницу');

setcookie('user_id_shcms', '');
setcookie('password_shcms', '');
session_destroy();
header('Location: ../index.php');

?>