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

/* /administration/contrato/contratoDetail.twig */
class __TwigTemplate_3e498d0b42e1fce8350dc2980d7cf9c084fd2fbd569002eaa993ebb2365cabfd extends \Twig\Template
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
        $context["menuLItem"] = "contrato";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/contrato/contratoDetail.twig", 3);
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
                \t<i class=\"fas fa-file-contract mr-3\"></i>Detalle de contrato
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
          \t\t\t<div class=\"f_imageref\"><span class=\"fas fa-file-contract\" style=\" color: #a69944\"></span></div>
  \t\t\t\t</div>
  \t\t\t\t<div class=\"d-inline-block align-top\">
  \t\t\t\t\t<span class=\"font-weight-bold f_inforef\">
  \t\t\t\t\t    ";
        // line 54
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 54)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 54, $this->source); })()), "contrato", [], "any", false, false, false, 54), "CTO_CODIGO", [], "any", false, false, false, 54), "html", null, true);
        }
        // line 55
        echo "  \t\t\t\t\t</span><br/>
  \t\t\t\t</div>
  \t\t\t</div>
  \t\t\t<div class=\"d-none d-sm-block\">
  \t\t\t\t<span><a href=\"";
        // line 59
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 59, $this->source); })()), "html", null, true);
        echo "/contrato/lista\" class=\"f_link font-weight-bold\">Volver a Lista</a></span>
  \t\t\t</div>
  \t\t</div>
  \t\t<div class=\"col-12 col-lg-6 table-responsive\">
  \t\t\t<table class=\"table f_table f_tableforfield\">
              <tbody>
              \t<tr>
                  <td>Ref.</td>
                  <td>";
        // line 67
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 67)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 67, $this->source); })()), "contrato", [], "any", false, false, false, 67), "CTO_CODIGO", [], "any", false, false, false, 67), "html", null, true);
        }
        echo "</td>
                </tr>
                </tr>
                <tr>
                  <td>Estado</td>
                  <td>
                  \t";
        // line 73
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 73)) {
            // line 74
            echo "                  \t\t";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 74, $this->source); })()), "contrato", [], "any", false, false, false, 74), "CTO_ESTADO", [], "any", false, false, false, 74) == 0)) {
                // line 75
                echo "                        \t<span class=\"badge badge-warning\">";
                echo "Tramite";
                echo "</span>
                        ";
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 76
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 76, $this->source); })()), "contrato", [], "any", false, false, false, 76), "CTO_ESTADO", [], "any", false, false, false, 76) == 1)) {
                // line 77
                echo "                        \t<span class=\"badge badge-success\">";
                echo "Activo";
                echo "</span>
                        ";
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 78
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 78, $this->source); })()), "contrato", [], "any", false, false, false, 78), "CTO_ESTADO", [], "any", false, false, false, 78) == 2)) {
                // line 79
                echo "                        \t<span class=\"badge badge-danger\">";
                echo "Anulado";
                echo "</span>
                        ";
            }
            // line 81
            echo "                  \t";
        }
        // line 82
        echo "                  </td>
                </tr>
                <tr>
                  <td>Fecha de Tramite</td>
                  <td>";
        // line 86
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 86)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 86, $this->source); })()), "contrato", [], "any", false, false, false, 86), "CTO_FECHA_TRAMITE", [], "any", false, false, false, 86), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de Inicio</td>
                  <td>";
        // line 90
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 90)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 90, $this->source); })()), "contrato", [], "any", false, false, false, 90), "CTO_FECHA_INICIO", [], "any", false, false, false, 90), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de Anulación</td>
                  <td>";
        // line 94
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 94)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 94, $this->source); })()), "contrato", [], "any", false, false, false, 94), "CTO_FECHA_ANULACION", [], "any", false, false, false, 94), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Servicios</td>
                  <td>
                  \t";
        // line 99
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "servicios", [], "any", true, true, false, 99)) {
            // line 100
            echo "                  \t\t";
            $context["cantServicios"] = 0;
            // line 101
            echo "                  \t    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 101, $this->source); })()), "servicios", [], "any", false, false, false, 101));
            foreach ($context['_seq'] as $context["_key"] => $context["servicio"]) {
                // line 102
                echo "                  \t    \t";
                $context["cantServicios"] = ((isset($context["cantServicios"]) || array_key_exists("cantServicios", $context) ? $context["cantServicios"] : (function () { throw new RuntimeError('Variable "cantServicios" does not exist.', 102, $this->source); })()) + 1);
                // line 103
                echo "                  \t    \t";
                if (((isset($context["cantServicios"]) || array_key_exists("cantServicios", $context) ? $context["cantServicios"] : (function () { throw new RuntimeError('Variable "cantServicios" does not exist.', 103, $this->source); })()) > 1)) {
                    echo twig_escape_filter($this->env, (" Y " . twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_NOMBRE", [], "any", false, false, false, 103)), "html", null, true);
                } else {
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_NOMBRE", [], "any", false, false, false, 103), "html", null, true);
                }
                // line 104
                echo "                  \t    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['servicio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 105
            echo "                  \t";
        }
        // line 106
        echo "                  </td>
                </tr>
                <tr>
                  <td>Costo Fijo</td>
                  <td>
                  \t";
        // line 111
        $context["costoFijo"] = 0;
        // line 112
        echo "                  \t";
        $context["tieneAgua"] = false;
        // line 113
        echo "                  \t";
        $context["tieneDesague"] = false;
        // line 114
        echo "                  \t
                  \t";
        // line 115
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "servicios", [], "any", true, true, false, 115)) {
            // line 116
            echo "                  \t\t";
            $context["cantServicios"] = 0;
            // line 117
            echo "                  \t    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 117, $this->source); })()), "servicios", [], "any", false, false, false, 117));
            foreach ($context['_seq'] as $context["_key"] => $context["servicio"]) {
                // line 118
                echo "                  \t    \t";
                if ((twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_CODIGO", [], "any", false, false, false, 118) == 1)) {
                    $context["tieneAgua"] = true;
                    // line 119
                    echo "                  \t    \t";
                } elseif ((twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_CODIGO", [], "any", false, false, false, 119) == 2)) {
                    $context["tieneDesague"] = true;
                }
                // line 120
                echo "                  \t    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['servicio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 121
            echo "                  \t";
        }
        // line 122
        echo "                  \t
                  \t";
        // line 123
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 123)) {
            // line 124
            echo "                  \t\t";
            if (((isset($context["tieneAgua"]) || array_key_exists("tieneAgua", $context) ? $context["tieneAgua"] : (function () { throw new RuntimeError('Variable "tieneAgua" does not exist.', 124, $this->source); })()) && (isset($context["tieneDesague"]) || array_key_exists("tieneDesague", $context) ? $context["tieneDesague"] : (function () { throw new RuntimeError('Variable "tieneDesague" does not exist.', 124, $this->source); })()))) {
                // line 125
                echo "                  \t\t\t";
                $context["costoFijo"] = (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 125, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 125), "TUP_TARIFA_AGUA", [], "any", false, false, false, 125) + twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 125, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 125), "TUP_TARIFA_DESAGUE", [], "any", false, false, false, 125));
                // line 126
                echo "                  \t\t";
            } elseif ((isset($context["tieneAgua"]) || array_key_exists("tieneAgua", $context) ? $context["tieneAgua"] : (function () { throw new RuntimeError('Variable "tieneAgua" does not exist.', 126, $this->source); })())) {
                // line 127
                echo "                  \t\t\t";
                $context["costoFijo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 127, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 127), "TUP_TARIFA_AGUA", [], "any", false, false, false, 127);
                // line 128
                echo "                  \t\t";
            } elseif ((isset($context["tieneDesague"]) || array_key_exists("tieneDesague", $context) ? $context["tieneDesague"] : (function () { throw new RuntimeError('Variable "tieneDesague" does not exist.', 128, $this->source); })())) {
                // line 129
                echo "                  \t\t\t";
                $context["costoFijo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 129, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 129), "TUP_TARIFA_DESAGUE", [], "any", false, false, false, 129);
                // line 130
                echo "                  \t\t";
            }
            // line 131
            echo "\t                ";
        }
        // line 132
        echo "\t                ";
        echo twig_escape_filter($this->env, twig_number_format_filter($this->env, (isset($context["costoFijo"]) || array_key_exists("costoFijo", $context) ? $context["costoFijo"] : (function () { throw new RuntimeError('Variable "costoFijo" does not exist.', 132, $this->source); })()), 2, ".", ","), "html", null, true);
        echo "
                  </td>
                </tr>
                <tr>
                  <td>Tipo de uso de predio</td>
                  <td>";
        // line 137
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 137)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 137, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 137), "TUP_NOMBRE", [], "any", false, false, false, 137), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Observación</td>
                  <td>";
        // line 141
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 141)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 141, $this->source); })()), "contrato", [], "any", false, false, false, 141), "CTO_OBSERVACION", [], "any", false, false, false, 141), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de Creación</td>
                  <td>";
        // line 145
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 145)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 145, $this->source); })()), "contrato", [], "any", false, false, false, 145), "CTO_CREATED", [], "any", false, false, false, 145), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Detalles de servicio</td>
                  <td>
                  \t<button type=\"button\" class=\"f_btngenerico\" style=\"padding:4px 8px; font-size:.8rem\" 
                    \t\tdata-toggle=\"modal\" data-target=\"#modalMasDetalleServicio\" id=\"btnMasDetalleServicios\">
        \t\t\t\tMás detalles
    \t\t\t\t</button>
                  </td>
                </tr>
                
              </tbody>
            </table>
            
            ";
        // line 161
        echo "            <div class=\"modal fade f_modal\" id=\"modalMasDetalleServicio\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">
                <div class=\"modal-dialog modal-lg\" role=\"document\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <span class=\"modal-title\">Detalle de servicios</span>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                            \t<span aria-hidden=\"true\">&times;</span>
                            </button>
                        </div>
                        <div class=\"modal-body\">
                        
                        \t";
        // line 173
        echo "                        \t<div id=\"divNuevoContratoMasDetalles\">
                        \t
                        \t\t";
        // line 175
        if ((isset($context["tieneAgua"]) || array_key_exists("tieneAgua", $context) ? $context["tieneAgua"] : (function () { throw new RuntimeError('Variable "tieneAgua" does not exist.', 175, $this->source); })())) {
            // line 176
            echo "                        \t\t";
            // line 177
            echo "                        \t\t<div id=\"divNuevoContratoMasDetallesAgua\">
                            \t\t<h5 style=\"color:#23878c\" class=\"mb-4\">Servicio de Agua</h5>
                            \t\t<div class=\"col-12 table-responsive\">
                              \t\t\t<table class=\"table f_table f_tableforfield\">
                                          <tbody>
                                            <tr>
                                              <td>Fecha de instalación</td>
                                              <td>";
            // line 184
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 184)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 184, $this->source); })()), "contrato", [], "any", false, false, false, 184), "CTO_AGU_FEC_INS", [], "any", false, false, false, 184), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Caracteristica de conexion</td>
                                              <td>";
            // line 188
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 188)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 188, $this->source); })()), "contrato", [], "any", false, false, false, 188), "CTO_AGU_CAR_CNX", [], "any", false, false, false, 188), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Diametro de conexion</td>
                                              <td>";
            // line 192
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 192) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 192, $this->source); })()), "contrato", [], "any", false, false, false, 192), "CTO_AGU_DTO_CNX", [], "any", false, false, false, 192)))) {
                // line 193
                echo "                                                      ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 193, $this->source); })()), "contrato", [], "any", false, false, false, 193), "CTO_AGU_DTO_CNX", [], "any", false, false, false, 193), "html", null, true);
                echo "\"";
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Diametro de red</td>
                                              <td>";
            // line 197
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 197) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 197, $this->source); })()), "contrato", [], "any", false, false, false, 197), "CTO_AGU_DTO_RED", [], "any", false, false, false, 197)))) {
                // line 198
                echo "                                                      ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 198, $this->source); })()), "contrato", [], "any", false, false, false, 198), "CTO_AGU_DTO_RED", [], "any", false, false, false, 198), "html", null, true);
                echo "\"";
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de conexion</td>
                                              <td>";
            // line 202
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 202)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 202, $this->source); })()), "contrato", [], "any", false, false, false, 202), "CTO_AGU_MAT_CNX", [], "any", false, false, false, 202), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de abrazadera</td>
                                              <td>";
            // line 206
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 206)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 206, $this->source); })()), "contrato", [], "any", false, false, false, 206), "CTO_AGU_MAT_ABA", [], "any", false, false, false, 206), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Ubicación de caja</td>
                                              <td>";
            // line 210
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 210)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 210, $this->source); })()), "contrato", [], "any", false, false, false, 210), "CTO_AGU_UBI_CAJ", [], "any", false, false, false, 210), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de caja</td>
                                              <td>";
            // line 214
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 214)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 214, $this->source); })()), "contrato", [], "any", false, false, false, 214), "CTO_AGU_MAT_CAJ", [], "any", false, false, false, 214), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Estado de caja</td>
                                              <td>";
            // line 218
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 218)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 218, $this->source); })()), "contrato", [], "any", false, false, false, 218), "CTO_AGU_EST_CAJ", [], "any", false, false, false, 218), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de tapa</td>
                                              <td>";
            // line 222
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 222)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 222, $this->source); })()), "contrato", [], "any", false, false, false, 222), "CTO_AGU_MAT_TAP", [], "any", false, false, false, 222), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Estado de tapa</td>
                                              <td>";
            // line 226
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 226)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 226, $this->source); })()), "contrato", [], "any", false, false, false, 226), "CTO_AGU_EST_TAP", [], "any", false, false, false, 226), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </div>
                                </div>";
            // line 232
            echo "                                ";
        }
        // line 233
        echo "                                
                                
                                ";
        // line 235
        if ((isset($context["tieneDesague"]) || array_key_exists("tieneDesague", $context) ? $context["tieneDesague"] : (function () { throw new RuntimeError('Variable "tieneDesague" does not exist.', 235, $this->source); })())) {
            // line 236
            echo "                                ";
            // line 237
            echo "                                <div id=\"divNuevoContratoMasDetallesAlc\">
                                    <h5 style=\"color:#23878c\" class=\"my-4\">Servicio de Alcantarillado</h5>
                            \t\t<div class=\"col-12 table-responsive\">
                              \t\t\t<table class=\"table f_table f_tableforfield\">
                                          <tbody>
                                            <tr>
                                              <td>Fecha de conexion</td>
                                              <td>";
            // line 244
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 244)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 244, $this->source); })()), "contrato", [], "any", false, false, false, 244), "CTO_ALC_FEC_INS", [], "any", false, false, false, 244), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Caracteristica de conexion</td>
                                              <td>";
            // line 248
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 248)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 248, $this->source); })()), "contrato", [], "any", false, false, false, 248), "CTO_ALC_CAR_CNX", [], "any", false, false, false, 248), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Tipo de conexion</td>
                                              <td>";
            // line 252
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 252)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 252, $this->source); })()), "contrato", [], "any", false, false, false, 252), "CTO_ALC_TIP_CNX", [], "any", false, false, false, 252), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Diametro de conexion</td>
                                              <td>";
            // line 256
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 256) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 256, $this->source); })()), "contrato", [], "any", false, false, false, 256), "CTO_ALC_DTO_CNX", [], "any", false, false, false, 256)))) {
                // line 257
                echo "                                                      ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 257, $this->source); })()), "contrato", [], "any", false, false, false, 257), "CTO_ALC_DTO_CNX", [], "any", false, false, false, 257), "html", null, true);
                echo "\"";
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Diametro de red</td>
                                              <td>";
            // line 261
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 261) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 261, $this->source); })()), "contrato", [], "any", false, false, false, 261), "CTO_ALC_DTO_RED", [], "any", false, false, false, 261)))) {
                // line 262
                echo "                                                      ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 262, $this->source); })()), "contrato", [], "any", false, false, false, 262), "CTO_ALC_DTO_RED", [], "any", false, false, false, 262), "html", null, true);
                echo "\"";
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de conexion</td>
                                              <td>";
            // line 266
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 266)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 266, $this->source); })()), "contrato", [], "any", false, false, false, 266), "CTO_ALC_MAT_CNX", [], "any", false, false, false, 266), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Ubicación de caja</td>
                                              <td>";
            // line 270
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 270)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 270, $this->source); })()), "contrato", [], "any", false, false, false, 270), "CTO_ALC_UBI_CAJ", [], "any", false, false, false, 270), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de caja</td>
                                              <td>";
            // line 274
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 274)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 274, $this->source); })()), "contrato", [], "any", false, false, false, 274), "CTO_ALC_MAT_CAJ", [], "any", false, false, false, 274), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Estado de caja</td>
                                              <td>";
            // line 278
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 278)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 278, $this->source); })()), "contrato", [], "any", false, false, false, 278), "CTO_ALC_EST_CAJ", [], "any", false, false, false, 278), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Dimención de caja</td>
                                              <td>";
            // line 282
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 282)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 282, $this->source); })()), "contrato", [], "any", false, false, false, 282), "CTO_ALC_DIM_CAJ", [], "any", false, false, false, 282), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de tapa</td>
                                              <td>";
            // line 286
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 286)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 286, $this->source); })()), "contrato", [], "any", false, false, false, 286), "CTO_ALC_MAT_TAP", [], "any", false, false, false, 286), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Estado de tapa</td>
                                              <td>";
            // line 290
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 290)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 290, $this->source); })()), "contrato", [], "any", false, false, false, 290), "CTO_ALC_EST_TAP", [], "any", false, false, false, 290), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Medidas de tapa</td>
                                              <td>";
            // line 294
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 294)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 294, $this->source); })()), "contrato", [], "any", false, false, false, 294), "CTO_ALC_MED_TAP", [], "any", false, false, false, 294), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </div>
                                </div>";
            // line 300
            echo "                                ";
        }
        // line 301
        echo "                                
                        \t</div>";
        // line 303
        echo "                        </div>
                    </div>
                </div>
            </div>";
        // line 307
        echo "  \t\t</div>
  \t\t
  \t\t<div class=\"col-12 col-lg-6 mt-3 mt-lg-0 table-responsive\">
    \t\t<table class=\"table f_table f_tableforfield\">
              <tbody>
              \t<tr>
                  <td>Predio/Ref.</td>
                  <td>";
        // line 314
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 314)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 314, $this->source); })()), "predio", [], "any", false, false, false, 314), "PRE_CODIGO", [], "any", false, false, false, 314), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Predio/Calle</td>
                  <td>";
        // line 318
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "calle", [], "any", true, true, false, 318)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 318, $this->source); })()), "calle", [], "any", false, false, false, 318), "CAL_NOMBRE", [], "any", false, false, false, 318), "html", null, true);
        }
        echo "</td>
                </tr>
              \t<tr>
                  <td>Predio/Dirección</td>
                  <td>";
        // line 322
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 322)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 322, $this->source); })()), "predio", [], "any", false, false, false, 322), "PRE_DIRECCION", [], "any", false, false, false, 322), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Cliente/Ref.</td>
                  <td>";
        // line 326
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 326)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 326, $this->source); })()), "cliente", [], "any", false, false, false, 326), "CLI_CODIGO", [], "any", false, false, false, 326), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Cliente/Documento.</td>
                  <td>";
        // line 330
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 330)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 330, $this->source); })()), "cliente", [], "any", false, false, false, 330), "CLI_DOCUMENTO", [], "any", false, false, false, 330), "html", null, true);
        }
        echo "</td>
                </tr>
              \t<tr>
                  <td>Cliente/Nombre</td>
                  <td>";
        // line 334
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 334)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 334, $this->source); })()), "cliente", [], "any", false, false, false, 334), "CLI_NOMBRES", [], "any", false, false, false, 334), "html", null, true);
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
  \t\t\t\t";
        // line 344
        list($context["styleBtnAnular"], $context["titleBtnModificar"], $context["titleBtnAnular"]) =         ["f_buttonactiondelete", "", ""];
        // line 345
        echo "\t\t\t\t";
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 345) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 345, $this->source); })()), "contrato", [], "any", false, false, false, 345), "CTO_ESTADO", [], "any", false, false, false, 345) == 2))) {
            // line 346
            echo "\t\t\t\t    ";
            $context["styleBtnAnular"] = "f_buttonactionrefused";
            // line 347
            echo "\t\t\t\t    ";
            $context["titleBtnModificar"] = "Desactivado porque el contrato no se puede modificar,
\t\t\t    \t\t\t\t\t\t\t\tya que fue anulado";
            // line 349
            echo "\t\t\t\t\t";
            $context["titleBtnAnular"] = "Desactivado porque el contrato ya fue anulado";
            // line 350
            echo "\t\t\t    ";
        }
        // line 351
        echo "\t\t\t    
