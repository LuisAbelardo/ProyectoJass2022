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

/* /administration/tipousopredio/tipoUsoPredioList.twig */
class __TwigTemplate_109dbbf4f8240420fe4bc45caf73c9cfdad8673d8ac94a6452c8de047cd92013 extends \Twig\Template
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
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/tipousopredio/tipoUsoPredioList.twig", 3);
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
        echo "/tipousopredio/lista/filtro\" id=\"formFilterListTipoUsoPredio\">
  \t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t\t<div class=\"f_cardheader\">
                    <div class=\"\"> 
                    \t<i class=\"fas fa-warehouse mr-3\"></i>Tipos de uso de predio
                    \t<span>(";
        // line 16
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 16, $this->source); })()), "pagination", [], "any", false, false, false, 16), "paginaCantidadRegistros", [], "any", false, false, false, 16), "html", null, true);
        echo ")</span>
                \t</div>
                  </div>
  \t\t\t</div>
  \t\t</div><!-- /.card-header -->
  \t\t
  \t\t
  \t\t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t";
        // line 26
        echo "                ";
        $context["classAlert"] = "";
        // line 27
        echo "                ";
        if (twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 27, $this->source); })()))) {
            // line 28
            echo "                \t";
            $context["classAlert"] = "d-none";
            // line 29
            echo "                ";
        } elseif ((((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 29, $this->source); })()) >= 200) && ((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 29, $this->source); })()) < 300))) {
            // line 30
            echo "                    ";
            $context["classAlert"] = "alert-success";
            // line 31
            echo "                ";
        } elseif (((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 31, $this->source); })()) >= 400)) {
            // line 32
            echo "                    ";
            $context["classAlert"] = "alert-danger";
            // line 33
            echo "                ";
        }
        // line 34
        echo "      \t\t\t<div class=\"alert ";
        echo twig_escape_filter($this->env, (isset($context["classAlert"]) || array_key_exists("classAlert", $context) ? $context["classAlert"] : (function () { throw new RuntimeError('Variable "classAlert" does not exist.', 34, $this->source); })()), "html", null, true);
        echo " alert-dismissible fade show\" id=\"f_alertsContainer\" role=\"alert\">
                 \t<ul class=\"mb-0\" id=\"f_alertsUl\">
                 \t\t";
        // line 36
        if ( !twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 36, $this->source); })()))) {
            // line 37
            echo "                          ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 37, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["msj"]) {
                // line 38
                echo "                            <li>";
                echo twig_escape_filter($this->env, $context["msj"], "html", null, true);
                echo "</li>
                          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['msj'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 40
            echo "                        ";
        }
        // line 41
        echo "                 \t</ul>
                 \t<button type=\"button\" class=\"close\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\" id=\"f_alertsDismiss\">
                 \t\t<span aria-hidden=\"true\">&times;</span>
                 \t</button>
                </div>";
        // line 46
        echo "      \t\t</div>
      \t</div>
      \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
          \t\t<div class=\"f_tabs\">
          \t\t\t<div class=\"f_tabactive\">
          \t\t\t\t<a href=\"#\" class=\"f_link\">Lista</a>
          \t\t\t</div>
          \t\t\t<div class=\"f_tabunactive\">
          \t\t\t\t<a href=\"";
        // line 56
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 56, $this->source); })()), "html", null, true);
        echo "/tipousopredio/nuevo\" class=\"f_link\">Nuevo</a>
          \t\t\t</div>
              \t</div>
      \t\t</div>
      \t</div><!-- /.tabs de contenido -->
      \t
  \t\t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_divwithbartop\">
      \t\t\t
      \t\t\t\t";
        // line 68
        echo "      \t\t\t\t<div class=\"d-flex justify-content-end align-items-center pb-2\">
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
        echo "/tipousopredio/lista\" class=\"f_link\">
                    \t\t\t<i class=\"fas fa-times\"></i>
                \t\t\t</a>
                        </div>
      \t\t\t\t</div>";
        // line 100
        echo "      \t\t\t\t
      \t\t\t
        \t\t\t<div class=\"table-responsive\">
                        <table class=\"table f_table f_tableliste f_listwidthfilterbefore\">
                          <thead>
                          \t<tr class=\"liste_title_filter\">
                              <td class=\"liste_title f_minwidth125\">
                              \t<i class=\"fas fa-filter mr-1\"></i>
        \t\t\t\t\t\t<input class=\"f_inputflat f_maxwidth80imp\" type=\"text\" name=\"filterCodigo\"
                              \t\tvalue='";
        // line 109
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListTiposUsoPredio", [], "any", false, true, false, 109), "filterCodigo", [], "any", true, true, false, 109)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 109, $this->source); })()), "formFilterListTiposUsoPredio", [], "any", false, false, false, 109), "filterCodigo", [], "any", false, false, false, 109), "html", null, true);
        }
        echo "'></td>
                        \t  <td class=\"liste_title\">
                        \t  \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterNombre\"
                        \t  \t\tvalue=\"";
        // line 112
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListTiposUsoPredio", [], "any", false, true, false, 112), "filterNombre", [], "any", true, true, false, 112)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 112, $this->source); })()), "formFilterListTiposUsoPredio", [], "any", false, false, false, 112), "filterNombre", [], "any", false, false, false, 112), "html", null, true);
        }
        echo "\"></td>
                \t  \t\t  <td class=\"liste_title\">
        \t\t\t\t\t\t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterTarifaAgua\" value=\"\" disabled></td>
                        \t  <td class=\"liste_title\">
                        \t  \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterTarifaDesague\" value=\"\" disabled></td>
