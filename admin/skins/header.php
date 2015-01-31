<!doctype html>
<html lang="ru"><head>
    <meta charset="utf-8">
    <title>SHCMS Engine - Администраторский центр</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/templates/web_default/css/style.css">
    <link rel="stylesheet" type="text/css" href="/admin/skins/lib/bootstrap/css/bootstrap.css">
    <link href="/admin/skins/lib/bootstrap/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/admin/skins/lib/bootstrap/css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" media="screen" href="/admin/skins/lib/bootstrap/css/date.css" />
    <link rel="stylesheet" href="/admin/skins/lib/font-awesome/css/font-awesome.css">
      <link rel="stylesheet" type="text/css" href="/admin/skins/lib/bootstrap/css/fuelux.css">
    <script src="/admin/skins/lib/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="/engine/template/module/editor/jquery.wysibb.js"></script>
<link rel="stylesheet" href="/engine/template/module/editor/theme/default/wbbtheme.css" />
    <script src="/admin/skins/lib/jQuery-Knob/js/jquery.knob.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $(".knob").knob();
        });
    </script>


    <link rel="stylesheet" type="text/css" href="/admin/skins/stylesheets/theme.css">
    <link rel="stylesheet" type="text/css" href="/admin/skins/stylesheets/premium.css">

</head>
<body class="fuelux theme-blue">

    <script type="text/javascript">
        $(function() {
            var match = document.cookie.match(new RegExp('color=([^;]+)'));
            if(match) var color = match[1];
            if(color) {
                $('body').removeClass(function (index, css) {
                    return (css.match (/\btheme-\S+/g) || []).join(' ')
                })
                $('body').addClass('theme-' + color);
            }

            $('[data-popover="true"]').popover({html: true});
            
        });
    </script>
    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .navbar-default .navbar-brand, .navbar-default .navbar-brand:hover { 
            color: #fff;
        }
    </style>

    <script type="text/javascript">
        $(function() {
            var uls = $('.sidebar-nav > ul > *').clone();
            uls.addClass('visible-xs');
            $('#main-menu').append(uls.clone());
        });
    </script>

    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <a class="" href="index.php"><span class="navbar-brand"><span class="fa fa-paper-plane"></span> SHCMS Engine - Администраторская</span></a></div>

        <div class="navbar-collapse collapse" style="height: 1px;">
          <ul id="main-menu" class="nav navbar-nav navbar-right">
            <li class="dropdown  hidden-xs">
                <a target="_blank" href="/index.php" class="dropdown-toggle">
                    Просмотр сайта
                </a>
                </li>
            <li class="dropdown hidden-xs">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-user padding-right-small" style="position:relative;top: 3px;"></span><?php echo $users['nick']; ?>
                    <i class="fa fa-caret-down"></i>
                </a>

              <ul class="dropdown-menu">
                  <?php echo "<li><a href='../../modules/profile.php?id={$users['id']}'>Мой профиль</a></li>";?>
                <li class="divider"></li>
                <li class="dropdown-header">Админ-панель</li>
                <li><a href="../../modules/menu.php">Настройки</a></li>
                <li class="divider"></li>
                <li><a tabindex="-1" href="/modules/exit.php">Выйти</a></li>
              </ul>
            </li>
          </ul>

        </div>
      </div>
    </div>
    

    <div class="sidebar-nav">
    <ul>
     <li><a href="/admin.php" class="nav-header"><i class="fa fa-spinner fa-question-circle"></i> Все разделы</a></li> 
    <li><a href="#" data-target=".dashboard-menu" class="nav-header" data-toggle="collapse"><i class="fa fa-fw fa-dashboard"></i> Основные<i class="fa fa-collapse"></i></a></li>
    <li><ul class="dashboard-menu nav nav-list collapse in">
            <li><a href="/admin/system/index.php?do=setting"><span class="fa fa-caret-right"></span> Настройки</a></li>
            <li ><a href="/admin/system/index.php?do=setting&act=advertisements"><span class="fa fa-caret-right"></span> Настройка рекламы</a></li>
            <li ><a href="/admin/system/index.php?do=setting&act=security_spam"><span class="fa fa-caret-right"></span> Настройки защиты</a></li>
            <li ><a href="/admin/system/index.php?do=application"><span class="fa fa-caret-right"></span> Приложение</a></li>
            <li ><a href="/admin/user/index.php?do=user&act=office"><span class="fa fa-caret-right"></span> Управление пользователями</a></li>
    </ul></li>
    
    <li><a href="#" data-target=".dashboard-menu2" class="nav-header" data-toggle="collapse"><i class="fa fa-fw fa-signal"></i> Поддержка<i class="fa fa-collapse"></i></a></li>
    <li><ul class="dashboard-menu2 nav nav-list collapse in">
            <li><a href="/admin/support/index.php?do=diagnostic"><span class="fa fa-caret-right"></span> Диагностика</a></li>
            <li><a href="/admin/support/index.php?do=sql"><span class="fa fa-caret-right"></span> Управление SQL</a></li>
            <li><a href="/admin/support/index.php?do=sql&act=backup"><span class="fa fa-caret-right"></span> Бэкап</a></li>
        </ul></li>    

        <li><a href="/admin/help/index.php?do=shcms_money" class="nav-header"><i class="fa fa-fw fa-question-circle"></i> Помощь проекту</a></li>
            </ul>
    </div>

    <div class="content">
        <div class="main-content">