\t\t\t    ";
        // line 352
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 352) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 352, $this->source); })()), "contrato", [], "any", false, false, false, 352), "CTO_ESTADO", [], "any", false, false, false, 352) == 2))) {
            // line 353
            echo "\t\t\t    \t<a href=\"#\" class=\"f_linkbtn f_linkbtnactionrefused classfortooltip\" title=\"";
            echo twig_escape_filter($this->env, (isset($context["titleBtnModificar"]) || array_key_exists("titleBtnModificar", $context) ? $context["titleBtnModificar"] : (function () { throw new RuntimeError('Variable "titleBtnModificar" does not exist.', 353, $this->source); })()), "html", null, true);
            echo "\">Modificar</a>
\t\t\t    ";
        } else {
            // line 355
            echo "\t\t\t    \t<a href=\"";
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 355, $this->source); })()), "html", null, true);
            echo "/contrato/editar/";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 355)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 355, $this->source); })()), "contrato", [], "any", false, false, false, 355), "CTO_CODIGO", [], "any", false, false, false, 355), "html", null, true);
            }
            echo "\" 
  \t\t\t\t\t\tclass=\"f_linkbtn f_linkbtnaction classfortooltip\" title=\"";
            // line 356
            echo twig_escape_filter($this->env, (isset($context["titleBtnModificar"]) || array_key_exists("titleBtnModificar", $context) ? $context["titleBtnModificar"] : (function () { throw new RuntimeError('Variable "titleBtnModificar" does not exist.', 356, $this->source); })()), "html", null, true);
            echo "\">Modificar</a>
