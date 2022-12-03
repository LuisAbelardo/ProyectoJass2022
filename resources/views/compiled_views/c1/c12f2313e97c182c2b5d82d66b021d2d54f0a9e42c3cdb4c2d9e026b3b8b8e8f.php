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

/* /administration/recibo/reciboList.twig */
class __TwigTemplate_06aa1fae29486fa465c2efae94f438edfc422841fad3412560e0bf3315a8fdbb extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["recibo", "lista"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/recibo/reciboList.twig", 3);
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
        echo "/recibo/lista/filtro\" id=\"formFilterListRecibos\">
  \t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t\t<div class=\"f_cardheader\">
                    <div> 
                    \t<i class=\"fas fa-file-invoice mr-3\"></i>Listado de recibos
                    \t<span>(";
        // line 16
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 16, $this->source); })()), "pagination", [], "any", false, false, false, 16), "paginaCantidadRegistros", [], "any", false, false, false, 16), "html", null, true);
        echo ")</span>
                \t</div>
                \t<div class=\"d-none\" id=\"divrboacciones\"> 
                    \t<select class=\"f_inputflat f_minwidth150\" name=\"rboAccion\" id=\"cmbRboAccion\">
                            <option value=\"-1\" class=\"f_opacitymedium\">Seleccione acción</option>
                            <option value=\"1\" 
                                ";
        // line 22
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 22), "rboAccion", [], "any", true, true, false, 22) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 22, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 22), "rboAccion", [], "any", false, false, false, 22) == "1"))) {
            // line 23
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
            \t\t\t\t\t";
        }
        // line 24
        echo ">
            \t\t\t\t\tFINANCIAR
        \t\t\t\t\t</option>
                      \t</select>
                      \t<button type=\"button\" class=\"f_button f_buttonaction\" id=\"btnRboConfirmarAccion\">confirmar
                      \t\t<span><i class=\"fas fa-spinner f_classforrotatespinner d-none\" id=\"spinnerCheckRboVencidos\"></i></span>
                      \t</button>
                \t</div>
                \t<div></div>
                  </div>
  \t\t\t</div>
  \t\t</div><!-- /.card-header -->
  \t\t
  \t\t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t";
        // line 40
        echo "                ";
        $context["classAlert"] = "";
        // line 41
        echo "                ";
        if (twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 41, $this->source); })()))) {
            // line 42
            echo "                \t";
            $context["classAlert"] = "d-none";
            // line 43
            echo "                ";
        } elseif ((((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 43, $this->source); })()) >= 200) && ((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 43, $this->source); })()) < 300))) {
            // line 44
            echo "                    ";
            $context["classAlert"] = "alert-success";
            // line 45
            echo "                ";
        } elseif (((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 45, $this->source); })()) >= 400)) {
            // line 46
            echo "                    ";
            $context["classAlert"] = "alert-danger";
            // line 47
            echo "                ";
        }
        // line 48
        echo "      \t\t\t<div class=\"alert ";
        echo twig_escape_filter($this->env, (isset($context["classAlert"]) || array_key_exists("classAlert", $context) ? $context["classAlert"] : (function () { throw new RuntimeError('Variable "classAlert" does not exist.', 48, $this->source); })()), "html", null, true);
        echo " alert-dismissible fade show\" id=\"f_alertsContainer\" role=\"alert\">
                 \t<ul class=\"mb-0\" id=\"f_alertsUl\">
                 \t\t";
        // line 50
        if ( !twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 50, $this->source); })()))) {
            // line 51
            echo "                          ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 51, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["msj"]) {
                // line 52
                echo "                            <li>";
                echo twig_escape_filter($this->env, $context["msj"], "html", null, true);
                echo "</li>
                          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['msj'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 54
            echo "                        ";
        }
        // line 55
        echo "                 \t</ul>
                 \t<button type=\"button\" class=\"close\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\" id=\"f_alertsDismiss\">
                 \t\t<span aria-hidden=\"true\">&times;</span>
                 \t</button>
                </div>";
        // line 60
        echo "      \t\t</div>
      \t</div>
      \t
  \t\t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t
  \t\t\t\t";
        // line 68
        echo "  \t\t\t\t<div class=\"d-flex justify-content-end align-items-center pb-2\">
  \t\t\t\t\t<div>
                    \t<ul class=\"pagination f_pagination-basic pagination-sm m-0\">
                            <li class=\"page-item ";
        // line 71
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 71, $this->source); })()), "pagination", [], "any", false, false, false, 71), "paginaAnterior", [], "any", false, false, false, 71) ==  -1)) {
            echo "disabled";
        }
        echo "\">
                            \t<a class=\"page-link\"
                            \t\tid=\"paginaAnterior\" 
                            \t\tdata-page=\"";
        // line 74
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 74, $this->source); })()), "pagination", [], "any", false, false, false, 74), "paginaAnterior", [], "any", false, false, false, 74), "html", null, true);
        echo "\" 
                            \t\thref=\"#\" ><i class=\"fas fa-chevron-left\"></i></a>
                        \t</li>
                            <li class=\"page-item\">
                            \t<span class=\"page-link info\">
                                \t<input type=\"text\" id=\"filterPaginaActual\" name=\"filterPaginaActual\" class=\"f_inputflat\" required size=\"10\"
                            \t\t\t\tvalue=\"";
        // line 80
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 80, $this->source); })()), "pagination", [], "any", false, false, false, 80), "paginaActual", [], "any", false, false, false, 80), "html", null, true);
        echo "\">
                        \t\t\tde ";
        // line 81
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 81, $this->source); })()), "pagination", [], "any", false, false, false, 81), "paginaCantidad", [], "any", false, false, false, 81), "html", null, true);
        echo "
                            \t</span>
                            </li>
                            <li class=\"page-item ";
        // line 84
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 84, $this->source); })()), "pagination", [], "any", false, false, false, 84), "paginaSiguiente", [], "any", false, false, false, 84) ==  -1)) {
            echo "disabled";
        }
        echo "\">
                            \t<a class=\"page-link\" id=\"paginaSiguiente\"
                            \t\tdata-page=\"";
        // line 86
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 86, $this->source); })()), "pagination", [], "any", false, false, false, 86), "paginaSiguiente", [], "any", false, false, false, 86), "html", null, true);
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
        // line 95
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 95, $this->source); })()), "html", null, true);
        echo "/recibo/lista\" class=\"f_link\">
                \t\t\t<i class=\"fas fa-times\"></i>
            \t\t\t</a>
                    </div>
  \t\t\t\t</div>";
        // line 100
        echo "  \t\t\t\t
  \t\t\t\t
      \t\t\t<div class=\"table-responsive\">
                    <table class=\"table f_table f_tableliste f_listwidthfilterbefore\">
                      <thead>
                      \t<tr class=\"liste_title_filter\">
                      \t\t<td colspan=\"11\">
                          \t\t<i class=\"fas fa-user mr-1\"></i>
        \t\t\t\t\t\t<input class=\"f_inputflat f_maxwidth200imp\" type=\"text\" name=\"filterCliente\" placeholder=\"Buscar por DNI o RUC\"
                              \t\tvalue='";
        // line 109
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 109), "filterCliente", [], "any", true, true, false, 109)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 109, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 109), "filterCliente", [], "any", false, false, false, 109), "html", null, true);
        }
        echo "'>
                      \t\t\t<button class=\"f_btngenerico\" type=\"button\" id=\"btnVerificarVencidos\">Verificar Vencidos
                      \t\t\t\t<span><i class=\"fas fa-spinner f_classforrotatespinner d-none\" id=\"spinnerVerificarVencidos\"></i></span>
                      \t\t\t</button>
                      \t\t</td>
                      \t<tr class=\"liste_title_filter\">
                            <td class=\"liste_title f_minwidth125\">
                                <i class=\"fas fa-filter mr-1\"></i>
                                <input class=\"f_inputflat f_maxwidth80imp\" type=\"text\" name=\"filterCodigo\" 
                                \tvalue='";
        // line 118
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 118), "filterCodigo", [], "any", true, true, false, 118)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 118, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 118), "filterCodigo", [], "any", false, false, false, 118), "html", null, true);
        }
        echo "'></td>
                            <td class=\"liste_title\">
                                <input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterContrato\"
                                \tvalue=\"";
        // line 121
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 121), "filterContrato", [], "any", true, true, false, 121)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 121, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 121), "filterContrato", [], "any", false, false, false, 121), "html", null, true);
        }
        echo "\"></td>
                            <td class=\"liste_title\">
                                <input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterPeriodo\"
                                \tvalue=\"";
        // line 124
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 124), "filterPeriodo", [], "any", true, true, false, 124)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 124, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 124), "filterPeriodo", [], "any", false, false, false, 124), "html", null, true);
        }
        echo "\"></td>
                            <td class=\"liste_title\">
                                <input class=\"f_inputflat f_maxwidth125imp\" type=\"date\" name=\"filterFechaEmision\"
                                \tvalue=\"";
        // line 127
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 127), "filterFechaEmision", [], "any", true, true, false, 127)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 127, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 127), "filterFechaEmision", [], "any", false, false, false, 127), "html", null, true);
        }
        echo "\"></td>
                            <td class=\"liste_title\">
                                <select class=\"f_inputflat\" name=\"filterEstado\" id=\"cmbFilterEstado\">
                                    <option value=\"-1\" class=\"f_opacitymedium\"></option>
                                    <option value=\"1\" 
                                        ";
        // line 132
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 132), "filterEstado", [], "any", true, true, false, 132) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 132, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 132), "filterEstado", [], "any", false, false, false, 132) == "1"))) {
            // line 133
            echo "                            \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t\t";
        }
        // line 134
        echo ">
                    \t\t\t\t\tPendiente
                \t\t\t\t\t</option>
                                    <option value=\"2\"
                                        ";
        // line 138
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 138), "filterEstado", [], "any", true, true, false, 138) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 138, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 138), "filterEstado", [], "any", false, false, false, 138) == "2"))) {
            // line 139
            echo "                            \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t\t";
        }
        // line 140
        echo ">
                    \t\t\t\t\tPagado
                \t\t\t\t\t</option>
                \t\t\t\t\t<option value=\"3\"
                                        ";
        // line 144
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 144), "filterEstado", [], "any", true, true, false, 144) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 144, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 144), "filterEstado", [], "any", false, false, false, 144) == "3"))) {
            // line 145
            echo "                            \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t\t";
        }
        // line 146
        echo ">
                    \t\t\t\t\tVencido
                \t\t\t\t\t</option>
                \t\t\t\t\t<option value=\"4\"
                                        ";
        // line 150
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 150), "filterEstado", [], "any", true, true, false, 150) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 150, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 150), "filterEstado", [], "any", false, false, false, 150) == "4"))) {
            // line 151
            echo "                            \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t\t";
        }
        // line 152
        echo ">
                    \t\t\t\t\tFinanciado
                \t\t\t\t\t</option>
                              \t</select>
                          \t</td>
                            <td class=\"liste_title\">
                                <input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" value=\"\" disabled></td>
                            <td class=\"liste_title\">
                                <input class=\"f_inputflat f_maxwidth125imp\" type=\"date\" name=\"filterFechaCorte\"
                                \tvalue=\"";
        // line 161
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListRecibos", [], "any", false, true, false, 161), "filterFechaCorte", [], "any", true, true, false, 161)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 161, $this->source); })()), "formFilterListRecibos", [], "any", false, false, false, 161), "filterFechaCorte", [], "any", false, false, false, 161), "html", null, true);
        }
        echo "\"></td>
                            <td class=\"liste_title\">
                                <input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" value=\"\" disabled></td>
                            <td class=\"liste_title\">
                                <input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" value=\"\" disabled></td>
                            <td class=\"liste_title\" colspan=\"2\">
                                <input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" value=\"\" disabled></td>
                        </tr>
                        <tr class=\"liste_title\">
                          <th class=\"wrapcolumntitle liste_title\">Ref.</th>
                          <th class=\"wrapcolumntitle liste_title\">Ref. Contrato</th>
                          <th class=\"wrapcolumntitle liste_title\">Periodo</th>
                          <th class=\"wrapcolumntitle liste_title\">Fecha emisión</th>
                          <th class=\"wrapcolumntitle liste_title\">Estado</th>
                          <th class=\"wrapcolumntitle liste_title\">Ultimo día pago</th>
                          <th class=\"wrapcolumntitle liste_title\">Fecha corte</th>
                          <th class=\"wrapcolumntitle liste_title\">Monto total</th>
                          <th class=\"wrapcolumntitle liste_title\">Detalle</th>
                          <th class=\"wrapcolumntitle liste_title\" colspan=\"2\">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      \t";
        // line 184
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 184, $this->source); })()), "recibos", [], "any", false, false, false, 184))) {
            // line 185
            echo "                      \t
                      \t\t";
            // line 186
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 186, $this->source); })()), "recibos", [], "any", false, false, false, 186));
            foreach ($context['_seq'] as $context["_key"] => $context["recibo"]) {
                // line 187
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"#\" class=\"f_link\">
                                  \t\t<span style=\" color: #a69944\">
                                  \t\t\t<i class=\"fas fa-file-invoice mr-1\"></i>
                              \t\t\t</span>
                                  \t\t<span class=\"align-middtle\">";
                // line 193
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_CODIGO", [], "any", false, false, false, 193), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td>
                                      <a href=\"";
                // line 197
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 197, $this->source); })()), "html", null, true);
                echo "/contrato/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "CTO_CODIGO", [], "any", false, false, false, 197), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span class=\"align-middtle\">";
                // line 198
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "CTO_CODIGO", [], "any", false, false, false, 198), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax250\">";
                // line 201
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_PERIODO", [], "any", false, false, false, 201), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax250\">
                                  \t";
                // line 203
                if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_CREATED", [], "any", false, false, false, 203))) {
                    echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_CREATED", [], "any", false, false, false, 203), "d/m/Y"), "html", null, true);
                }
                // line 204
                echo "                                  </td>
                                  <td class=\"f_overflowmax250\">
                                    ";
                // line 206
                if ((twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_ESTADO", [], "any", false, false, false, 206) == 1)) {
                    // line 207
                    echo "                                    \t<span class=\"badge badge-warning\">";
                    echo "Pendiente";
                    echo "</span>
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 208
$context["recibo"], "RBO_ESTADO", [], "any", false, false, false, 208) == 2)) {
                    // line 209
                    echo "                                    \t<span class=\"badge badge-success\">";
                    echo "Pagado";
                    echo "</span>
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 210
$context["recibo"], "RBO_ESTADO", [], "any", false, false, false, 210) == 3)) {
                    // line 211
                    echo "                                    \t<span class=\"badge badge-danger\">";
                    echo "Vencido";
                    echo "</span>
                                \t";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 212
$context["recibo"], "RBO_ESTADO", [], "any", false, false, false, 212) == 4)) {
                    // line 213
                    echo "                                    \t<span class=\"badge badge-info\">";
                    echo "Financiado";
                    echo "</span>
                                    ";
                }
                // line 215
                echo "                                  </td>
                                  <td class=\"f_overflowmax250\">
                                      ";
                // line 217
                if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_ULT_DIA_PAGO", [], "any", false, false, false, 217))) {
                    echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_ULT_DIA_PAGO", [], "any", false, false, false, 217), "d/m/Y"), "html", null, true);
                }
                // line 218
                echo "                                  </td>
                                  <td class=\"f_overflowmax250\">
                                      ";
                // line 220
                if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_FECHA_CORTE", [], "any", false, false, false, 220))) {
                    echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_FECHA_CORTE", [], "any", false, false, false, 220), "d/m/Y"), "html", null, true);
                }
                // line 221
                echo "                                  </td>
                                  <td class=\"f_overflowmax250\">";
                // line 222
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_MNTO_TOTAL", [], "any", false, false, false, 222), 2, ".", ","), "html", null, true);
                echo "</td>
                                  <td>
                                      <a class=\"f_link ver_recibo\" href=\"";
                // line 224
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 224, $this->source); })()), "html", null, true);
                echo "/reporte/recibo/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_CODIGO", [], "any", false, false, false, 224), "html", null, true);
                echo "\">
                                  \t\t<span class=\"align-middtle\">";
                // line 225
                echo "ver detalle";
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax250\">
                                    ";
                // line 229
                if ((twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_ESTADO", [], "any", false, false, false, 229) == 1)) {
                    // line 230
                    echo "                                    \t<a class=\"f_link pagar_recibo\" href=\"";
                    echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 230, $this->source); })()), "html", null, true);
                    echo "/ingreso/recibo/nuevo/";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_CODIGO", [], "any", false, false, false, 230), "html", null, true);
                    echo "\">
                                      \t\t<span class=\"align-middtle\">";
                    // line 231
                    echo "Registrar pago";
                    echo "</span>
                                        </a>
                                    ";
                }
                // line 234
                echo "                                  </td>
                                  <td>
                                    ";
                // line 236
                if ((twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_ESTADO", [], "any", false, false, false, 236) == 3)) {
                    // line 237
                    echo "                                    \t<input type=\"checkbox\" class=\"classforhighlightrow chkrbovencido\" name=\"rboVencidos[]\" 
                                \t\t\t\tvalue=\"";
                    // line 238
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["recibo"], "RBO_CODIGO", [], "any", false, false, false, 238), "html", null, true);
                    echo "\">
                                    ";
                }
                // line 240
                echo "                                  </td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['recibo'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 243
            echo "                        
                      \t";
        } else {
            // line 245
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 3));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 246
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 250
            echo "                      \t";
        }
        // line 251
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
        // line 265
        echo "    
