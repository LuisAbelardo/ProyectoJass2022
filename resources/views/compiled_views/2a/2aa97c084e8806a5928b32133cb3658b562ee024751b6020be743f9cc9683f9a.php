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

/* /administration/predio/predioNew.twig */
class __TwigTemplate_704d1a0beb903bb1def2e172ea248fd124931a74093115e22fa71d0d5202e31f extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["predio", "nuevo"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/predio/predioNew.twig", 3);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 6
        echo "
<div class=\"f_card\">
\t";
        // line 9
        echo "\t<form class=\"f_inputflat\" id=\"formNuevoPredio\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/predio/create\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-house-user mr-3\"></i>Nuevo predio
                \t</div>
      \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-header -->
      \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t";
        // line 24
        echo "                ";
        $context["classAlert"] = "";
        // line 25
        echo "                ";
        if (twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 25, $this->source); })()))) {
            // line 26
            echo "                \t";
            $context["classAlert"] = "d-none";
            // line 27
            echo "                ";
        } elseif ((((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 27, $this->source); })()) >= 200) && ((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 27, $this->source); })()) < 300))) {
            // line 28
            echo "                    ";
            $context["classAlert"] = "alert-success";
            // line 29
            echo "                ";
        } elseif (((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 29, $this->source); })()) >= 400)) {
            // line 30
            echo "                    ";
            $context["classAlert"] = "alert-danger";
            // line 31
            echo "                ";
        }
        // line 32
        echo "      \t\t\t<div class=\"alert ";
        echo twig_escape_filter($this->env, (isset($context["classAlert"]) || array_key_exists("classAlert", $context) ? $context["classAlert"] : (function () { throw new RuntimeError('Variable "classAlert" does not exist.', 32, $this->source); })()), "html", null, true);
        echo " alert-dismissible fade show\" id=\"f_alertsContainer\" role=\"alert\">
                 \t<ul class=\"mb-0\" id=\"f_alertsUl\">
                 \t\t";
        // line 34
        if ( !twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 34, $this->source); })()))) {
            // line 35
            echo "                          ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 35, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["msj"]) {
                // line 36
                echo "                            <li>";
                echo twig_escape_filter($this->env, $context["msj"], "html", null, true);
                echo "</li>
                          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['msj'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 38
            echo "                        ";
        }
        // line 39
        echo "                 \t</ul>
                 \t<button type=\"button\" class=\"close\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\" id=\"f_alertsDismiss\">
                 \t\t<span aria-hidden=\"true\">&times;</span>
                 \t</button>
                </div>";
        // line 44
        echo "      \t\t</div>
      \t</div>
      \t
  
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_divwithbartop f_divwithbarbottom\">
      \t\t\t
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpCliente\">Cliente (DNI / RUC)</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpCliente\" name=\"cliente\" required maxlength=\"11\"
                        \t\t\tvalue='";
        // line 56
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", false, true, false, 56), "cliente", [], "any", true, true, false, 56)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 56, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 56), "cliente", [], "any", false, false, false, 56), "html", null, true);
        }
        echo "'>
                \t\t\t<button type=\"button\" class=\"f_btnflat\" name=\"btnBuscarCliente\" id=\"btnBuscarCliente\">
                \t\t\t\t<span class=\"fas fa-search\"></span>
            \t\t\t\t</button>
                \t\t\t<span><i class=\"fas fa-spinner f_classforrotatespinner d-none\" id=\"spinnerBuscarCliente\"></i></span>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpClienteNombre\">Cliente Nombre</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpClienteNombre\" name=\"clienteNombre\" required disabled value=''>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbCalle\">Calle</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"calle\" class=\"f_minwidth300\" id=\"cmbCalle\" required> 
                            \t";
        // line 73
        $context["selectedCalle"] = false;
        // line 74
        echo "                                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 74, $this->source); })()), "calles", [], "any", false, false, false, 74));
        foreach ($context['_seq'] as $context["_key"] => $context["calle"]) {
            // line 75
            echo "                                \t<option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["calle"], "CAL_CODIGO", [], "any", false, false, false, 75), "html", null, true);
            echo "\"
                            \t\t\t\t";
            // line 76
            if ((( !(isset($context["selectedCalle"]) || array_key_exists("selectedCalle", $context) ? $context["selectedCalle"] : (function () { throw new RuntimeError('Variable "selectedCalle" does not exist.', 76, $this->source); })()) && twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 76)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 77
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 77, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 77), "calle", [], "any", false, false, false, 77) == twig_get_attribute($this->env, $this->source, $context["calle"], "CAL_CODIGO", [], "any", false, false, false, 77)))) {
                // line 78
                echo "                            \t\t\t\t\t";
                echo "selected";
                $context["selectedCalle"] = true;
                // line 79
                echo "                                            ";
            }
            echo ">
                        \t\t\t\t";
            // line 80
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["calle"], "CAL_NOMBRE", [], "any", false, false, false, 80), "html", null, true);
            echo "
                    \t\t\t\t</option>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['calle'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 83
        echo "                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpDireccion\">Dirección</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth250\" id=\"inpDireccion\" name=\"direccion\" required
                        \t\t\tvalue='";
        // line 90
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", false, true, false, 90), "direccion", [], "any", true, true, false, 90)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 90, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 90), "direccion", [], "any", false, false, false, 90), "html", null, true);
        }
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"cmbHabitada\">Habitada</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"habitada\" class=\"f_minwidth300\" id=\"cmbHabitada\"> 
                        \t\t<option value=\"-1\"></option>
                            \t<option value=\"si\"
                        \t\t\t\t";
        // line 99
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 99) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 100
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 100, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 100), "habitada", [], "any", false, false, false, 100) == "si"))) {
            // line 101
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                                        ";
        }
        // line 102
        echo ">
                                        Si
                \t\t\t\t</option>
                \t\t\t\t<option value=\"no\"
                        \t\t\t\t";
        // line 106
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 106) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 107
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 107, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 107), "habitada", [], "any", false, false, false, 107) == "no"))) {
            // line 108
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                                        ";
        }
        // line 109
        echo ">
                                        No
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"cmbMaterialConst\">Material de Construcción</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"materialConst\" class=\"f_minwidth300\" id=\"cmbMaterialConst\"> 
                            \t<option value=\"-1\"></option>
                            \t<option value=\"noble\"
                        \t\t\t\t";
        // line 121
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 121) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 122
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 122, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 122), "materialConst", [], "any", false, false, false, 122) == "noble"))) {
            // line 123
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                                        ";
        }
        // line 124
        echo ">
                                        Noble
                \t\t\t\t</option>
                \t\t\t\t<option value=\"adobe\"
                        \t\t\t\t";
        // line 128
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 128) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 129
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 129, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 129), "materialConst", [], "any", false, false, false, 129) == "adobe"))) {
            // line 130
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                                        ";
        }
        // line 131
        echo ">
                                        Adobe
                \t\t\t\t</option>
                \t\t\t\t<option value=\"madera\"
                        \t\t\t\t";
        // line 135
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 135) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 136
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 136, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 136), "materialConst", [], "any", false, false, false, 136) == "madera"))) {
            // line 137
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                                        ";
        }
        // line 138
        echo ">
                                        Madera
                \t\t\t\t</option>
                \t\t\t\t<option value=\"no aplicable\"
                        \t\t\t\t";
        // line 142
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 142) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 143
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 143, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 143), "materialConst", [], "any", false, false, false, 143) == "no aplicable"))) {
            // line 144
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                                        ";
        }
        // line 145
        echo ">
                                        No aplicable
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpPisos\">Pisos</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth250\" id=\"inpPisos\" name=\"pisos\"
                        \t\t\tvalue='";
        // line 155
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", false, true, false, 155), "pisos", [], "any", true, true, false, 155)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 155, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 155), "pisos", [], "any", false, false, false, 155), "html", null, true);
        }
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpFamilias\">Familias</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth250\" id=\"inpFamilias\" name=\"familias\"
                        \t\t\tvalue='";
        // line 162
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", false, true, false, 162), "familias", [], "any", true, true, false, 162)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 162, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 162), "familias", [], "any", false, false, false, 162), "html", null, true);
        }
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpHabitantes\">Habitantes</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth250\" id=\"inpHabitantes\" name=\"habitantes\"
                        \t\t\tvalue='";
        // line 169
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", false, true, false, 169), "habitantes", [], "any", true, true, false, 169)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 169, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 169), "habitantes", [], "any", false, false, false, 169), "html", null, true);
        }
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"cmbPozoTabular\">Pozo Tabular</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"pozoTabular\" class=\"f_minwidth300\" id=\"cmbPozoTabular\"> 
                        \t\t<option value=\"-1\"></option>
                            \t<option value=\"si\"
                    \t\t\t\t";
        // line 178
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 178) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 179
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 179, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 179), "pozoTabular", [], "any", false, false, false, 179) == "si"))) {
            // line 180
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 181
        echo ">
                                    Si
                \t\t\t\t</option>
                \t\t\t\t<option value=\"no\"
                    \t\t\t\t";
        // line 185
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 185) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 186
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 186, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 186), "pozoTabular", [], "any", false, false, false, 186) == "no"))) {
            // line 187
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 188
        echo ">
                                    No
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"cmbPiscina\">Piscina</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"piscina\" class=\"f_minwidth300\" id=\"cmbPiscina\"> 
                        \t\t<option value=\"-1\"></option>
                            \t<option value=\"si\"
                        \t\t\t\t";
        // line 200
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 200) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 201
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 201, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 201), "piscina", [], "any", false, false, false, 201) == "si"))) {
            // line 202
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                                        ";
        }
        // line 203
        echo ">
                                        Si
                \t\t\t\t</option>
                \t\t\t\t<option value=\"no\"
                        \t\t\t\t";
        // line 207
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPredio", [], "any", true, true, false, 207) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 208
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 208, $this->source); })()), "formNuevoPredio", [], "any", false, false, false, 208), "piscina", [], "any", false, false, false, 208) == "no"))) {
            // line 209
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                                        ";
        }
        // line 210
        echo ">
                                        No
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    
      \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-body -->
  \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardfooter f_cardfooteractions text-center\">
        \t\t\t<button type=\"submit\" class=\"f_button f_buttonaction\">Guardar predio</button>
        \t\t\t<a href=\"";
        // line 225
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 225, $this->source); })()), "html", null, true);
        echo "/predio/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 231
        echo "  