\t\t\t\t\t\t\t  <td class=\"liste_title\">
\t\t\t\t\t\t\t\t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterTarifaAmbos\" value=\"\" disabled></td>
\t\t\t\t\t\t\t  <td class=\"liste_title\">
                        \t  \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterTarifaManten\" value=\"\" disabled></td>
                    \t  \t  <td class=\"liste_title\">
                    \t  \t  \t<select class=\"f_inputflat f_maxwidth300imp\" name=\"filterTipoPredio\" id=\"cmbFilterTipoPredio\">
                                    <option value=\"-1\" class=\"f_opacitymedium\"></option>
                                    ";
        // line 124
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tiposPredio", [], "any", true, true, false, 124)) {
            // line 125
            echo "                                    \t";
            $context["selectedFilterTipoPredio"] = false;
            // line 126
            echo "                                \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 126, $this->source); })()), "tiposPredio", [], "any", false, false, false, 126));
            foreach ($context['_seq'] as $context["_key"] => $context["tipoPredio"]) {
                // line 127
                echo "                                        \t<option value=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 127), "html", null, true);
                echo "\"
                                    \t\t\t\t";
                // line 128
                if ((( !(isset($context["selectedFilterTipoPredio"]) || array_key_exists("selectedFilterTipoPredio", $context) ? $context["selectedFilterTipoPredio"] : (function () { throw new RuntimeError('Variable "selectedFilterTipoPredio" does not exist.', 128, $this->source); })()) && twig_get_attribute($this->env, $this->source,                 // line 129
($context["data"] ?? null), "formFilterListTiposUsoPredio", [], "any", true, true, false, 129)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,                 // line 130
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 130, $this->source); })()), "formFilterListTiposUsoPredio", [], "any", false, false, false, 130), "filterTipoPredio", [], "any", false, false, false, 130) == twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 130)))) {
                    // line 131
                    echo "                        \t\t\t\t\t            ";
                    echo "selected";
                    $context["selectedFilterTipoPredio"] = true;
                }
                echo ">
                                \t\t\t\t";
                // line 132
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_NOMBRE", [], "any", false, false, false, 132), "html", null, true);
                echo "
                            \t\t\t\t</option>
                                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tipoPredio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 135
            echo "                                \t";
        }
        // line 136
        echo "                                  </select>
                    \t  \t  </td>
                            </tr>
                            <tr class=\"liste_title\">
                              <th class=\"wrapcolumntitle liste_title\">Ref.</th>
                              <th class=\"wrapcolumntitle liste_title\">Tipo de uso de predio</th>
                              <th class=\"wrapcolumntitle liste_title\">Tarifa agua</th>
                              <th class=\"wrapcolumntitle liste_title\">Tarifa desague</th>
\t\t\t\t\t\t\t  <th class=\"wrapcolumntitle liste_title\">Tarifa Ambos</th>
\t\t\t\t\t\t\t  <th class=\"wrapcolumntitle liste_title\">Tarifa Mantenimiento</th>
                              <th class=\"wrapcolumntitle liste_title\">Tipo predio</th>
                            </tr>
                          </thead>
                          <tbody>
                          
                          \t";
        // line 151
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 151, $this->source); })()), "tiposUsoPredio", [], "any", false, false, false, 151))) {
            // line 152
            echo "                          \t
                          \t\t";
            // line 153
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 153, $this->source); })()), "tiposUsoPredio", [], "any", false, false, false, 153));
            foreach ($context['_seq'] as $context["_key"] => $context["tipoUsoPredio"]) {
                // line 154
                echo "                                    <tr class=\"f_oddeven\">
                                      <td>
                                          <a href=\"";
                // line 156
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 156, $this->source); })()), "html", null, true);
                echo "/tipousopredio/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_CODIGO", [], "any", false, false, false, 156), "html", null, true);
                echo "\" class=\"f_link\">
                                      \t\t<span style=\" color: #a69944\">
                                      \t\t\t<i class=\"fas fa-warehouse mr-1\"></i>
                                  \t\t\t</span>
                                      \t\t<span class=\"align-middtle\">";
                // line 160
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_CODIGO", [], "any", false, false, false, 160), "html", null, true);
                echo "</span>
                                          </a>
                                      </td>
                                      <td class=\"f_overflowmax150\">";
                // line 163
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_NOMBRE", [], "any", false, false, false, 163), "html", null, true);
                echo "</td>
                                      <td class=\"f_overflowmax150\">";
                // line 164
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_TARIFA_AGUA", [], "any", false, false, false, 164), 2, ".", ","), "html", null, true);
                echo "</td>
                                      <td class=\"f_overflowmax150\">";
                // line 165
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_TARIFA_DESAGUE", [], "any", false, false, false, 165), 2, ".", ","), "html", null, true);
                echo "</td>
