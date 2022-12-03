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

/* /administration/contrato/contratoList.twig */
class __TwigTemplate_e0082b1c3c3a71501488e6b171f7db3f98d249933de543ecd9c583112217e78b extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["contrato", "lista"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/contrato/contratoList.twig", 3);
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
        echo "/contrato/lista/filtro\" id=\"formFilterListContratos\">
  \t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t\t<div class=\"f_cardheader\">
                    <div> 
                    \t<i class=\"fas fa-file-contract mr-3\"></i>Listado de contratos
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
        echo "/contrato/lista\" class=\"f_link\">
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
                      \t\t<td colspan=\"4\">
                          \t\t<i class=\"fas fa-user mr-1\"></i>
        \t\t\t\t\t\t<input class=\"f_inputflat f_maxwidth200imp\" type=\"text\" name=\"filterCliente\" placeholder=\"Buscar por DNI o RUC\"
                              \t\tvalue='";
        // line 94
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListContratos", [], "any", false, true, false, 94), "filterCliente", [], "any", true, true, false, 94)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 94, $this->source); })()), "formFilterListContratos", [], "any", false, false, false, 94), "filterCliente", [], "any", false, false, false, 94), "html", null, true);
        }
        echo "'></td></td>
                  \t\t</tr>
                      \t<tr class=\"liste_title_filter\">
                          <td class=\"liste_title f_minwidth125\">
                          \t<i class=\"fas fa-filter mr-1\"></i>
    \t\t\t\t\t\t<input class=\"f_inputflat f_maxwidth80imp\" type=\"text\" name=\"filterCodigo\" 
                          \t\tvalue='";
        // line 100
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListContratos", [], "any", false, true, false, 100), "filterCodigo", [], "any", true, true, false, 100)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 100, $this->source); })()), "formFilterListContratos", [], "any", false, false, false, 100), "filterCodigo", [], "any", false, false, false, 100), "html", null, true);
        }
        echo "'></td>
                    \t  <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterPredio\"
                          \t\tvalue=\"";
        // line 103
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListContratos", [], "any", false, true, false, 103), "filterPredio", [], "any", true, true, false, 103)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 103, $this->source); })()), "formFilterListContratos", [], "any", false, false, false, 103), "filterPredio", [], "any", false, false, false, 103), "html", null, true);
        }
        echo "\"></td>
                  \t\t  <td class=\"liste_title\">
                  \t\t  \t<select class=\"f_inputflat\" name=\"filterEstado\" id=\"cmbFilterEstado\">
                                <option value=\"-1\" class=\"f_opacitymedium\"></option>
                                <option value=\"0\" 
                                    ";
        // line 108
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListContratos", [], "any", false, true, false, 108), "filterEstado", [], "any", true, true, false, 108) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 108, $this->source); })()), "formFilterListContratos", [], "any", false, false, false, 108), "filterEstado", [], "any", false, false, false, 108) == "0"))) {
            // line 109
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 110
        echo ">
                \t\t\t\t\tTramite
            \t\t\t\t\t</option>
                                <option value=\"1\"
                                    ";
        // line 114
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListContratos", [], "any", false, true, false, 114), "filterEstado", [], "any", true, true, false, 114) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 114, $this->source); })()), "formFilterListContratos", [], "any", false, false, false, 114), "filterEstado", [], "any", false, false, false, 114) == "1"))) {
            // line 115
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 116
        echo ">
                \t\t\t\t\tActivo
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"2\"
                                    ";
        // line 120
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListContratos", [], "any", false, true, false, 120), "filterEstado", [], "any", true, true, false, 120) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 120, $this->source); })()), "formFilterListContratos", [], "any", false, false, false, 120), "filterEstado", [], "any", false, false, false, 120) == "2"))) {
            // line 121
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 122
        echo ">
                \t\t\t\t\tAnulado
            \t\t\t\t\t</option>
                              </select>
                          </td>
                          <td class=\"liste_title\">
                    \t  \t<input class=\"f_inputflat f_maxwidth150imp\" type=\"date\" name=\"filterFechaTramite\"
                    \t  \t\tvalue=\"";
        // line 129
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListContratos", [], "any", false, true, false, 129), "filterFechaTramite", [], "any", true, true, false, 129)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 129, $this->source); })()), "formFilterListContratos", [], "any", false, false, false, 129), "filterFechaTramite", [], "any", false, false, false, 129), "html", null, true);
        }
        echo "\"></td>
                        </tr>
                        <tr class=\"liste_title\">
                          <th class=\"wrapcolumntitle liste_title\">Ref.</th>
                          <th class=\"wrapcolumntitle liste_title\">Ref. Predio</th>
                          <th class=\"wrapcolumntitle liste_title\">Estado</th>
                          <th class=\"wrapcolumntitle liste_title\">Fecha Tramite</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      \t";
        // line 140
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 140, $this->source); })()), "contratos", [], "any", false, false, false, 140))) {
            // line 141
            echo "                      \t
                      \t\t";
            // line 142
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 142, $this->source); })()), "contratos", [], "any", false, false, false, 142));
            foreach ($context['_seq'] as $context["_key"] => $context["contrato"]) {
                // line 143
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"";
                // line 145
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 145, $this->source); })()), "html", null, true);
                echo "/contrato/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["contrato"], "CTO_CODIGO", [], "any", false, false, false, 145), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span style=\" color: #a69944\">
                                  \t\t\t<i class=\"fas fa-file-contract mr-1\"></i>
                              \t\t\t</span>
                                  \t\t<span class=\"align-middtle\">";
                // line 149
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["contrato"], "CTO_CODIGO", [], "any", false, false, false, 149), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax250\">
                                    <a href=\"";
                // line 153
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 153, $this->source); })()), "html", null, true);
                echo "/predio/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["contrato"], "PRE_CODIGO", [], "any", false, false, false, 153), "html", null, true);
                echo "\" class=\"f_link\">
                                    \t<span class=\"align-middtle\">";
                // line 154
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["contrato"], "PRE_CODIGO", [], "any", false, false, false, 154), "html", null, true);
                echo "</span>
                                    </a>
                                  </td>
                                  <td class=\"f_overflowmax250\">
                                    ";
                // line 158
                if ((twig_get_attribute($this->env, $this->source, $context["contrato"], "CTO_ESTADO", [], "any", false, false, false, 158) == 0)) {
                    // line 159
                    echo "                                    \t<span class=\"badge badge-warning\">";
                    echo "Tramite";
                    echo "</span>
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 160
$context["contrato"], "CTO_ESTADO", [], "any", false, false, false, 160) == 1)) {
                    // line 161
                    echo "                                    \t<span class=\"badge badge-success\">";
                    echo "Activo";
                    echo "</span>
                                    ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 162
$context["contrato"], "CTO_ESTADO", [], "any", false, false, false, 162) == 2)) {
                    // line 163
                    echo "                                    \t<span class=\"badge badge-danger\">";
                    echo "Anulado";
                    echo "</span>
                                    ";
                }
                // line 165
                echo "                                  </td>
                                  <td class=\"f_overflowmax150\">";
                // line 166
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["contrato"], "CTO_FECHA_TRAMITE", [], "any", false, false, false, 166), "html", null, true);
                echo "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['contrato'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 169
            echo "                        
                      \t";
        } else {
            // line 171
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 3));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 172
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td><td></td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 176
            echo "                      \t";
        }
        // line 177
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
        // line 191
        echo "    
