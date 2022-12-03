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

/* /administration/egreso/egresoDetail.twig */
class __TwigTemplate_5b7e2fa211aa00280f1dc00f6c41556485caed9ae7c81252c26b9af2ebbc2b06 extends \Twig\Template
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
        $context["menuLItem"] = "egreso";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/egreso/egresoDetail.twig", 3);
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
                \t<i class=\"fas fa-money-bill mr-3\"></i>Detalle de egreso
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
          \t\t\t<div class=\"f_imageref\"><span class=\"fas fa-money-bill\" style=\" color: #a69944\"></span></div>
  \t\t\t\t</div>
  \t\t\t\t<div class=\"d-inline-block align-top\">
  \t\t\t\t\t<span class=\"font-weight-bold f_inforef\">
  \t\t\t\t\t    ";
        // line 54
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 54)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 54, $this->source); })()), "egreso", [], "any", false, false, false, 54), "EGR_CODIGO", [], "any", false, false, false, 54), "html", null, true);
        }
        // line 55
        echo "  \t\t\t\t\t</span><br/>
  \t\t\t\t</div>
  \t\t\t</div>
  \t\t\t<div class=\"d-none d-sm-block\">
  \t\t\t\t<span><a href=\"";
        // line 59
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 59, $this->source); })()), "html", null, true);
        echo "/egreso/lista\" class=\"f_link font-weight-bold\">Volver a Lista</a></span>
  \t\t\t</div>
  \t\t</div>
  \t\t<div class=\"col-12 col-lg-6 table-responsive\">
  \t\t\t<table class=\"table f_table f_tableforfield\">
              <tbody>
              \t<tr>
                  <td>Ref.</td>
                  <td>";
        // line 67
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 67)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 67, $this->source); })()), "egreso", [], "any", false, false, false, 67), "EGR_CODIGO", [], "any", false, false, false, 67), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Monto</td>
                  <td>";
        // line 71
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 71)) {
            echo twig_escape_filter($this->env, ("S/. " . twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 71, $this->source); })()), "egreso", [], "any", false, false, false, 71), "EGR_CANTIDAD", [], "any", false, false, false, 71), 2, ".", ",")), "html", null, true);
        }
        echo "</td>
                </tr>
                </tr>
                <tr>
                  <td>Tipo comprobante</td>
                  <td>
                  \t";
        // line 77
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 77)) {
            // line 78
            echo "                  \t\t";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 78, $this->source); })()), "egreso", [], "any", false, false, false, 78), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 78) == 1)) {
                echo "TICKET";
                echo "
                        ";
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 79
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 79, $this->source); })()), "egreso", [], "any", false, false, false, 79), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 79) == 2)) {
                echo "BOLETA";
                echo "
                        ";
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 80
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 80, $this->source); })()), "egreso", [], "any", false, false, false, 80), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 80) == 3)) {
                echo "FACTURA";
                echo "
                        ";
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 81
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 81, $this->source); })()), "egreso", [], "any", false, false, false, 81), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 81) == 4)) {
                echo "SIN COMPROBANTE";
                echo "
                        ";
            }
            // line 83
            echo "                  \t";
        }
        // line 84
        echo "                  </td>
                </tr>
                <tr>
                  <td>Nro. Comprobante</td>
                  <td>";
        // line 88
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 88)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 88, $this->source); })()), "egreso", [], "any", false, false, false, 88), "EGR_COD_COMPROBANTE", [], "any", false, false, false, 88), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Caja</td>
                  <td>
                  \t";
        // line 93
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "caja", [], "any", true, true, false, 93)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 93, $this->source); })()), "caja", [], "any", false, false, false, 93), "CAJ_NOMBRE", [], "any", false, false, false, 93), "html", null, true);
        }
        // line 94
        echo "                  </td>
                </tr>
                <tr>
                  <td>Fecha</td>
                  <td>";
        // line 98
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 98)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 98, $this->source); })()), "egreso", [], "any", false, false, false, 98), "EGR_CREATED", [], "any", false, false, false, 98), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Estado</td>
                  <td>
                  \t";
        // line 103
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 103)) {
            // line 104
            echo "                  \t\t";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 104, $this->source); })()), "egreso", [], "any", false, false, false, 104), "EGR_ESTADO", [], "any", false, false, false, 104) == 0)) {
                // line 105
                echo "                  \t\t\t<span class=\"badge badge-warning\">";
                echo "Anulado";
                echo "</span>
                        ";
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 106
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 106, $this->source); })()), "egreso", [], "any", false, false, false, 106), "EGR_ESTADO", [], "any", false, false, false, 106) == 1)) {
                echo "Activo";
                echo "
                        ";
            }
            // line 108
            echo "                  \t";
        }
        // line 109
        echo "                  </td>
                </tr>
                <tr>
                  <td>Descripción</td>
                  <td>";
        // line 113
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 113)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 113, $this->source); })()), "egreso", [], "any", false, false, false, 113), "EGR_DESCRIPCION", [], "any", false, false, false, 113), "html", null, true);
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
                  <td>Usuario/Ref.</td>
                  <td>";
        // line 123
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "usuario", [], "any", true, true, false, 123)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 123, $this->source); })()), "usuario", [], "any", false, false, false, 123), "USU_CODIGO", [], "any", false, false, false, 123), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Usuario/Nombre</td>
                  <td>";
        // line 127
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "usuario", [], "any", true, true, false, 127)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 127, $this->source); })()), "usuario", [], "any", false, false, false, 127), "USU_NOMBRES", [], "any", false, false, false, 127), "html", null, true);
        }
        echo "</td>
                </tr>
              \t<tr>
                  <td>Usuario/Apellidos</td>
                  <td>";
        // line 131
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "usuario", [], "any", true, true, false, 131)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 131, $this->source); })()), "usuario", [], "any", false, false, false, 131), "USU_APELLIDOS", [], "any", false, false, false, 131), "html", null, true);
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
  \t\t\t    ";
        // line 141
        $context["egresoAnulado"] = false;
        // line 142
        echo "  \t\t\t\t";
        list($context["styleBtnAnular"], $context["titleBtnAnular"]) =         ["f_buttonactiondelete", ""];
        // line 143
        echo "\t\t\t\t";
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 143) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 143, $this->source); })()), "egreso", [], "any", false, false, false, 143), "EGR_ESTADO", [], "any", false, false, false, 143) == 0))) {
            // line 144
            echo "\t\t\t\t    ";
            $context["styleBtnAnular"] = "f_buttonactionrefused";
            // line 145
            echo "\t\t\t\t\t";
            $context["titleBtnAnular"] = "Desactivado porque el egreso ya fue anulado";
            // line 146
            echo "\t\t\t\t\t";
            $context["egresoAnulado"] = true;
            // line 147
            echo "\t\t\t    ";
        }
        // line 148
        echo "\t\t\t    
