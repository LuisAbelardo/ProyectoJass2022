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
class __TwigTemplate_c9f2f292328628bacb9f17f0e69b0cc9d172d109e5d9251f6afd599cafa94943 extends \Twig\Template
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
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 80
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 80, $this->source); })()), "contrato", [], "any", false, false, false, 80), "CTO_ESTADO", [], "any", false, false, false, 80) == 3)) {
                // line 81
                echo "                        \t<span class=\"badge badge-info\">";
                echo "Cortado";
                echo "</span>
                        ";
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 82
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 82, $this->source); })()), "contrato", [], "any", false, false, false, 82), "CTO_ESTADO", [], "any", false, false, false, 82) == 4)) {
                // line 83
                echo "                        <span class=\"badge badge-success\">";
                echo "Mantenimiento";
                echo "</span>
                        ";
            } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 84
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 84, $this->source); })()), "contrato", [], "any", false, false, false, 84), "CTO_ESTADO", [], "any", false, false, false, 84) == 5)) {
                // line 85
                echo "                        <span class=\"badge badge-secondary\">";
                echo "Pasivo";
                echo "</span>
                        ";
            }
            // line 87
            echo "                  \t";
        }
        // line 88
        echo "                  </td>
                </tr>
                <tr>
                  <td>Fecha de Tramite</td>
                  <td>";
        // line 92
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 92)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 92, $this->source); })()), "contrato", [], "any", false, false, false, 92), "CTO_FECHA_TRAMITE", [], "any", false, false, false, 92), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de Inicio</td>
                  <td>";
        // line 96
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 96)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 96, $this->source); })()), "contrato", [], "any", false, false, false, 96), "CTO_FECHA_INICIO", [], "any", false, false, false, 96), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de Anulación</td>
                  <td>";
        // line 100
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 100)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 100, $this->source); })()), "contrato", [], "any", false, false, false, 100), "CTO_FECHA_ANULACION", [], "any", false, false, false, 100), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de Corte</td>
                  <td>";
        // line 104
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 104)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 104, $this->source); })()), "contrato", [], "any", false, false, false, 104), "CTO_FECHA_SUSPENCION", [], "any", false, false, false, 104), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de Reconección</td>
                  <td>";
        // line 108
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 108)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 108, $this->source); })()), "contrato", [], "any", false, false, false, 108), "CTO_FECHA_RECONECCION", [], "any", false, false, false, 108), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha Inicio Mantenimiento</td>
                  <td>";
        // line 112
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 112)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 112, $this->source); })()), "contrato", [], "any", false, false, false, 112), "CTO_FECHA_INICIO_MANTENIMIENTO", [], "any", false, false, false, 112), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha Fin Mantenimiento</td>
                  <td>";
        // line 116
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 116)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 116, $this->source); })()), "contrato", [], "any", false, false, false, 116), "CTO_FECHA_FIN_MANTENIMIENTO", [], "any", false, false, false, 116), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Servicios</td>
                  <td>
                  \t";
        // line 121
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "servicios", [], "any", true, true, false, 121)) {
            // line 122
            echo "                  \t\t";
            $context["cantServicios"] = 0;
            // line 123
            echo "                  \t    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 123, $this->source); })()), "servicios", [], "any", false, false, false, 123));
            foreach ($context['_seq'] as $context["_key"] => $context["servicio"]) {
                // line 124
                echo "                  \t    \t";
                $context["cantServicios"] = ((isset($context["cantServicios"]) || array_key_exists("cantServicios", $context) ? $context["cantServicios"] : (function () { throw new RuntimeError('Variable "cantServicios" does not exist.', 124, $this->source); })()) + 1);
                // line 125
                echo "                  \t    \t";
                if (((isset($context["cantServicios"]) || array_key_exists("cantServicios", $context) ? $context["cantServicios"] : (function () { throw new RuntimeError('Variable "cantServicios" does not exist.', 125, $this->source); })()) > 1)) {
                    echo twig_escape_filter($this->env, (" Y " . twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_NOMBRE", [], "any", false, false, false, 125)), "html", null, true);
                } else {
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_NOMBRE", [], "any", false, false, false, 125), "html", null, true);
                }
                // line 126
                echo "                  \t    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['servicio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 127
            echo "                  \t";
        }
        // line 128
        echo "                  </td>
                </tr>
                <tr>
                  <td>Costo Fijo</td>
                  <td>
                  ";
        // line 159
        echo "
                  ";
        // line 160
        $context["costoFijo"] = 0;
        // line 161
        echo "                  ";
        $context["tieneAgua"] = false;
        // line 162
        echo "                  ";
        $context["tieneDesague"] = false;
        // line 163
        echo "                  
                  ";
        // line 164
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "servicios", [], "any", true, true, false, 164)) {
            // line 165
            echo "                    ";
            $context["cantServicios"] = 0;
            // line 166
            echo "                      ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 166, $this->source); })()), "servicios", [], "any", false, false, false, 166));
            foreach ($context['_seq'] as $context["_key"] => $context["servicio"]) {
                // line 167
                echo "                        ";
                if ((twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_CODIGO", [], "any", false, false, false, 167) == 1)) {
                    $context["tieneAgua"] = true;
                    // line 168
                    echo "                        ";
                } elseif ((twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_CODIGO", [], "any", false, false, false, 168) == 2)) {
                    $context["tieneDesague"] = true;
                }
                // line 169
                echo "                      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['servicio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 170
            echo "                  ";
        }
        // line 171
        echo "
                  ";
        // line 173
        echo "                  ";
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 173) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 173, $this->source); })()), "contrato", [], "any", false, false, false, 173), "CTO_ESTADO", [], "any", false, false, false, 173) == 4))) {
            // line 174
            echo "                    ";
            $context["costoFijo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 174, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 174), "TUP_TARIFA_MANTENIMIENTO", [], "any", false, false, false, 174);
            // line 175
            echo "                  ";
        } else {
            // line 176
            echo "                    ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 176)) {
                // line 177
                echo "                      ";
                if (((isset($context["tieneAgua"]) || array_key_exists("tieneAgua", $context) ? $context["tieneAgua"] : (function () { throw new RuntimeError('Variable "tieneAgua" does not exist.', 177, $this->source); })()) && (isset($context["tieneDesague"]) || array_key_exists("tieneDesague", $context) ? $context["tieneDesague"] : (function () { throw new RuntimeError('Variable "tieneDesague" does not exist.', 177, $this->source); })()))) {
                    // line 178
                    echo "                        ";
                    $context["costoFijo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 178, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 178), "TUP_TARIFA_AMBOS", [], "any", false, false, false, 178);
                    // line 179
                    echo "                      ";
                } elseif ((isset($context["tieneAgua"]) || array_key_exists("tieneAgua", $context) ? $context["tieneAgua"] : (function () { throw new RuntimeError('Variable "tieneAgua" does not exist.', 179, $this->source); })())) {
                    // line 180
                    echo "                        ";
                    $context["costoFijo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 180, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 180), "TUP_TARIFA_AGUA", [], "any", false, false, false, 180);
                    // line 181
                    echo "                      ";
                } elseif ((isset($context["tieneDesague"]) || array_key_exists("tieneDesague", $context) ? $context["tieneDesague"] : (function () { throw new RuntimeError('Variable "tieneDesague" does not exist.', 181, $this->source); })())) {
                    // line 182
                    echo "                        ";
                    $context["costoFijo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 182, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 182), "TUP_TARIFA_DESAGUE", [], "any", false, false, false, 182);
                    // line 183
                    echo "                      ";
                }
                // line 184
                echo "                    ";
            }
            echo " 
                  ";
        }
        // line 186
        echo "                  ";
        echo twig_escape_filter($this->env, twig_number_format_filter($this->env, (isset($context["costoFijo"]) || array_key_exists("costoFijo", $context) ? $context["costoFijo"] : (function () { throw new RuntimeError('Variable "costoFijo" does not exist.', 186, $this->source); })()), 2, ".", ","), "html", null, true);
        echo "
                  
                  </td>
                </tr>
                <tr>
                  <td>Tipo de uso de predio</td>
                  <td>";
        // line 192
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 192)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 192, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 192), "TUP_NOMBRE", [], "any", false, false, false, 192), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Observación</td>
                  <td>";
        // line 196
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 196)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 196, $this->source); })()), "contrato", [], "any", false, false, false, 196), "CTO_OBSERVACION", [], "any", false, false, false, 196), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Fecha de Creación</td>
                  <td>";
        // line 200
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 200)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 200, $this->source); })()), "contrato", [], "any", false, false, false, 200), "CTO_CREATED", [], "any", false, false, false, 200), "html", null, true);
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
        // line 216
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
        // line 228
        echo "                        \t<div id=\"divNuevoContratoMasDetalles\">
                        \t
                        \t\t";
        // line 230
        if ((isset($context["tieneAgua"]) || array_key_exists("tieneAgua", $context) ? $context["tieneAgua"] : (function () { throw new RuntimeError('Variable "tieneAgua" does not exist.', 230, $this->source); })())) {
            // line 231
            echo "                        \t\t";
            // line 232
            echo "                        \t\t<div id=\"divNuevoContratoMasDetallesAgua\">
                            \t\t<h5 style=\"color:#23878c\" class=\"mb-4\">Servicio de Agua</h5>
                            \t\t<div class=\"col-12 table-responsive\">
                              \t\t\t<table class=\"table f_table f_tableforfield\">
                                          <tbody>
                                            <tr>
                                              <td>Fecha de instalación</td>
                                              <td>";
            // line 239
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 239)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 239, $this->source); })()), "contrato", [], "any", false, false, false, 239), "CTO_AGU_FEC_INS", [], "any", false, false, false, 239), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Caracteristica de conexion</td>
                                              <td>";
            // line 243
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 243)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 243, $this->source); })()), "contrato", [], "any", false, false, false, 243), "CTO_AGU_CAR_CNX", [], "any", false, false, false, 243), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Diametro de conexion</td>
                                              <td>";
            // line 247
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 247) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 247, $this->source); })()), "contrato", [], "any", false, false, false, 247), "CTO_AGU_DTO_CNX", [], "any", false, false, false, 247)))) {
                // line 248
                echo "                                                      ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 248, $this->source); })()), "contrato", [], "any", false, false, false, 248), "CTO_AGU_DTO_CNX", [], "any", false, false, false, 248), "html", null, true);
                echo "\"";
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Diametro de red</td>
                                              <td>";
            // line 252
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 252) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 252, $this->source); })()), "contrato", [], "any", false, false, false, 252), "CTO_AGU_DTO_RED", [], "any", false, false, false, 252)))) {
                // line 253
                echo "                                                      ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 253, $this->source); })()), "contrato", [], "any", false, false, false, 253), "CTO_AGU_DTO_RED", [], "any", false, false, false, 253), "html", null, true);
                echo "\"";
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de conexion</td>
                                              <td>";
            // line 257
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 257)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 257, $this->source); })()), "contrato", [], "any", false, false, false, 257), "CTO_AGU_MAT_CNX", [], "any", false, false, false, 257), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de abrazadera</td>
                                              <td>";
            // line 261
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 261)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 261, $this->source); })()), "contrato", [], "any", false, false, false, 261), "CTO_AGU_MAT_ABA", [], "any", false, false, false, 261), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Ubicación de caja</td>
                                              <td>";
            // line 265
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 265)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 265, $this->source); })()), "contrato", [], "any", false, false, false, 265), "CTO_AGU_UBI_CAJ", [], "any", false, false, false, 265), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de caja</td>
                                              <td>";
            // line 269
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 269)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 269, $this->source); })()), "contrato", [], "any", false, false, false, 269), "CTO_AGU_MAT_CAJ", [], "any", false, false, false, 269), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Estado de caja</td>
                                              <td>";
            // line 273
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 273)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 273, $this->source); })()), "contrato", [], "any", false, false, false, 273), "CTO_AGU_EST_CAJ", [], "any", false, false, false, 273), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de tapa</td>
                                              <td>";
            // line 277
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 277)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 277, $this->source); })()), "contrato", [], "any", false, false, false, 277), "CTO_AGU_MAT_TAP", [], "any", false, false, false, 277), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Estado de tapa</td>
                                              <td>";
            // line 281
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 281)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 281, $this->source); })()), "contrato", [], "any", false, false, false, 281), "CTO_AGU_EST_TAP", [], "any", false, false, false, 281), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </div>
                                </div>";
            // line 287
            echo "                                ";
        }
        // line 288
        echo "                                
                                
                                ";
        // line 290
        if ((isset($context["tieneDesague"]) || array_key_exists("tieneDesague", $context) ? $context["tieneDesague"] : (function () { throw new RuntimeError('Variable "tieneDesague" does not exist.', 290, $this->source); })())) {
            // line 291
            echo "                                ";
            // line 292
            echo "                                <div id=\"divNuevoContratoMasDetallesAlc\">
                                    <h5 style=\"color:#23878c\" class=\"my-4\">Servicio de Alcantarillado</h5>
                            \t\t<div class=\"col-12 table-responsive\">
                              \t\t\t<table class=\"table f_table f_tableforfield\">
                                          <tbody>
                                            <tr>
                                              <td>Fecha de conexion</td>
                                              <td>";
            // line 299
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 299)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 299, $this->source); })()), "contrato", [], "any", false, false, false, 299), "CTO_ALC_FEC_INS", [], "any", false, false, false, 299), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Caracteristica de conexion</td>
                                              <td>";
            // line 303
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 303)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 303, $this->source); })()), "contrato", [], "any", false, false, false, 303), "CTO_ALC_CAR_CNX", [], "any", false, false, false, 303), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Tipo de conexion</td>
                                              <td>";
            // line 307
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 307)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 307, $this->source); })()), "contrato", [], "any", false, false, false, 307), "CTO_ALC_TIP_CNX", [], "any", false, false, false, 307), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Diametro de conexion</td>
                                              <td>";
            // line 311
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 311) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 311, $this->source); })()), "contrato", [], "any", false, false, false, 311), "CTO_ALC_DTO_CNX", [], "any", false, false, false, 311)))) {
                // line 312
                echo "                                                      ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 312, $this->source); })()), "contrato", [], "any", false, false, false, 312), "CTO_ALC_DTO_CNX", [], "any", false, false, false, 312), "html", null, true);
                echo "\"";
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Diametro de red</td>
                                              <td>";
            // line 316
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 316) &&  !(null === twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 316, $this->source); })()), "contrato", [], "any", false, false, false, 316), "CTO_ALC_DTO_RED", [], "any", false, false, false, 316)))) {
                // line 317
                echo "                                                      ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 317, $this->source); })()), "contrato", [], "any", false, false, false, 317), "CTO_ALC_DTO_RED", [], "any", false, false, false, 317), "html", null, true);
                echo "\"";
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de conexion</td>
                                              <td>";
            // line 321
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 321)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 321, $this->source); })()), "contrato", [], "any", false, false, false, 321), "CTO_ALC_MAT_CNX", [], "any", false, false, false, 321), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Ubicación de caja</td>
                                              <td>";
            // line 325
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 325)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 325, $this->source); })()), "contrato", [], "any", false, false, false, 325), "CTO_ALC_UBI_CAJ", [], "any", false, false, false, 325), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de caja</td>
                                              <td>";
            // line 329
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 329)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 329, $this->source); })()), "contrato", [], "any", false, false, false, 329), "CTO_ALC_MAT_CAJ", [], "any", false, false, false, 329), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Estado de caja</td>
                                              <td>";
            // line 333
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 333)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 333, $this->source); })()), "contrato", [], "any", false, false, false, 333), "CTO_ALC_EST_CAJ", [], "any", false, false, false, 333), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Dimención de caja</td>
                                              <td>";
            // line 337
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 337)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 337, $this->source); })()), "contrato", [], "any", false, false, false, 337), "CTO_ALC_DIM_CAJ", [], "any", false, false, false, 337), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Material de tapa</td>
                                              <td>";
            // line 341
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 341)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 341, $this->source); })()), "contrato", [], "any", false, false, false, 341), "CTO_ALC_MAT_TAP", [], "any", false, false, false, 341), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Estado de tapa</td>
                                              <td>";
            // line 345
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 345)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 345, $this->source); })()), "contrato", [], "any", false, false, false, 345), "CTO_ALC_EST_TAP", [], "any", false, false, false, 345), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                            <tr>
                                              <td>Medidas de tapa</td>
                                              <td>";
            // line 349
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 349)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 349, $this->source); })()), "contrato", [], "any", false, false, false, 349), "CTO_ALC_MED_TAP", [], "any", false, false, false, 349), "html", null, true);
            }
            echo "</td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </div>
                                </div>";
            // line 355
            echo "                                ";
        }
        // line 356
        echo "                                
                        \t</div>";
        // line 358
        echo "                        </div>
                    </div>
                </div>
            </div>";
        // line 362
        echo "  \t\t</div>
  \t\t
  \t\t<div class=\"col-12 col-lg-6 mt-3 mt-lg-0 table-responsive\">
    \t\t<table class=\"table f_table f_tableforfield\">
              <tbody>
              \t<tr>
                  <td>Predio/Ref.</td>
                  <td>";
        // line 369
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 369)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 369, $this->source); })()), "predio", [], "any", false, false, false, 369), "PRE_CODIGO", [], "any", false, false, false, 369), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Predio/Calle</td>
                  <td>";
        // line 373
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "calle", [], "any", true, true, false, 373)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 373, $this->source); })()), "calle", [], "any", false, false, false, 373), "CAL_NOMBRE", [], "any", false, false, false, 373), "html", null, true);
        }
        echo "</td>
                </tr>
              \t<tr>
                  <td>Predio/Dirección</td>
                  <td>";
        // line 377
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 377)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 377, $this->source); })()), "predio", [], "any", false, false, false, 377), "PRE_DIRECCION", [], "any", false, false, false, 377), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Cliente/Ref.</td>
                  <td>";
        // line 381
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 381)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 381, $this->source); })()), "cliente", [], "any", false, false, false, 381), "CLI_CODIGO", [], "any", false, false, false, 381), "html", null, true);
        }
        echo "</td>
                </tr>
                <tr>
                  <td>Cliente/Documento.</td>
                  <td>";
        // line 385
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 385)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 385, $this->source); })()), "cliente", [], "any", false, false, false, 385), "CLI_DOCUMENTO", [], "any", false, false, false, 385), "html", null, true);
        }
        echo "</td>
                </tr>
              \t<tr>
                  <td>Cliente/Nombre</td>
                  <td>";
        // line 389
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 389)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 389, $this->source); })()), "cliente", [], "any", false, false, false, 389), "CLI_NOMBRES", [], "any", false, false, false, 389), "html", null, true);
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
        // line 399
        list($context["styleBtnAnular"], $context["titleBtnModificar"], $context["titleBtnAnular"]) =         ["f_buttonactiondelete", "", ""];
        // line 400
        echo "\t\t\t\t";
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 400) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 400, $this->source); })()), "contrato", [], "any", false, false, false, 400), "CTO_ESTADO", [], "any", false, false, false, 400) == 2))) {
            // line 401
            echo "\t\t\t\t    ";
            $context["styleBtnAnular"] = "f_buttonactionrefused";
            // line 402
            echo "\t\t\t\t    ";
            $context["titleBtnModificar"] = "Desactivado porque el contrato no se puede modificar,
\t\t\t    \t\t\t\t\t\t\t\tya que fue anulado";
            // line 404
            echo "\t\t\t\t\t";
            $context["titleBtnAnular"] = "Desactivado porque el contrato ya fue anulado";
            // line 405
            echo "\t\t\t    ";
        }
        // line 406
        echo "\t\t\t    
