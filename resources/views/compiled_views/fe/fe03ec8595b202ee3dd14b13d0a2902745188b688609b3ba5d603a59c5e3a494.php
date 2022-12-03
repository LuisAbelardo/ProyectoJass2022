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

/* /administration/tipousopredio/tipoUsoPredioDetail.twig */
class __TwigTemplate_87f7221094aeeb54c5082d1f2f6ca4b2fba4859d0732587cc4b75ff77d714c8d extends \Twig\Template
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
        $context["menuLItem"] = "tipousopredio";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/tipousopredio/tipoUsoPredioDetail.twig", 3);
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
                \t<i class=\"fas fa-warehouse mr-3\"></i>Tipos de uso de predio
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
  \t\t<div class=\"col-12\">
      \t\t<div class=\"f_tabs\">
      \t\t\t<div class=\"f_tabunactive\">
      \t\t\t\t<a href=\"";
        // line 49
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 49, $this->source); })()), "html", null, true);
        echo "/tipousopredio/lista\" class=\"f_link\">Lista</a>
      \t\t\t</div>
      \t\t\t<div class=\"f_tabunactive\">
      \t\t\t\t<a href=\"";
        // line 52
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 52, $this->source); })()), "html", null, true);
        echo "/tipousopredio/nuevo\" class=\"f_link\">Nuevo</a>
      \t\t\t</div>
      \t\t\t<div class=\"f_tabactive\">
      \t\t\t\t<a href=\"#\" class=\"f_link\">Detalle</a>
      \t\t\t</div>
          \t</div>
  \t\t</div>
  \t</div><!-- /.tabs de contenido -->
  \t
  \t<div class=\"f_divwithbartop\">
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
          \t\t<div class=\"d-flex justify-content-between f_arearef\">
          \t\t\t<div>
          \t\t\t\t<div class=\"d-inline-block mr-2 mr-md-4\">
                  \t\t\t<div class=\"f_imageref\"><span class=\"fas fa-warehouse\" style=\" color: #a69944\"></span></div>
          \t\t\t\t</div>
          \t\t\t\t<div class=\"d-inline-block align-top\">
          \t\t\t\t\t<span class=\"font-weight-bold f_inforef\">
          \t\t\t\t\t    ";
        // line 71
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 71)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 71, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 71), "TUP_CODIGO", [], "any", false, false, false, 71), "html", null, true);
        }
        // line 72
        echo "          \t\t\t\t\t</span><br/>
          \t\t\t\t\t<span class=\"font-weight-bold f_inforef\">
          \t\t\t\t\t    ";
        // line 74
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 74)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 74, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 74), "TUP_NOMBRE", [], "any", false, false, false, 74), "html", null, true);
        }
        // line 75
        echo "        \t\t\t\t    </span><br/>
          \t\t\t\t</div>
          \t\t\t</div>
        \t\t</div>
      \t\t</div>
      \t\t
      \t\t<div class=\"col-12 col-lg-6 table-responsive\">
      \t\t\t<table class=\"table f_table f_tableforfield\">
                  <tbody>
                  \t<tr>
                      <td>Ref.</td>
                      <td>";
        // line 86
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 86)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 86, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 86), "TUP_CODIGO", [], "any", false, false, false, 86), "html", null, true);
        }
        echo "</td>
                    </tr>
                    <tr>
                      <td>Nombre</td>
                      <td>";
        // line 90
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 90)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 90, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 90), "TUP_NOMBRE", [], "any", false, false, false, 90), "html", null, true);
        }
        echo "</td>
                    </tr>
                    <tr>
                      <td>Tarifa Agua</td>
                      <td>";
        // line 94
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 94)) {
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 94, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 94), "TUP_TARIFA_AGUA", [], "any", false, false, false, 94), 2, ".", ","), "html", null, true);
        }
        echo "</td>
                    </tr>
                    <tr>
                      <td>Tarifa Desague</td>
                      <td>";
        // line 98
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 98)) {
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 98, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 98), "TUP_TARIFA_DESAGUE", [], "any", false, false, false, 98), 2, ".", ","), "html", null, true);
        }
        echo "</td>
                    </tr>
\t\t\t\t\t<tr>
                      <td>Tarifa Ambos Servicios</td>
                      <td>";
        // line 102
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 102)) {
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 102, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 102), "TUP_TARIFA_AMBOS", [], "any", false, false, false, 102), 2, ".", ","), "html", null, true);
        }
        echo "</td>
                    </tr>
