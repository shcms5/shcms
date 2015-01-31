<?php
/**
 * Работа с Группами пользователей
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
class group {

    /**
     * Получение прав администратора
     * 
     * @param $group Нумерное права (1 или 15)
     */
    public function setAdmin($group = false) {
        global $user,$id_user;

        //Если у пользователя права меньше 15 ему ничего не выводит
        if($group != 15) {
            $user_group =  false;
	    //Если у пользователя права 15 то ему дается полный доступ
        }else {
            $user_group .= $user->users($id_user,array('group'));
        }

    return $user_group;
    }
	

    /**
     * Получает определенное название по назначенной группе
     * 
     * @param $group Группы пользователей (1 или 15)
     */
    public function group_profile($group) {		
		if($group == 15) {
		    echo '<font color="red">'.Lang::__('Администраторы').'</font>';
		}elseif($group == 1) {
		    echo Lang::__('Пользователи');		
		}else {
		    echo Lang::__('Не определено');
		}
	}
    /**
     * Получает определенное название по назначенной группе
     * 
     * @param $groups Группы пользователей (1 или 15)
     */
    public function user_group($groups) {		
		if($groups == 15) {
		    $group = '<font color="red">'.Lang::__('Администраторы').'</font>';
		}elseif($groups == 1) {
		    $group .= '<font color="green">'.Lang::__('Пользователи').'</font>';		
		}else {
		    $group .= Lang::__('Не определено');
		}
            return $group;    
	}        

}
$groups = new group;