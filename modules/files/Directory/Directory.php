<?php
/**
 * ����� ��� ������ � ��������� �������
 * 
 * @package            Files/Directory/
 * @author             Shamsik
 * @link               http://shcms.ru
 */

namespace files\Directory;

class Directory {
    
    /**
     * ��������� ��������� �������� � ������������ ������
     * 
     * @param $param [nav , menu , razdel]
     */           
    public static function Navi($param = []) {
        global $user_group,$dbase,$do,$glob_core,$app;

        //������� ��������� ��������
        if($param['nav'] == 'Nav') {
            //��������� ��������
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a disabled href="index.php" class="btn btn-default">��������</a>';
            echo '</span>';
        }elseif($param['nav'] == 'Search') {
            //��������� ��������
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="/modules/files/" class="btn btn-default">��������</a>
                <a disabled href="index.php" class="btn btn-default">����� ������</a>';
            echo '</span>';
        }elseif($param['nav'] == 'Top') {
            //��������� ��������
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="/modules/files/" class="btn btn-default">��������</a>
                <a disabled href="index.php" class="btn btn-default">��� �����������</a>';
            echo '</span>';     
        }elseif($param['nav'] == 'Popular') {
            //��������� ��������
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="/modules/files/" class="btn btn-default">��������</a>
                <a disabled href="index.php" class="btn btn-default">����������</a>';
            echo '</span>';    
        }
        
        //������������������ ���� ��� ����������
        if($param['menu'] == 'Menu') {
            if(\Shcms\Component\Users\Group\Group::Groups($user_group)) {

                switch ($do):
                    //���� �������� �� ��������
                    case 'enter':
                        $dbase->update('system_settings',[
                            'filesadmin' => ''.intval(1).''
                        ]);
                        //�������������
                        $app->redirect('/modules/files/');
                    break;    
                    //���� ������� �� ���������
                    case 'exit':
                        $dbase->update('system_settings',[
                            'filesadmin' => ''.intval(0).''
                        ]);
                        //�������������
                        $app->redirect('/modules/files/');
                    break;  
                
                endswitch;
                
                //���� ����������
                echo '<div style="margin-left:550px;margin-bottom:4px;" class="btn-group">';
                echo '<div class="dropdown">';
                echo '<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">';
                echo '����������&nbsp;<span class="caret"></span></button>';
                echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
                //���� ���������� ���������
                if($glob_core['filesadmin'] == 0) {
                    echo '<li><a role="menuitem" tabindex="-1" href="index.php?do=enter"><i class="glyphicon glyphicon-plus"></i>&nbsp;'.\Lang::__('�������� ����������').'</a></li>'; 
                }else {
                    echo '<li><a role="menuitem" tabindex="-1" href="index.php?do=exit"><i class="glyphicon glyphicon-plus"></i>&nbsp;'.\Lang::__('��������� ����������').'</a></li>'; 
                }
                //������� ����� �����
                echo '<li><a role="menuitem" tabindex="-1" href="foldernew"><i class="glyphicon glyphicon-plus"></i>&nbsp;'.\Lang::__('�������� �����').'</a></li>';                
        
                //������� � ����������� �����
                $dirs = $dbase->query("SELECT COUNT(*) as count FROM `files_dir`");
                $dir = $dirs->fetchArray();
                
                echo '</ul></div></div>';
            }
        }
        
        
        //���������������� ������� 
        if($param['razdel'] == 'Razdel') {
            echo '<div class="mainname">'.\Lang::__('�������').'</div>';
            echo '<div class="mainpost"><center>';
            //����� ������������ ������
            echo '<div class="btn-group">';
	    echo '<a class="btn btn-default" href="search">';
            echo '<i class="glyphicon glyphicon-search"></i>&nbsp;'.\Lang::__('����� ������').'</a>';
            //��� �����������
            echo '<a class="btn btn-default" href="topdownload">';
            echo '<i class="glyphicon glyphicon-globe"></i>&nbsp;'.\Lang::__('��� �����������').'</a>';
            //���������� �����
            echo '<a class="btn btn-default" href="popular">';
            echo '<i class="glyphicon glyphicon-star"></i>&nbsp;'.\Lang::__('����������').'</a>';            
            //��������� �����
            echo '<a class="btn btn-default" href="favorites">';
            echo '<i class="glyphicon glyphicon-heart"></i>&nbsp;'.\Lang::__('���������').'</a>';  
            
            echo '</div></center></div>';
        }        
    }

      
    /**
     * �������� ������ ���� �������� �����
     * 
     * @param Null
     */       
    public static function GetDirectory(){
        global $user_group,$dbase,$do,$glob_core,$app;
        
        //��������� �������� �� �����
        $row = $dbase->query("SELECT COUNT(*) as count FROM `files_dir` WHERE `dir` = ?",[\intval(0)]);
        $rows = $row->fetchArray();
        
        //���� �� ������� �����
        if($rows['count'] == 0) {
            echo \engine::warning(\Lang::__('� ������� ����� �� ����������'));
            exit;
        }
        
        //��������� ���������
         $newlist = new \Navigation($rows['count'],10, true); 
         
        //������ � ������ ��������
        $views = $dbase->query("SELECT * FROM `files_dir` WHERE `dir` = ? ". $newlist->limit()."",[\intval(0)]);
         
        //������� �������
        echo '<div class="mainname">'.\Lang::__('���������').'</div>';
        echo '<div class="mainpost">';
        echo '<table class="itable">';
        
        //�������� ������
        foreach ($views->fetchAll() as $view) {
            //������� ������
            $counts = $dbase->query("SELECT COUNT(*) as count FROM `files` WHERE `id_dir` = ? OR `idir` = ? ",[$view->id,$view->id]);
            $count = $counts->fetchArray();

                echo  '<tr class="">';
		echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/icons/dir3.png"></td>';
		echo '<td class="c_forum"><b><a style="font-size:15px;" href="'.\Shcms\Component\String\Url::filter($view->name).'-'.$view->id.'">'.$view->name.'</a></b>';
                    
                //�������� �����
		echo '<p style="margin-top:4px;" class="desc">'.$view->text.'</p></td>';
                    
                echo '<td class="c_stats"><ul>';
                
                    //���� �� ����� � � ��� �������� ���������� �� �������� �������
                    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == true AND $glob_core['filesadmin']  == 1) {
                        echo '<li style="font-size:14px;">';
                        echo '<a href="/modules/files/editor/'.\Shcms\Component\String\Url::filter($view->name).'-'.$view->id.'"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;';
                        echo '<a href="/modules/files/delete/'.\Shcms\Component\String\Url::filter($view->name).'-'.$view->id.'"><i class="glyphicon glyphicon-remove"></i></a>';
                        echo '</li>';
                    }else {
                        //����� ����������� ����������
                        echo '<li><b>'.\engine::number($count['count']).'</b> ������</li>';
                    } 
                    
                echo '</ul></td>';
                echo '</tr>';
        }
        
