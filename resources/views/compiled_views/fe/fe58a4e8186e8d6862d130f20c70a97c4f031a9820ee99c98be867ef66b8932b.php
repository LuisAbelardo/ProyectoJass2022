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

/* /administration/recibo/reciboImpresionMasiva.twig */
class __TwigTemplate_1e896a7855a34f3c9f13a64c1263a8ac1f404dee5bd2e4d619017a22ac3bacfa extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["recibo", "verporperiodo"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/recibo/reciboImpresionMasiva.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formVerRecibosPorperiodo\" method=\"post\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-file-invoice mr-3\"></i>Recibos para impresión masiva
                \t</div>
      \t\t\t</div>
      \t\t</div>
      \t\t<div class=\"col-12 pb-2 text-secondary\">
      \t\t\t<span>Generar los recibos de forma masiva es un proceso que puede tomar varios minutos.
      \t\t\t\t\tUtilize esta función sólo cuando sea necesario.</span>
      \t\t</div>
      \t</div><!-- /.card-header -->
      \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t";
        // line 28
        echo "                ";
        $context["classAlert"] = "";
        // line 29
        echo "                ";
        if (twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 29, $this->source); })()))) {
            // line 30
            echo "                \t";
            $context["classAlert"] = "d-none";
            // line 31
            echo "                ";
        } elseif ((((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 31, $this->source); })()) >= 200) && ((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 31, $this->source); })()) < 300))) {
            // line 32
            echo "                    ";
            $context["classAlert"] = "alert-success";
            // line 33
            echo "                ";
        } elseif (((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 33, $this->source); })()) >= 400)) {
            // line 34
            echo "                    ";
            $context["classAlert"] = "alert-danger";
            // line 35
            echo "                ";
        }
        // line 36
        echo "      \t\t\t<div class=\"alert ";
        echo twig_escape_filter($this->env, (isset($context["classAlert"]) || array_key_exists("classAlert", $context) ? $context["classAlert"] : (function () { throw new RuntimeError('Variable "classAlert" does not exist.', 36, $this->source); })()), "html", null, true);
        echo " alert-dismissible fade show\" id=\"f_alertsContainer\" role=\"alert\">
                 \t<ul class=\"mb-0\" id=\"f_alertsUl\">
                 \t\t";
        // line 38
        if ( !twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 38, $this->source); })()))) {
            // line 39
            echo "                          ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 39, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["msj"]) {
                // line 40
                echo "                            <li>";
                echo twig_escape_filter($this->env, $context["msj"], "html", null, true);
                echo "</li>
                          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['msj'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 42
            echo "                        ";
        }
        // line 43
        echo "                 \t</ul>
                 \t<button type=\"button\" class=\"close\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\" id=\"f_alertsDismiss\">
                 \t\t<span aria-hidden=\"true\">&times;</span>
                 \t</button>
                </div>";
        // line 48
        echo "      \t\t</div>
      \t</div>
      \t
  
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_divwithbartop f_divwithbarbottom\">
      \t\t\t\t
      \t\t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbSector\">Sector</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"sector\" class=\"f_minwidth200\" id=\"cmbSector\" required>
                        \t\t<option value=\"-1\" class=\"f_opacitymedium\"></option>
                            \t";
        // line 61
        $context["selectedSector"] = false;
        // line 62
        echo "                                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 62, $this->source); })()), "sectores", [], "any", false, false, false, 62));
        foreach ($context['_seq'] as $context["_key"] => $context["sector"]) {
            // line 63
            echo "                                \t<option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["sector"], "STR_CODIGO", [], "any", false, false, false, 63), "html", null, true);
            echo "\"
                            \t\t\t\t";
            // line 64
            if ((( !(isset($context["selectedSector"]) || array_key_exists("selectedSector", $context) ? $context["selectedSector"] : (function () { throw new RuntimeError('Variable "selectedSector" does not exist.', 64, $this->source); })()) && twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", true, true, false, 64)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 65
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 65, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 65), "sector", [], "any", false, false, false, 65) == twig_get_attribute($this->env, $this->source, $context["sector"], "STR_CODIGO", [], "any", false, false, false, 65)))) {
                // line 66
                echo "                            \t\t\t\t\t";
                echo "selected";
                $context["selectedSector"] = true;
                // line 67
                echo "                                            ";
            }
            echo ">
                        \t\t\t\t";
            // line 68
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["sector"], "STR_NOMBRE", [], "any", false, false, false, 68), "html", null, true);
            echo "
                    \t\t\t\t</option>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['sector'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 71
        echo "                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbMonth\">Mes</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 77
        $context["mes"] = "";
        // line 78
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "mes", [], "any", true, true, false, 78)) {
            $context["mes"] = twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 78, $this->source); })()), "mes", [], "any", false, false, false, 78);
            // line 79
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevosRecibos", [], "any", true, true, false, 79)) {
            // line 80
            echo "                        \t    ";
            $context["mes"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 80, $this->source); })()), "formNuevosRecibos", [], "any", false, false, false, 80), "mes", [], "any", false, false, false, 80);
        }
        // line 81
        echo "                        \t<select class=\"f_inputflat f_minwidth200\" name=\"month\" id=\"cmbMonth\">
                        \t\t<option value=\"-1\" class=\"f_opacitymedium\"></option>
                                <option value=\"ENERO\" 
                                    ";
        // line 84
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 84), "month", [], "any", true, true, false, 84) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 84, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 84), "month", [], "any", false, false, false, 84) == "ENERO"))) {
            // line 85
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 86
        echo ">
                \t\t\t\t\tENERO
            \t\t\t\t\t</option>
                                <option value=\"FEBRERO\"
                                    ";
        // line 90
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 90), "month", [], "any", true, true, false, 90) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 90, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 90), "month", [], "any", false, false, false, 90) == "FEBRERO"))) {
            // line 91
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 92
        echo ">
                \t\t\t\t\tFEBRERO
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"MARZO\"
                                    ";
        // line 96
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 96), "month", [], "any", true, true, false, 96) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 96, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 96), "month", [], "any", false, false, false, 96) == "MARZO"))) {
            // line 97
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 98
        echo ">
                \t\t\t\t\tMARZO
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"ABRIL\" 
                                    ";
        // line 102
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 102), "month", [], "any", true, true, false, 102) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 102, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 102), "month", [], "any", false, false, false, 102) == "ABRIL"))) {
            // line 103
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 104
        echo ">
                \t\t\t\t\tABRIL
            \t\t\t\t\t</option>
                                <option value=\"MAYO\"
                                    ";
        // line 108
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 108), "month", [], "any", true, true, false, 108) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 108, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 108), "month", [], "any", false, false, false, 108) == "MAYO"))) {
            // line 109
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 110
        echo ">
                \t\t\t\t\tMAYO
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"JUNIO\"
                                    ";
        // line 114
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 114), "month", [], "any", true, true, false, 114) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 114, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 114), "month", [], "any", false, false, false, 114) == "JUNIO"))) {
            // line 115
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 116
        echo ">
                \t\t\t\t\tJUNIO
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"JULIO\" 
                                    ";
        // line 120
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 120), "month", [], "any", true, true, false, 120) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 120, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 120), "month", [], "any", false, false, false, 120) == "JULIO"))) {
            // line 121
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 122
        echo ">
                \t\t\t\t\tJULIO
            \t\t\t\t\t</option>
                                <option value=\"AGOSTO\"
                                    ";
        // line 126
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 126), "month", [], "any", true, true, false, 126) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 126, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 126), "month", [], "any", false, false, false, 126) == "AGOSTO"))) {
            // line 127
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 128
        echo ">
                \t\t\t\t\tAGOSTO
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"SEPTIEMBRE\"
                                    ";
        // line 132
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 132), "month", [], "any", true, true, false, 132) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 132, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 132), "month", [], "any", false, false, false, 132) == "SEPTIEMBRE"))) {
            // line 133
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 134
        echo ">
                \t\t\t\t\tSEPTIEMBRE
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"OCTUBRE\" 
                                    ";
        // line 138
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 138), "month", [], "any", true, true, false, 138) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 138, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 138), "month", [], "any", false, false, false, 138) == "OCTUBRE"))) {
            // line 139
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 140
        echo ">
                \t\t\t\t\tOCTUBRE
            \t\t\t\t\t</option>
                                <option value=\"NOVIEMBRE\"
                                    ";
        // line 144
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 144), "month", [], "any", true, true, false, 144) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 144, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 144), "month", [], "any", false, false, false, 144) == "NOVIEMBRE"))) {
            // line 145
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 146
        echo ">
                \t\t\t\t\tNOVIEMBRE
            \t\t\t\t\t</option>
            \t\t\t\t\t<option value=\"DICIEMBRE\"
                                    ";
        // line 150
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", false, true, false, 150), "month", [], "any", true, true, false, 150) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 150, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 150), "month", [], "any", false, false, false, 150) == "DICIEMBRE"))) {
            // line 151
            echo "                        \t\t\t\t\t";
            echo "selected";
            echo "
                \t\t\t\t\t";
        }
        // line 152
        echo ">
                \t\t\t\t\tDICIEMBRE
            \t\t\t\t\t</option>
                              </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbYear\">Año</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 161
        $context["year"] = "";
        // line 162
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "year", [], "any", true, true, false, 162)) {
            $context["year"] = twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 162, $this->source); })()), "year", [], "any", false, false, false, 162);
            // line 163
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formVerRecibosPorperiodo", [], "any", true, true, false, 163)) {
            // line 164
            echo "                        \t    ";
            $context["year"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 164, $this->source); })()), "formVerRecibosPorperiodo", [], "any", false, false, false, 164), "year", [], "any", false, false, false, 164);
        }
        // line 165
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpYear\" name=\"year\" maxlength=\"4\" required value='";
        echo twig_escape_filter($this->env, (isset($context["year"]) || array_key_exists("year", $context) ? $context["year"] : (function () { throw new RuntimeError('Variable "year" does not exist.', 165, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
      \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-body -->
  \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardfooter f_cardfooteractions text-center\">
      \t\t\t\t<button type=\"button\" class=\"f_button f_buttonaction\" id=\"btnVerRecibos\">Ver recibos</button>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 181
        echo "  
</div><!-- /.card -->


";
    }

    // line 187
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 188
        echo "
    ";
        // line 189
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formVerRecibosPorperiodo').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        f_select2(\"#cmbSector\");
        f_select2(\"#cmbMonth\");
        
        \$('#btnVerRecibos').click(function(){
        \t\$(\"#inpYear\").val(\$(\"#inpYear\").val().trim());
        \tvar sector = \$('#cmbSector').val();
        \tvar month = \$('#cmbMonth').val();
        \tvar year = \$('#inpYear').val();
        \t
        \tif(sector != \"\" && month != \"\" && year != \"\" && sector != -1 && month != -1 ){
        \t\tif(year.length == 4){
        \t\t\tvar href = \"";
        // line 209
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 209, $this->source); })()), "html", null, true);
        echo "/reporte/recibos\"+\"/\"+sector+\"/\"+month+\"/\"+year;
                \twindow.location.href = href;
        \t\t}else{
        \t\t\t\$(document).Toasts('create', {
                    \tclass: 'bg-danger',
                        title: 'Error de validación',
                        position: 'topRight',
                        autohide: true,
    \t\t\t\t\tdelay: 4000,
                        body: \"El año debe estar en formato de 4 digitos\"
                    });
        \t\t}
        \t}else{
            \t\$(document).Toasts('create', {
                \tclass: 'bg-danger',
                    title: 'Error de validación',
                    position: 'topRight',
                    autohide: true,
\t\t\t\t\tdelay: 4000,
                    body: \"Debe completar todos los campos\"
                });
        \t}
        \treturn false;
        });
\t</script>
";
    }

    public function getTemplateName()
    {
        return "/administration/recibo/reciboImpresionMasiva.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  450 => 209,  427 => 189,  424 => 188,  420 => 187,  412 => 181,  393 => 165,  389 => 164,  386 => 163,  382 => 162,  380 => 161,  369 => 152,  363 => 151,  361 => 150,  355 => 146,  349 => 145,  347 => 144,  341 => 140,  335 => 139,  333 => 138,  327 => 134,  321 => 133,  319 => 132,  313 => 128,  307 => 127,  305 => 126,  299 => 122,  293 => 121,  291 => 120,  285 => 116,  279 => 115,  277 => 114,  271 => 110,  265 => 109,  263 => 108,  257 => 104,  251 => 103,  249 => 102,  243 => 98,  237 => 97,  235 => 96,  229 => 92,  223 => 91,  221 => 90,  215 => 86,  209 => 85,  207 => 84,  202 => 81,  198 => 80,  195 => 79,  191 => 78,  189 => 77,  181 => 71,  172 => 68,  167 => 67,  163 => 66,  161 => 65,  160 => 64,  155 => 63,  150 => 62,  148 => 61,  133 => 48,  127 => 43,  124 => 42,  115 => 40,  110 => 39,  108 => 38,  102 => 36,  99 => 35,  96 => 34,  93 => 33,  90 => 32,  87 => 31,  84 => 30,  81 => 29,  78 => 28,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/recibo/reciboImpresionMasiva.twig", "/home/franco/proyectos/php/jass/resources/views/administration/recibo/reciboImpresionMasiva.twig");
    }
}