\t\t\t    ";
        // line 407
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 407) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 407, $this->source); })()), "contrato", [], "any", false, false, false, 407), "CTO_ESTADO", [], "any", false, false, false, 407) == 2))) {
            // line 408
            echo "\t\t\t    \t<a href=\"#\" class=\"f_linkbtn f_linkbtnactionrefused classfortooltip\" title=\"";
            echo twig_escape_filter($this->env, (isset($context["titleBtnModificar"]) || array_key_exists("titleBtnModificar", $context) ? $context["titleBtnModificar"] : (function () { throw new RuntimeError('Variable "titleBtnModificar" does not exist.', 408, $this->source); })()), "html", null, true);
            echo "\">Modificar</a>
\t\t\t    ";
        } else {
            // line 410
            echo "
\t\t\t    \t<a href=\"";
            // line 411
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 411, $this->source); })()), "html", null, true);
            echo "/contrato/editar/";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 411)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 411, $this->source); })()), "contrato", [], "any", false, false, false, 411), "CTO_CODIGO", [], "any", false, false, false, 411), "html", null, true);
            }
            echo "\" 
  \t\t\t\t\t\tclass=\"f_linkbtn f_linkbtnaction classfortooltip\" title=\"";
            // line 412
            echo twig_escape_filter($this->env, (isset($context["titleBtnModificar"]) || array_key_exists("titleBtnModificar", $context) ? $context["titleBtnModificar"] : (function () { throw new RuntimeError('Variable "titleBtnModificar" does not exist.', 412, $this->source); })()), "html", null, true);
            echo "\">Modificar</a>
