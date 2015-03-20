<?php

/* twig/menu/popular.twig */
class __TwigTemplate_32a5f77da4acbe3674df9f6c34610c6382af06f2dd2154ded321a713db1f4152 extends Twig_Template
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
        // line 3
        if (((isset($context["news_twig"]) ? $context["news_twig"] : null) == true)) {
            // line 6
            echo "<div class=\"mainname\"><i class=\"icon-desktop\"></i> Популярные новости</div>
    <div class=\"mainpost\">";
            // line 10
            echo "<ul class=\"nav nav-pills nav-stacked\">
    ";
            // line 11
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["view_news"]) ? $context["view_news"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["v"]) {
                // line 12
                echo "        <li>";
                // line 14
                echo "<a href=\"/modules/news/view.php?id=";
                echo twig_escape_filter($this->env, $this->getAttribute($context["v"], "id", array()));
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["v"], "title", array()), "html");
                echo "</a>
        </li>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['v'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 17
            echo "    </ul>

    </div>   
    
";
        }
    }

    public function getTemplateName()
    {
        return "twig/menu/popular.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 17,  33 => 14,  31 => 12,  27 => 11,  24 => 10,  21 => 6,  19 => 3,);
    }
}
