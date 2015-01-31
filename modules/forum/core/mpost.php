<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Форум</a>
            <a href="#" class="btn btn-default disabled">Мои посты</a>
        </div>';

    $mcount = $db->get_array($db->query( "SELECT COUNT(*) FROM `forum_post` WHERE `id_user` = '".$id_user."'" ));

		echo '<div class="mainname">Сообщения от: '.$users['nick'].'&nbsp;('.$mcount[0].')</div>';
		
		    $newlist = new Navigation($mcount[0],10,true); 
		    if($mcount[0] > 0) {
		    $post = $db->query( "SELECT * FROM `forum_post` WHERE `id_user` = '".$id_user."' ORDER BY `id` DESC ". $newlist->limit()."" );
		    }else {
			    echo engine::error('Моих постов не найдено');
				echo '</div>';
				exit;
			}
			while($mpost = $db->get_array($post)) {
                            $topic = $db->get_array($db->query( "SELECT * FROM `forum_topics` WHERE `id` = '{$mpost['id_top']}'" ));
			    
		            echo  '<div class="posts_gl">';			
				echo  '<table cellspacing="0" callpadding="0" width="100%"><tr>';	
				echo  '<td class="icons"><img src="/engine/template/icons/fol_txt.png"></td>';
				echo  '<td class="name" colspan="10"><b>&nbsp;Тема: <a href="post.php?id='.$topic['id'].'">'.engine::ucfirst($topic['name']).'</a></b></td>
				    </tr><tr><td class="content" colspan="10">'.engine::input_text($mpost['text']).'</td></tr></table>';
			    echo '</div>';
					
			}
		
		
		echo $newlist->pagination('do=mpost');
		
	
	