\t\t\t    ";
        }
        // line 414
        echo "
          ";
        // line 415
        if (((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 415) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 415, $this->source); })()), "contrato", [], "any", false, false, false, 415), "CTO_ESTADO", [], "any", false, false, false, 415) == 1)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 415, $this->source); })()), "tipoPredio", [], "any", false, false, false, 415), "TIP_CODIGO", [], "any", false, false, false, 415) == 1))) {
            // line 416
            echo "            <button type=\"button\" class=\"f_button ";
            echo twig_escape_filter($this->env, (isset($context["styleBtnAnular"]) || array_key_exists("styleBtnAnular", $context) ? $context["styleBtnAnular"] : (function () { throw new RuntimeError('Variable "styleBtnAnular" does not exist.', 416, $this->source); })()), "html", null, true);
            echo " classfortooltip\" data-toggle=\"modal\" 
  \t\t\t\t\t\tdata-target=\"#modalMantenimientoContrato\" title=\"\">
  \t\t\t\t\tMantenimiento</button>
          ";
        }
        // line 420
        echo "
          ";
        // line 421
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 421) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 421, $this->source); })()), "contrato", [], "any", false, false, false, 421), "CTO_ESTADO", [], "any", false, false, false, 421) == 4))) {
            // line 422
            echo "            <button type=\"button\" class=\"f_button ";
            echo twig_escape_filter($this->env, (isset($context["styleBtnAnular"]) || array_key_exists("styleBtnAnular", $context) ? $context["styleBtnAnular"] : (function () { throw new RuntimeError('Variable "styleBtnAnular" does not exist.', 422, $this->source); })()), "html", null, true);
            echo " classfortooltip\" data-toggle=\"modal\" 
  \t\t\t\t\t\tdata-target=\"#modalFinMantenimientoContrato\" title=\"\">
  \t\t\t\t\tActivar</button>
          ";
        }
        // line 426
        echo "          
          ";
        // line 427
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 427) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 427, $this->source); })()), "contrato", [], "any", false, false, false, 427), "CTO_ESTADO", [], "any", false, false, false, 427) == 1))) {
            // line 428
            echo "            <button type=\"button\" class=\"f_button ";
            echo twig_escape_filter($this->env, (isset($context["styleBtnAnular"]) || array_key_exists("styleBtnAnular", $context) ? $context["styleBtnAnular"] : (function () { throw new RuntimeError('Variable "styleBtnAnular" does not exist.', 428, $this->source); })()), "html", null, true);
            echo " classfortooltip\" data-toggle=\"modal\" 
  \t\t\t\t\t\tdata-target=\"#modalSuspenderContrato\" title=\"\">
  \t\t\t\t\tCorte</button>
          ";
        }
        // line 432
        echo "
          
          ";
        // line 434
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 434) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 434, $this->source); })()), "contrato", [], "any", false, false, false, 434), "CTO_ESTADO", [], "any", false, false, false, 434) == 3))) {
            // line 435
            echo "            <button type=\"button\" class=\"f_button ";
            echo twig_escape_filter($this->env, (isset($context["styleBtnAnular"]) || array_key_exists("styleBtnAnular", $context) ? $context["styleBtnAnular"] : (function () { throw new RuntimeError('Variable "styleBtnAnular" does not exist.', 435, $this->source); })()), "html", null, true);
            echo " classfortooltip\" data-toggle=\"modal\" 
  \t\t\t\t\t\tdata-target=\"#modalReconectarContrato\" title=\"\">
  \t\t\t\t\tReconectar</button>
          ";
        }
        // line 439
        echo "
          
  \t\t\t\t<button type=\"button\" class=\"f_button ";
        // line 441
        echo twig_escape_filter($this->env, (isset($context["styleBtnAnular"]) || array_key_exists("styleBtnAnular", $context) ? $context["styleBtnAnular"] : (function () { throw new RuntimeError('Variable "styleBtnAnular" does not exist.', 441, $this->source); })()), "html", null, true);
        echo " classfortooltip\" data-toggle=\"modal\" 
  \t\t\t\t\t\tdata-target=\"#modalAnularContrato\" title=\"";
        // line 442
        echo twig_escape_filter($this->env, (isset($context["titleBtnAnular"]) || array_key_exists("titleBtnAnular", $context) ? $context["titleBtnAnular"] : (function () { throw new RuntimeError('Variable "titleBtnAnular" does not exist.', 442, $this->source); })()), "html", null, true);
        echo "\">
  \t\t\t\t\tAnular contrato</button>
  \t\t\t</div>
  \t\t\t
  \t\t</div>
  \t</div><!-- /.card-footer -->
  