\t\t\t    ";
        // line 149
        list($context["styleBtnImprimir"], $context["titleBtnImprimir"]) =         ["f_buttonaction", ""];
        // line 150
        echo "\t\t\t\t";
        if (((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 150) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 150, $this->source); })()), "egreso", [], "any", false, false, false, 150), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 150) != 1)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 150, $this->source); })()), "egreso", [], "any", false, false, false, 150), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 150) != 4))) {
            // line 151
            echo "\t\t\t\t    ";
            $context["styleBtnImprimir"] = "f_buttonactionrefused";
            // line 152
            echo "\t\t\t\t\t";
            $context["titleBtnImprimir"] = "Desactivado porque el comprobante no fue emitido por el sistema";
            // line 153
            echo "\t\t\t    ";
        }
        // line 154
        echo "\t\t\t    
\t\t\t    ";
        // line 155
        if ((( !(isset($context["egresoAnulado"]) || array_key_exists("egresoAnulado", $context) ? $context["egresoAnulado"] : (function () { throw new RuntimeError('Variable "egresoAnulado" does not exist.', 155, $this->source); })()) && twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 155)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 155, $this->source); })()), "egreso", [], "any", false, false, false, 155), "EGR_TIPO", [], "any", false, false, false, 155) != "TRANSF"))) {
            // line 156
            echo "    \t\t\t    ";
            if (((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 156, $this->source); })()), "egreso", [], "any", false, false, false, 156), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 156) == 1) || (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 156, $this->source); })()), "egreso", [], "any", false, false, false, 156), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 156) == 4))) {
                // line 157
                echo "    \t\t\t    \t<a href=\"";
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 157, $this->source); })()), "html", null, true);
                echo "/reporte/egreso/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 157, $this->source); })()), "egreso", [], "any", false, false, false, 157), "EGR_CODIGO", [], "any", false, false, false, 157), "html", null, true);
                echo "\" 
      \t\t\t\t\t\tclass=\"f_linkbtn f_linkbtnaction classfortooltip\" id=\"btnImprimir\" title=\"";
                // line 158
                echo twig_escape_filter($this->env, (isset($context["titleBtnImprimir"]) || array_key_exists("titleBtnImprimir", $context) ? $context["titleBtnImprimir"] : (function () { throw new RuntimeError('Variable "titleBtnImprimir" does not exist.', 158, $this->source); })()), "html", null, true);
                echo "\">Imprimir</a>
    \t\t\t    ";
            } else {
                // line 160
                echo "    \t\t\t    \t<a href=\"#\" class=\"f_linkbtn f_linkbtnactionrefused classfortooltip\" id=\"btnImprimir\" title=\"";
                echo twig_escape_filter($this->env, (isset($context["titleBtnImprimir"]) || array_key_exists("titleBtnImprimir", $context) ? $context["titleBtnImprimir"] : (function () { throw new RuntimeError('Variable "titleBtnImprimir" does not exist.', 160, $this->source); })()), "html", null, true);
                echo "\">Imprimir</a>
    \t\t\t    ";
            }
            // line 162
            echo "\t\t\t    ";
        }
        // line 163
        echo "  \t\t\t\t<button type=\"button\" class=\"f_button ";
        echo twig_escape_filter($this->env, (isset($context["styleBtnAnular"]) || array_key_exists("styleBtnAnular", $context) ? $context["styleBtnAnular"] : (function () { throw new RuntimeError('Variable "styleBtnAnular" does not exist.', 163, $this->source); })()), "html", null, true);
        echo " classfortooltip\" data-toggle=\"modal\" 
  \t\t\t\t\t\tdata-target=\"#modalAnularEgreso\" title=\"";
        // line 164
        echo twig_escape_filter($this->env, (isset($context["titleBtnAnular"]) || array_key_exists("titleBtnAnular", $context) ? $context["titleBtnAnular"] : (function () { throw new RuntimeError('Variable "titleBtnAnular" does not exist.', 164, $this->source); })()), "html", null, true);
        echo "\">
  \t\t\t\t\tAnular egreso</button>
  \t\t\t</div>
  \t\t\t
  \t\t</div>
  \t</div><!-- /.card-footer -->
  
