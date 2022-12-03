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

/* /administration/egreso/egresoList.twig */
class __TwigTemplate_129cd2a3fc216b95cc5cedd4cc58903c52830d413593ca77b8f850b15a9d4f8e extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["egreso", "lista"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/egreso/egresoList.twig", 3);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 6
        echo "\t
<div class=\"f_card\">
\t";
        // line 9
        echo "  \t<form method=\"get\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/egreso/lista/filtro\" id=\"formFilterListEgresos\">
  \t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t\t<div class=\"f_cardheader\">
                    <div> 
                    \t<i class=\"fas fa-money-bill mr-3\"></i>Listado de egresos
                    \t<span>(";
        // line 16
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 16, $this->source); })()), "pagination", [], "any", false, false, false, 16), "paginaCantidadRegistros", [], "any", false, false, false, 16), "html", null, true);
        echo ")</span>
                \t</div>
                  </div>
  \t\t\t</div>
  \t\t</div><!-- /.card-header -->
  \t\t
  \t\t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t";
        // line 25
        echo "                ";
        $context["classAlert"] = "";
        // line 26
        echo "                ";
        if (twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 26, $this->source); })()))) {
            // line 27
            echo "                \t";
            $context["classAlert"] = "d-none";
            // line 28
            echo "                ";
        } elseif ((((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 28, $this->source); })()) >= 200) && ((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 28, $this->source); })()) < 300))) {
            // line 29
            echo "                    ";
            $context["classAlert"] = "alert-success";
            // line 30
            echo "                ";
        } elseif (((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 30, $this->source); })()) >= 400)) {
            // line 31
            echo "                    ";
            $context["classAlert"] = "alert-danger";
            // line 32
            echo "                ";
        }
        // line 33
        echo "      \t\t\t<div class=\"alert ";
        echo twig_escape_filter($this->env, (isset($context["classAlert"]) || array_key_exists("classAlert", $context) ? $context["classAlert"] : (function () { throw new RuntimeError('Variable "classAlert" does not exist.', 33, $this->source); })()), "html", null, true);
        echo " alert-dismissible fade show\" id=\"f_alertsContainer\" role=\"alert\">
                 \t<ul class=\"mb-0\" id=\"f_alertsUl\">
                 \t\t";
        // line 35
        if ( !twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 35, $this->source); })()))) {
            // line 36
            echo "                          ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 36, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["msj"]) {
                // line 37
                echo "                            <li>";
                echo twig_escape_filter($this->env, $context["msj"], "html", null, true);
                echo "</li>
                          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['msj'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            echo "                        ";
        }
        // line 40
        echo "                 \t</ul>
                 \t<button type=\"button\" class=\"close\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\" id=\"f_alertsDismiss\">
                 \t\t<span aria-hidden=\"true\">&times;</span>
                 \t</button>
                </div>";
        // line 45
        echo "      \t\t</div>
      \t</div>
      \t
  \t\t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t
  \t\t\t\t";
        // line 53
        echo "  \t\t\t\t<div class=\"d-flex justify-content-end align-items-center pb-2\">
  \t\t\t\t\t<div>
                    \t<ul class=\"pagination f_pagination-basic pagination-sm m-0\">
                            <li class=\"page-item ";
        // line 56
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 56, $this->source); })()), "pagination", [], "any", false, false, false, 56), "paginaAnterior", [], "any", false, false, false, 56) ==  -1)) {
            echo "disabled";
        }
        echo "\">
                            \t<a class=\"page-link\"
                            \t\tid=\"paginaAnterior\" 
                            \t\tdata-page=\"";
        // line 59
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 59, $this->source); })()), "pagination", [], "any", false, false, false, 59), "paginaAnterior", [], "any", false, false, false, 59), "html", null, true);
        echo "\" 
                            \t\thref=\"#\" ><i class=\"fas fa-chevron-left\"></i></a>
                        \t</li>
                            <li class=\"page-item\">
                            \t<span class=\"page-link info\">
                                \t<input type=\"text\" id=\"filterPaginaActual\" name=\"filterPaginaActual\" class=\"f_inputflat\" required size=\"10\"
                            \t\t\t\tvalue=\"";
        // line 65
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 65, $this->source); })()), "pagination", [], "any", false, false, false, 65), "paginaActual", [], "any", false, false, false, 65), "html", null, true);
        echo "\">
                        \t\t\tde ";
        // line 66
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 66, $this->source); })()), "pagination", [], "any", false, false, false, 66), "paginaCantidad", [], "any", false, false, false, 66), "html", null, true);
        echo "
                            \t</span>
                            </li>
                            <li class=\"page-item ";
        // line 69
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 69, $this->source); })()), "pagination", [], "any", false, false, false, 69), "paginaSiguiente", [], "any", false, false, false, 69) ==  -1)) {
            echo "disabled";
        }
        echo "\">
                            \t<a class=\"page-link\" id=\"paginaSiguiente\"
                            \t\tdata-page=\"";
        // line 71
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 71, $this->source); })()), "pagination", [], "any", false, false, false, 71), "paginaSiguiente", [], "any", false, false, false, 71), "html", null, true);
        echo "\"
                            \t\thref=\"#\"><i class=\"fas fa-chevron-right\"></i></a>
                        \t</li>
                        </ul>
                    </div>
                    <div class=\"px-2\">
                    \t<button type=\"submit\" class=\"f_btnflat\" name=\"btnBuscarFiltros\">
                    \t\t<span class=\"fas fa-search\"></span>
                \t\t</button>
                \t\t<a href=\"";
        // line 80
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 80, $this->source); })()), "html", null, true);
        echo "/egreso/lista\" class=\"f_link\">
                \t\t\t<i class=\"fas fa-times\"></i>
            \t\t\t</a>
                    </div>
  \t\t\t\t</div>";
        // line 85
        echo "  \t\t\t\t
  \t\t\t\t
      \t\t\t<div class=\"table-responsive\">
                    <table class=\"table f_table f_tableliste f_listwidthfilterbefore\">
                      <thead>
                      \t<tr class=\"liste_title_filter\">
                          <td class=\"liste_title f_minwidth125\">
                          \t<i class=\"fas fa-filter mr-1\"></i>
    \t\t\t\t\t\t<input class=\"f_inputflat f_maxwidth80imp\" type=\"text\" name=\"filterCodigo\" 
                          \t\tvalue='";
        // line 94
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 94), "filterCodigo", [], "any", true, true, false, 94)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 94, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 94), "filterCodigo", [], "any", false, false, false, 94), "html", null, true);
        }
        echo "'></td>
                    \t  <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth150imp\" type=\"date\" name=\"filterFecha\"
                          \t\tvalue=\"";
        // line 97
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 97), "filterFecha", [], "any", true, true, false, 97)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 97, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 97), "filterFecha", [], "any", false, false, false, 97), "html", null, true);
        }
        echo "\"></td>
                  \t\t  <td class=\"liste_title\">
                  \t\t  \t<select class=\"f_inputflat\" name=\"filterEstado\" id=\"cmbFilterEstado\">
                                <option value=\"-1\" class=\"f_opacitymedium\"></option>
                                <option value=\"0\" 
                                    ";
        // line 102
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 102), "filterEstado", [], "any", true, true, false, 102) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 102, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 102), "filterEstado", [], "any", false, false, false, 102) == "0"))) {
            // line 103
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 104
        echo ">
                \t\t\t\t\tAnulado
            \t\t\t\t\t</option>
                                <option value=\"1\"
                                    ";
        // line 108
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 108), "filterEstado", [], "any", true, true, false, 108) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 108, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 108), "filterEstado", [], "any", false, false, false, 108) == "1"))) {
            // line 109
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 110
        echo ">
                \t\t\t\t\tActivo
            \t\t\t\t\t</option>
                              </select>
                          </td>
                          <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterMonto\" disabled value=\"\"></td>
                          <td class=\"liste_title\">
                              <select class=\"f_inputflat\" name=\"filterCaja\" id=\"cmbFilterCaja\">
                                <option value=\"-1\" class=\"f_opacitymedium\"></option>
                                ";
        // line 120
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cajas", [], "any", true, true, false, 120)) {
            // line 121
            echo "                                \t";
            $context["selectedCaja"] = false;
            // line 122
            echo "                            \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 122, $this->source); })()), "cajas", [], "any", false, false, false, 122));
            foreach ($context['_seq'] as $context["_key"] => $context["caja"]) {
                // line 123
                echo "                                    \t<option value=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_CODIGO", [], "any", false, false, false, 123), "html", null, true);
                echo "\"
                                \t\t\t\t";
                // line 124
                if ((( !(isset($context["selectedCaja"]) || array_key_exists("selectedCaja", $context) ? $context["selectedCaja"] : (function () { throw new RuntimeError('Variable "selectedCaja" does not exist.', 124, $this->source); })()) && twig_get_attribute($this->env, $this->source,                 // line 125
($context["data"] ?? null), "formFilterListEgresos", [], "any", true, true, false, 125)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,                 // line 126
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 126, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 126), "filterCaja", [], "any", false, false, false, 126) == twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_CODIGO", [], "any", false, false, false, 126)))) {
                    // line 127
                    echo "                    \t\t\t\t\t            ";
                    echo "selected";
                    $context["formFilterListEgresos"] = true;
                }
                echo ">
                            \t\t\t\t";
                // line 128
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_NOMBRE", [], "any", false, false, false, 128), "html", null, true);
                echo "
                        \t\t\t\t</option>
                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['caja'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 131
            echo "                            \t";
        }
        // line 132
        echo "                              </select>
                          </td>
                          <td class=\"liste_title\">
                  \t\t  \t<select class=\"f_inputflat\" name=\"filterComprobanteTipo\" id=\"cmbFilterComprobanteTipo\">
                                <option value=\"-1\" class=\"f_opacitymedium\"></option>
                                <option value=\"1\" 
                                    ";
        // line 138
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 138), "filterComprobanteTipo", [], "any", true, true, false, 138) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 138, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 138), "filterComprobanteTipo", [], "any", false, false, false, 138) == "1"))) {
            // line 139
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 140
        echo ">
                \t\t\t\t\tTICKET
            \t\t\t\t\t</option>
                                <option value=\"2\"
                                    ";
        // line 144
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 144), "filterComprobanteTipo", [], "any", true, true, false, 144) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 144, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 144), "filterComprobanteTipo", [], "any", false, false, false, 144) == "2"))) {
            // line 145
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 146
        echo ">
                \t\t\t\t\tBOLETA
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"3\"
                                    ";
        // line 150
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 150), "filterComprobanteTipo", [], "any", true, true, false, 150) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 150, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 150), "filterComprobanteTipo", [], "any", false, false, false, 150) == "3"))) {
            // line 151
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 152
        echo ">
                \t\t\t\t\tFACTURA
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"4\"
                                    ";
        // line 156
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 156), "filterComprobanteTipo", [], "any", true, true, false, 156) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 156, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 156), "filterComprobanteTipo", [], "any", false, false, false, 156) == "3"))) {
            // line 157
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 158
        echo ">
                \t\t\t\t\tSIN COMPROBANTE
            \t\t\t\t\t</option>
                              </select>
                          </td>
                          <td class=\"liste_title\">
                    \t  \t<input class=\"f_inputflat f_maxwidth150imp\" type=\"text\" name=\"filterComprobanteNro\"
                    \t  \t\tvalue=\"";
        // line 165
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 165), "filterComprobanteNro", [], "any", true, true, false, 165)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 165, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 165), "filterComprobanteNro", [], "any", false, false, false, 165), "html", null, true);
        }
        echo "\"></td>
                          <td class=\"liste_title\">
                    \t  \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterUsuario\"
                    \t  \t\tvalue=\"";
        // line 168
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListEgresos", [], "any", false, true, false, 168), "filterUsuario", [], "any", true, true, false, 168)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 168, $this->source); })()), "formFilterListEgresos", [], "any", false, false, false, 168), "filterUsuario", [], "any", false, false, false, 168), "html", null, true);
        }
        echo "\"></td>
                          <td class=\"liste_title\">
                    \t  \t<input class=\"f_inputflat f_maxwidth150imp\" type=\"text\" name=\"filterDescripcion\" disabled value=\"\"></td>
                        </tr>
                        <tr class=\"liste_title\">
                          <th class=\"wrapcolumntitle liste_title\">Ref.</th>
                          <th class=\"wrapcolumntitle liste_title\">Fecha</th>
                          <th class=\"wrapcolumntitle liste_title\">Estado</th>
                          <th class=\"wrapcolumntitle liste_title\">Monto</th>
                          <th class=\"wrapcolumntitle liste_title\">Caja</th>
                          <th class=\"wrapcolumntitle liste_title\">Tipo comprobante</th>
                          <th class=\"wrapcolumntitle liste_title\">Nro. Comprobante</th>
                          <th class=\"wrapcolumntitle liste_title\">Usuario</th>
                          <th class=\"wrapcolumntitle liste_title\">Descripción</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      \t";
        // line 186
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 186, $this->source); })()), "egresos", [], "any", false, false, false, 186))) {
            // line 187
            echo "                      \t
                      \t\t";
            // line 188
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 188, $this->source); })()), "egresos", [], "any", false, false, false, 188));
            foreach ($context['_seq'] as $context["_key"] => $context["egreso"]) {
                // line 189
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"";
                // line 191
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 191, $this->source); })()), "html", null, true);
                echo "/egreso/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_CODIGO", [], "any", false, false, false, 191), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span style=\" color: #a69944\">
                                  \t\t\t<i class=\"fas fa-money-bill mr-1\"></i>
                              \t\t\t</span>
                                  \t\t<span class=\"align-middtle\">";
                // line 195
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_CODIGO", [], "any", false, false, false, 195), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 198
                ((twig_test_empty(twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_CREATED", [], "any", false, false, false, 198))) ? (print ("")) : (print (twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_CREATED", [], "any", false, false, false, 198), "d/m/Y"), "html", null, true))));
                echo "</td>
                                  <td class=\"f_overflowmax250\">
                                    ";
                // line 200
                if ((twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_ESTADO", [], "any", false, false, false, 200) == 0)) {
                    // line 201
                    echo "                                    \t<span class=\"badge badge-warning\">";
                    echo "Anulado";
                    echo "</span>
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 202
$context["egreso"], "EGR_ESTADO", [], "any", false, false, false, 202) == 1)) {
                    echo "Activo";
                    echo "
                                    ";
                }
                // line 204
                echo "                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 205
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_CANTIDAD", [], "any", false, false, false, 205), 2, ".", ","), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax250\">";
                // line 206
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "CAJ_NOMBRE", [], "any", false, false, false, 206), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax250\">
                                    ";
                // line 208
                if ((twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 208) == 1)) {
                    echo "TICKET";
                    echo "
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 209
$context["egreso"], "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 209) == 2)) {
                    echo "BOLETA";
                    echo "
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 210
$context["egreso"], "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 210) == 3)) {
                    echo "FACTURA";
                    echo "
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 211
$context["egreso"], "EGR_TIPO_COMPROBANTE", [], "any", false, false, false, 211) == 4)) {
                    echo "SIN COMPROBANTE";
                    echo "
                                    ";
                }
                // line 213
                echo "                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 214
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_COD_COMPROBANTE", [], "any", false, false, false, 214), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax250\">
                                    <a href=\"";
                // line 216
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 216, $this->source); })()), "html", null, true);
                echo "/usuario/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "USU_CODIGO", [], "any", false, false, false, 216), "html", null, true);
                echo "\" class=\"f_link\">
                                    \t<span style=\" color: #a69944\">
                                  \t\t\t<i class=\"fas fa-user-shield mr-1\"></i>
                              \t\t\t</span>
                                    \t<span class=\"align-middtle\">";
                // line 220
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "USU_CODIGO", [], "any", false, false, false, 220), "html", null, true);
                echo "</span>
                                    </a>
                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 223
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["egreso"], "EGR_DESCRIPCION", [], "any", false, false, false, 223), "html", null, true);
                echo "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['egreso'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 226
            echo "                        
                      \t";
        } else {
            // line 228
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 3));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 229
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 233
            echo "                      \t";
        }
        // line 234
        echo "                      
                      \t
                      </tbody>
                    </table>
                </div>
  \t\t\t</div>
  \t\t\t
  \t\t</div><!-- /.card-body -->
  \t\t
  \t\t<div class=\"row\">
      \t\t<div class=\"col-12 f_cardfooter text-right\"></div>
      \t</div><!-- /.card-footer -->
  \t\t
    </form>";
        // line 248
        echo "    
