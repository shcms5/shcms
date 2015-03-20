<?php

/* header.twig */
class __TwigTemplate_a83e18a110365034735e7696b5da8d3639368f0a6abdf86572fa2ef22125bdaa extends Twig_Template
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
        echo "<!DOCTYPE html>";
        // line 3
        echo "<html lang=\"ru\" ng-app=\" \">";
        // line 6
        $this->env->loadTemplate("twig/document.twig")->display($context);
        echo "  

<body data-spy=\"scroll\" data-target=\".bs-docs-sidebar\">";
        // line 11
        $this->env->loadTemplate("twig/upper.twig")->display($context);
        // line 13
        $this->env->loadTemplate("twig/slider.twig")->display($context);
        // line 16
        echo "<section>
    <div class=\"container\">
    <div class=\"box first\">
    <div class=\"row\">";
        // line 21
        $this->env->loadTemplate("twig/rightmenu.twig")->display($context);
        // line 23
        echo "<div class=\"col-lg-9 col-md-9 col-sm-6 col-xs-4\">
                            ";
    }

    public function getTemplateName()
    {
        return "header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 23,  37 => 21,  32 => 16,  30 => 13,  28 => 11,  23 => 6,  21 => 3,  19 => 1,);
    }
}
