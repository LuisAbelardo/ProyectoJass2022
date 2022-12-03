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

/* /administration/financiamiento/financiamientoList.twig */
class __TwigTemplate_e855866464e50983e188706f21a2b99befc56592d63db131527466f03bdf051a extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["recibo", "financiamientos"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/financiamiento/financiamientoList.twig", 3);
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
        echo "/financiamiento/lista/filtro\" id=\"formFilterListFiananciamientos\">
  \t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t\t<div class=\"f_cardheader\">
                    <div> 
                    \t<i class=\"fas fa-clipboard mr-3\"></i>Listado de financiamientos
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
        echo "/financiamiento/lista\" class=\"f_link\">
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
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListFiananciamientos", [], "any", false, true, false, 94), "filterCodigo", [], "any", true, true, false, 94)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 94, $this->source); })()), "formFilterListFiananciamientos", [], "any", false, false, false, 94), "filterCodigo", [], "any", false, false, false, 94), "html", null, true);
        }
        echo "'></td>
                    \t  <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterContrato\"
                          \t\tvalue=\"";
        // line 97
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListFiananciamientos", [], "any", false, true, false, 97), "filterContrato", [], "any", true, true, false, 97)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 97, $this->source); })()), "formFilterListFiananciamientos", [], "any", false, false, false, 97), "filterContrato", [], "any", false, false, false, 97), "html", null, true);
        }
        echo "\"></td>
                    \t  <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth150imp\" type=\"date\" name=\"filterFecha\"
                          \t\tvalue=\"";
        // line 100
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListFiananciamientos", [], "any", false, true, false, 100), "filterFecha", [], "any", true, true, false, 100)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 100, $this->source); })()), "formFilterListFiananciamientos", [], "any", false, false, false, 100), "filterFecha", [], "any", false, false, false, 100), "html", null, true);
        }
        echo "\"></td>
                    \t  <td class=\"liste_title\">
                                <select class=\"f_inputflat\" name=\"filterEstado\" id=\"cmbFilterEstado\">
                                    <option value=\"-1\" class=\"f_opacitymedium\"></option>
                                    <option value=\"1\" 
                                        ";
        // line 105
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListFiananciamientos", [], "any", false, true, false, 105), "filterEstado", [], "any", true, true, false, 105) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 105, $this->source); })()), "formFilterListFiananciamientos", [], "any", false, false, false, 105), "filterEstado", [], "any", false, false, false, 105) == "1"))) {
            // line 106
            echo "                            \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t\t";
        }
        // line 107
        echo ">
                    \t\t\t\t\tSIN CONFIRMAR
                \t\t\t\t\t</option>
                                    <option value=\"2\"
                                        ";
        // line 111
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListFiananciamientos", [], "any", false, true, false, 111), "filterEstado", [], "any", true, true, false, 111) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 111, $this->source); })()), "formFilterListFiananciamientos", [], "any", false, false, false, 111), "filterEstado", [], "any", false, false, false, 111) == "2"))) {
            // line 112
            echo "                            \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t\t";
        }
        // line 113
        echo ">
                    \t\t\t\t\tCONFIRMADO
                \t\t\t\t\t</option>
                \t\t\t\t\t<option value=\"3\"
                                        ";
        // line 117
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListFiananciamientos", [], "any", false, true, false, 117), "filterEstado", [], "any", true, true, false, 117) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 117, $this->source); })()), "formFilterListFiananciamientos", [], "any", false, false, false, 117), "filterEstado", [], "any", false, false, false, 117) == "3"))) {
            // line 118
            echo "                            \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t\t";
        }
        // line 119
        echo ">
                    \t\t\t\t\tANULADO
                \t\t\t\t\t</option>
                              \t</select>
                          \t</td>
                    \t  <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterMonto\" disabled></td>
                  \t\t  <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterNroCuotas\" disabled></td>
                          \t
                        </tr>
                        <tr class=\"liste_title\">
                          <th class=\"wrapcolumntitle liste_title\">Ref.</th>
                          <th class=\"wrapcolumntitle liste_title\">Ref. Contrato</th>
                          <th class=\"wrapcolumntitle liste_title\">Fecha</th>
                          <th class=\"wrapcolumntitle liste_title\">Estado</th>
                          <th class=\"wrapcolumntitle liste_title\">Monto</th>
                          <th class=\"wrapcolumntitle liste_title\">Nro. Cuotas</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      \t";
        // line 141
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 141, $this->source); })()), "financiamientos", [], "any", false, false, false, 141))) {
            // line 142
            echo "                      \t
                      \t\t";
            // line 143
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 143, $this->source); })()), "financiamientos", [], "any", false, false, false, 143));
            foreach ($context['_seq'] as $context["_key"] => $context["financiamiento"]) {
                // line 144
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"";
                // line 146
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 146, $this->source); })()), "html", null, true);
                echo "/financiamiento/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CODIGO", [], "any", false, false, false, 146), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span style=\" color: #a69944\">
                                  \t\t\t<i class=\"fas fa-clipboard mr-1\"></i>
                              \t\t\t</span>
                                  \t\t<span class=\"align-middtle\">";
                // line 150
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CODIGO", [], "any", false, false, false, 150), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td>
                                      <a href=\"";
                // line 154
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 154, $this->source); })()), "html", null, true);
                echo "/contrato/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "CTO_CODIGO", [], "any", false, false, false, 154), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span class=\"align-middtle\">";
                // line 155
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "CTO_CODIGO", [], "any", false, false, false, 155), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax250\">";
                // line 158
                ((twig_test_empty(twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CREATED", [], "any", false, false, false, 158))) ? (print ("")) : (print (twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_CREATED", [], "any", false, false, false, 158), "d/m/Y"), "html", null, true))));
                echo "</td>
                                  <td class=\"f_overflowmax250\">
                                  \t";
                // line 160
                if ((twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_ESTADO", [], "any", false, false, false, 160) == 1)) {
                    // line 161
                    echo "                                    \t<span class=\"badge badge-warning\">";
                    echo "Sin Confirmar";
                    echo "</span>
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 162
$context["financiamiento"], "FTO_ESTADO", [], "any", false, false, false, 162) == 2)) {
                    // line 163
                    echo "                                    \t<span class=\"badge badge-success\">";
                    echo "Confirmado";
                    echo "</span>
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 164
$context["financiamiento"], "FTO_ESTADO", [], "any", false, false, false, 164) == 3)) {
                    // line 165
                    echo "                                    \t<span class=\"badge badge-danger\">";
                    echo "Anulado";
                    echo "</span>
                                    ";
                }
                // line 167
                echo "                                  </td>
                                  <td class=\"f_overflowmax250\">";
                // line 168
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_DEUDA", [], "any", false, false, false, 168), 2, ".", ","), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax250\">";
                // line 169
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["financiamiento"], "FTO_NUM_CUOTAS", [], "any", false, false, false, 169), "html", null, true);
                echo "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['financiamiento'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 172
            echo "                        
                      \t";
        } else {
            // line 174
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 3));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 175
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 179
            echo "                      \t";
        }
        // line 180
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
        // line 194
        echo "    
