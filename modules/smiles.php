<?
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
         
    //Название страницы
    $templates->template(Lang::__('Список пользователей'));
	
	
	echo '<div class="mainname">'.Lang::__('Все смайлики').'<span class="time"><a href="/modules/chat/">Назад</a></span></div>';
	echo '<div class="mainpost">';	     
	$pak = file(H.'engine/template/smilies/smiles.pak');
	$smiles = array();
	echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
                            echo "<tr>
				                    <th>".Lang::__('Смайлик')." </th> 
                                    <th>".Lang::__('Значение')."</th> 
				                </tr>";					
	            foreach ( $pak as $val ) {
                $val = trim($val);
                    if (! $val || '#' == $val{0}) { continue; }
                        list($gif,$alt,$symbol) = explode('=+:',$val);
                       $smiles[$symbol] = '<tr class="even"><td><img src="/engine/template/smilies/'.htmlspecialchars($gif).'"/></td>';
					   $smiles[$symbol] .= '<td>'.htmlspecialchars($symbol).'</td></tr>';
					   echo $smiles[$symbol];
            }
    	                echo "</table>";	

	echo '</div>';
?>