\t\t\t    ";
        }
        // line 358
        echo "  \t\t\t\t<button type=\"button\" class=\"f_button ";
        echo twig_escape_filter($this->env, (isset($context["styleBtnAnular"]) || array_key_exists("styleBtnAnular", $context) ? $context["styleBtnAnular"] : (function () { throw new RuntimeError('Variable "styleBtnAnular" does not exist.', 358, $this->source); })()), "html", null, true);
        echo " classfortooltip\" data-toggle=\"modal\" 
  \t\t\t\t\t\tdata-target=\"#modalAnularContrato\" title=\"";
        // line 359
        echo twig_escape_filter($this->env, (isset($context["titleBtnAnular"]) || array_key_exists("titleBtnAnular", $context) ? $context["titleBtnAnular"] : (function () { throw new RuntimeError('Variable "titleBtnAnular" does not exist.', 359, $this->source); })()), "html", null, true);
        echo "\">
  \t\t\t\t\tAnular contrato</button>
  \t\t\t</div>
  \t\t\t
  \t\t</div>
  \t</div><!-- /.card-footer -->
  
</div><!-- /.card -->


";
        // line 370
        echo "<div class=\"row\">
    <div class=\"col-12 col-lg-6\">
        <div class=\"f_card\">
        
          \t<div class=\"row\">
          \t\t<div class=\"col-12\">
          \t\t\t<div class=\"f_cardheader\">
          \t\t\t\t<div class=\"\">Financiamientos (Activos)</div>
          \t\t\t</div>
          \t\t</div>
          \t</div><!-- /.card-header -->
          \t
          \t<div class=\"row\">
              \t<div class=\"col-12 table-responsive\">
          \t\t\t<table class=\"table f_table f_tableliste\">
          \t\t\t  <thead>
          \t\t\t  \t<tr class=\"liste_title\">
          \t\t\t  \t\t<td class=\"liste_title\">Ref.</td>
          \t\t\t  \t\t<td class=\"liste_title\">Monto</td>
          \t\t\t  \t\t<td class=\"liste_title\">Fecha</td>
          \t\t\t  \t</tr>
          \t\t\t  </thead>
                      <tbody>
                      \t";
        // line 393
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "financiamientos", [], "any", true, true, false, 393) &&  !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 393, $this->source); })()), "financiamientos", [], "any", false, false, false, 393)))) {
            // line 394
            echo "                                  \t
                      \t\t";
            // line 395
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 395, $this->source); })()), "financiamientos", [], "any", false, false, false, 395));
            foreach ($context['_seq'] as $context["_key"] => $context["financiamiento"]) {
                // line 396
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"";
                // line 398
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 398, $this->source); })()), "html", null, true);
                echo "/financiamiento/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CODIGO", [], "any", false, false, false, 398), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span class=\"align-middtle\">";
                // line 399
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CODIGO", [], "any", false, false, false, 399), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 402
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_DEUDA", [], "any", false, false, false, 402), 2, ".", ","), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax150\">";
                // line 403
                ((twig_test_empty(twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CREATED", [], "any", false, false, false, 403))) ? (print ("")) : (print (twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CREATED", [], "any", false, false, false, 403), "d/m/Y"), "html", null, true))));
                echo "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['financiamiento'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 406
            echo "                        
                      \t";
        } else {
            // line 408
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 2));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 409
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 413
            echo "                      \t";
        }
        // line 414
        echo "                      </tbody>
                    </table>
            \t</div>
          \t</div><!-- /.card-body -->
          \t
          \t<div class=\"row\">
          \t\t<div class=\"col-12 f_cardfooter text-right\"></div>
          \t</div><!-- /.card-footer -->
        
        </div><!-- /.card -->
    </div>

