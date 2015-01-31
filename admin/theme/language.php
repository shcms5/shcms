<?php

        $ini = new iniFile(H."/engine/language/Ru/locale.ini");
        $file = $ini->read();

		echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
                echo "<tr>
		    <th>".Lang::__('Название')." </th> 
                    <th>".Lang::__('Локаль')."</th>
 		    <th>".Lang::__('Формат даты')."</th>
		    <th>".Lang::__('По умолчанию')."</th>
		</tr>";
					
                echo '<tr class="even">';
                    echo '<td>'.$file['russian']['name'].'</td>';	
                    echo '<td>'.$file['russian']['locale'].'</td>';	
                    echo '<td>'.$file['russian']['date'].'</td>';	
		    echo '<td><img src="../icons/theme/tick.png"></td>';	
		
                echo '</tr>';		
    	echo "</table>";	

 echo engine::home(array(Lang::__('Назад'),'index.php'));