<?php

/* twig/upper/menu.twig */
class __TwigTemplate_a84ec624f4c2089238b0ba563be4103c88141353545a62c282f27d0c172f1b9c extends Twig_Template
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
    <li><a href=\"/modules/download/\">Загрузки</a></li>
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
