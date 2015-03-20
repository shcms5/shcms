<?php
namespace Shcms\Component\Users\User;

class User {
    
   public $id = 0;
   
   protected $_id = 0;

    public function __construct($id = 0) {
        $this->id = $this->_id = (int) $id;
    }
    
    public function Icons($id) {
        global $dbase;
        
        $icons = $dbase->query("SELECT * FROM `users` WHERE `id` = ? ",[$id]);
        $icon = $icons->fetchArray();
        
        if($icon['pol'] == 1) {
            echo '<img style="margin-top:-4px;" src="/engine/template/icons/user/1.png">';
        }
        elseif($icon['pol'] == 2) {
            echo '<img style="margin-top:-4px;" src="/engine/template/icons/user/2.png">';
        }
        elseif($icon['pol'] == 3) {
            echo '<img style="margin-top:-4px;" src="/engine/template/icons/user/3.png">';
        }
    }

    public function Views($param = [],$id = false) {
        global $dbase;
            if($id == false) {
                $id = $this->id;
            }
             
            $params = \implode(",", $param);
                $users = $dbase->query("SELECT {$params} FROM `users` WHERE `id` = ?",[$id]);
                $user = $users->fetchArray();
                
            return $user;     
    }
    
    public function UserLogin($login) {
        global $db;
        
            $res = $db->query("SELECT * FROM `users` WHERE `nick` = '".$db->safesql($login)."' LIMIT 1");
            if($db->num_rows($res) == 0) {
                throw new \Exception('Пользователь не найден');
            }else {
            $data = $db->get_array($res);
            }
        return $data;
    }
}