</div><!-- /.card -->
    
";
    }

    // line 199
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 200
        echo "
\t";
        // line 201
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#paginaAnterior').click(function(event){
\t\t\tif(\$('#paginaAnterior').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) - 1);
\t\t\t\t\$('#formFilterListFiananciamientos').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\t\$('#paginaSiguiente').click(function(event){
\t\t\tif(\$('#paginaSiguiente').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) + 1);
\t\t\t\t\$('#formFilterListFiananciamientos').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\tf_select2(\"#cmbFilterEstado\");
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/financiamiento/financiamientoList.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  433 => 201,  430 => 200,  426 => 199,  419 => 194,  404 => 180,  401 => 179,  392 => 175,  387 => 174,  383 => 172,  374 => 169,  370 => 168,  367 => 167,  361 => 165,  359 => 164,  354 => 163,  352 => 162,  347 => 161,  345 => 160,  340 => 158,  334 => 155,  328 => 154,  321 => 150,  312 => 146,  308 => 144,  304 => 143,  301 => 142,  299 => 141,  275 => 119,  269 => 118,  267 => 117,  261 => 113,  255 => 112,  253 => 111,  247 => 107,  241 => 106,  239 => 105,  229 => 100,  221 => 97,  213 => 94,  202 => 85,  195 => 80,  183 => 71,  176 => 69,  170 => 66,  166 => 65,  157 => 59,  149 => 56,  144 => 53,  135 => 45,  129 => 40,  126 => 39,  117 => 37,  112 => 36,  110 => 35,  104 => 33,  101 => 32,  98 => 31,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  69 => 16,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/financiamiento/financiamientoList.twig", "/home/franco/proyectos/php/jass/resources/views/administration/financiamiento/financiamientoList.twig");
    }
}
