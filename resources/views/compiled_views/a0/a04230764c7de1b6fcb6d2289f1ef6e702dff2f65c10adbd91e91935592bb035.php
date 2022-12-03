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

/* /administration/predio/predioEdit.twig */
class __TwigTemplate_ce0234118ce21e2b0049658a69ea72e9abaf58c7cf5bfb08662ebb665e034211 extends \Twig\Template
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
        $context["menuLItem"] = "predio";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/predio/predioEdit.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formEditarPredio\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/predio/update\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-house-user mr-3\"></i>Editar predio
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
      \t\t
      \t\t\t<div class=\"f_divwithbartop f_divwithbarbottom\">
      \t\t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\">Ref.</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 55
        $context["codigoPredio"] = "";
        // line 56
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 56)) {
            $context["codigoPredio"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 56, $this->source); })()), "predio", [], "any", false, false, false, 56), "PRE_CODIGO", [], "any", false, false, false, 56);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 56, $this->source); })()), "predio", [], "any", false, false, false, 56), "PRE_CODIGO", [], "any", false, false, false, 56), "html", null, true);
            echo "
                        \t";
        } elseif (twig_get_attribute($this->env, $this->source,         // line 57
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 57)) {
            // line 58
            echo "                        \t    ";
            $context["codigoPredio"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 58, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 58), "codigo", [], "any", false, false, false, 58);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 58, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 58), "codigo", [], "any", false, false, false, 58), "html", null, true);
        }
        // line 59
        echo "                        \t<input type=\"hidden\" class=\"f_minwidth300\" id=\"inpCodigo\" name=\"codigo\" value='";
        echo twig_escape_filter($this->env, (isset($context["codigoPredio"]) || array_key_exists("codigoPredio", $context) ? $context["codigoPredio"] : (function () { throw new RuntimeError('Variable "codigoPredio" does not exist.', 59, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpCliente\">Cliente (DNI / RUC)</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                            ";
        // line 65
        $context["clienteDocumento"] = "";
        // line 66
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 66)) {
            $context["clienteDocumento"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 66, $this->source); })()), "cliente", [], "any", false, false, false, 66), "CLI_DOCUMENTO", [], "any", false, false, false, 66);
            // line 67
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 67)) {
            $context["clienteDocumento"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 67, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 67), "cliente", [], "any", false, false, false, 67);
        }
        // line 68
        echo "                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpCliente\" name=\"cliente\" required maxlength=\"11\" 
                        \t\t\tvalue='";
        // line 69
        echo twig_escape_filter($this->env, (isset($context["clienteDocumento"]) || array_key_exists("clienteDocumento", $context) ? $context["clienteDocumento"] : (function () { throw new RuntimeError('Variable "clienteDocumento" does not exist.', 69, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbCalle\">Calle</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"calle\" class=\"f_minwidth300\" id=\"cmbCalle\" required> 
                            \t";
        // line 76
        $context["selectedCalle"] = false;
        // line 77
        echo "                                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 77, $this->source); })()), "calles", [], "any", false, false, false, 77));
        foreach ($context['_seq'] as $context["_key"] => $context["calle"]) {
            // line 78
            echo "                                \t<option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["calle"], "CAL_CODIGO", [], "any", false, false, false, 78), "html", null, true);
            echo "\"
                                \t\t";
            // line 79
            if ( !(isset($context["selectedCalle"]) || array_key_exists("selectedCalle", $context) ? $context["selectedCalle"] : (function () { throw new RuntimeError('Variable "selectedCalle" does not exist.', 79, $this->source); })())) {
                // line 80
                echo "                                \t\t    ";
                if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "calle", [], "any", true, true, false, 80) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 80, $this->source); })()), "calle", [], "any", false, false, false, 80), "CAL_CODIGO", [], "any", false, false, false, 80) == twig_get_attribute($this->env, $this->source, $context["calle"], "CAL_CODIGO", [], "any", false, false, false, 80)))) {
                    // line 81
                    echo "                            \t\t\t\t\t";
                    echo "selected";
                    $context["selectedCalle"] = true;
                    // line 82
                    echo "                            \t\t\t\t";
                } elseif ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 82) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 82, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 82), "calle", [], "any", false, false, false, 82) == twig_get_attribute($this->env, $this->source, $context["calle"], "CAL_CODIGO", [], "any", false, false, false, 82)))) {
                    // line 83
                    echo "                            \t\t\t\t\t";
                    echo "selected";
                    $context["selectedCalle"] = true;
                }
                // line 84
                echo "                        \t\t\t\t";
            }
            echo ">
                        \t\t\t\t";
            // line 85
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["calle"], "CAL_NOMBRE", [], "any", false, false, false, 85), "html", null, true);
            echo "
                    \t\t\t\t</option>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['calle'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 88
        echo "                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpDireccion\">Dirección</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 94
        $context["direccion"] = "";
        // line 95
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 95)) {
            $context["direccion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 95, $this->source); })()), "predio", [], "any", false, false, false, 95), "PRE_DIRECCION", [], "any", false, false, false, 95);
            // line 96
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 96)) {
            $context["direccion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 96, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 96), "direccion", [], "any", false, false, false, 96);
        }
        // line 97
        echo "                        \t<input type=\"text\" class=\"f_minwidth250\" id=\"inpDireccion\" name=\"direccion\" required value='";
        echo twig_escape_filter($this->env, (isset($context["direccion"]) || array_key_exists("direccion", $context) ? $context["direccion"] : (function () { throw new RuntimeError('Variable "direccion" does not exist.', 97, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"cmbHabitada\">Habitada</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"habitada\" class=\"f_minwidth300\" id=\"cmbHabitada\"> 
                        \t\t<option value=\"-1\"></option>
                            \t<option value=\"si\"
                        \t\t\t";
        // line 106
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 106) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 106, $this->source); })()), "predio", [], "any", false, false, false, 106), "PRE_HABITADA", [], "any", false, false, false, 106) == "si"))) {
            // line 107
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 108
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 108) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 108, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 108), "habitada", [], "any", false, false, false, 108) == "si"))) {
            // line 109
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t";
        }
        // line 110
        echo ">
                                    Si
                \t\t\t\t</option>
                \t\t\t\t<option value=\"no\"
                    \t\t\t\t";
        // line 114
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 114) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 114, $this->source); })()), "predio", [], "any", false, false, false, 114), "PRE_HABITADA", [], "any", false, false, false, 114) == "no"))) {
            // line 115
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 116
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 116) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 116, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 116), "habitada", [], "any", false, false, false, 116) == "no"))) {
            // line 117
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t";
        }
        // line 118
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
                            \t\t";
        // line 130
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 130) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 130, $this->source); })()), "predio", [], "any", false, false, false, 130), "PRE_MAT_CONSTRUCCION", [], "any", false, false, false, 130) == "noble"))) {
            // line 131
            echo "                        \t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 132
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 132) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 132, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 132), "materialConst", [], "any", false, false, false, 132) == "noble"))) {
            // line 133
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 134
        echo ">
                                    Noble
                \t\t\t\t</option>
                \t\t\t\t<option value=\"adobe\"
                \t\t\t\t\t";
        // line 138
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 138) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 138, $this->source); })()), "predio", [], "any", false, false, false, 138), "PRE_MAT_CONSTRUCCION", [], "any", false, false, false, 138) == "adobe"))) {
            // line 139
            echo "                        \t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 140
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 140) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 140, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 140), "materialConst", [], "any", false, false, false, 140) == "adobe"))) {
            // line 141
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 142
        echo ">
                                    Adobe
                \t\t\t\t</option>
                \t\t\t\t<option value=\"madera\"
                \t\t\t\t\t";
        // line 146
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 146) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 146, $this->source); })()), "predio", [], "any", false, false, false, 146), "PRE_MAT_CONSTRUCCION", [], "any", false, false, false, 146) == "madera"))) {
            // line 147
            echo "                        \t\t\t    ";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 148
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 148) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 148, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 148), "materialConst", [], "any", false, false, false, 148) == "madera"))) {
            // line 149
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 150
        echo ">
                                    Madera
                \t\t\t\t</option>
                \t\t\t\t<option value=\"no-aplicable\"
                \t\t\t\t\t";
        // line 154
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 154) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 154, $this->source); })()), "predio", [], "any", false, false, false, 154), "PRE_MAT_CONSTRUCCION", [], "any", false, false, false, 154) == "no-aplicable"))) {
            // line 155
            echo "                        \t\t\t\t";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 156
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 156) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 156, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 156), "materialConst", [], "any", false, false, false, 156) == "no-aplicable"))) {
            // line 157
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 158
        echo ">
                                    No aplicable
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpPisos\">Pisos</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 167
        $context["pisos"] = "";
        // line 168
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 168)) {
            $context["pisos"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 168, $this->source); })()), "predio", [], "any", false, false, false, 168), "PRE_PISOS", [], "any", false, false, false, 168);
            // line 169
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 169)) {
            $context["pisos"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 169, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 169), "pisos", [], "any", false, false, false, 169);
        }
        // line 170
        echo "                        \t<input type=\"text\" class=\"f_minwidth250\" id=\"inpPisos\" name=\"pisos\" value='";
        echo twig_escape_filter($this->env, (isset($context["pisos"]) || array_key_exists("pisos", $context) ? $context["pisos"] : (function () { throw new RuntimeError('Variable "pisos" does not exist.', 170, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpFamilias\">Familias</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 176
        $context["familias"] = "";
        // line 177
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 177)) {
            $context["familias"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 177, $this->source); })()), "predio", [], "any", false, false, false, 177), "PRE_FAMILIAS", [], "any", false, false, false, 177);
            // line 178
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 178)) {
            $context["familias"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 178, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 178), "familias", [], "any", false, false, false, 178);
        }
        // line 179
        echo "                        \t<input type=\"text\" class=\"f_minwidth250\" id=\"inpFamilias\" name=\"familias\"value='";
        echo twig_escape_filter($this->env, (isset($context["familias"]) || array_key_exists("familias", $context) ? $context["familias"] : (function () { throw new RuntimeError('Variable "familias" does not exist.', 179, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpHabitantes\">Habitantes</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 185
        $context["habitantes"] = "";
        // line 186
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 186)) {
            $context["habitantes"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 186, $this->source); })()), "predio", [], "any", false, false, false, 186), "PRE_HABITANTES", [], "any", false, false, false, 186);
            // line 187
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 187)) {
            $context["habitantes"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 187, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 187), "habitantes", [], "any", false, false, false, 187);
        }
        // line 188
        echo "                        \t<input type=\"text\" class=\"f_minwidth250\" id=\"inpHabitantes\" name=\"habitantes\" value='";
        echo twig_escape_filter($this->env, (isset($context["habitantes"]) || array_key_exists("habitantes", $context) ? $context["habitantes"] : (function () { throw new RuntimeError('Variable "habitantes" does not exist.', 188, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"cmbPozoTabular\">Pozo Tabular</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"pozoTabular\" class=\"f_minwidth300\" id=\"cmbPozoTabular\"> 
                        \t\t<option value=\"-1\"></option>
                            \t<option value=\"si\"
                            \t\t";
        // line 197
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 197) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 197, $this->source); })()), "predio", [], "any", false, false, false, 197), "PRE_POZO_TABULAR", [], "any", false, false, false, 197) == "si"))) {
            // line 198
            echo "                        \t\t\t    ";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 199
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 199) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 199, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 199), "pozoTabular", [], "any", false, false, false, 199) == "si"))) {
            // line 200
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 201
        echo ">
                                    Si
                \t\t\t\t</option>
                \t\t\t\t<option value=\"no\"
                \t\t\t\t\t";
        // line 205
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 205) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 205, $this->source); })()), "predio", [], "any", false, false, false, 205), "PRE_POZO_TABULAR", [], "any", false, false, false, 205) == "no"))) {
            // line 206
            echo "                        \t\t\t    ";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 207
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 207) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 207, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 207), "pozoTabular", [], "any", false, false, false, 207) == "no"))) {
            // line 208
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 209
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
                            \t\t";
        // line 221
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 221) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 221, $this->source); })()), "predio", [], "any", false, false, false, 221), "PRE_PISCINA", [], "any", false, false, false, 221) == "si"))) {
            // line 222
            echo "                        \t\t\t    ";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 223
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 223) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 223, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 223), "piscina", [], "any", false, false, false, 223) == "si"))) {
            // line 224
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 225
        echo ">
                                    Si
                \t\t\t\t</option>
                \t\t\t\t<option value=\"no\"
                \t\t\t\t\t";
        // line 229
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 229) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 229, $this->source); })()), "predio", [], "any", false, false, false, 229), "PRE_PISCINA", [], "any", false, false, false, 229) == "no"))) {
            // line 230
            echo "                        \t\t\t    ";
            echo "selected";
            echo "
                    \t\t\t\t";
        } elseif ((twig_get_attribute($this->env, $this->source,         // line 231
($context["data"] ?? null), "formEditarPredio", [], "any", true, true, false, 231) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 231, $this->source); })()), "formEditarPredio", [], "any", false, false, false, 231), "piscina", [], "any", false, false, false, 231) == "no"))) {
            // line 232
            echo "                    \t\t\t\t\t";
            echo "selected";
            echo "
                                    ";
        }
        // line 233
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
          \t\t<div class=\"f_cardfooter f_cardfooteractions text-center\">
        \t\t\t<button type=\"submit\" class=\"f_button f_buttonaction\">Guardar cambios</button>
        \t\t\t<a href=\"";
        // line 248
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 248, $this->source); })()), "html", null, true);
        echo "/predio/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 254
        echo "  