        echo '</table></div>';
    }

    /**
     * ������������ ���. ����������
     * 
     * @param $n 
     */     
    public static function TypeOk($n) {
        return ($n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2));
    }

    /**
     * �������� ������ �� ������ ������
     * 
     * @param Null
     */   
    public static function Search() {
        global $dbase;
        
        //���������
	echo '<div class="mainname">'.\Lang::__('����� ������').'</div>';
        echo '<div class="mainpost">';           
        
            //��������� �����
            $form = new \Shcms\Component\Form\Form();
            //�������� �����
            echo $form->open(['class' => 'navbar-form']);
            //���� ��� ������
            echo '<div class="input-group col-sm-6">';
            echo $form->input(['type' => 'search','id' => 'container-search','class' => 'form-control','name' => 'search']);
            //������ ������
            echo '<div class="input-group-btn">';
            echo $form->button(['class' => 'btn btn-default','type' => 'submit','value' => '<i class="glyphicon glyphicon-search"></i>']);
            echo '</div></div>';
            //�������� �����
            $form->close();
            echo '</div>';
            
            
            echo '<div id="searchlist" class="list-group">';
            
                $searchs = $dbase->query("SELECT * FROM `files`");
                    foreach ($searchs->fetchAll() as $search) {
                        echo '<div class="list-group-item">';
                        echo '<div class="row_3">';
                        echo '<a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($search->name).'-'.$search->id.'"><b>'.$search->name.'</b></a>';
                        echo '</div>';
                        echo '<div class="row_3">'.\engine::input_text($search->text2).'</div></div>';
                    }
            echo '</div>';        
            
            
    }

    /**
     * �������� ������ �� ���� ����������
     * 
     * @param Null
     */ 
    public static function TopDownload() {
        global $dbase;
        
        //���. �������
        $sum = $sum ? $sum : 10;
        //������ ������ �� ����������
        $alls_num = ['���','����'];
        //������ �� ����
        $count = $dbase->query("SELECT * FROM `files` WHERE `countd` > '0' ORDER BY `countd` DESC LIMIT 0, ?",[$sum]);
        //���. ������ ��������
        $i = 1;
        
        echo '<div class="mainname">'.\Lang::__('��� �����������').'</div>';
        //���� ���� ����������
        if($count->num_rows > 0) {
            //����� ��� ������
            foreach ($count->fetchAll() as $counts) {
                //��������� ��������
                $global = $alls_num[self::TypeOk($counts->countd)];
                //��������� ��������
                $name = htmlspecialchars( strip_tags( stripslashes( $counts->name ) ), ENT_QUOTES) ;
                $name = str_replace("{", "&#123;", $counts->name);
                $name = strip_tags($counts->name);
                
                //������� ���
                echo  '<div class="posts_gl">';
                //������� , �������� ����� � ����
                echo $i.').<a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($counts->name).'-'.$counts->id.'"><b>'.$name.'</b></a>';
                //���. ����������
                echo '<span class="right text-primary">'.\Lang::__('���������').': '.$counts->countd.'&nbsp;'.$global.'</span><br/>';
                //�������� �����
                echo \engine::input_text($counts->text2);
                echo  '</div>';
                $i++;
            }
        }else {
            //���� ��� ������
            echo \engine::warning(\Lang::__('�� ���� ������� ������'));
        }
    }

    
    public static function Popular() {
        global $dbase,$profi;
        
        echo '<div class="mainname">'.\Lang::__('���������� �����').'</div>';
        echo '<div class="mainpost">';
            //������ ��������
            $pfiles = $dbase->query("SELECT * FROM `files` WHERE `count` > '10' OR `countd` > '10' ORDER BY rand() LIMIT 10");
                //��������� ���� �� ���������� �����
                if($pfiles->num_rows > 0) {
                    echo '<table class="itable">';
                    
                    //������� ��� ������
                    foreach ($pfiles->fetchAll() as $pfile) {
                        echo  '<tr class="">';
                        //������
                        echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/down/'.\engine::format($pfile->files).'.png"></td>';
                        //�������� � ����
                        echo '<td class="c_forum"><b><a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($pfile->name).'-'.$pfile->id.'">'.$pfile->name.'</a></b>';
                        //������� ��������
                        echo '<p class="desc">'.$pfile->text1.'</p></td>';

                        //�������������� ����������
                        echo '<td class="c_stats"><ul>';
                            //���. ����������
                            echo '<li><b>'.\engine::number($pfile->count).'</b> ����������</li>';
                            //���. ��������
                            echo '<li><b>'.\engine::number($pfile->countd).'</b> ��������</li>';
                        echo '</ul></td>';
                        echo '</tr>';
                    }
                    
                    echo '</table>';
                    
                }else {
                    //���� ��� ������
                    echo \engine::warning(\Lang::__('���������� ������ �� �������'));
                }
        echo '</div>';
        
        
        echo '<div class="mainname">'.\Lang::__('���������� ������').'</div>';
        echo '<div class="mainpost">';
            //������ ��������
            $afiles = $dbase->query("SELECT DISTINCT(id_user) FROM `files` ORDER BY rand() LIMIT 10");
                //��������� ���� �� ���������� �����
                if($afiles->num_rows > 0) {
                    echo '<table class="itable">';
                    
                    //������� ��� ������
                    foreach ($afiles->fetchAll() as $afile) {
                        $profile = $profi->Views(['*'],$afile->id_user);
                        
                        $cfiles = $dbase->query("SELECT COUNT(*) as count FROM `files` WHERE `id_user` = ?",[$afile->id_user]);
                        $cfile = $cfiles->fetchArray();
                        
                        echo  '<tr class="">';
                        //������
                        if($profile['avatar'] == true) {
                            echo '<td class="c_icon"><img src="/upload/avatar/'.$profile['avatar'].'" class="UserPhoto UserPhoto_mini"></td>';
                        }else {
                            echo '<td class="c_icon"><img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini"></td>';
                        }
                        //�������� � ����
                        echo '<td class="c_forum"><b><a href="'.\PROFILE.'?id='.$profile['id'].'">'.$profile['nick'].'</a></b>';

                        //�������������� ����������
                        echo '<td class="c_stats"><ul>';
                            //���. ��������
                            echo '<li><b>'.\engine::number($cfile['count']).'</b> ������ �����</li>';
                        echo '</ul></td>';
                        echo '</tr>';
                    }
                    
                    echo '</table>';
                    
                }else {
                    //���� ��� ������
                    echo \engine::warning(\Lang::__('���������� ������ �� �������'));
                }        
        echo '</div>';        
    }

    



    /**
     * ��������� ������ � ������� �����
     * 
     * @param Null
     */          
    public static function PostAdd() {
        global $dbase,$app;
        //��������� ������
        $submit = \filter_input(\INPUT_POST,'submit');
        //��������� ��������
        $name = \filter_input(\INPUT_POST,'name',\FILTER_SANITIZE_STRING);
        //��������� checkbox`a
        $new_file = \filter_input(\INPUT_POST,'new_file',\FILTER_SANITIZE_NUMBER_INT);
        //��������� ������
        $text = \filter_input(\INPUT_POST,'text',\FILTER_SANITIZE_STRING);
   
        if(isset($submit)) {
	    //�������� �� ������������
	    $new_file = \intval($new_file);		
            //�������� ���������� �� ��������
            if(empty($name)) {
	        $app->redirect('/');
	    }
            //�������� ���������� �� ��������
            if(empty($text)){
                $app->redirect('/');
            }else {		
                if($new_file != 2) {
                    $new_file = 1;
                }
                    
                //�������� ������ � ����
                $dbase->insert('files_dir',[
                    'name'   => ''.$dbase->escape($name).'',
                    'text'   => ''.$dbase->escape($text).'',
                    'time'   => ''.time().'',
                    'dir'    => ''.intval(0).'',
                    'load'   => ''.intval($new_file).''
                ]);
                //�������� ������ �� �������
                $viewi = $dbase->query("SELECT * FROM `files_dir` WHERE `id` = ?",[$id]);
                $view = $viewi->fetchArray();
            
                //�������������
                $app->redirect('/modules/files/');
            }	
        }
    }
    
    /**
     * �������� ����� �������� �����
     * 
     * @param Null
     */      
    public static function GetPost() {
        //������ AngularJS
        echo \engine::AngularJS();
    
        //���� ��������
        echo '<div class="mainname">'.\Lang::__('�������� ����� ������').'</div>';                
        echo '<div class="mainpost">';
        //��������� �����
        $form = new \Shcms\Component\Form\Form();
        //�������� �����
        echo $form->open(['action' => '','class' => 'form-horizontal','name' => 'myForm','ng' => 'FormController']);
        
            //��������
            echo '<div class="form-group">';
            echo '<label class="col-sm-2 control-label">'.\Lang::__('��������').'</label>';
            echo '<div class="col-sm-10">';
            //����� ��������
            echo $form->input(['name' => 'name','class' => 'form-control','ng' => 'ng-model="form.name" ng-minlength="5" ng-maxlength="26"','required' => '']);
                //����� ������            
                echo '<p><div class="text-danger right" ng-messages="myForm.name.$error">
                    <div ng-message="required">������� ��������</div>
                    <div ng-message="minlength">������������ ��������</div>
                    <div ng-message="maxlength">�������� ������ 26 ��������</div>';
                echo '</div></p>';
        
            echo '</div></div>';
        
            //�������� �������
            echo '<div class="form-group">';
            echo '<label class="col-sm-2 control-label">'.\Lang::__('��������').'</label>';
            echo '<div class="col-sm-10">';
            //����� ��������
            echo $form->textbox(['name' => 'text','class' => 'form-control','ng' => 'ng-model="form.text" ng-minlength="5" ng-maxlength="100"','required' => '']);
                //����� ������                    
                echo '<p><div class="text-danger right" ng-messages="myForm.text.$error">
                    <div ng-message="required">������� ��������</div>
                    <div ng-message="minlength">������������ �������</div>
                    <div ng-message="maxlength">�������� ������ 100 ��������</div>';
                echo '</div></p>';
        
            echo '</div></div>';        
        
            //�������������� ������
            echo '<div class="form-group">';
            echo '<label class="col-sm-2 control-label">'.\Lang::__('����������').'</label>';
            echo '<div class="col-sm-10">';
        
            //�������� ������
            echo '<div class="col-sm-5"><label>';
            echo $form->checkbox([1 => ['name'=>'dir_open', 'value'=>'1','checked' => 'checked']]);
            echo \Lang::__('����� �������?');
            echo '</label>';
        
            //���������� ������
            echo '</div><div class="col-sm-5"><label>';
            echo $form->checkbox([1 => ['name'=>'new_file', 'value'=>'2','checked' => 'checked']]);
            echo \Lang::__('���������� �� ���������� ������');
            echo '</label>';        
            echo '</div></div></div>';
        
            echo '<div class="modal-footer">';
            echo $form->submit(['name' => 'submit','value' => '������� �����','class' => 'btn btn-success','ng' => 'ng-disabled="myForm.$invalid"']);
            echo '<a class="btn btn-default" href="/modules/files">������</a>';
            echo '</div>';
        
            //�������� �����
            echo $form->close();
            echo '</div>';            
    }       
    
}
