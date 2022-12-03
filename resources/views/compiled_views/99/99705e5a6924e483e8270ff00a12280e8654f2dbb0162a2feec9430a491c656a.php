<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* /administration/dashboard/dashboardAdmin.twig */
class __TwigTemplate_4533ecd8a5487f8f21b6788f4f5dd0001615cf2a501d0c0d0310abca18122d4b extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content_main' => [$this, 'block_content_main'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 3
        return "administration/templateAdministration.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        $context["menuLItem"] = "home";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/dashboard/dashboardAdmin.twig", 3);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 6
        echo "\t
<div class=\"f_card\">
\t<div class=\"row\">
\t\t";
        // line 9
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cajas", [], "any", true, true, false, 9)) {
            // line 10
            echo "        \t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 10, $this->source); })()), "cajas", [], "any", false, false, false, 10));
            foreach ($context['_seq'] as $context["_key"] => $context["caja"]) {
                // line 11
                echo "        \t\t<div class=\"col-md-3 col-sm-6 col-12\">
                    <div class=\"info-box p-0\">
                        <span class=\"info-box-icon bg-info\" style=\"background-color:#ececec !important\">
                        \t<i class=\"fas fa-archive\" style=\"color:#000 !important; font-size:1.5rem\"></i>
                    \t</span>
                        
                        <div class=\"info-box-content\">
                            <span class=\"info-box-text\">SALDO ";
                // line 18
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_NOMBRE", [], "any", false, false, false, 18), "html", null, true);
                echo "</span>
                            <span class=\"info-box-number\">";
                // line 19
                echo twig_escape_filter($this->env, ("S/. " . twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_SALDO", [], "any", false, false, false, 19), 2, ".", ",")), "html", null, true);
                echo "</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                \t<!-- /.info-box -->
                </div>
        \t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['caja'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 26
            echo "        ";
        }
        // line 27
        echo "\t</div>
</div><!-- /.card -->
    
";
    }

    public function getTemplateName()
    {
        return "/administration/dashboard/dashboardAdmin.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  94 => 27,  91 => 26,  78 => 19,  74 => 18,  65 => 11,  60 => 10,  58 => 9,  53 => 6,  49 => 5,  44 => 3,  42 => 1,  35 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/dashboard/dashboardAdmin.twig", "/home/franco/proyectos/php/jass/resources/views/administration/dashboard/dashboardAdmin.twig");
    }
}