</div><!-- /.card -->



";
        // line 271
        echo "<div class=\"modal fade f_modal\" id=\"modalMostrarRecibo\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-lg\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Recibo</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<div id=\"contentRecibo\" src=\"\">

                </div>
            </div>
        </div>
    </div>
</div>";
        // line 288
        echo "
    
";
    }

    // line 292
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 293
        echo "
\t";
        // line 294
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
    
\t<script type=\"text/javascript\">
\t\t\$('#paginaAnterior').click(function(event){
\t\t\tif(\$('#paginaAnterior').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) - 1);
\t\t\t\t\$('#formFilterListRecibos').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\t\$('#paginaSiguiente').click(function(event){
\t\t\tif(\$('#paginaSiguiente').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) + 1);
\t\t\t\t\$('#formFilterListRecibos').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\tf_select2(\"#cmbFilterEstado\");
\t\tf_select2(\"#cmbRboAccion\");
\t\t
\t\t
\t\t\$('.ver_recibo').click(function(){
            \$('#contentRecibo').empty();
            \$('#modalMostrarRecibo').modal('show');
            \$('#contentRecibo').html('<object style=\"width:100%;height: 600px\" id=\"pdf\" data=\"'+\$(this).attr('href')+'\" type=\"application/pdf\"></object>');
            return false;
        });
        
        
        \$(\"#btnVerificarVencidos\").click(function(){
        \t
        \t\$(\"#btnVerificarVencidos\").attr(\"disabled\", true);
    \t\tvar spinnerVerificarVencidos = \$(\"#spinnerVerificarVencidos\");
    \t\tspinnerVerificarVencidos.removeClass(\"d-none\");
    \t
    \t\t\$.ajax({
                method: \"POST\",
                url: \"";
        // line 333
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 333, $this->source); })()), "html", null, true);
        echo "/recibo/verificarvencidos\",
                dataType: \"json\",
                data: {},
                complete: function(){
                \tspinnerVerificarVencidos.addClass(\"d-none\");
                \t\$(\"#btnVerificarVencidos\").attr(\"disabled\", false);
                },
    \t\t\terror: function(jqXHR ){console.log(jqXHR);
    \t\t\t\t\$(document).Toasts('create', {
                    \tclass: 'bg-danger',
                        title: 'Respuesta de solicitud',
                        position: 'topRight',
                        autohide: true,
\t\t\t\t\t\tdelay: 8000,
                        body: \"Ocurrio un error inesperado, vuelva a intentarlo\"
                     });
    \t\t\t},
    \t\t\tsuccess: function(respons){
    \t\t\t\t\$(document).Toasts('create', {
                    \tclass: 'bg-success',
                        title: 'Respuesta de solicitud',
                        position: 'topRight',
                        autohide: true,
\t\t\t\t\t\tdelay: 8000,
                        body: \"Estado de recibos actualizados\"
                     });
    \t\t\t
    \t\t\t\tsetTimeout(function(){
    \t\t\t\t\twindow.location.href = \"";
        // line 361
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 361, $this->source); })()), "html", null, true);
        echo "/recibo/lista\";
\t\t\t\t\t}, 2000);
                     
    \t\t\t}
            });
        });
        
        
        \$(\".classforhighlightrow\").click(function(){
        \tif(\$(this).prop('checked')) {
                \$(this).parent().parent().css(\"background\", \"#edf4fb\");
            }else{
            \t\$(this).parent().parent().css(\"background\", \"inherit\");
            }
        });
        
        
        \$(\".chkrbovencido\").click(function(){
        \tvar mostrarAcciones = false;
        \t\$(\".chkrbovencido\").each(function(indice, elemento) {
            \tif(\$(elemento).prop('checked')){ mostrarAcciones = true; return false;}
            });
            if(mostrarAcciones){
            \t\$(\"#divrboacciones\").removeClass(\"d-none\");
            }else{
            \t\$(\"#divrboacciones\").addClass(\"d-none\");
            \t\$(\"#cmbRboAccion\").val(\"-1\");
            \t\$('#cmbRboAccion').change();
        \t}
        });
        
        
        if(\$(\"#cmbRboAccion\").val() != -1){
    \t\t\$(\"#btnRboConfirmarAccion\").css(\"opacity\", \"1\");
    \t\t\$(\"#btnRboConfirmarAccion\").prop(\"disabled\", false);
    \t}else{
    \t\t\$(\"#btnRboConfirmarAccion\").css(\"opacity\", \"0.5\");
    \t\t\$(\"#btnRboConfirmarAccion\").prop(\"disabled\", true);
    \t}
        
        \$(\"#cmbRboAccion\").change(function(){
        \tif(\$(this).val() != -1){
        \t\t\$(\"#btnRboConfirmarAccion\").css(\"opacity\", 1);
        \t\t\$(\"#btnRboConfirmarAccion\").prop(\"disabled\", false);
        \t}else{
        \t\t\$(\"#btnRboConfirmarAccion\").css(\"opacity\", 0.5);
        \t\t\$(\"#btnRboConfirmarAccion\").prop(\"disabled\", true);
        \t}
        });
        
        
        \$(\"#btnRboConfirmarAccion\").click(function(){
        
            \$(\"#btnRboConfirmarAccion\").attr(\"disabled\", true);
    \t\tvar spinnerCheckRboVencidos = \$(\"#spinnerCheckRboVencidos\");
    \t\tspinnerCheckRboVencidos.removeClass(\"d-none\");
    \t\tvar dataSend = new FormData(document.getElementById('formFilterListRecibos'));
        \t
        \t\$.ajax({
                method: \"POST\",
                url: \"";
        // line 421
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 421, $this->source); })()), "html", null, true);
        echo "/financiamiento/checkrbovencidos\",
                dataType: \"json\",
                processData: false,
                contentType: false,
                data: dataSend,
                complete: function(){
                \tspinnerCheckRboVencidos.addClass(\"d-none\");
                \t\$(\"#btnRboConfirmarAccion\").attr(\"disabled\", false);
                },
    \t\t\terror: function(jqXHR){
    \t\t\t\tvar msjRta = \"Ocurrio un error inesperado, vuelva a intentarlo\";
    \t\t\t\tif(jqXHR.responseJSON != undefined){
    \t\t\t\t\tmsjRta = '<ul class=\"pl-3\">';
    \t\t\t\t\tdataDetalle = jqXHR.responseJSON.estadoDetalle;
    \t\t\t\t\tfor (const property in dataDetalle) {
                          msjRta += \"<li>\" + dataDetalle[property] + \"</li>\";
                        }
                        msjRta += \"</ul>\";
    \t\t\t\t}
    \t\t\t
    \t\t\t\t\$(document).Toasts('create', {
                    \tclass: 'bg-danger',
                        title: 'Respuesta de solicitud',
                        position: 'topRight',
                        autohide: true,
\t\t\t\t\t\tdelay: 8000,
                        body: msjRta
                     })
    \t\t\t},
    \t\t\tsuccess: function(respons){
    \t\t\t\twindow.location.href = \"";
        // line 451
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 451, $this->source); })()), "html", null, true);
        echo "/financiamiento/nuevo\";
    \t\t\t}
            });
        \t
        });
        
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/recibo/reciboList.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  768 => 451,  735 => 421,  672 => 361,  641 => 333,  599 => 294,  596 => 293,  592 => 292,  586 => 288,  568 => 271,  561 => 265,  546 => 251,  543 => 250,  534 => 246,  529 => 245,  525 => 243,  517 => 240,  512 => 238,  509 => 237,  507 => 236,  503 => 234,  497 => 231,  490 => 230,  488 => 229,  481 => 225,  475 => 224,  470 => 222,  467 => 221,  463 => 220,  459 => 218,  455 => 217,  451 => 215,  445 => 213,  443 => 212,  438 => 211,  436 => 210,  431 => 209,  429 => 208,  424 => 207,  422 => 206,  418 => 204,  414 => 203,  409 => 201,  403 => 198,  397 => 197,  390 => 193,  382 => 187,  378 => 186,  375 => 185,  373 => 184,  345 => 161,  334 => 152,  328 => 151,  326 => 150,  320 => 146,  314 => 145,  312 => 144,  306 => 140,  300 => 139,  298 => 138,  292 => 134,  286 => 133,  284 => 132,  274 => 127,  266 => 124,  258 => 121,  250 => 118,  236 => 109,  225 => 100,  218 => 95,  206 => 86,  199 => 84,  193 => 81,  189 => 80,  180 => 74,  172 => 71,  167 => 68,  158 => 60,  152 => 55,  149 => 54,  140 => 52,  135 => 51,  133 => 50,  127 => 48,  124 => 47,  121 => 46,  118 => 45,  115 => 44,  112 => 43,  109 => 42,  106 => 41,  103 => 40,  86 => 24,  80 => 23,  78 => 22,  69 => 16,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/recibo/reciboList.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\recibo\\reciboList.twig");
    }
}
