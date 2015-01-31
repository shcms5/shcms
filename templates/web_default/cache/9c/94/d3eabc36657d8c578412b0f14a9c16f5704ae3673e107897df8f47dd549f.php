<?php

/* twig/menu/count.twig */
class __TwigTemplate_9c94d3eabc36657d8c578412b0f14a9c16f5704ae3673e107897df8f47dd549f extends Twig_Template
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
        if (isset($context["m"])) { $_m_ = $context["m"]; } else { $_m_ = null; }
        if (($this->getAttribute($_m_, "dir", array()) == "chat")) {
            echo "         
    <span class=\"badge right\">";
            // line 3
            if (isset($context["chat"])) { $_chat_ = $context["chat"]; } else { $_chat_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_chat_, 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute($_m_, "dir", array()) == "forum")) {
            // line 5
            echo "       
    <span class=\"badge right\">";
            // line 6
            if (isset($context["forum"])) { $_forum_ = $context["forum"]; } else { $_forum_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_forum_, 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute($_m_, "dir", array()) == "download")) {
            // line 8
            echo "  
    <span class=\"badge right\">";
            // line 9
            if (isset($context["download"])) { $_download_ = $context["download"]; } else { $_download_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_download_, 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute($_m_, "dir", array()) == "news")) {
            // line 11
            echo " 
    <span class=\"badge right\">";
            // line 12
            if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_news_, 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute($_m_, "dir", array()) == "libs")) {
            // line 14
            echo "     
    <span class=\"badge right\">";
            // line 15
            if (isset($context["libs"])) { $_libs_ = $context["libs"]; } else { $_libs_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_libs_, 0, array(), "array"), "html", null, true);
            echo "</span>";
        } elseif (($this->getAttribute($_m_, "dir", array()) == "gallery")) {
            // line 17
            echo "  
    <span class=\"badge right\">";
            // line 18
            if (isset($context["gallery"])) { $_gallery_ = $context["gallery"]; } else { $_gallery_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_gallery_, 0, array(), "array"), "html", null, true);
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
        return array (  64 => 18,  61 => 17,  56 => 15,  53 => 14,  48 => 12,  45 => 11,  40 => 9,  37 => 8,  32 => 6,  29 => 5,  24 => 3,  19 => 2,);
    }
}