</div><!-- /.card -->


";
        // line 453
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
        // line 476
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "financiamientos", [], "any", true, true, false, 476) &&  !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 476, $this->source); })()), "financiamientos", [], "any", false, false, false, 476)))) {
            // line 477
            echo "                                  \t
                      \t\t";
            // line 478
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 478, $this->source); })()), "financiamientos", [], "any", false, false, false, 478));
            foreach ($context['_seq'] as $context["_key"] => $context["financiamiento"]) {
                // line 479
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"";
                // line 481
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 481, $this->source); })()), "html", null, true);
                echo "/financiamiento/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CODIGO", [], "any", false, false, false, 481), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span class=\"align-middtle\">";
                // line 482
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CODIGO", [], "any", false, false, false, 482), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 485
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_DEUDA", [], "any", false, false, false, 485), 2, ".", ","), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax150\">";
                // line 486
                ((twig_test_empty(twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CREATED", [], "any", false, false, false, 486))) ? (print ("")) : (print (twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CREATED", [], "any", false, false, false, 486), "d/m/Y"), "html", null, true))));
                echo "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['financiamiento'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 489
            echo "                        
                      \t";
        } else {
            // line 491
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 2));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 492
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 496
            echo "                      \t";
        }
        // line 497
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
        // line 529
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "proyectos", [], "any", true, true, false, 529) &&  !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 529, $this->source); })()), "proyectos", [], "any", false, false, false, 529)))) {
            // line 530
            echo "                                  \t
                      \t\t";
            // line 531
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 531, $this->source); })()), "proyectos", [], "any", false, false, false, 531));
            foreach ($context['_seq'] as $context["_key"] => $context["proyecto"]) {
                // line 532
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"";
                // line 534
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 534, $this->source); })()), "html", null, true);
                echo "/proyecto/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["proyecto"], "PTO_CODIGO", [], "any", false, false, false, 534), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span class=\"align-middtle\">";
                // line 535
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["proyecto"], "PTO_CODIGO", [], "any", false, false, false, 535), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 538
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["proyecto"], "PTO_NOMBRE", [], "any", false, false, false, 538), "html", null, true);
                echo "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['proyecto'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 541
            echo "                        
                      \t";
        } else {
            // line 543
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 2));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 544
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 548
            echo "                      \t";
        }
        // line 549
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
        // line 562
        echo "


