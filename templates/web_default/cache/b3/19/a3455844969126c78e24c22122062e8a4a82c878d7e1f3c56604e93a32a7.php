<?php

/* twig/upper/profile.twig */
class __TwigTemplate_b319a3455844969126c78e24c22122062e8a4a82c878d7e1f3c56604e93a32a7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<ul class=\"nav navbar-nav pull-right\">     
    <li>";
        // line 4
        echo "<a data-toggle=\"dropdown\" href=\"#\" ><i class=\"icon-eye-open\"></i> ";
        if (isset($context["login"])) { $_login_ = $context["login"]; } else { $_login_ = null; }
        echo twig_escape_filter($this->env, $_login_, "html", null, true);
        echo " <span class=\"caret\"></span></a>    
        <ul class=\"dropdown-menu\">";
        // line 7
        echo "<li><a  href=\"/modules/profile.php?id=";
        if (isset($context["id_user"])) { $_id_user_ = $context["id_user"]; } else { $_id_user_ = null; }
        echo twig_escape_filter($this->env, $_id_user_, "html", null, true);
        echo "\">Мой профиль</a></li>             
            <li><a  href=\"/modules/menu.php\">Основное меню</a></li>   
            <li><a href=\"/modules/messaging.php\">Почта</a></li>             
            <li><a href=\"/modules/profile.php?act=edit_profile\">Настройки</a></li>
\t    <li><a href=\"/modules/theme.php\">Оформления</a></li>
\t    <li class=\"divider\"></li>
\t        <li><a href=\"/modules/exit.php\">Выход</a></li>
            </ul>
    </li>";
        // line 17
        echo "<li> <a href=\"/modules/friends.php\"><i class=\"icon-user\"></i> Друзья ";
        if (isset($context["friend"])) { $_friend_ = $context["friend"]; } else { $_friend_ = null; }
        echo twig_escape_filter($this->env, $_friend_, "html", null, true);
        echo "</a></li>
    <li> <a href=\"/modules/profile.php?act=notificationlog\"><i class=\"icon-bell\"></i> ";
        // line 18
        if (isset($context["mail"])) { $_mail_ = $context["mail"]; } else { $_mail_ = null; }
        echo twig_escape_filter($this->env, $_mail_, "html", null, true);
        echo " | ";
        if (isset($context["countf"])) { $_countf_ = $context["countf"]; } else { $_countf_ = null; }
        echo twig_escape_filter($this->env, $_countf_, "html", null, true);
        echo "</a>\t</li>      
</ul>";
    }

    public function getTemplateName()
    {
        return "twig/upper/profile.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 18,  41 => 17,  28 => 7,  22 => 4,  19 => 1,);
    }
}