</div><!-- /.card -->
    
";
    }

    // line 196
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 197
        echo "
\t";
        // line 198
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#paginaAnterior').click(function(event){
\t\t\tif(\$('#paginaAnterior').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) - 1);
\t\t\t\t\$('#formFilterListContratos').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\t\$('#paginaSiguiente').click(function(event){
\t\t\tif(\$('#paginaSiguiente').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) + 1);
\t\t\t\t\$('#formFilterListContratos').submit();
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
        return "/administration/contrato/contratoList.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  429 => 198,  426 => 197,  422 => 196,  415 => 191,  400 => 177,  397 => 176,  388 => 172,  383 => 171,  379 => 169,  370 => 166,  367 => 165,  361 => 163,  359 => 162,  354 => 161,  352 => 160,  347 => 159,  345 => 158,  338 => 154,  332 => 153,  325 => 149,  316 => 145,  312 => 143,  308 => 142,  305 => 141,  303 => 140,  287 => 129,  278 => 122,  272 => 121,  270 => 120,  264 => 116,  258 => 115,  256 => 114,  250 => 110,  244 => 109,  242 => 108,  232 => 103,  224 => 100,  213 => 94,  202 => 85,  195 => 80,  183 => 71,  176 => 69,  170 => 66,  166 => 65,  157 => 59,  149 => 56,  144 => 53,  135 => 45,  129 => 40,  126 => 39,  117 => 37,  112 => 36,  110 => 35,  104 => 33,  101 => 32,  98 => 31,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  69 => 16,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/contrato/contratoList.twig", "/home/franco/proyectos/php/jass/resources/views/administration/contrato/contratoList.twig");
    }
}
