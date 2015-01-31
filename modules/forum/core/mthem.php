<?PHP
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Форум</a>
            <a href="#" class="btn btn-default disabled">Мои темы</a>
        </div>';

    $mcount = $db->get_array($db->query( "SELECT COUNT(*) FROM `forum_topics` WHERE `id_user` = '".$id_user."'" ));

		echo '<div class="mainname">Темы, созданные: '.$users['nick'].'&nbsp;('.$mcount[0].')</div>';
		
		    $newlist = new Navigation($mcount[0],10,true); 
		    if($mcount[0] > 0) {
		        $them = $db->query( "SELECT * FROM `forum_topics` WHERE `id_user` = '".$id_user."' ORDER BY `id` DESC ". $newlist->limit()."" );
		    }else {
			    echo engine::error('Моих тем не найдено');
				echo '</div>';
				exit;
			}
			while($mthem = $db->get_array($them)) {
			    $otvet = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$mthem['id']."'"));
		            
			    echo  '<div class="posts_gl">';			
				echo  '<table cellspacing="0" callpadding="0" width="100%"><tr>';	
				echo  '<td class="icons"><img src="/engine/template/icons/fol_txt.png"></td>';
				echo  '<td class="name" colspan="10"><b>&nbsp;<a href="post.php?id='.$mthem['id'].'">'.engine::ucfirst($mthem['name']).'</a></b>
					<span class="time">Ответов: <b>'.$otvet[0].'</b></span></td>
					</tr><tr><td class="content" colspan="10">'.engine::input_text($mthem['text']).'</td></tr></table>';
				echo '</div>';
					
			}
		
		
		echo $newlist->pagination('do=mthem');