</div><!-- /.card -->
    
";
    }

    // line 253
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 254
        echo "
\t";
        // line 255
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#paginaAnterior').click(function(event){
\t\t\tif(\$('#paginaAnterior').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) - 1);
\t\t\t\t\$('#formFilterListEgresos').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\t\$('#paginaSiguiente').click(function(event){
\t\t\tif(\$('#paginaSiguiente').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) + 1);
\t\t\t\t\$('#formFilterListEgresos').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\tf_select2(\"#cmbFilterTipo\");
\t\tf_select2(\"#cmbFilterEstado\");
\t\tf_select2(\"#cmbFilterCaja\");
\t\tf_select2(\"#cmbFilterComprobanteTipo\");
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/egreso/egresoList.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  562 => 255,  559 => 254,  555 => 253,  548 => 248,  533 => 234,  530 => 233,  521 => 229,  516 => 228,  512 => 226,  503 => 223,  497 => 220,  488 => 216,  483 => 214,  480 => 213,  474 => 211,  469 => 210,  464 => 209,  459 => 208,  454 => 206,  450 => 205,  447 => 204,  441 => 202,  436 => 201,  434 => 200,  429 => 198,  423 => 195,  414 => 191,  410 => 189,  406 => 188,  403 => 187,  401 => 186,  378 => 168,  370 => 165,  361 => 158,  355 => 157,  353 => 156,  347 => 152,  341 => 151,  339 => 150,  333 => 146,  327 => 145,  325 => 144,  319 => 140,  313 => 139,  311 => 138,  303 => 132,  300 => 131,  291 => 128,  284 => 127,  282 => 126,  281 => 125,  280 => 124,  275 => 123,  270 => 122,  267 => 121,  265 => 120,  253 => 110,  247 => 109,  245 => 108,  239 => 104,  233 => 103,  231 => 102,  221 => 97,  213 => 94,  202 => 85,  195 => 80,  183 => 71,  176 => 69,  170 => 66,  166 => 65,  157 => 59,  149 => 56,  144 => 53,  135 => 45,  129 => 40,  126 => 39,  117 => 37,  112 => 36,  110 => 35,  104 => 33,  101 => 32,  98 => 31,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  69 => 16,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/egreso/egresoList.twig", "/home/franco/proyectos/php/jass/resources/views/administration/egreso/egresoList.twig");
    }
}
