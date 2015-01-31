<?php
switch($act):
    default:
        
    echo '<div class="panel panel-default">
            <a href="#widget1container" class="panel-heading" data-toggle="collapse">'.Lang::__('Что такое SHCMS?').'</a>
            <div id="widget1container" class="panel-body collapse in">
                <p><b>SHCMS Engine</b> является многофункциональной  системой управления контентом (CMS),</p>
                <p>которая позволяет создавать веб(вап)-сайты и онлайн мощных приложений. </p>
                <p>Многие аспекты, в том числе простота использования и расширяемость,</p>
                <p>сделали SHCMS самым функциональным программным обеспечением.</p>
                <p>Лучше всего, <b>SHCMS Engine</b> является открытым исходным кодом</p>
                <div class="row"></div>
                <center>'.$sversion->copyright.'</center>
        </div></div>';   	
	
    break;
        
endswitch;
