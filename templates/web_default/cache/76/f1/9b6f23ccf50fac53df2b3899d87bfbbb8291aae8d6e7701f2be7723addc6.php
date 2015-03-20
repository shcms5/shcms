<?php

/* twig/menu/count.twig */
class __TwigTemplate_76f19b6f23ccf50fac53df2b3899d87bfbbb8291aae8d6e7701f2be7723addc6 extends Twig_Template
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
        if (($this->getAttribute((isset($context["m"]) ? $context["m"] : null), "dir", array()) == "chat")) {
            echo "         
    <span class=\"badge right\">";
            // line 3
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["chat"]) ? $context["chat"] : null), 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute((isset($context["m"]) ? $context["m"] : null), "dir", array()) == "forum")) {
            // line 5
            echo "       
    <span class=\"badge right\">";
            // line 6
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["forum"]) ? $context["forum"] : null), 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute((isset($context["m"]) ? $context["m"] : null), "dir", array()) == "download")) {
            // line 8
            echo "  
    <span class=\"badge right\">";
            // line 9
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["download"]) ? $context["download"] : null), 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute((isset($context["m"]) ? $context["m"] : null), "dir", array()) == "news")) {
            // line 11
            echo " 
    <span class=\"badge right\">";
            // line 12
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["news"]) ? $context["news"] : null), 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute((isset($context["m"]) ? $context["m"] : null), "dir", array()) == "libs")) {
            // line 14
            echo "     
    <span class=\"badge right\">";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["libs"]) ? $context["libs"] : null), 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute((isset($context["m"]) ? $context["m"] : null), "dir", array()) == "gallery")) {
            // line 17
            echo "  
    <span class=\"badge right\">";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["gallery"]) ? $context["gallery"] : null), 0, array(), "array"), "html", null, true);
            echo "</span>          
";
        }
    }

    public function getTemplateName()
    {
        return "twig/menu/count.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  58 => 18,  55 => 17,  51 => 15,  48 => 14,  44 => 12,  41 => 11,  37 => 9,  34 => 8,  30 => 6,  27 => 5,  23 => 3,  19 => 2,);
    }
}
