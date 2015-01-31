<?php

/* twig/document/metateg.twig */
class __TwigTemplate_a519a79f62dd17ce9ece65e1e65053b9f2795a5078c9b33bfb56cf5dd2ce0bd9 extends Twig_Template
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
        if (isset($context["copyright"])) { $_copyright_ = $context["copyright"]; } else { $_copyright_ = null; }
        echo twig_escape_filter($this->env, $_copyright_, "html", null, true);
        echo "-->
<meta property=\"og:cms\" content=\"SHCMS Engine\" />";
        // line 11
        echo "<meta property=\"og:site_name\" content=\"";
        if (isset($context["site_name"])) { $_site_name_ = $context["site_name"]; } else { $_site_name_ = null; }
        echo twig_escape_filter($this->env, $_site_name_, "html", null, true);
        echo "\" />
<!--Разработик скрипта-->
<meta property=\"og:url\" content=\"http://www.shcms.ru/\" />";
        // line 15
        echo "<meta property=\"og:title\" content=\"";
        if (isset($context["title"])) { $_title_ = $context["title"]; } else { $_title_ = null; }
        echo twig_escape_filter($this->env, $_title_, "html", null, true);
        echo "\" />";
        // line 17
        echo "<meta name=\"description\" content=\"";
        if (isset($context["description"])) { $_description_ = $context["description"]; } else { $_description_ = null; }
        echo twig_escape_filter($this->env, $_description_, "html", null, true);
        echo "\" />";
        // line 19
        echo "<meta name=\"keywords\" content=\"";
        if (isset($context["keywords"])) { $_keywords_ = $context["keywords"]; } else { $_keywords_ = null; }
        echo twig_escape_filter($this->env, $_keywords_, "html", null, true);
        echo "\" />";
        // line 22
        echo "<title>";
        if (isset($context["title"])) { $_title_ = $context["title"]; } else { $_title_ = null; }
        echo twig_escape_filter($this->env, $_title_, "html", null, true);
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
        return array (  54 => 22,  49 => 19,  44 => 17,  39 => 15,  32 => 11,  27 => 8,  19 => 2,);
    }
}
