<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

/*
 * ��������� ����������� �������
 */
$loader = new SplLoader('forum', H.'modules');
//����������� �������
$register = $loader->register();