";
        // line 566
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 566) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 566, $this->source); })()), "contrato", [], "any", false, false, false, 566), "CTO_ESTADO", [], "any", false, false, false, 566) != 2))) {
            // line 567
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
            // line 583
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 583, $this->source); })()), "html", null, true);
            echo "/contrato/annular\" method=\"post\">
            \t\t<input type=\"hidden\" name=\"codigo\" value=\"";
            // line 584
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 584)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 584, $this->source); })()), "contrato", [], "any", false, false, false, 584), "CTO_CODIGO", [], "any", false, false, false, 584), "html", null, true);
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
        // line 596
        echo "



";
        // line 601
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 601) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 601, $this->source); })()), "contrato", [], "any", false, false, false, 601), "CTO_ESTADO", [], "any", false, false, false, 601) != 2))) {
            // line 602
            echo "<div class=\"modal fade f_modal\" id=\"modalSuspenderContrato\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Corte de contrato</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i>
            \t<span>¿Está seguro de querer cortar este contrato?</span><br/>
            \t<span>
            \t\tSi corta este contrato dejará de emitirse los recibos para futuras facturaciones mensuales.
            \t</span>
              <form class=\"d-none\" id=\"formSuspenderContrato\" action=\"";
            // line 617
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 617, $this->source); })()), "html", null, true);
            echo "/contrato/suspend\" method=\"post\">
            \t  <input type=\"hidden\" name=\"codigo\" value=\"";
            // line 618
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 618)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 618, $this->source); })()), "contrato", [], "any", false, false, false, 618), "CTO_CODIGO", [], "any", false, false, false, 618), "html", null, true);
            }
            echo "\">
              </form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnSuspenderContrato\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>
