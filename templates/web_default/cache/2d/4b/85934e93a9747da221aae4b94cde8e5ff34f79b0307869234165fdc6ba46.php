<?php

/* twig/upper.twig */
class __TwigTemplate_2d4b85934e93a9747da221aae4b94cde8e5ff34f79b0307869234165fdc6ba46 extends Twig_Template
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
        echo "<header id=\"header\" role=\"banner\">
        
    <div class=\"container\">
    <div class=\"navbar navbar-default navbar-fixed-top\">";
        // line 7
        $this->env->loadTemplate("twig/upper/navigation.twig")->display($context);
        echo "  
                
        <div class=\"collapse navbar-collapse\">";
        // line 11
        $this->env->loadTemplate("twig/upper/menu.twig")->display($context);
        // line 14
        if (isset($context["id_user"])) { $_id_user_ = $context["id_user"]; } else { $_id_user_ = null; }
        if (($_id_user_ == false)) {
            // line 16
            $this->env->loadTemplate("twig/upper/login.twig")->display($context);
            echo "  
            ";
        } else {
            // line 19
            $this->env->loadTemplate("twig/upper/profile.twig")->display($context);
            echo "  
            ";
        }
        // line 21
        echo "            
        </div>
    </div>
    </div>
</header>";
    }

    public function getTemplateName()
    {
        return "twig/upper.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 21,  39 => 19,  34 => 16,  31 => 14,  29 => 11,  24 => 7,  19 => 2,);
    }
}
