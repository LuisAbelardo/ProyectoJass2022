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

/* /administration/cliente/clienteList.twig */
class __TwigTemplate_156a3327fa059e693bed7ea0dad684e5ce8bfe1fe955f95d029cc45454f9f45b extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["cliente", "lista"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/cliente/clienteList.twig", 3);
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
        echo "/cliente/lista/filtro\" id=\"formFilterListClientes\">
  \t
  \t\t<div class=\"row\">
  \t\t\t<div class=\"col-12\">
  \t\t\t\t<div class=\"f_cardheader\">
                    <div> 
                    \t<i class=\"fas fa-user mr-3\"></i>Listado de clientes
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
        echo "/cliente/lista\" class=\"f_link\">
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
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListClientes", [], "any", false, true, false, 94), "filterCodigo", [], "any", true, true, false, 94)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 94, $this->source); })()), "formFilterListClientes", [], "any", false, false, false, 94), "filterCodigo", [], "any", false, false, false, 94), "html", null, true);
        }
        echo "'></td>
                    \t  <td class=\"liste_title\">
                    \t  \t<select class=\"f_inputflat\" name=\"filterTipo\" id=\"cmbFilterTipo\">
                                <option value=\"-1\" class=\"f_opacitymedium\"></option>
                                <option value=\"1\" 
                                    ";
        // line 99
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListClientes", [], "any", false, true, false, 99), "filterTipo", [], "any", true, true, false, 99) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 99, $this->source); })()), "formFilterListClientes", [], "any", false, false, false, 99), "filterTipo", [], "any", false, false, false, 99) == "1"))) {
            // line 100
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 101
        echo ">
                \t\t\t\t\tNatual
            \t\t\t\t\t</option>
                                <option value=\"2\"
                                    ";
        // line 105
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListClientes", [], "any", false, true, false, 105), "filterTipo", [], "any", true, true, false, 105) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 105, $this->source); })()), "formFilterListClientes", [], "any", false, false, false, 105), "filterTipo", [], "any", false, false, false, 105) == "2"))) {
            // line 106
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 107
        echo ">
                \t\t\t\t\tJuridico
            \t\t\t\t\t</option>
                              </select>
                    \t  </td>\t\t
                    \t  <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterDocumento\" maxlength=\"11\"
                          \t\tvalue=\"";
        // line 114
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListClientes", [], "any", false, true, false, 114), "filterDocumento", [], "any", true, true, false, 114)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 114, $this->source); })()), "formFilterListClientes", [], "any", false, false, false, 114), "filterDocumento", [], "any", false, false, false, 114), "html", null, true);
        }
        echo "\"></td>
                          <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterNombres\"
                          \t\tvalue=\"";
        // line 117
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListClientes", [], "any", false, true, false, 117), "filterNombres", [], "any", true, true, false, 117)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 117, $this->source); })()), "formFilterListClientes", [], "any", false, false, false, 117), "filterNombres", [], "any", false, false, false, 117), "html", null, true);
        }
        echo "\"></td>
                          <td class=\"liste_title\">
                          \t<input class=\"f_inputflat f_maxwidth100imp\" type=\"text\" name=\"filterTelefono\" disabled
                          \t\tvalue=\"";
        // line 120
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formFilterListClientes", [], "any", false, true, false, 120), "filterTelefono", [], "any", true, true, false, 120)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 120, $this->source); })()), "formFilterListClientes", [], "any", false, false, false, 120), "filterTelefono", [], "any", false, false, false, 120), "html", null, true);
        }
        echo "\"></td>
                        </tr>
                        <tr class=\"liste_title\">
                          <th class=\"wrapcolumntitle liste_title\">Ref.</th>
                          <th class=\"wrapcolumntitle liste_title\">Tipo</th>
                          <th class=\"wrapcolumntitle liste_title\">Documento</th>
                          <th class=\"wrapcolumntitle liste_title\">Nombre</th>
                          <th class=\"wrapcolumntitle liste_title\">Teléfono</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      \t";
        // line 132
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 132, $this->source); })()), "clientes", [], "any", false, false, false, 132))) {
            // line 133
            echo "                      \t
                      \t\t";
            // line 134
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 134, $this->source); })()), "clientes", [], "any", false, false, false, 134));
            foreach ($context['_seq'] as $context["_key"] => $context["cliente"]) {
                // line 135
                echo "                                <tr class=\"f_oddeven\">
                                  <td>
                                      <a href=\"";
                // line 137
                echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 137, $this->source); })()), "html", null, true);
                echo "/cliente/detalle/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["cliente"], "CLI_CODIGO", [], "any", false, false, false, 137), "html", null, true);
                echo "\" class=\"f_link\">
                                  \t\t<span style=\" color: #a69944\">
                                  \t\t\t<i class=\"fas fa-user mr-1\"></i>
                              \t\t\t</span>
                                  \t\t<span class=\"align-middtle\">";
                // line 141
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["cliente"], "CLI_CODIGO", [], "any", false, false, false, 141), "html", null, true);
                echo "</span>
                                      </a>
                                  </td>
                                  <td class=\"f_overflowmax150\">
                                  \t  ";
                // line 145
                if ((twig_get_attribute($this->env, $this->source, $context["cliente"], "CLI_TIPO", [], "any", false, false, false, 145) == 1)) {
                    // line 146
                    echo "                                      \t";
                    echo "Natural";
                    echo "
                                      ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 147
$context["cliente"], "CLI_TIPO", [], "any", false, false, false, 147) == 2)) {
                    // line 148
                    echo "                                      \t";
                    echo "Juridico";
                    echo "
                                      ";
                }
                // line 150
                echo "                                  </td>
                                  <td class=\"f_overflowmax250\">";
                // line 151
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["cliente"], "CLI_DOCUMENTO", [], "any", false, false, false, 151), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax150\">";
                // line 152
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["cliente"], "CLI_NOMBRES", [], "any", false, false, false, 152), "html", null, true);
                echo "</td>
                                  <td class=\"f_overflowmax150\">";
                // line 153
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["cliente"], "CLI_TELEFONO", [], "any", false, false, false, 153), "html", null, true);
                echo "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cliente'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 156
            echo "                        
                      \t";
        } else {
            // line 158
            echo "                      \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, 3));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 159
                echo "                          \t\t<tr>
        \t\t\t\t\t\t\t<td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 163
            echo "                      \t";
        }
        // line 164
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
        // line 178
        echo "    