";
        }
        // line 630
        echo "


";
        // line 634
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 634) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 634, $this->source); })()), "contrato", [], "any", false, false, false, 634), "CTO_ESTADO", [], "any", false, false, false, 634) == 3))) {
            // line 635
            echo "<div class=\"modal fade f_modal\" id=\"modalReconectarContrato\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Reconectar un contrato</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i>
            \t<span>¿Está seguro de querer reconectar este contrato?</span><br/>
            \t<span>
            \t\tAl reconectar este contrato continuará con el servicio y se emitirá los recibos en las siguientes facturaciones mensuales.
            \t</span>
              <form class=\"d-none\" id=\"formReconectarContrato\" action=\"";
            // line 650
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 650, $this->source); })()), "html", null, true);
            echo "/contrato/reconnect\" method=\"post\">
            \t  <input type=\"hidden\" name=\"codigo\" value=\"";
            // line 651
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 651)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 651, $this->source); })()), "contrato", [], "any", false, false, false, 651), "CTO_CODIGO", [], "any", false, false, false, 651), "html", null, true);
            }
            echo "\">
              </form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnReconectarContrato\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>
";
        }
        // line 663
        echo "


";
        // line 667
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 667) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 667, $this->source); })()), "contrato", [], "any", false, false, false, 667), "CTO_ESTADO", [], "any", false, false, false, 667) != 2))) {
            // line 668
            echo "<div class=\"modal fade f_modal\" id=\"modalMantenimientoContrato\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Mantenimiento un contrato</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i>
            \t<span>¿Está seguro de querer cambiar en mantenimiento este contrato?</span><br/>
            \t<span>
            \t\tSi cambia el estado en mantenimiento de este contrato solo se cobrará el costo de mantenimiento en los recibos para futuras facturaciones mensuales.
            \t</span>
              <form class=\"d-none\" id=\"formMantenimientoContrato\" action=\"";
            // line 683
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 683, $this->source); })()), "html", null, true);
            echo "/contrato/maintenance\" method=\"post\">
            \t  <input type=\"hidden\" name=\"codigo\" value=\"";
            // line 684
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 684)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 684, $this->source); })()), "contrato", [], "any", false, false, false, 684), "CTO_CODIGO", [], "any", false, false, false, 684), "html", null, true);
            }
            echo "\">
              </form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnMantenimientoContrato\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>
