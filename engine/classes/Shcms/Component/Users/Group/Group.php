<?php

namespace Shcms\Component\Users\Group;

class Group {
    
    /**
     * Получение прав администратора
     * 
     * @param $group Нумерное права (1 или 15)
     */
    public static function Groups($group = false) {
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
}