</div><!-- /.card -->

";
    }

    // line 236
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 237
        echo "
    ";
        // line 238
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formNuevoPredio').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        f_select2(\"#cmbCalle\");
        f_select2(\"#cmbHabitada\");
        f_select2(\"#cmbMaterialConst\");
        f_select2(\"#cmbPozoTabular\");
        f_select2(\"#cmbPiscina\");
        
        
        \$(\"#btnBuscarCliente\").click(function(){
        
\t\t\t\$(\"#inpClienteNombre\").val(\"\");
        \t
        \t\$(\"#inpCliente\").val(\$(\"#inpCliente\").val().trim());
        \tvar inpCliente = \$(\"#inpCliente\").val();
        \t
        \tif(inpCliente != \"\"){
        \t
        \t\tvar spinnerBuscarCliente = \$(\"#spinnerBuscarCliente\");
        \t\tspinnerBuscarCliente.removeClass(\"d-none\");
        \t
        \t\t\$.ajax({
                    method: \"GET\",
                    url: \"";
        // line 268
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 268, $this->source); })()), "html", null, true);
        echo "/cliente/detalle/json/\"+inpCliente,
                    dataType: \"json\",
                    complete: function(){
                    \tspinnerBuscarCliente.addClass(\"d-none\");
                    },
        \t\t\terror: function(jqXHR){
        \t\t\t\tvar msj = \"\";
        \t\t\t\tif(jqXHR.status == 404){
        \t\t\t\t\tmsj = \"No se encontro el cliente solicitado\";
        \t\t\t\t\tconsole.log(msj);
        \t\t\t\t}else{
        \t\t\t\t\tmsj = \"Ocurrio un error inesperado, vuelva a intentarlo\";
        \t\t\t\t\tconsole.log(msj);
        \t\t\t\t}
        \t\t\t\t\$(document).Toasts('create', {
                            \tclass: 'bg-danger',
                                title: 'Respuesta de busqueda',
                                position: 'topRight',
                                autohide: true,
       \t\t\t\t\t\t\tdelay: 4000,
                                body: msj
                            })
        \t\t\t},
        \t\t\tsuccess: function(respons){
        \t\t\t\t\$(\"#inpClienteNombre\").val(respons.data.cliente.nombre);
        \t\t\t}
                });
        \t}
        });
\t</script>
";
    }

    public function getTemplateName()
    {
        return "/administration/predio/predioNew.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  499 => 268,  466 => 238,  463 => 237,  459 => 236,  452 => 231,  444 => 225,  427 => 210,  421 => 209,  419 => 208,  418 => 207,  412 => 203,  406 => 202,  404 => 201,  403 => 200,  389 => 188,  383 => 187,  381 => 186,  380 => 185,  374 => 181,  368 => 180,  366 => 179,  365 => 178,  351 => 169,  339 => 162,  327 => 155,  315 => 145,  309 => 144,  307 => 143,  306 => 142,  300 => 138,  294 => 137,  292 => 136,  291 => 135,  285 => 131,  279 => 130,  277 => 129,  276 => 128,  270 => 124,  264 => 123,  262 => 122,  261 => 121,  247 => 109,  241 => 108,  239 => 107,  238 => 106,  232 => 102,  226 => 101,  224 => 100,  223 => 99,  209 => 90,  200 => 83,  191 => 80,  186 => 79,  182 => 78,  180 => 77,  179 => 76,  174 => 75,  169 => 74,  167 => 73,  145 => 56,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/predio/predioNew.twig", "/home/franco/proyectos/php/jass/resources/views/administration/predio/predioNew.twig");
    }
}