";
        }
        // line 696
        echo "
";
        // line 698
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 698) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 698, $this->source); })()), "contrato", [], "any", false, false, false, 698), "CTO_ESTADO", [], "any", false, false, false, 698) == 4))) {
            // line 699
            echo "<div class=\"modal fade f_modal\" id=\"modalFinMantenimientoContrato\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Contrato en Mantenimiento</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i>
            \t<span>¿Está seguro de querer activar este contrato?</span><br/>
            \t<span>
            \t\tAl activar este contrato continuará con el servicio normal y se emitirá los recibos en las siguientes facturaciones mensuales.
            \t</span>
              <form class=\"d-none\" id=\"formFinMantenimientoContrato\" action=\"";
            // line 714
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 714, $this->source); })()), "html", null, true);
            echo "/contrato/endMaintenance\" method=\"post\">
            \t  <input type=\"hidden\" name=\"codigo\" value=\"";
            // line 715
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 715)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 715, $this->source); })()), "contrato", [], "any", false, false, false, 715), "CTO_CODIGO", [], "any", false, false, false, 715), "html", null, true);
            }
            echo "\">
              </form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnFinMantenimientoContrato\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>
";
        }
        // line 727
        echo "
    
";
    }

    // line 731
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 732
        echo "
\t";
        // line 733
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#btnAnularContrato').click(function(event){
\t\t\t\$('#formAnularContrato').submit();
\t\t\treturn false;
\t\t});


    \$('#btnSuspenderContrato').click(function(event){
\t\t\t\$('#formSuspenderContrato').submit();
\t\t\treturn false;
\t\t});

    \$('#btnReconectarContrato').click(function(event){
\t\t\t\$('#formReconectarContrato').submit();
\t\t\treturn false;
\t\t});

    \$('#btnMantenimientoContrato').click(function(event){
\t\t\t\$('#formMantenimientoContrato').submit();
\t\t\treturn false;
\t\t});

    \$('#btnFinMantenimientoContrato').click(function(event){
\t\t\t\$('#formFinMantenimientoContrato').submit();
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
        return array (  1358 => 733,  1355 => 732,  1351 => 731,  1345 => 727,  1329 => 715,  1325 => 714,  1308 => 699,  1306 => 698,  1303 => 696,  1287 => 684,  1283 => 683,  1266 => 668,  1264 => 667,  1259 => 663,  1243 => 651,  1239 => 650,  1222 => 635,  1220 => 634,  1215 => 630,  1199 => 618,  1195 => 617,  1178 => 602,  1176 => 601,  1170 => 596,  1154 => 584,  1150 => 583,  1132 => 567,  1130 => 566,  1125 => 562,  1111 => 549,  1108 => 548,  1099 => 544,  1094 => 543,  1090 => 541,  1081 => 538,  1075 => 535,  1069 => 534,  1065 => 532,  1061 => 531,  1058 => 530,  1056 => 529,  1022 => 497,  1019 => 496,  1010 => 492,  1005 => 491,  1001 => 489,  992 => 486,  988 => 485,  982 => 482,  976 => 481,  972 => 479,  968 => 478,  965 => 477,  963 => 476,  938 => 453,  925 => 442,  921 => 441,  917 => 439,  909 => 435,  907 => 434,  903 => 432,  895 => 428,  893 => 427,  890 => 426,  882 => 422,  880 => 421,  877 => 420,  869 => 416,  867 => 415,  864 => 414,  859 => 412,  851 => 411,  848 => 410,  842 => 408,  840 => 407,  837 => 406,  834 => 405,  831 => 404,  827 => 402,  824 => 401,  821 => 400,  819 => 399,  804 => 389,  795 => 385,  786 => 381,  777 => 377,  768 => 373,  759 => 369,  750 => 362,  745 => 358,  742 => 356,  739 => 355,  729 => 349,  720 => 345,  711 => 341,  702 => 337,  693 => 333,  684 => 329,  675 => 325,  666 => 321,  656 => 317,  654 => 316,  644 => 312,  642 => 311,  633 => 307,  624 => 303,  615 => 299,  606 => 292,  604 => 291,  602 => 290,  598 => 288,  595 => 287,  585 => 281,  576 => 277,  567 => 273,  558 => 269,  549 => 265,  540 => 261,  531 => 257,  521 => 253,  519 => 252,  509 => 248,  507 => 247,  498 => 243,  489 => 239,  480 => 232,  478 => 231,  476 => 230,  472 => 228,  459 => 216,  439 => 200,  430 => 196,  421 => 192,  411 => 186,  405 => 184,  402 => 183,  399 => 182,  396 => 181,  393 => 180,  390 => 179,  387 => 178,  384 => 177,  381 => 176,  378 => 175,  375 => 174,  372 => 173,  369 => 171,  366 => 170,  360 => 169,  355 => 168,  351 => 167,  346 => 166,  343 => 165,  341 => 164,  338 => 163,  335 => 162,  332 => 161,  330 => 160,  327 => 159,  320 => 128,  317 => 127,  311 => 126,  304 => 125,  301 => 124,  296 => 123,  293 => 122,  291 => 121,  281 => 116,  272 => 112,  263 => 108,  254 => 104,  245 => 100,  236 => 96,  227 => 92,  221 => 88,  218 => 87,  212 => 85,  210 => 84,  205 => 83,  203 => 82,  198 => 81,  196 => 80,  191 => 79,  189 => 78,  184 => 77,  182 => 76,  177 => 75,  174 => 74,  172 => 73,  161 => 67,  150 => 59,  144 => 55,  140 => 54,  126 => 42,  120 => 37,  117 => 36,  108 => 34,  103 => 33,  101 => 32,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  77 => 24,  74 => 23,  71 => 22,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/contrato/contratoDetail.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\contrato\\contratoDetail.twig");
    }
}