</div><!-- /.card -->
    
";
    }

    // line 259
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 260
        echo "
    ";
        // line 261
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formEditarPredio').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        f_select2(\"#cmbCalle\");
        f_select2(\"#cmbHabitada\");
        f_select2(\"#cmbMaterialConst\");
        f_select2(\"#cmbPozoTabular\");
        f_select2(\"#cmbPiscina\");
\t</script>
";
    }

    public function getTemplateName()
    {
        return "/administration/predio/predioEdit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  599 => 261,  596 => 260,  592 => 259,  585 => 254,  577 => 248,  560 => 233,  554 => 232,  552 => 231,  547 => 230,  545 => 229,  539 => 225,  533 => 224,  531 => 223,  526 => 222,  524 => 221,  510 => 209,  504 => 208,  502 => 207,  497 => 206,  495 => 205,  489 => 201,  483 => 200,  481 => 199,  476 => 198,  474 => 197,  461 => 188,  456 => 187,  452 => 186,  450 => 185,  440 => 179,  435 => 178,  431 => 177,  429 => 176,  419 => 170,  414 => 169,  410 => 168,  408 => 167,  397 => 158,  391 => 157,  389 => 156,  384 => 155,  382 => 154,  376 => 150,  370 => 149,  368 => 148,  363 => 147,  361 => 146,  355 => 142,  349 => 141,  347 => 140,  342 => 139,  340 => 138,  334 => 134,  328 => 133,  326 => 132,  321 => 131,  319 => 130,  305 => 118,  299 => 117,  297 => 116,  292 => 115,  290 => 114,  284 => 110,  278 => 109,  276 => 108,  271 => 107,  269 => 106,  256 => 97,  251 => 96,  247 => 95,  245 => 94,  237 => 88,  228 => 85,  223 => 84,  218 => 83,  215 => 82,  211 => 81,  208 => 80,  206 => 79,  201 => 78,  196 => 77,  194 => 76,  184 => 69,  181 => 68,  176 => 67,  172 => 66,  170 => 65,  160 => 59,  155 => 58,  153 => 57,  146 => 56,  144 => 55,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/predio/predioEdit.twig", "/home/franco/proyectos/php/jass/resources/views/administration/predio/predioEdit.twig");
    }
}
