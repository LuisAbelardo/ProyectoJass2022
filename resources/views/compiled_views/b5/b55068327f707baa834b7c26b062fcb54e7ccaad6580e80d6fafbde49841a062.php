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

/* /administration/tipopredio/tipoPredioList.twig */
class __TwigTemplate_f1492d5151adc330a2a3ccfc66ed5308857b6de8249106d15af5241cc14fd37a extends \Twig\Template
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
        $context["menuLItem"] = "tipopredio";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/tipopredio/tipoPredioList.twig", 3);
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
        echo "/tipopredio/lista/filtro\" id=\"formFilterListTipospredio\">
  \t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t\t<div class=\"f_cardheader\">
                    <div class=\"\"> 
                    \t<i class=\"fas fa-home mr-3\"></i>Tipos de predio
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
        echo "/tipopredio/nuevo\" class=\"f_link\">Nuevo</a>
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
        echo "/tipopredio/lista\" class=\"f_link\">
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
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListTiposPredio", [], "any", false, true, false, 109), "filterCodigo", [], "any", true, true, false, 109)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 109, $this->source); })()), "formFilterListTiposPredio", [], "any", false, false, false, 109), "filterCodigo", [], "any", false, false, false, 109), "html", null, true);
        }
        echo "'></td>
                        \t  <td class=\"liste_title\">
                        \t  \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterNombre\"
                        \t  \t\tvalue=\"";
        // line 112
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListTiposPredio", [], "any", false, true, false, 112), "filterNombre", [], "any", true, true, false, 112)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 112, $this->source); })()), "formFilterListTiposPredio", [], "any", false, false, false, 112), "filterNombre", [], "any", false, false, false, 112), "html", null, true);
        }
        echo "\"></td>
                            </tr>
                            <tr class=\"liste_title\">
                              <th class=\"wrapcolumntitle liste_title\">Ref.</th>
                              <th class=\"wrapcolumntitle liste_title\">Tipo predio</th>
                            </tr>
                          </thead>
                          <tbody>
                          
                          \t";
        // line 121
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 121, $this->source); })()), "tiposPredio", [], "any", false, false, false, 121))) {
            // line 122
            echo "                          \t
                          \t\t";
            // line 123
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 123, $this->source); })()), "tiposPredio", [], "any", false, false, false, 123));
            foreach ($context['_seq'] as $context["_key"] => $context["tipoPredio"]) {
                // line 124
                echo "                                    <tr class=\"f_oddeven\">
                                      <td>
                                          <a href=\"";
                // line 126
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 126, $this->source); })()), "html", null, true);
                echo "/tipopredio/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 126), "html", null, true);
                echo "\" class=\"f_link\">
                                      \t\t<span style=\" color: #a69944\">
                                      \t\t\t<i class=\"fas fa-home mr-1\"></i>
                                  \t\t\t</span>
                                      \t\t<span class=\"align-middtle\">";
                // line 130
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 130), "html", null, true);
                echo "</span>
                                          </a>
                                      </td>
                                      <td class=\"f_overflowmax150\">";
                // line 133
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_NOMBRE", [], "any", false, false, false, 133), "html", null, true);
                echo "</td>
                                    </tr>
                                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tipoPredio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 136
            echo "                            
                          \t";
        } else {
            // line 138
            echo "                          \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 3));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 139
                echo "                              \t\t<tr>
            \t\t\t\t\t\t\t<td>&nbsp;</td><td></td>
                                    </tr>
                                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 143
            echo "                          \t";
        }
        // line 144
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
        // line 158
        echo "    
</div><!-- /.card -->
    
";
    }

    // line 163
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 164
        echo "
\t";
        // line 165
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#paginaAnterior').click(function(event){
\t\t\tif(\$('#paginaAnterior').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) - 1);
\t\t\t\t\$('#formFilterListTiposPredio').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\t\$('#paginaSiguiente').click(function(event){
\t\t\tif(\$('#paginaSiguiente').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) + 1);
\t\t\t\t\$('#formFilterListTiposPredio').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/tipopredio/tipoPredioList.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  340 => 165,  337 => 164,  333 => 163,  326 => 158,  311 => 144,  308 => 143,  299 => 139,  294 => 138,  290 => 136,  281 => 133,  275 => 130,  266 => 126,  262 => 124,  258 => 123,  255 => 122,  253 => 121,  239 => 112,  231 => 109,  220 => 100,  213 => 95,  201 => 86,  194 => 84,  188 => 81,  184 => 80,  175 => 74,  167 => 71,  162 => 68,  148 => 56,  136 => 46,  130 => 41,  127 => 40,  118 => 38,  113 => 37,  111 => 36,  105 => 34,  102 => 33,  99 => 32,  96 => 31,  93 => 30,  90 => 29,  87 => 28,  84 => 27,  81 => 26,  69 => 16,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/tipopredio/tipoPredioList.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\tipopredio\\tipoPredioList.twig");
    }
}
