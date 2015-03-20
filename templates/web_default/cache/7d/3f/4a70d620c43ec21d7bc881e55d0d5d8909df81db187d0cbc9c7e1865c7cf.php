<?php

/* twig/document.twig */
class __TwigTemplate_7d3f4a70d620c43ec21d7bc881e55d0d5d8909df81db187d0cbc9c7e1865c7cf extends Twig_Template
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
        echo "<head>";
        // line 4
        $this->env->loadTemplate("twig/document/metateg.twig")->display($context);
        // line 6
        $this->env->loadTemplate("twig/document/css.twig")->display($context);
        // line 8
        $this->env->loadTemplate("twig/document/js.twig")->display($context);
        // line 10
        $this->env->loadTemplate("twig/document/link.twig")->display($context);
        echo " 
    
</head> 
";
    }

    public function getTemplateName()
    {
        return "twig/document.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 10,  25 => 8,  23 => 6,  21 => 4,  19 => 1,);
    }
}
