<?php
namespace Shcms\Component\Users\User;

class User {
    
   public $id = 0;
   
   protected $_id = 0;

    public function __construct($id = 0) {
        $this->id = $this->_id = (int) $id;
    }

    public function Views($param = array(),$id = false) {
        global $db;
            if($id == false) {
                $id = $this->id;
            }
             $params = \implode(",", $param);
                $user = $db->get_array($db->query("SELECT {$params} FROM `users` WHERE `id` = '{$id}'"));
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
