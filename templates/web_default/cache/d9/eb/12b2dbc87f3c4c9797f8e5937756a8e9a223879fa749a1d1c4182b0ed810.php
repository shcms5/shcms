<?php

/* twig/upper/menu.twig */
class __TwigTemplate_d9eb12b2dbc87f3c4c9797f8e5937756a8e9a223879fa749a1d1c4182b0ed810 extends Twig_Template
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
        // line 2
        echo "<ul class=\"nav navbar-nav pull-left\">
    <li><a href=\"/\"><i class=\"icon-home\"></i></a></li>
    <li><a href=\"/modules/chat/\">Общение</a></li>
    <li><a href=\"/modules/news/\">Новости</a></li>
    <li><a href=\"/modules/forum/\">Форум</a></li>
    <li><a href=\"/modules/files/\">Загрузки</a></li>
    <li><a href=\"/modules/gallery/\">Альбомы</a></li>
</ul> ";
    }

    public function getTemplateName()
    {
        return "twig/upper/menu.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 2,);
    }
}