\t\t\t\t\t\t\t\t\t  <td class=\"f_overflowmax150\">";
                // line 166
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_TARIFA_AMBOS", [], "any", false, false, false, 166), 2, ".", ","), "html", null, true);
                echo "</td>
\t\t\t\t\t\t\t\t\t  <td class=\"f_overflowmax150\">";
                // line 167
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_TARIFA_MANTENIMIENTO", [], "any", false, false, false, 167), 2, ".", ","), "html", null, true);
                echo "</td>
                                      <td class=\"f_overflowmax300\">
                                      \t";
                // line 169
                if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tiposPredio", [], "any", true, true, false, 169)) {
                    // line 170
                    echo "                                      \t\t";
                    $context["selectedTipoPredio"] = false;
                    // line 171
                    echo "        \t\t\t\t\t\t\t\t\t";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 171, $this->source); })()), "tiposPredio", [], "any", false, false, false, 171));
                    foreach ($context['_seq'] as $context["_key"] => $context["tipoPredio"]) {
                        if ( !(isset($context["selectedTipoPredio"]) || array_key_exists("selectedTipoPredio", $context) ? $context["selectedTipoPredio"] : (function () { throw new RuntimeError('Variable "selectedTipoPredio" does not exist.', 171, $this->source); })())) {
                            // line 172
                            echo "        \t\t\t\t\t\t\t\t\t\t";
                            if ((twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 172) == twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TIP_CODIGO", [], "any", false, false, false, 172))) {
                                // line 173
                                echo "        \t\t\t\t\t\t\t\t\t\t\t";
                                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_NOMBRE", [], "any", false, false, false, 173), "html", null, true);
                                $context["selectedTipoPredio"] = true;
                                // line 174
                                echo "        \t\t\t\t\t\t\t\t\t\t";
                            }
                            // line 175
                            echo "        \t\t\t\t\t\t\t\t\t";
                        }
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tipoPredio'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 176
                    echo "        \t\t\t\t\t\t\t\t";
                }
                // line 177
                echo "                                      </td>
                                    </tr>
                                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tipoUsoPredio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 180
            echo "                            
                          \t";
        } else {
            // line 182
            echo "                          \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 3));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 183
                echo "                              \t\t<tr>
            \t\t\t\t\t\t\t<td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    </tr>
                                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 187
            echo "                          \t";
        }
        // line 188
        echo "                          
                          \t
                          </tbody>
                        </table>
                    </div>
      \t\t\t</div>
    \t\t</div>
  \t\t</div><!-- /.card-body -->
  \t\t
  \t\t<div class=\"row\">
      \t\t<div class=\"col-12 f_cardfooter text-right\"></div>
      \t</div><!-- /.card-footer -->
  \t\t
    </form>";
        // line 202
        echo "    
</div><!-- /.card -->
    
";
    }

    // line 207
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 208
        echo "
\t";
        // line 209
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#paginaAnterior').click(function(event){
\t\t\tif(\$('#paginaAnterior').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) - 1);
\t\t\t\t\$('#formFilterListTipoUsoPredio').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\t\$('#paginaSiguiente').click(function(event){
\t\t\tif(\$('#paginaSiguiente').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) + 1);
\t\t\t\t\$('#formFilterListTipoUsoPredio').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\tf_select2(\"#cmbFilterTipoPredio\");
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/tipousopredio/tipoUsoPredioList.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  449 => 209,  446 => 208,  442 => 207,  435 => 202,  420 => 188,  417 => 187,  408 => 183,  403 => 182,  399 => 180,  391 => 177,  388 => 176,  381 => 175,  378 => 174,  374 => 173,  371 => 172,  365 => 171,  362 => 170,  360 => 169,  355 => 167,  351 => 166,  347 => 165,  343 => 164,  339 => 163,  333 => 160,  324 => 156,  320 => 154,  316 => 153,  313 => 152,  311 => 151,  294 => 136,  291 => 135,  282 => 132,  275 => 131,  273 => 130,  272 => 129,  271 => 128,  266 => 127,  261 => 126,  258 => 125,  256 => 124,  239 => 112,  231 => 109,  220 => 100,  213 => 95,  201 => 86,  194 => 84,  188 => 81,  184 => 80,  175 => 74,  167 => 71,  162 => 68,  148 => 56,  136 => 46,  130 => 41,  127 => 40,  118 => 38,  113 => 37,  111 => 36,  105 => 34,  102 => 33,  99 => 32,  96 => 31,  93 => 30,  90 => 29,  87 => 28,  84 => 27,  81 => 26,  69 => 16,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/tipousopredio/tipoUsoPredioList.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\tipousopredio\\tipoUsoPredioList.twig");
    }
}
