<?php

/* twig/upper/login.twig */
class __TwigTemplate_9f7447ff2a7a94712b4771315a59abf427b4d7978d4ffda628ce9211e26c9330 extends Twig_Template
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
        echo "<div class=\"modal fade\" id=\"login\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
        <div class=\"modal-dialog\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">";
        // line 8
        echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                        <span aria-hidden=\"true\">&times;</span>
                    </button>";
        // line 12
        echo "<h4 class=\"modal-title\" id=\"myModalLabel\">Авторизация</h4>
                </div>";
        // line 15
        echo "<form class=\"form-horizontal\" method=\"post\" action=\"/modules/auth.php\">
                    <div class=\"modal-body\">";
        // line 18
        echo "<div class=\"form-group\">
                            <label class=\"col-sm-3 control-label\">Логин</label>
                                <div class=\"col-sm-8\">
                                    <input name=\"nick\" placeholder=\"Имя пользователя\" type=\"text\" class=\"form-control\" required>
                                </div>
                        </div>";
        // line 25
        echo "<div class=\"form-group\">
                            <label class=\"col-sm-3 control-label\">Пароль</label>
                                <div class=\"col-sm-8\">
                                    <input name=\"password\" type=\"password\" class=\"form-control\" placeholder=\"Пароль\" required>
                                   <div style=\"margin-top:10px;\" class=\"text-danger\">
                                        <a href=\"/modules/lostpass.php\">Забыли пароль?</a>
                                   </div>
                                </div>   
                        </div>
                    </div>";
        // line 36
        echo "<div class=\"modal-footer\">
                        <span class=\"left\">";
        // line 39
        if (isset($context["vk_id"])) { $_vk_id_ = $context["vk_id"]; } else { $_vk_id_ = null; }
        if (isset($context["vk_key"])) { $_vk_key_ = $context["vk_key"]; } else { $_vk_key_ = null; }
        if (isset($context["vk_close"])) { $_vk_close_ = $context["vk_close"]; } else { $_vk_close_ = null; }
        if (((twig_test_empty($_vk_id_) || twig_test_empty($_vk_key_)) || ($_vk_close_ == 1))) {
            echo " 
                               <!--Не все заполнено Вконтакте-->
                            ";
        } else {
            // line 41
            echo "   
                           <a href=\"/modules/auth.php?do=vk\">
                               <img src=\"/engine/template/icons/auth/vk.png\">
                            </a>
                           ";
        }
        // line 47
        if (isset($context["fc_id"])) { $_fc_id_ = $context["fc_id"]; } else { $_fc_id_ = null; }
        if (isset($context["fc_key"])) { $_fc_key_ = $context["fc_key"]; } else { $_fc_key_ = null; }
        if (isset($context["fc_close"])) { $_fc_close_ = $context["fc_close"]; } else { $_fc_close_ = null; }
        if (((twig_test_empty($_fc_id_) || twig_test_empty($_fc_key_)) || ($_fc_close_ == 1))) {
            echo " 
                            ";
        } else {
            // line 48
            echo "   
                           <a href=\"/modules/auth.php?do=fc\">
                               <img src=\"/engine/template/icons/auth/fc.png\">
                            </a>
                           ";
        }
        // line 53
        echo "                           
                        </span>";
        // line 56
        echo "<input name=\"submit\" type=\"submit\" class=\"btn btn-success\" value=\"Войти\">
                        <a href=\"/modules/register.php\" class=\"btn\">Регистрация →</a>
                    </div>
                </form>
            </div>
        </div>
    </div>";
        // line 64
        echo "<ul class=\"nav navbar-nav pull-right\">\t
        <li><a data-toggle=\"modal\" data-target=\"#login\"> Войти</a></li>
\t<li><a href=\"/modules/register.php\"> Регистрация</a></li>
    </ul>";
    }

    public function getTemplateName()
    {
        return "twig/upper/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 64,  89 => 56,  86 => 53,  79 => 48,  71 => 47,  64 => 41,  55 => 39,  52 => 36,  41 => 25,  34 => 18,  31 => 15,  28 => 12,  24 => 8,  19 => 2,);
    }
}
