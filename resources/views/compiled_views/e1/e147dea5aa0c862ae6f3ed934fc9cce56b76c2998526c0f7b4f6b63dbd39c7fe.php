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

/* /administration/predio/predioDetail.twig */
class __TwigTemplate_8a046f85c84a44d89488c0e1fab33b485810df105adea727897960a36bc906ce extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content_main' => [$this, 'block_content_main'],
            'scripts' => [$this, 'block_scripts'],
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
        $context["menuLItem"] = "predio";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/predio/predioDetail.twig", 3);
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
  \t\t<div class=\"col-12\">
  \t\t\t<div class=\"f_cardheader\">
  \t\t\t\t<div class=\"\"> 
                \t<i class=\"fas fa-house-user mr-3\"></i>Detalle de predio 
            \t</div>
  \t\t\t</div>
  \t\t</div>
  \t</div><!-- /.card-header -->
  \t
  \t<div class=\"row\">
  \t\t<div class=\"col-12\">
  \t\t\t";
        // line 22
        echo "            ";
        $context["classAlert"] = "";
        // line 23
        echo "            ";
        if (twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 23, $this->source); })()))) {
            // line 24
            echo "            \t";
            $context["classAlert"] = "d-none";
            // line 25
            echo "            ";
        } elseif ((((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 25, $this->source); })()) >= 200) && ((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 25, $this->source); })()) < 300))) {
            // line 26
            echo "                ";
            $context["classAlert"] = "alert-success";
            // line 27
            echo "            ";
        } elseif (((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 27, $this->source); })()) >= 400)) {
            // line 28
            echo "                ";
            $context["classAlert"] = "alert-danger";
            // line 29
            echo "            ";
        }
        // line 30
        echo "  \t\t\t<div class=\"alert ";
        echo twig_escape_filter($this->env, (isset($context["classAlert"]) || array_key_exists("classAlert", $context) ? $context["classAlert"] : (function () { throw new RuntimeError('Variable "classAlert" does not exist.', 30, $this->source); })()), "html", null, true);
        echo " alert-dismissible fade show\" id=\"f_alertsContainer\" role=\"alert\">
             \t<ul class=\"mb-0\" id=\"f_alertsUl\">
             \t\t";
        // line 32
        if ( !twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 32, $this->source); })()))) {
            // line 33
            echo "                      ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 33, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["msj"]) {
                // line 34
                echo "                        <li>";
                echo twig_escape_filter($this->env, $context["msj"], "html", null, true);
                echo "</li>
                      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['msj'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 36
            echo "                    ";
        }
        // line 37
        echo "             \t</ul>
             \t<button type=\"button\" class=\"close\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\" id=\"f_alertsDismiss\">
             \t\t<span aria-hidden=\"true\">&times;</span>
             \t</button>
            </div>";
        // line 42
        echo "  \t\t</div>
  \t</div>
  \t
  
  \t<div class=\"row\">
  \t\t<div class=\"col-12 d-flex justify-content-between f_arearef\">
  \t\t\t<div>
  \t\t\t\t<div class=\"d-inline-block mr-2 mr-md-4\">
          \t\t\t<div class=\"f_imageref\"><span class=\"fas fa-house-user\" style=\" color: #a69944\"></span></div>
  \t\t\t\t</div>
  \t\t\t\t<div class=\"d-inline-block align-top\">
  \t\t\t\t\t<span class=\"font-weight-bold f_inforef\">
  \t\t\t\t\t    ";
        // line 54
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 54)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 54, $this->source); })()), "predio", [], "any", false, false, false, 54), "PRE_CODIGO", [], "any", false, false, false, 54), "html", null, true);
        }
        // line 55
        echo "  \t\t\t\t\t</span><br/>
  \t\t\t\t\t<span class=\"font-weight-bold f_inforef\">
  \t\t\t\t\t    ";
        // line 57
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 57)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 57, $this->source); })()), "predio", [], "any", false, false, false, 57), "PRE_DIRECCION", [], "any", false, false, false, 57), "html", null, true);
        }
        // line 58
        echo "\t\t\t\t    </span><br/>
  \t\t\t\t</div>
  \t\t\t</div>
  \t\t\t<div class=\"d-none d-sm-block\">
  \t\t\t\t<span><a href=\"";
        // line 62
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 62, $this->source); })()), "html", null, true);
        echo "/predio/lista\" class=\"f_link font-weight-bold\">Volver a Lista</a></span>
  \t\t\t</div>
  \t\t</div>
  \t\t<div class=\"col-12 col-lg-6 table-responsive\">
  \t\t\t<table class=\"table f_table f_tableforfield\">
              <tbody>
              \t<tr>
                  <td>Ref.</td>
                  <td>";
        // line 70
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 70)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 70, $this->source); })()), "predio", [], "any", false, false, false, 70), "PRE_CODIGO", [], "any", false, false, false, 70), "html", null, true);
        }
        echo "</td>
                </tr>
                </tr>
                <tr>
                  <td>Dirección</td>
                  <td>";
        // line 75
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 75)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 75, $this->source); })()), "predio", [], "any", false, false, false, 75), "PRE_DIRECCION", [], "any", false, false, false, 75), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Habitada</td>
                  <td>";
        // line 79
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 79)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 79, $this->source); })()), "predio", [], "any", false, false, false, 79), "PRE_HABITADA", [], "any", false, false, false, 79), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Material de construcción</td>
                  <td>";
        // line 83
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 83)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 83, $this->source); })()), "predio", [], "any", false, false, false, 83), "PRE_MAT_CONSTRUCCION", [], "any", false, false, false, 83), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Pisos</td>
                  <td>";
        // line 87
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 87)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 87, $this->source); })()), "predio", [], "any", false, false, false, 87), "PRE_PISOS", [], "any", false, false, false, 87), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Familias</td>
                  <td>";
        // line 91
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 91)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 91, $this->source); })()), "predio", [], "any", false, false, false, 91), "PRE_FAMILIAS", [], "any", false, false, false, 91), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Habitantes</td>
                  <td>";
        // line 95
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 95)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 95, $this->source); })()), "predio", [], "any", false, false, false, 95), "PRE_HABITANTES", [], "any", false, false, false, 95), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Pozo Tabular</td>
                  <td>";
        // line 99
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 99)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 99, $this->source); })()), "predio", [], "any", false, false, false, 99), "PRE_POZO_TABULAR", [], "any", false, false, false, 99), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Piscina</td>
                  <td>";
        // line 103
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 103)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 103, $this->source); })()), "predio", [], "any", false, false, false, 103), "PRE_PISCINA", [], "any", false, false, false, 103), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de creación</td>
                  <td>";
        // line 107
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 107)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 107, $this->source); })()), "predio", [], "any", false, false, false, 107), "PRE_CREATED", [], "any", false, false, false, 107), "html", null, true);
        }
        echo "</td>
                </tr>
              </tbody>
            </table>
  \t\t</div>
  \t\t<div class=\"col-12 col-lg-6 mt-3 mt-lg-0 table-responsive\">
    \t\t<table class=\"table f_table f_tableforfield\">
              <tbody>
              \t<tr>
                  <td>Calle/Ref.</td>
                  <td>";
        // line 117
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "calle", [], "any", true, true, false, 117)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 117, $this->source); })()), "calle", [], "any", false, false, false, 117), "CAL_CODIGO", [], "any", false, false, false, 117), "html", null, true);
        }
        echo "</td>
                </tr>
              \t<tr>
                  <td>Calle/Nombre</td>
                  <td>";
        // line 121
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "calle", [], "any", true, true, false, 121)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 121, $this->source); })()), "calle", [], "any", false, false, false, 121), "CAL_NOMBRE", [], "any", false, false, false, 121), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Cliente/Ref.</td>
                  <td>";
        // line 125
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 125)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 125, $this->source); })()), "cliente", [], "any", false, false, false, 125), "CLI_CODIGO", [], "any", false, false, false, 125), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Cliente/Documento.</td>
                  <td>";
        // line 129
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 129)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 129, $this->source); })()), "cliente", [], "any", false, false, false, 129), "CLI_DOCUMENTO", [], "any", false, false, false, 129), "html", null, true);
        }
        echo "</td>
                </tr>
              \t<tr>
                  <td>Cliente/Nombre</td>
                  <td>";
        // line 133
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 133)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 133, $this->source); })()), "cliente", [], "any", false, false, false, 133), "CLI_NOMBRES", [], "any", false, false, false, 133), "html", null, true);
        }
        echo "</td>
                </tr>
              </tbody>
            </table>
    \t</div>
  \t</div><!-- /.card-body -->
  \t
  \t<div class=\"row\">
  \t\t<div class=\"col-12\">
  \t\t\t<div class=\"f_cardfooter f_cardfooteractions text-right\">
  \t\t\t\t<a href=\"";
        // line 143
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 143, $this->source); })()), "html", null, true);
        echo "/predio/editar/";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 143)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 143, $this->source); })()), "predio", [], "any", false, false, false, 143), "PRE_CODIGO", [], "any", false, false, false, 143), "html", null, true);
        }
        echo "\" 
  \t\t\t\t\tclass=\"f_linkbtn f_linkbtnaction\">Modificar</a>
  \t\t\t\t<button type=\"button\" class=\"f_button f_buttonactiondelete\" data-toggle=\"modal\" data-target=\"#modalEliminarPredio\">
  \t\t\t\t\tEliminar</button>
  \t\t\t</div>
  \t\t\t
  \t\t</div>
  \t</div><!-- /.card-footer -->
  