\t\t\t\t\t<tr>
                      <td>Tarifa Mantenimiento</td>
                      <td>";
        // line 106
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 106)) {
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 106, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 106), "TUP_TARIFA_MANTENIMIENTO", [], "any", false, false, false, 106), 2, ".", ","), "html", null, true);
        }
        echo "</td>
                    </tr>
                    
                    <tr>
                      <td>Fecha de creación</td>
                      <td>";
        // line 111
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 111)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 111, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 111), "TUP_CREATED", [], "any", false, false, false, 111), "html", null, true);
        }
        echo "</td>
                    </tr>
                  </tbody>
                </table>
      \t\t</div>
      \t\t
      \t\t<div class=\"col-12 col-lg-6 mt-3 mt-lg-0 table-responsive\">
      \t\t\t<table class=\"table f_table f_tableforfield\">
                  <tbody>
                  \t<tr>
                      <td>Tipo Predio/Codigo</td>
                      <td>";
        // line 122
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoPredio", [], "any", true, true, false, 122)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 122, $this->source); })()), "tipoPredio", [], "any", false, false, false, 122), "TIP_CODIGO", [], "any", false, false, false, 122), "html", null, true);
        }
        echo "</td>
                    </tr>
                  \t<tr>
                      <td>Tipo Predio/Nombre</td>
                      <td>";
        // line 126
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoPredio", [], "any", true, true, false, 126)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 126, $this->source); })()), "tipoPredio", [], "any", false, false, false, 126), "TIP_NOMBRE", [], "any", false, false, false, 126), "html", null, true);
        }
        echo "</td>
                    </tr>
                  </tbody>
                </table>
      \t\t</div>
      \t</div>
  \t</div><!-- /.card-body -->
  \t
  \t<div class=\"row\">
  \t\t<div class=\"col-12\">
  \t\t\t<div class=\"f_cardfooter f_cardfooteractions text-right\">
  \t\t\t\t<a href=\"";
        // line 137
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 137, $this->source); })()), "html", null, true);
        echo "/tipousopredio/editar/";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 137)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 137, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 137), "TUP_CODIGO", [], "any", false, false, false, 137), "html", null, true);
        }
        echo "\" 
  \t\t\t\t\tclass=\"f_linkbtn f_linkbtnaction\">Modificar</a>
  \t\t\t\t<button type=\"button\" class=\"f_button f_buttonactiondelete\" data-toggle=\"modal\" data-target=\"#modalEliminarTipoUsoPredio\">
  \t\t\t\t\tEliminar</button>
  \t\t\t</div>
  \t\t\t
  \t\t</div>
  \t</div><!-- /.card-footer -->

</div><!-- /.card -->



";
        // line 151
        echo "<div class=\"modal fade f_modal\" id=\"modalEliminarTipoUsoPredio\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Eliminar un tipo de uso de predio</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i><span>¿Está seguro de querer eliminar este tipo de uso de predio?</span>
            \t<form class=\"d-none\" id=\"formEliminarTipoUsoPredio\" action=\"";
        // line 162
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 162, $this->source); })()), "html", null, true);
        echo "/tipousopredio/delete\" method=\"post\">
            \t\t<input type=\"hidden\" name=\"codigo\" value=\"";
        // line 163
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 163)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 163, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 163), "TUP_CODIGO", [], "any", false, false, false, 163), "html", null, true);
        }
        echo "\">
            \t</form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnEliminarTipoUsoPredio\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>";
        // line 173
        echo "    
";
    }

    // line 176
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 177
        echo "
\t";
        // line 178
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#btnEliminarTipoUsoPredio').click(function(event){
\t\t\t\$('#formEliminarTipoUsoPredio').submit();
\t\t\treturn false;
\t\t});
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/tipousopredio/tipoUsoPredioDetail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  347 => 178,  344 => 177,  340 => 176,  335 => 173,  321 => 163,  317 => 162,  304 => 151,  284 => 137,  268 => 126,  259 => 122,  243 => 111,  233 => 106,  224 => 102,  215 => 98,  206 => 94,  197 => 90,  188 => 86,  175 => 75,  171 => 74,  167 => 72,  163 => 71,  141 => 52,  135 => 49,  126 => 42,  120 => 37,  117 => 36,  108 => 34,  103 => 33,  101 => 32,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  77 => 24,  74 => 23,  71 => 22,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/tipousopredio/tipoUsoPredioDetail.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\tipousopredio\\tipoUsoPredioDetail.twig");
    }
}
