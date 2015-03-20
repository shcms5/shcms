<?php

/* twig/document/metateg.twig */
class __TwigTemplate_c14f92c53161ee70727a948de4bb5ab3771a076c9ff64c8d32d207900d4603ca extends Twig_Template
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
        echo "<meta charset=\"utf-8\">
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">

        
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<!--Не снимать ";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["copyright"]) ? $context["copyright"] : null), "html", null, true);
        echo "-->
<meta property=\"og:cms\" content=\"SHCMS Engine\" />";
        // line 11
        echo "<meta property=\"og:site_name\" content=\"";
        echo twig_escape_filter($this->env, (isset($context["site_name"]) ? $context["site_name"] : null), "html", null, true);
        echo "\" />
<!--Разработик скрипта-->
<meta property=\"og:url\" content=\"http://www.shcms.ru/\" />";
        // line 15
        echo "<meta property=\"og:title\" content=\"";
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "\" />";
        // line 17
        echo "<meta name=\"description\" content=\"";
        echo twig_escape_filter($this->env, (isset($context["description"]) ? $context["description"] : null), "html", null, true);
        echo "\" />";
        // line 19
        echo "<meta name=\"keywords\" content=\"";
        echo twig_escape_filter($this->env, (isset($context["keywords"]) ? $context["keywords"] : null), "html", null, true);
        echo "\" />";
        // line 22
        echo "<title>";
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</title>";
    }

    public function getTemplateName()
    {
        return "twig/document/metateg.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 22,  45 => 19,  41 => 17,  37 => 15,  31 => 11,  27 => 8,  19 => 2,);
    }
}