</div><!-- /.card -->



";
        // line 157
        echo "<div class=\"modal fade f_modal\" id=\"modalEliminarPredio\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Eliminar un predio</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i><span>¿Está seguro de querer eliminar este predio?</span>
            \t<form class=\"d-none\" id=\"formEliminarPredio\" action=\"";
        // line 168
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 168, $this->source); })()), "html", null, true);
        echo "/predio/delete\" method=\"post\">
            \t\t<input type=\"hidden\" name=\"codigo\" value=\"";
        // line 169
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 169)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 169, $this->source); })()), "predio", [], "any", false, false, false, 169), "PRE_CODIGO", [], "any", false, false, false, 169), "html", null, true);
        }
        echo "\">
            \t</form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnEliminarPredio\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>";
        // line 179
        echo "    
";
    }

    // line 182
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 183
        echo "
\t";
        // line 184
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#btnEliminarPredio').click(function(event){
\t\t\t\$('#formEliminarPredio').submit();
\t\t\treturn false;
\t\t});
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/predio/predioDetail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  380 => 184,  377 => 183,  373 => 182,  368 => 179,  354 => 169,  350 => 168,  337 => 157,  317 => 143,  302 => 133,  293 => 129,  284 => 125,  275 => 121,  266 => 117,  251 => 107,  242 => 103,  233 => 99,  224 => 95,  215 => 91,  206 => 87,  197 => 83,  188 => 79,  179 => 75,  169 => 70,  158 => 62,  152 => 58,  148 => 57,  144 => 55,  140 => 54,  126 => 42,  120 => 37,  117 => 36,  108 => 34,  103 => 33,  101 => 32,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  77 => 24,  74 => 23,  71 => 22,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/predio/predioDetail.twig", "/home/franco/proyectos/php/jass/resources/views/administration/predio/predioDetail.twig");
    }
}