\t<div class=\"col-12 col-lg-6 mt-3 mt-lg-0\">
\t\t<div class=\"f_card\">
        
          \t<div class=\"row\">
          \t\t<div class=\"col-12\">
          \t\t\t<div class=\"f_cardheader\">
          \t\t\t\t<div class=\"\">Proyectos (Activos)</div>
          \t\t\t</div>
          \t\t</div>
          \t</div><!-- /.card-header -->
          \t
          \t<div class=\"row\">
              \t<div class=\"col-12 table-responsive\">
          \t\t\t<table class=\"table f_table f_tableliste\">
          \t\t\t  <thead>
          \t\t\t  \t<tr class=\"liste_title\">
          \t\t\t  \t\t<td class=\"liste_title\" colspan=\"2\">Proyectos</td>
          \t\t\t  \t</tr>
          \t\t\t  </thead>
                      <tbody>
                      \t";
        // line 446
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "proyectos", [], "any", true, true, false, 446) &&  !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 446, $this->source); })()), "proyectos", [], "any", false, false, false, 446)))) {
            // line 447
            echo "                                  \t
                      \t\t";
            // line 448
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 448, $this->source); })()), "proyectos", [], "any", false, false, false, 448));
            foreach ($context['_seq'] as $context["_key"] => $context["proyecto"]) {
                // line 449
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"";
                // line 451
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 451, $this->source); })()), "html", null, true);
                echo "/proyecto/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["proyecto"], "PTO_CODIGO", [], "any", false, false, false, 451), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span class=\"align-middtle\">";
                // line 452
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["proyecto"], "PTO_CODIGO", [], "any", false, false, false, 452), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 455
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["proyecto"], "PTO_NOMBRE", [], "any", false, false, false, 455), "html", null, true);
                echo "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['proyecto'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 458
            echo "                        
                      \t";
        } else {
            // line 460
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 2));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 461
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 465
            echo "                      \t";
        }
        // line 466
        echo "                      </tbody>
                    </table>
            \t</div>
          \t</div><!-- /.card-body -->
          \t
          \t<div class=\"row\">
          \t\t<div class=\"col-12 f_cardfooter text-right\"></div>
          \t</div><!-- /.card-footer -->
        
        </div><!-- /.card -->
