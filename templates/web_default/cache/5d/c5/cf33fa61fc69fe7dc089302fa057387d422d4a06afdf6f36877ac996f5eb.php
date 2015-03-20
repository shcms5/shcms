<?php

/* twig/rightmenu.twig */
class __TwigTemplate_5dc5cf33fa61fc69fe7dc089302fa057387d422d4a06afdf6f36877ac996f5eb extends Twig_Template
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
        echo "<div class=\"col-lg-3 col-md-3 col-sm-6 col-xs-4 right\">";
        // line 5
        echo "<div class=\"mainname\"><i class=\"icon-home\"></i>&nbsp;Навигация</div>";
        // line 7
        echo "<div class=\"mainpost\">
    
<ul class=\"nav nav-pills nav-stacked\">
";
        // line 10
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["data"]) ? $context["data"] : null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["m"]) {
            // line 11
            echo "    <li>";
            // line 13
            echo "<a href=\"/modules/";
            echo twig_escape_filter($this->env, $this->getAttribute($context["m"], "dir", array()));
            echo "/\"><img src=\"/admin/icons/module/";
            echo twig_escape_filter($this->env, $this->getAttribute($context["m"], "icon", array()));
            echo "\">&nbsp;";
            echo twig_escape_filter($this->env, $this->getAttribute($context["m"], "name", array()));
            // line 15
            $this->env->loadTemplate("twig/menu/count.twig")->display($context);
            echo " </a>
    </li>
";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['m'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 18
        echo "</ul>
</div>";
        // line 22
        $this->env->loadTemplate("twig/menu/popular.twig")->display($context);
        echo " 
</div>";
    }

    public function getTemplateName()
    {
        return "twig/rightmenu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 22,  71 => 18,  54 => 15,  47 => 13,  45 => 11,  28 => 10,  23 => 7,  21 => 5,  19 => 2,);
    }
}