</div><!-- /.card -->
    
";
    }

    // line 183
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 184
        echo "
\t";
        // line 185
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t
\t<script type=\"text/javascript\">
\t\t\$('#paginaAnterior').click(function(event){
\t\t\tif(\$('#paginaAnterior').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) - 1);
\t\t\t\t\$('#formFilterListClientes').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
\t\t
\t\t\$('#paginaSiguiente').click(function(event){
\t\t\tif(\$('#paginaSiguiente').attr(\"data-page\") != -1){
\t\t\t\t\$('#filterPaginaActual').val(parseInt(\$('#filterPaginaActual').val()) + 1);
\t\t\t\t\$('#formFilterListClientes').submit();
\t\t\t}
\t\t\treturn false;
\t\t});
        
        f_select2(\"#cmbFilterTipo\");
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/cliente/clienteList.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  401 => 185,  398 => 184,  394 => 183,  387 => 178,  372 => 164,  369 => 163,  360 => 159,  355 => 158,  351 => 156,  342 => 153,  338 => 152,  334 => 151,  331 => 150,  325 => 148,  323 => 147,  318 => 146,  316 => 145,  309 => 141,  300 => 137,  296 => 135,  292 => 134,  289 => 133,  287 => 132,  270 => 120,  262 => 117,  254 => 114,  245 => 107,  239 => 106,  237 => 105,  231 => 101,  225 => 100,  223 => 99,  213 => 94,  202 => 85,  195 => 80,  183 => 71,  176 => 69,  170 => 66,  166 => 65,  157 => 59,  149 => 56,  144 => 53,  135 => 45,  129 => 40,  126 => 39,  117 => 37,  112 => 36,  110 => 35,  104 => 33,  101 => 32,  98 => 31,  95 => 30,  92 => 29,  89 => 28,  86 => 27,  83 => 26,  80 => 25,  69 => 16,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/cliente/clienteList.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\cliente\\clienteList.twig");
    }
}
