<?php

/* twig/upper/profile.twig */
class __TwigTemplate_d611c952fa50e70a5ff45f56cad6d19f15dfbc55e9c65c2c74930735578a3ba5 extends Twig_Template
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
        echo twig_escape_filter($this->env, (isset($context["login"]) ? $context["login"] : null), "html", null, true);
        echo " <span class=\"caret\"></span></a>    
        <ul class=\"dropdown-menu\">";
        // line 7
        echo "<li><a  href=\"/modules/profile.php?id=";
        echo twig_escape_filter($this->env, (isset($context["id_user"]) ? $context["id_user"] : null), "html", null, true);
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
        echo twig_escape_filter($this->env, (isset($context["friend"]) ? $context["friend"] : null), "html", null, true);
        echo "</a></li>
    <li> <a href=\"/modules/profile.php?act=notificationlog\"><i class=\"icon-bell\"></i> ";
        // line 18
        echo twig_escape_filter($this->env, (isset($context["mail"]) ? $context["mail"] : null), "html", null, true);
        echo " | ";
        echo twig_escape_filter($this->env, (isset($context["countf"]) ? $context["countf"] : null), "html", null, true);
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
        return array (  44 => 18,  39 => 17,  27 => 7,  22 => 4,  19 => 1,);
    }
}
