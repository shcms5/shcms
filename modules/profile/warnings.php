<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
//Модуль: Предупреждение
    switch($do): //&do=параметр[name]

        default:   //По умолчанию
            //Выводим id пользователя выбранного
		    $id_warning = intval($id);
			
                //Если параметр $id_warning пуст то передаст ошибку
				if(empty($id_warning)) {
                    echo engine::error(Lang::__('Вы неправильно зашли на страницу перезайдите пожалуйста!'));
                    echo engine::home(array('Назад','../'));
                    exit;
                }
				    //выводим данные по $id пользователя
                    $warning = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".intval($id_warning)."'"));
                            //НиК предупрежденного
                            echo '<div class="mainname">'.Lang::__('Предупреждения').': '.$warning['nick'].'</div>';
                            echo '<div class="mainpost">'; //___1
							//И сколько баллов получил предупрежденный за нарушение
                            echo '<div style="padding-bottom:10px;" class="subpost">'.$warning['war_balls'].'&nbsp;'.Lang::__('баллов предупреждения');
            //Если id_user есть права администратора то он может добавить предупреждение
            if($user_group == 15) { //___ Param [15]
                echo'<span class="time">
                    <a title="'.Lang::__('Предупреждаем пользователя о последствиях').'" class="Button_secondary"href="?act=warnings&id='.$id_warning.'&do=add">'.Lang::__('Предупредить').'</a>							
                    </span>';
            }
                            echo '</div>';
            //Таблица со всема предупреждениями
            echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
                echo "<tr>
				<th>".Lang::__('Дата')." </th> 
                    <th>".Lang::__('Причина')."</th> 
                    <th>".Lang::__('Баллов')."</th>  					 
				</tr>";
		//Выводим все действующие разделы нарушений			
		$reas = $db->query("SELECT * FROM `warnings_list` WHERE `id_user` = '".$id_warning."'");
			while($reason = $db->get_array($reas)) {
				echo '<tr class="even">';
                    echo '<td>'.$tdate->make_date($reason['time']).'</td>'; 
							$wars_list = $db->query("SELECT * FROM `warnings` WHERE `id` = '".$reason['type']."'");
								while($war_list = $db->get_array($wars_list)){
									echo '<td>'.$war_list['name'].'</td>'; 
								}
                        echo '<td>'.$reason['balls'].'</td>'; 	
                        //Подробная информация						
                        echo '<td  style="padding: 1px;">
								<a title="'.Lang::__('Подробнее').'" class="Button_secondary"href="?act=warnings&do=more&id_war='.$reason['id'].'">'.Lang::__('Подробнее').'</a>
								</td>';									
						echo '</tr>';
			}			//____ 131
    	
		    echo "</table>";		 

        echo '</div>';
        echo engine::home(array('Назад','?id='.$id_warning.'')); //Назад
    break;
    
	//Подробная информация
    case 'more':
        
		//ID по выводу предупреждений
        $id_war = intval($_GET['id_war']); 
		//Получение данных по id
        $war = $db->get_array($db->query("SELECT * FROM `warnings_list` WHERE `id` = '".intval($id_war)."'"));
		//Получаем все действующие параметры предупреждений
        $wars_list = $db->get_array($db->query("SELECT * FROM `warnings` WHERE `id` = '".$war['type']."'"));
		//Ник предупредивщегося
        $nick = user::users($war['id_user'],array('nick'));  //___ 123
		//Баллы нарушений
        $balls_war = user::users($war['id_user'],array('war_balls')); // ___ +121
		//ID администратора
        $nick_admin = user::users($war['id_admin'],array('nick')); //___ : 112
            
			//Полученные детали выводим
            echo '<div class="mainname">'.Lang::__('Детали').'</div>';
            echo '<div class="mainpost">';
            echo 'Предупреждение <b>'.$nick.'</b> от администратора <font color="red">'.$nick_admin.'</font>, причина: '.$wars_list['name'].' 
                Выдано '.$balls_war.' баллов';
            echo '</div>';
            echo engine::home(array('Назад','?act=warnings&id='.$war['id_user'].''));
    break;

	//Новое предупреждение
	case 'add':
	     //ID пользователя которого нужно предупредить о нарушение
        $id_warning = intval($_GET['id_warning']);
        $warning = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".intval($id_warning)."'"));
            //Если авторизованные является администратором то можно добавить нарушение
            if($user_group != 15) {
                header('Location: ?act=warnings&id_warning='.$id.'');
            }

        echo '<div class="mainname">'.Lang::__('Предупредить').'&nbsp;'.$warning['nick'].'</div>';
        echo '<div class="mainpost">';

            if(isset($_POST['submit_exit'])) {
		        $war_form = intval($_POST['type']);
			    $balls = intval($_POST['balls']);

				    if($balls == 0) {
					    echo engine::error(Lang::__('Баллов должно быть не меньше 1'));
						echo engine::home(array('Назад','?act=warnings&id='.$id_warning.'&do=add'));	
						exit;
					}
						$text = engine::input_text($_POST['pr_user']);
							
				$ok_submit = $db->query("INSERT INTO `warnings_list` (`type`,`pr_user`,`balls`,`time`,`id_user`,`id_admin`) VALUES ('".$war_form."','".$db->safesql($text)."','".intval($balls)."','".time()."','".intval($id_warning)."','".$id_user."')");
							
					if(isset($ok_submit)) {
						echo engine::success(Lang::__('Вы успешно предупредили пользователя'));
							$user_war = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".intval($id_warning)."'"));
						    $db->query("UPDATE `users` SET `war_balls` = '".($user_war['war_balls']+$balls)."'`warnings` = '".($user_war['warning']+1)."' WHERE `id` = '".intval($id_warning)."'");
						echo '</div>';
						echo engine::home(array('Назад','?act=warnings&id_warning='.$id_warning.''));	
						exit;
					}else {
						echo engine::error(Lang::__('Возникли ошибки при предупреждении'));
						echo '</div>';
						echo engine::home(array('Назад','?act=warnings&id='.$id_warning.'&do=add'));	
						exit;								
					}
			}	
			
            $form = new form('?act=warnings&id='.$id_warning.'&do=add');
			$form->select2(Lang::__('Причина:'),'type','*','warnings','id','name');
			$form->input2(Lang::__('Баллов'),'balls','text',2,'readonly','<img title="'.Lang::__('Баллы нужны для автоматического назначения соответствующего наказания.').'" src="../engine/template/icons/help.png">');
	        $form->textarea(Lang::__('Пояснение для пользователя'),'pr_user');
	        $form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Это примечание будет показано пользователю. Вы должны пояснить здесь за что выдается предупреждение.').' </div>');
			$form->text('</div><div class="submit">');
	        $form->submit(Lang::__('Предупредить'),'submit_exit');
	        $form->text('или <span class="cancel"><a href="?act=warnings&id='.$id_warning.'">'.Lang::__('Отменить').'</a></span></div>');
	        $form->display();		    
		
        echo '</div>';
    break;
        
		endswitch;