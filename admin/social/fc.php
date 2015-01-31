<?php
switch($do):
    
    default:
        
        echo '<div class="panel panel-default">
            <a class="panel-heading" data-toggle="collapse">'.Lang::__('Настроить авторизацию через Facebook').'</a>
            <div class="panel-body collapse in">';
        
        $submit = filter_input(INPUT_POST,'submit',FILTER_DEFAULT);
        
        if(isset($submit)) {
            
            $close = filter_input(INPUT_POST,'close',FILTER_SANITIZE_NUMBER_INT);
            $close = intval($close);
            
            $fc_id = filter_input(INPUT_POST,'fc_id',FILTER_SANITIZE_NUMBER_INT);
            
            $fc_key = filter_input(INPUT_POST,'fc_key',FILTER_SANITIZE_STRING);
            
                $db->query("UPDATE `social` SET `fc_close` = '{$close}',`fc_id` = '{$fc_id}',`fc_key` = '{$fc_key}'");
                header('Location: index.php?do=fc');
            
        }
        $form = new form('?do=fc','','','class="form-horizontal"');
            //Ник при регистрации
        
            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Авторизация в Facebook').'</label>');
            $form->text('<div class="col-sm-10">');
            $form->select(false,'close',array(Lang::__('Отключить') => 1, Lang::__('Включить') => 2),$social['fc_close'],'','','','','class="form-control"');
            $form->text('</div></div>');
            
            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('ID Приложения в Вконтакте').'</label>');
            $form->text('<div class="col-sm-10">');
            $form->input2(false,'fc_id','text',$social['fc_id'],'class="form-control"','',false);
            $form->text('<span class="desc" style="color:#969a9d;">Укажите ID вашего приложения в сети Facebook</span>');
            $form->text('</div></div>');
        
            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Секретный ключ в Facebook').'</label>');
            $form->text('<div class="col-sm-10">');
            $form->input2(false,'fc_key','text',$social['fc_key'],'class="form-control"','',false);
            $form->text('<span class="desc" style="color:#969a9d;">Укажите секретный ключ вашего приложения в сети Facebook</span>');
            $form->text('</div></div>');
            
            $form->text('<div class="row"></div>');
            $form->text('<center><div class="form-actions">');
            $form->submit(Lang::__('Применить'),'submit',false,'btn btn-success');
            $form->text('<a class="btn btn-warning" href="index.php">Отмена</a>');
            $form->text('</div></center>');
        
        $form->display();
        echo '</div></div>';
    break;    
    
endswitch;

