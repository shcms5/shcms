<?php

/* twig/document/js.twig */
class __TwigTemplate_841ff733cb649a7daa278b6dd6fb0f0ec96f604dcbfd5b773768836363eeb5d4 extends Twig_Template
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
        echo "<script src=\"/engine/template/module/editor/jquery-1.11.0.js\"></script>
<script src=\"/engine/template/module/bootstrap/js/bootstrap.min.js\"></script>
<script src=\"/engine/template/module/bootstrap/js/jquery.searchable-1.1.0.min.js\"></script>   
    
<script type=\"text/javascript\">
\t\$(function () {
    \$( '#table' ).searchable({
        striped: true,
        searchType: 'fuzzy'
    });
    
    \$( '#searchlist' ).searchable({
        searchField: '#container-search',
        selector: '.list-group-item',
        childSelector: '.row_3',
        show: function( elem ) {
            elem.slideDown(100);
        },
        hide: function( elem ) {
            elem.slideUp( 100 );
        }
    })
    \$( '#searchlist2' ).searchable({
        searchField: '#container-search',
        selector: '.row3',
        childSelector: '.listing',
        show: function( elem ) {
            elem.slideDown(100);
        },
        hide: function( elem ) {
            elem.slideUp( 100 );
        }
    })\t
});
</script>        ";
    }

    public function getTemplateName()
    {
        return "twig/document/js.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 2,);
    }
}