\t</div>

</div>";
        // line 479
        echo "


";
        // line 483
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 483) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 483, $this->source); })()), "contrato", [], "any", false, false, false, 483), "CTO_ESTADO", [], "any", false, false, false, 483) != 2))) {
            // line 484
            echo "<div class=\"modal fade f_modal\" id=\"modalAnularContrato\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Anular un contrato</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i>
            \t<span>¿Está seguro de querer anular este contrato?</span><br/>
            \t<span>
            \t\tSi anula el contrato los financiamientos y cuotas extraordinarias pendientes de pago
            \t\tdejarán de tenerse en cuenta para futuras facturaciones.
            \t</span>
            \t<form class=\"d-none\" id=\"formAnularContrato\" action=\"";
            // line 500
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 500, $this->source); })()), "html", null, true);
            echo "/contrato/annular\" method=\"post\">
            \t\t<input type=\"hidden\" name=\"codigo\" value=\"";
            // line 501
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 501)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 501, $this->source); })()), "contrato", [], "any", false, false, false, 501), "CTO_CODIGO", [], "any", false, false, false, 501), "html", null, true);
            }
            echo "\">
            \t</form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnAnularContrato\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>
";
        }
        // line 513
        echo "    
";
    }

    // line 516
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 517
        echo "
\t";
        // line 518
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#btnAnularContrato').click(function(event){
\t\t\t\$('#formAnularContrato').submit();
\t\t\treturn false;
\t\t});
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/contrato/contratoDetail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1050 => 518,  1047 => 517,  1043 => 516,  1038 => 513,  1022 => 501,  1018 => 500,  1000 => 484,  998 => 483,  993 => 479,  979 => 466,  976 => 465,  967 => 461,  962 => 460,  958 => 458,  949 => 455,  943 => 452,  937 => 451,  933 => 449,  929 => 448,  926 => 447,  924 => 446,  890 => 414,  887 => 413,  878 => 409,  873 => 408,  869 => 406,  860 => 403,  856 => 402,  850 => 399,  844 => 398,  840 => 396,  836 => 395,  833 => 394,  831 => 393,  806 => 370,  793 => 359,  788 => 358,  783 => 356,  774 => 355,  768 => 353,  766 => 352,  763 => 351,  760 => 350,  757 => 349,  753 => 347,  750 => 346,  747 => 345,  745 => 344,  730 => 334,  721 => 330,  712 => 326,  703 => 322,  694 => 318,  685 => 314,  676 => 307,  671 => 303,  668 => 301,  665 => 300,  655 => 294,  646 => 290,  637 => 286,  628 => 282,  619 => 278,  610 => 274,  601 => 270,  592 => 266,  582 => 262,  580 => 261,  570 => 257,  568 => 256,  559 => 252,  550 => 248,  541 => 244,  532 => 237,  530 => 236,  528 => 235,  524 => 233,  521 => 232,  511 => 226,  502 => 222,  493 => 218,  484 => 214,  475 => 210,  466 => 206,  457 => 202,  447 => 198,  445 => 197,  435 => 193,  433 => 192,  424 => 188,  415 => 184,  406 => 177,  404 => 176,  402 => 175,  398 => 173,  385 => 161,  365 => 145,  356 => 141,  347 => 137,  338 => 132,  335 => 131,  332 => 130,  329 => 129,  326 => 128,  323 => 127,  320 => 126,  317 => 125,  314 => 124,  312 => 123,  309 => 122,  306 => 121,  300 => 120,  295 => 119,  291 => 118,  286 => 117,  283 => 116,  281 => 115,  278 => 114,  275 => 113,  272 => 112,  270 => 111,  263 => 106,  260 => 105,  254 => 104,  247 => 103,  244 => 102,  239 => 101,  236 => 100,  234 => 99,  224 => 94,  215 => 90,  206 => 86,  200 => 82,  197 => 81,  191 => 79,  189 => 78,  184 => 77,  182 => 76,  177 => 75,  174 => 74,  172 => 73,  161 => 67,  150 => 59,  144 => 55,  140 => 54,  126 => 42,  120 => 37,  117 => 36,  108 => 34,  103 => 33,  101 => 32,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  77 => 24,  74 => 23,  71 => 22,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/contrato/contratoDetail.twig", "/home/franco/proyectos/php/jass/resources/views/administration/contrato/contratoDetail.twig");
    }
}
