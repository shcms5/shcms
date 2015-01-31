<?php

/* twig/menu/popular.twig */
class __TwigTemplate_5be96b12bb672ffaf29b660456bbf83f91f30547283f1ed87c971128b63c638f extends Twig_Template
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
        if (isset($context["news_twig"])) { $_news_twig_ = $context["news_twig"]; } else { $_news_twig_ = null; }
        if (($_news_twig_ == true)) {
            // line 6
            echo "<div class=\"mainname\"><i class=\"icon-desktop\"></i> Популярные новости</div>
    <div class=\"mainpost\">";
            // line 10
            echo "<ul class=\"nav nav-pills nav-stacked\">
    ";
            // line 11
            if (isset($context["view_news"])) { $_view_news_ = $context["view_news"]; } else { $_view_news_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_view_news_);
            foreach ($context['_seq'] as $context["_key"] => $context["v"]) {
                // line 12
                echo "        <li>";
                // line 14
                echo "<a href=\"/modules/news/view.php?id=";
                if (isset($context["v"])) { $_v_ = $context["v"]; } else { $_v_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_v_, "id", array()));
                echo "\">";
                if (isset($context["v"])) { $_v_ = $context["v"]; } else { $_v_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_v_, "title", array()), "html");
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
        return array (  49 => 17,  35 => 14,  33 => 12,  28 => 11,  25 => 10,  22 => 6,  19 => 3,);
    }
}
