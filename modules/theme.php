<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
//Если не авторизован то FALSE
if($id_user == false) {
    header("Location: ../index.php"); 
    exit;
}       
$templates->template(Lang::__('Темы Оформление')); //Название страницы

use Shcms\Component\Provider\Json\JsonScript;
$jsonl = new JsonScript;


//Определяем количество Шаблонов
$cdir = glob(H.'/templates/*', GLOB_ONLYDIR );
//Заносим в $number полученные данные
$number = 'Всего: '.engine::number(count($cdir));

//Данные о Щаблонах
echo '<div class="mainname">'.Lang::__('Выборка Шаблонов').'<span class="right">'.$number.'</span></div>';

        //Данные по устройству вашему web wap
	$detect = new Shcms\Component\Provider\MobileDetect\MobileDetect();
	// Получение данных по web wap темы
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	//Обработка темы
        //Обработка кнопки отмена
        $exit = filter_input(INPUT_POST,'exit',FILTER_DEFAULT);
        //Обработка кнопки установить
        $submit = filter_input(INPUT_POST,'submit',FILTER_DEFAULT);
        //Обработка полученной темы
        $themes = filter_input(INPUT_GET,'theme',FILTER_SANITIZE_STRING);
        
	//Если в $ есть данных то передаем их
	if($themes == true) { 
            //Если нажата Exit то выходим
	    if($exit == true) {
		header('Location: theme.php');
		exit;
	    }
            
            //Выводим данные шаблона
            $string = file_get_contents(H.'templates/'.$themes.'/theme.json');
            $json = $jsonl->decode($string);
            
            //Подтвержаем установку шаблона
	    if($submit == false) {
                echo '<div class="mainpost">';
		echo engine::info('Вы действительно хотите установить тему <b>'.$json['template_info']['name'].' | '.$json['template_info']['type'].'</b>');
		echo '<div class="modal-footer">';
                    //Форма HTML
                    $form = new form('?theme='.$themes);		
		    $form->submit('Установить','submit',false,'btn btn-success');
                    $form->submit('Нет отменить','exit',true,'btn btn-danger');		
                    $form->display();
		                    
                echo '</div></div>';

            }elseif($submit == true) {
		if($deviceType == 'computer') {
		    header('Location: /modules/theme.php');
                    $db->query("UPDATE `users` SET `web_template` = '".$db->safesql($themes)."' WHERE `id` = '".intval($id_user)."'");
		    echo engine::home(array('Назад','/modules/theme.php')); //Переадресация								
	        }elseif($deviceType == 'phone') {
		    header('Location: /modules/theme.php');
                    $db->query("UPDATE `users` SET `wap_template` = '".$db->safesql($themes)."' WHERE `id` = '".intval($id_user)."'");	
		    echo engine::home(array('Назад','/modules/theme.php')); //Переадресация									
		}else {
		    header('Location: /modules/theme.php');
		    $db->query("UPDATE `users` SET `wap_template` = '".$db->safesql($themes)."' WHERE `id` = '".intval($id_user)."'");	
		    echo engine::home(array('Назад','/modules/theme.php')); //Переадресация								
		}
            }
	}else {
            //Получаем все шаблоны из папки
            $dir_them = opendir('../templates/');
            //Выводим все найденые шаблоны        
            while ($des_them = readdir( $dir_them)) {
                if (($des_them != '.') && ($des_them != '..' ) && is_dir(H.'templates/'.$des_them)) {
                    
                    //Выводим данные по шаблоны
                    $string = file_get_contents(H.'templates/'.$des_them.'/theme.json');
                    $json = $jsonl->decode($string);
                    
                    //Если в шаблоне присутствует theme.json
                    // то выводим его параметры
                    if(!empty($string)) {
                        
		     echo '<table class="posts_gl"><tbody><tr class="">';
                     
                        //Если тема установлена то DISABLED иначе даем путь к установке
		        if($deviceType == 'computer') {
                            //Для WEB пользователей
			    if($users['web_template'] == $des_them) {
                                echo '<td class="c_icon"><img src="../templates/'.$des_them.'/'.$json['template_info']['icon'].'"></td>';
				echo '<td class="c_forum">'.$json['template_info']['name'];
                                echo ' | <span class="text-success"><b>'.$json['template_info']['type'].'</b></span>';
                                echo '<p class="text-primary font-size-1">';
				echo 'Автор: '.$json['template_info']['author'].' | Версия темы: '.$json['template_info']['version'].'';
		                echo '</p></td>';
                                echo '<td class="c_stats width-1">';
                                echo '<button class="btn btn-warning disabled">Уже установлено</button>';
                                echo '</td>';
                            }else {
                                echo '<td class="c_icon"><img src="../templates/'.$des_them.'/'.$json['template_info']['icon'].'"></td>';
				echo '<td class="c_forum">'.$json['template_info']['name'];
                                echo ' | <span class="text-success"><b>'.$json['template_info']['type'].'</b></span>';
                                echo '<p class="text-primary font-size-1">';
 				echo 'Автор: '.$json['template_info']['author'].' | Версия темы: '.$json['template_info']['version'].' ';
		                echo '</p></td>';
                                echo '<td class="c_stats width-1">';
                                echo '<a class="btn btn-success" href="?theme='.$des_them.'">Установить</a>';
                                echo '</td>';
			    }
                            
			}elseif($deviceType == 'phone') {
                            //Для Мобильных Пользователей
			    if($users['wap_template'] == $des_them) {
				echo '<td class="c_icon"><img src="../templates/'.$des_them.'/'.$json['template_info']['icon'].'"></td>';
				echo '<td class="c_forum">'.$json['template_info']['name'];
                                echo ' | <span class="text-success"><b>'.$json['template_info']['type'].'</b></span>';
                                echo '<p class="text-primary font-size-1">';
				echo 'Автор: '.$json['template_info']['author'].' | Версия темы: '.$json['template_info']['version'].' ';
		                echo '</p></td>';
                                echo '<td class="c_stats width-1">';
                                echo '<button class="btn btn-warning disabled">Уже установлено</button>';
                                echo '</td>';
			    }else {
                                echo '<td class="c_icon"><img src="../templates/'.$des_them.'/'.$file['info']['icon'].'"></td>';
				echo '<td class="c_forum">'.$json['template_info']['name'];
                                echo ' | <span class="text-success"><b>'.$json['template_info']['type'].'</b></span>';
                                echo '<p class="text-primary font-size-1">';
				echo 'Автор: '.$json['template_info']['author'].' | Версия темы: '.$json['template_info']['version'].' ';
		                echo '</p></td>';
                                echo '<td class="c_stats width-1">';
                                echo '<a class="btn btn-success" href="?theme='.$des_them.'">Установить</a>';
                                echo '</td>';
			    }		
                            
			}else {
                            //И для пользователей Другий Аппатаров
			    if($users['wap_template'] == $des_them) {
				echo '<td class="c_icon"><img src="../templates/'.$des_them.'/'.$json['template_info']['icon'].'"></td>';
				echo '<td class="c_forum">'.$json['template_info']['name'];
                                echo ' | <span class="text-success"><b>'.$json['template_info']['type'].'</b></span>';
                                echo '<p class="text-primary font-size-1">';
				echo 'Автор: '.$json['template_info']['author'].' | Версия темы: '.$json['template_info']['version'].'';
		                echo '</p></td>';
                                echo '<td class="c_stats width-1">';
                                echo '<button class="btn btn-warning disabled">Уже установлено</button>';
                                echo '</td>';
			    }else {
                                echo '<td class="c_icon"><img src="../templates/'.$des_them.'/'.$file['info']['icon'].'"></td>';
				echo '<td class="c_forum">'.$json['template_info']['name'];
                                echo ' | <span class="text-success"><b>'.$json['template_info']['type'].'</b></span>';
                                echo '<p class="text-primary font-size-1">';
				echo 'Автор: '.$json['template_info']['author'].' | Версия темы: '.$json['template_info']['version'].'';
		                echo '</p></td>';
                                echo '<td class="c_stats width-1">';
                                echo '<a class="btn btn-success" href="?theme='.$des_them.'">Установить</a>';
                                echo '</td>';
			    }
                        }    
                                echo '</tr></tbody></table>';
                        }
                    }    
                }
                closedir($dir_them); // Close
            }   
            
//Переадресация
echo engine::home(array('Назад','/modules/menu.php'));