</div><!-- /.card -->



";
        // line 176
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 176) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 176, $this->source); })()), "egreso", [], "any", false, false, false, 176), "EGR_ESTADO", [], "any", false, false, false, 176) == 1))) {
            // line 177
            echo "<div class=\"modal fade f_modal\" id=\"modalAnularEgreso\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Anular un Egreso</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i><span>¿Está seguro de querer anular este egreso?</span>
            \t<form class=\"d-none\" id=\"formAnularEgreso\" action=\"";
            // line 188
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 188, $this->source); })()), "html", null, true);
            echo "/egreso/annular\" method=\"post\">
            \t\t<input type=\"hidden\" name=\"codigo\" value=\"";
            // line 189
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 189)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 189, $this->source); })()), "egreso", [], "any", false, false, false, 189), "EGR_CODIGO", [], "any", false, false, false, 189), "html", null, true);
            }
            echo "\">
            \t</form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnAnularEgreso\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>
";
        }
        // line 201
        echo "

";
        // line 204
        if (((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "egreso", [], "any", true, true, false, 204) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 204, $this->source); })()), "egreso", [], "any", false, false, false, 204), "EGR_ESTADO", [], "any", false, false, false, 204) == 1)) && ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 205
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 205, $this->source); })()), "egreso", [], "any", false, false, false, 205), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 205) == 1) || (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 205, $this->source); })()), "egreso", [], "any", false, false, false, 205), "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 205) == 4)))) {
            // line 206
            echo "<div class=\"modal fade f_modal\" id=\"modalMostrarTicket\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-lg\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Ticket</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<div id=\"contentTicket\" src=\"\">

                </div>
            </div>
        </div>
    </div>
</div>
";
        }
        // line 223
        echo " ";
        // line 224
        echo "

    
";
    }

    // line 229
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 230
        echo "
\t";
        // line 231
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#btnAnularEgreso').click(function(event){
\t\t\t\$('#formAnularEgreso').submit();
\t\t\treturn false;
\t\t});
\t\t
\t\t\$('#btnImprimir').click(function(){
            \$('#contentTicket').empty();
            \$('#modalMostrarTicket').modal('show');
            \$('#contentTicket').html('<object style=\"width:100%;height: 600px\" id=\"pdf\" data=\"'+\$(this).attr('href')+'\" type=\"application/pdf\"></object>');
            return false;
        });
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/egreso/egresoDetail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  480 => 231,  477 => 230,  473 => 229,  466 => 224,  464 => 223,  444 => 206,  442 => 205,  441 => 204,  437 => 201,  421 => 189,  417 => 188,  404 => 177,  402 => 176,  388 => 164,  383 => 163,  380 => 162,  374 => 160,  369 => 158,  362 => 157,  359 => 156,  357 => 155,  354 => 154,  351 => 153,  348 => 152,  345 => 151,  342 => 150,  340 => 149,  337 => 148,  334 => 147,  331 => 146,  328 => 145,  325 => 144,  322 => 143,  319 => 142,  317 => 141,  302 => 131,  293 => 127,  284 => 123,  269 => 113,  263 => 109,  260 => 108,  254 => 106,  249 => 105,  246 => 104,  244 => 103,  234 => 98,  228 => 94,  224 => 93,  214 => 88,  208 => 84,  205 => 83,  199 => 81,  194 => 80,  189 => 79,  183 => 78,  181 => 77,  170 => 71,  161 => 67,  150 => 59,  144 => 55,  140 => 54,  126 => 42,  120 => 37,  117 => 36,  108 => 34,  103 => 33,  101 => 32,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  77 => 24,  74 => 23,  71 => 22,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/egreso/egresoDetail.twig", "/home/franco/proyectos/php/jass/resources/views/administration/egreso/egresoDetail.twig");
    }
}
