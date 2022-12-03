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

/* /administration/contrato/contratoEdit.twig */
class __TwigTemplate_192d6c515e5fc62607dc4b258bcfeba5bdbeb62e4858a528b0b0b9b55d7a3bbf extends \Twig\Template
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
        $context["menuLItem"] = "contrato";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/contrato/contratoEdit.twig", 3);
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
        echo "/contrato/update\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-file-contract mr-3\"></i>Editar contrato
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
  \t\t<div class=\"f_divwithbartop f_divwithbarbottom\">
          \t<div class=\"row\">
          \t\t<div class=\"col-6\">
      \t\t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 f_field\">Ref.</label>
                        <div class=\"col-12 col-md-9\">
                        \t";
        // line 53
        $context["codigoContrato"] = "";
        // line 54
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 54)) {
            $context["codigoContrato"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 54, $this->source); })()), "contrato", [], "any", false, false, false, 54), "CTO_CODIGO", [], "any", false, false, false, 54);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 54, $this->source); })()), "contrato", [], "any", false, false, false, 54), "CTO_CODIGO", [], "any", false, false, false, 54), "html", null, true);
            echo "
                        \t";
        } elseif (twig_get_attribute($this->env, $this->source,         // line 55
($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 55)) {
            // line 56
            echo "                        \t    ";
            $context["codigoContrato"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 56, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 56), "codigo", [], "any", false, false, false, 56);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 56, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 56), "codigo", [], "any", false, false, false, 56), "html", null, true);
        }
        // line 57
        echo "                        \t<input type=\"hidden\" class=\"f_minwidth300\" id=\"inpCodigo\" name=\"codigo\" value='";
        echo twig_escape_filter($this->env, (isset($context["codigoContrato"]) || array_key_exists("codigoContrato", $context) ? $context["codigoContrato"] : (function () { throw new RuntimeError('Variable "codigoContrato" does not exist.', 57, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 f_field\" for=\"inpPredio\">Código de predio</label>
                        <div class=\"col-12 col-md-9\">
                        \t";
        // line 63
        $context["predioCodigo"] = "";
        // line 64
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 64)) {
            $context["predioCodigo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 64, $this->source); })()), "predio", [], "any", false, false, false, 64), "PRE_CODIGO", [], "any", false, false, false, 64);
            // line 65
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 65)) {
            $context["predioCodigo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 65, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 65), "predioCodigo", [], "any", false, false, false, 65);
        }
        // line 66
        echo "                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpPredio\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["predioCodigo"]) || array_key_exists("predioCodigo", $context) ? $context["predioCodigo"] : (function () { throw new RuntimeError('Variable "predioCodigo" does not exist.', 66, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"predioCodigo\" value='";
        // line 67
        echo twig_escape_filter($this->env, (isset($context["predioCodigo"]) || array_key_exists("predioCodigo", $context) ? $context["predioCodigo"] : (function () { throw new RuntimeError('Variable "predioCodigo" does not exist.', 67, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 f_field\" for=\"inpPredioCalle\">Predio calle</label>
                        <div class=\"col-12 col-md-9\">
                        \t";
        // line 73
        $context["predioCalle"] = "";
        // line 74
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "calle", [], "any", true, true, false, 74)) {
            $context["predioCalle"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 74, $this->source); })()), "calle", [], "any", false, false, false, 74), "CAL_NOMBRE", [], "any", false, false, false, 74);
            // line 75
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 75)) {
            $context["predioCalle"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 75, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 75), "predioCalle", [], "any", false, false, false, 75);
        }
        // line 76
        echo "                        \t<input type=\"text\" class=\"f_minwidth400\" id=\"inpPredioCalle\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["predioCalle"]) || array_key_exists("predioCalle", $context) ? $context["predioCalle"] : (function () { throw new RuntimeError('Variable "predioCalle" does not exist.', 76, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"predioCalle\" value='";
        // line 77
        echo twig_escape_filter($this->env, (isset($context["predioCalle"]) || array_key_exists("predioCalle", $context) ? $context["predioCalle"] : (function () { throw new RuntimeError('Variable "predioCalle" does not exist.', 77, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 f_field\" for=\"inpPredioDireccion\">Predio dirección</label>
                        <div class=\"col-12 col-md-9\">
                        \t";
        // line 83
        $context["predioDireccion"] = "";
        // line 84
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 84)) {
            $context["predioDireccion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 84, $this->source); })()), "predio", [], "any", false, false, false, 84), "PRE_DIRECCION", [], "any", false, false, false, 84);
            // line 85
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 85)) {
            // line 86
            echo "                        \t    ";
            $context["predioDireccion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 86, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 86), "predioDireccion", [], "any", false, false, false, 86);
        }
        // line 87
        echo "                        \t<input type=\"text\" class=\"f_minwidth400\" id=\"inpPredioDireccion\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["predioDireccion"]) || array_key_exists("predioDireccion", $context) ? $context["predioDireccion"] : (function () { throw new RuntimeError('Variable "predioDireccion" does not exist.', 87, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"predioDireccion\" value='";
        // line 88
        echo twig_escape_filter($this->env, (isset($context["predioDireccion"]) || array_key_exists("predioDireccion", $context) ? $context["predioDireccion"] : (function () { throw new RuntimeError('Variable "predioDireccion" does not exist.', 88, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 f_field\" for=\"inpClienteDocumento\">Cliente DNI / RUC</label>
                        <div class=\"col-12 col-md-9\">
                        \t";
        // line 94
        $context["clienteDocumento"] = "";
        // line 95
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 95)) {
            $context["clienteDocumento"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 95, $this->source); })()), "cliente", [], "any", false, false, false, 95), "CLI_DOCUMENTO", [], "any", false, false, false, 95);
            // line 96
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 96)) {
            // line 97
            echo "                        \t    ";
            $context["clienteDocumento"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 97, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 97), "clienteDocumento", [], "any", false, false, false, 97);
        }
        // line 98
        echo "                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpClienteDocumento\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["clienteDocumento"]) || array_key_exists("clienteDocumento", $context) ? $context["clienteDocumento"] : (function () { throw new RuntimeError('Variable "clienteDocumento" does not exist.', 98, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"clienteDocumento\" value='";
        // line 99
        echo twig_escape_filter($this->env, (isset($context["clienteDocumento"]) || array_key_exists("clienteDocumento", $context) ? $context["clienteDocumento"] : (function () { throw new RuntimeError('Variable "clienteDocumento" does not exist.', 99, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 f_field\" for=\"inpClienteNombre\">Cliente nombre</label>
                        <div class=\"col-12 col-md-9\">
                        \t";
        // line 105
        $context["clienteNombre"] = "";
        // line 106
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 106)) {
            $context["clienteNombre"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 106, $this->source); })()), "cliente", [], "any", false, false, false, 106), "CLI_NOMBRES", [], "any", false, false, false, 106);
            // line 107
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 107)) {
            // line 108
            echo "                        \t    ";
            $context["clienteNombre"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 108, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 108), "clienteNombre", [], "any", false, false, false, 108);
        }
        // line 109
        echo "                        \t<input type=\"text\" class=\"f_minwidth400\" id=\"inpClienteNombre\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["clienteNombre"]) || array_key_exists("clienteNombre", $context) ? $context["clienteNombre"] : (function () { throw new RuntimeError('Variable "clienteNombre" does not exist.', 109, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"clienteNombre\" value='";
        // line 110
        echo twig_escape_filter($this->env, (isset($context["clienteNombre"]) || array_key_exists("clienteNombre", $context) ? $context["clienteNombre"] : (function () { throw new RuntimeError('Variable "clienteNombre" does not exist.', 110, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    ";
        // line 114
        echo "                    <div class=\"form-group row d-none\">
                    \t<label class=\"col-12 col-md-3 f_field\" for=\"inpEstado\">Old Estate</label>
                        <div class=\"col-12 col-md-9\">
                    \t\t";
        // line 117
        $context["estado"] = "";
        // line 118
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 118)) {
            $context["estado"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 118, $this->source); })()), "contrato", [], "any", false, false, false, 118), "CTO_ESTADO", [], "any", false, false, false, 118);
            // line 119
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 119)) {
            $context["estado"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 119, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 119), "estado", [], "any", false, false, false, 119);
        }
        // line 120
        echo "                        \t<input type=\"hidden\" class=\"f_minwidth300\" id=\"inpEstado\" name=\"estado\" value='";
        echo twig_escape_filter($this->env, (isset($context["estado"]) || array_key_exists("estado", $context) ? $context["estado"] : (function () { throw new RuntimeError('Variable "estado" does not exist.', 120, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>";
        // line 123
        echo "                    
                    ";
        // line 124
        if (((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 124) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 124, $this->source); })()), "contrato", [], "any", false, false, false, 124), "CTO_ESTADO", [], "any", false, false, false, 124) == 0)) || (twig_get_attribute($this->env, $this->source,         // line 125
($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 125) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 125, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 125), "estado", [], "any", false, false, false, 125) == 0)))) {
            // line 126
            echo "                    <div class=\"form-group row\">
                    \t<span class=\"col-12 col-md-3 f_field\" for=\"chkContratoInicio\">Estado</span>
                        <div class=\"col-12 col-md-9\">
                        \t";
            // line 129
            $context["chkEstado"] = "";
            // line 130
            echo "                        \t";
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", false, true, false, 130), "estadoNuevo", [], "any", true, true, false, 130) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 130, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 130), "estadoNuevo", [], "any", false, false, false, 130) == 1))) {
                // line 131
                echo "                        \t    ";
                $context["chkEstado"] = "checked";
            }
            // line 132
            echo "                        \t<input type=\"checkbox\" id=\"chkContratoInicio\" name=\"estadoNuevo\" ";
            echo twig_escape_filter($this->env, (isset($context["chkEstado"]) || array_key_exists("chkEstado", $context) ? $context["chkEstado"] : (function () { throw new RuntimeError('Variable "chkEstado" does not exist.', 132, $this->source); })()), "html", null, true);
            echo " value=\"1\">
                        \t<label class=\"f_field\" for=\"chkContratoInicio\">Contrato activo</label>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 f_field\" for=\"inpFechaInicio\">Fecha Inicio</label>
                        <div class=\"col-12 col-md-9\">
                    \t\t";
            // line 139
            $context["fechaInicio"] = "";
            // line 140
            echo "                        \t";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 140)) {
                $context["fechaInicio"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 140, $this->source); })()), "contrato", [], "any", false, false, false, 140), "CTO_FECHA_INICIO", [], "any", false, false, false, 140);
                // line 141
                echo "                        \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", false, true, false, 141), "fechaInicio", [], "any", true, true, false, 141)) {
                // line 142
                echo "                        \t    ";
                $context["fechaInicio"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 142, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 142), "fechaInicio", [], "any", false, false, false, 142);
            }
            // line 143
            echo "                        \t<input type=\"date\" class=\"f_minwidth300\" id=\"inpFechaInicio\" name=\"fechaInicio\" value='";
            echo twig_escape_filter($this->env, (isset($context["fechaInicio"]) || array_key_exists("fechaInicio", $context) ? $context["fechaInicio"] : (function () { throw new RuntimeError('Variable "fechaInicio" does not exist.', 143, $this->source); })()), "html", null, true);
            echo "'>
                        </div>
                    </div>
                    ";
        }
        // line 147
        echo "                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 f_field\" for=\"txaObservacion\">Observación</label>
                        <div class=\"col-12 col-md-9\">
                        \t";
        // line 150
        $context["observacion"] = "";
        // line 151
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 151)) {
            $context["observacion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 151, $this->source); })()), "contrato", [], "any", false, false, false, 151), "CTO_OBSERVACION", [], "any", false, false, false, 151);
            // line 152
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarContrato", [], "any", true, true, false, 152)) {
            $context["observacion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 152, $this->source); })()), "formEditarContrato", [], "any", false, false, false, 152), "observacion", [], "any", false, false, false, 152);
        }
        // line 153
        echo "                        \t<textarea class=\"f_minwidth400\" id=\"txaObservacion\" rows=\"2\" maxlength=\"256\" 
                        \t\tname=\"observacion\">";
        // line 154
        echo twig_escape_filter($this->env, (isset($context["observacion"]) || array_key_exists("observacion", $context) ? $context["observacion"] : (function () { throw new RuntimeError('Variable "observacion" does not exist.', 154, $this->source); })()), "html", null, true);
        echo "</textarea>
                        </div>
                    </div>
          \t\t</div>
          \t\t
          \t\t
          \t\t
          \t\t
          \t\t";
        // line 162
        $context["tieneAgua"] = false;
        // line 163
        echo "              \t";
        $context["tieneDesague"] = false;
        // line 164
        echo "              \t
              \t";
        // line 165
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "servicios", [], "any", true, true, false, 165)) {
            // line 166
            echo "              \t\t";
            $context["cantServicios"] = 0;
            // line 167
            echo "              \t    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 167, $this->source); })()), "servicios", [], "any", false, false, false, 167));
            foreach ($context['_seq'] as $context["_key"] => $context["servicio"]) {
                // line 168
                echo "              \t    \t";
                if ((twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_CODIGO", [], "any", false, false, false, 168) == 1)) {
                    $context["tieneAgua"] = true;
                    // line 169
                    echo "              \t    \t";
                } elseif ((twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_CODIGO", [], "any", false, false, false, 169) == 2)) {
                    $context["tieneDesague"] = true;
                }
                // line 170
                echo "              \t    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['servicio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 171
            echo "              \t";
        }
        // line 172
        echo "          \t\t<div class=\"col-6\">
          \t\t\t";
        // line 174
        echo "                \t<div id=\"divNuevoContratoMasDetalles\">
                \t
                \t\t";
        // line 176
        if ((isset($context["tieneAgua"]) || array_key_exists("tieneAgua", $context) ? $context["tieneAgua"] : (function () { throw new RuntimeError('Variable "tieneAgua" does not exist.', 176, $this->source); })())) {
            // line 177
            echo "                        ";
            // line 178
            echo "                \t\t<div id=\"divNuevoContratoMasDetallesAgua\">
                    \t\t<h6 class=\"mb-4 text-bold\">Servicio de Agua</h6>
                    \t\t<div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"inpAguaFechaInstalacion\">Fecha de instalación</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 183
            $context["aguaFechaInstalacion"] = "";
            // line 184
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 184)) {
                $context["aguaFechaInstalacion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 184, $this->source); })()), "contrato", [], "any", false, false, false, 184), "CTO_AGU_FEC_INS", [], "any", false, false, false, 184);
                // line 185
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 185), "aguaFechaInstalacion", [], "any", true, true, false, 185)) {
                // line 186
                echo "                                \t    ";
                $context["aguaFechaInstalacion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 186, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 186), "aguaFechaInstalacion", [], "any", false, false, false, 186);
            }
            // line 187
            echo "                                \t<input type=\"date\" class=\"f_minwidth300\" id=\"inpAguaFechaInstalacion\" name=\"aguaFechaInstalacion\" 
                                \t\t\tvalue=\"";
            // line 188
            echo twig_escape_filter($this->env, (isset($context["aguaFechaInstalacion"]) || array_key_exists("aguaFechaInstalacion", $context) ? $context["aguaFechaInstalacion"] : (function () { throw new RuntimeError('Variable "aguaFechaInstalacion" does not exist.', 188, $this->source); })()), "html", null, true);
            echo "\">
                                </div>
                            </div>
                    \t\t<div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Característica de la conexion</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 194
            $context["aguaConexionCaracteristica"] = "";
            // line 195
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 195)) {
                $context["aguaConexionCaracteristica"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 195, $this->source); })()), "contrato", [], "any", false, false, false, 195), "CTO_AGU_CAR_CNX", [], "any", false, false, false, 195);
                // line 196
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 196), "aguaConexionCaracteristica", [], "any", true, true, false, 196)) {
                // line 197
                echo "                                \t    ";
                $context["aguaConexionCaracteristica"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 197, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 197), "aguaConexionCaracteristica", [], "any", false, false, false, 197);
            }
            // line 198
            echo "                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionCaracteristica\" 
                                        \t\tid=\"inpAguaConexionCaracteristicaSC\" value=\"sin caja\"
                                        \t\t";
            // line 201
            if (((isset($context["aguaConexionCaracteristica"]) || array_key_exists("aguaConexionCaracteristica", $context) ? $context["aguaConexionCaracteristica"] : (function () { throw new RuntimeError('Variable "aguaConexionCaracteristica" does not exist.', 201, $this->source); })()) == "sin caja")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaConexionCaracteristicaSC\">Sin caja</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionCaracteristica\" 
                                        \t\tid=\"inpAguaConexionCaracteristicaCC\" value=\"con caja\"
                                        \t\t";
            // line 207
            if (((isset($context["aguaConexionCaracteristica"]) || array_key_exists("aguaConexionCaracteristica", $context) ? $context["aguaConexionCaracteristica"] : (function () { throw new RuntimeError('Variable "aguaConexionCaracteristica" does not exist.', 207, $this->source); })()) == "con caja")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaConexionCaracteristicaCC\">Con caja</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Diametro de la conexion</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 215
            $context["aguaConexionDiametro"] = "";
            // line 216
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 216)) {
                $context["aguaConexionDiametro"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 216, $this->source); })()), "contrato", [], "any", false, false, false, 216), "CTO_AGU_DTO_CNX", [], "any", false, false, false, 216);
                // line 217
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 217), "aguaConexionDiametro", [], "any", true, true, false, 217)) {
                // line 218
                echo "                                \t    ";
                $context["aguaConexionDiametro"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 218, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 218), "aguaConexionDiametro", [], "any", false, false, false, 218);
            }
            // line 219
            echo "                                \t<div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionDiametro\" 
                                        \t\tid=\"inpAguaConexionDiametro1-2\" value=\"1/2\"
                                        \t\t";
            // line 222
            if (((isset($context["aguaConexionDiametro"]) || array_key_exists("aguaConexionDiametro", $context) ? $context["aguaConexionDiametro"] : (function () { throw new RuntimeError('Variable "aguaConexionDiametro" does not exist.', 222, $this->source); })()) == "1/2")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaConexionDiametro1-2\">1/2\"</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionDiametro\" 
                                        \t\tid=\"inpAguaConexionDiametro3-4\" value=\"3/4\"
                                        \t\t";
            // line 228
            if (((isset($context["aguaConexionDiametro"]) || array_key_exists("aguaConexionDiametro", $context) ? $context["aguaConexionDiametro"] : (function () { throw new RuntimeError('Variable "aguaConexionDiametro" does not exist.', 228, $this->source); })()) == "3/4")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaConexionDiametro3-4\">3/4\"</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Diametro de la red</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 236
            $context["aguaDiametroRed"] = "";
            // line 237
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 237)) {
                $context["aguaDiametroRed"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 237, $this->source); })()), "contrato", [], "any", false, false, false, 237), "CTO_AGU_DTO_RED", [], "any", false, false, false, 237);
                // line 238
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 238), "aguaDiametroRed", [], "any", true, true, false, 238)) {
                // line 239
                echo "                                \t    ";
                $context["aguaDiametroRed"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 239, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 239), "aguaDiametroRed", [], "any", false, false, false, 239);
            }
            // line 240
            echo "                                \t<div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaDiametroRed\" 
                                        \t\tid=\"inpAguaDiametroRed2\" value=\"2\"
                                        \t\t";
            // line 243
            if (((isset($context["aguaDiametroRed"]) || array_key_exists("aguaDiametroRed", $context) ? $context["aguaDiametroRed"] : (function () { throw new RuntimeError('Variable "aguaDiametroRed" does not exist.', 243, $this->source); })()) == "2")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaDiametroRed2\">2\"</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaDiametroRed\" 
                                        \t\tid=\"inpAguaDiametroRed3\" value=\"3\"
                                        \t\t";
            // line 249
            if (((isset($context["aguaDiametroRed"]) || array_key_exists("aguaDiametroRed", $context) ? $context["aguaDiametroRed"] : (function () { throw new RuntimeError('Variable "aguaDiametroRed" does not exist.', 249, $this->source); })()) == "3")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaDiametroRed3\">3\"</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaDiametroRed\" 
                                        \t\tid=\"inpAguaDiametroRed4\" value=\"4\"
                                        \t\t";
            // line 255
            if (((isset($context["aguaDiametroRed"]) || array_key_exists("aguaDiametroRed", $context) ? $context["aguaDiametroRed"] : (function () { throw new RuntimeError('Variable "aguaDiametroRed" does not exist.', 255, $this->source); })()) == "4")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaDiametroRed4\">4\"</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Material de conexion</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 263
            $context["aguaMaterialConexion"] = "";
            // line 264
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 264)) {
                $context["aguaMaterialConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 264, $this->source); })()), "contrato", [], "any", false, false, false, 264), "CTO_AGU_MAT_CNX", [], "any", false, false, false, 264);
                // line 265
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 265), "aguaMaterialConexion", [], "any", true, true, false, 265)) {
                // line 266
                echo "                                \t    ";
                $context["aguaMaterialConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 266, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 266), "aguaMaterialConexion", [], "any", false, false, false, 266);
            }
            // line 267
            echo "                                \t<div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialConexion\" 
                                        \t\tid=\"inpAguaMaterialConexionPVC\" value=\"pvc\"
                                        \t\t";
            // line 270
            if (((isset($context["aguaMaterialConexion"]) || array_key_exists("aguaMaterialConexion", $context) ? $context["aguaMaterialConexion"] : (function () { throw new RuntimeError('Variable "aguaMaterialConexion" does not exist.', 270, $this->source); })()) == "pvc")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaMaterialConexionPVC\">PVC</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialConexion\" 
                                        \t\tid=\"inpAguaMaterialConexionF\" value=\"fierro\"
                                        \t\t";
            // line 276
            if (((isset($context["aguaMaterialConexion"]) || array_key_exists("aguaMaterialConexion", $context) ? $context["aguaMaterialConexion"] : (function () { throw new RuntimeError('Variable "aguaMaterialConexion" does not exist.', 276, $this->source); })()) == "fierro")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaMaterialConexionF\">Fierro</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Material de abrazadera</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 284
            $context["aguaMaterialAbrazadera"] = "";
            // line 285
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 285)) {
                $context["aguaMaterialAbrazadera"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 285, $this->source); })()), "contrato", [], "any", false, false, false, 285), "CTO_AGU_MAT_ABA", [], "any", false, false, false, 285);
                // line 286
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 286), "aguaMaterialAbrazadera", [], "any", true, true, false, 286)) {
                // line 287
                echo "                                \t    ";
                $context["aguaMaterialAbrazadera"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 287, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 287), "aguaMaterialAbrazadera", [], "any", false, false, false, 287);
            }
            // line 288
            echo "                                \t<div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialAbrazadera\" 
                                        \t\tid=\"inpAguaMaterialAbrazaderaPVC\" value=\"pvc\"
                                        \t\t";
            // line 291
            if (((isset($context["aguaMaterialAbrazadera"]) || array_key_exists("aguaMaterialAbrazadera", $context) ? $context["aguaMaterialAbrazadera"] : (function () { throw new RuntimeError('Variable "aguaMaterialAbrazadera" does not exist.', 291, $this->source); })()) == "pvc")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaMaterialAbrazaderaPVC\">PVC</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialAbrazadera\" 
                                        \t\tid=\"inpAguaMaterialAbrazaderaF\" value=\"fierro\"
                                        \t\t";
            // line 297
            if (((isset($context["aguaMaterialAbrazadera"]) || array_key_exists("aguaMaterialAbrazadera", $context) ? $context["aguaMaterialAbrazadera"] : (function () { throw new RuntimeError('Variable "aguaMaterialAbrazadera" does not exist.', 297, $this->source); })()) == "fierro")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAguaMaterialAbrazaderaF\">Fierro</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaUbicacionCaja\">Ubicacion de la caja</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 305
            $context["aguaUbicacionCaja"] = "";
            // line 306
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 306)) {
                $context["aguaUbicacionCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 306, $this->source); })()), "contrato", [], "any", false, false, false, 306), "CTO_AGU_UBI_CAJ", [], "any", false, false, false, 306);
                // line 307
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 307), "aguaUbicacionCaja", [], "any", true, true, false, 307)) {
                // line 308
                echo "                                \t    ";
                $context["aguaUbicacionCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 308, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 308), "aguaUbicacionCaja", [], "any", false, false, false, 308);
            }
            // line 309
            echo "                                \t<select name=\"aguaUbicacionCaja\" class=\"f_minwidth300\" id=\"cmbAguaUbicacionCaja\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"vereda\"
                                \t\t\t\t";
            // line 312
            if (((isset($context["aguaUbicacionCaja"]) || array_key_exists("aguaUbicacionCaja", $context) ? $context["aguaUbicacionCaja"] : (function () { throw new RuntimeError('Variable "aguaUbicacionCaja" does not exist.', 312, $this->source); })()) == "vereda")) {
                echo "selected";
            }
            echo ">
                                                Vereda
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"jardin\"
                                \t\t\t\t";
            // line 316
            if (((isset($context["aguaUbicacionCaja"]) || array_key_exists("aguaUbicacionCaja", $context) ? $context["aguaUbicacionCaja"] : (function () { throw new RuntimeError('Variable "aguaUbicacionCaja" does not exist.', 316, $this->source); })()) == "jardin")) {
                echo "selected";
            }
            echo ">
                                                Jardin
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"interior casa\"
                                \t\t\t\t";
            // line 320
            if (((isset($context["aguaUbicacionCaja"]) || array_key_exists("aguaUbicacionCaja", $context) ? $context["aguaUbicacionCaja"] : (function () { throw new RuntimeError('Variable "aguaUbicacionCaja" does not exist.', 320, $this->source); })()) == "interior casa")) {
                echo "selected";
            }
            echo ">
                                                Interior casa
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"no tiene\"
                                \t\t\t\t";
            // line 324
            if (((isset($context["aguaUbicacionCaja"]) || array_key_exists("aguaUbicacionCaja", $context) ? $context["aguaUbicacionCaja"] : (function () { throw new RuntimeError('Variable "aguaUbicacionCaja" does not exist.', 324, $this->source); })()) == "no tiene")) {
                echo "selected";
            }
            echo ">
                                                No tiene
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaMaterialCaja\">Material de la caja</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 333
            $context["aguaMaterialCaja"] = "";
            // line 334
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 334)) {
                $context["aguaMaterialCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 334, $this->source); })()), "contrato", [], "any", false, false, false, 334), "CTO_AGU_MAT_CAJ", [], "any", false, false, false, 334);
                // line 335
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 335), "aguaMaterialCaja", [], "any", true, true, false, 335)) {
                // line 336
                echo "                                \t    ";
                $context["aguaMaterialCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 336, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 336), "aguaMaterialCaja", [], "any", false, false, false, 336);
            }
            // line 337
            echo "                                \t<select name=\"aguaMaterialCaja\" class=\"f_minwidth300\" id=\"cmbAguaMaterialCaja\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"concreto\"
                                \t\t\t\t";
            // line 340
            if (((isset($context["aguaMaterialCaja"]) || array_key_exists("aguaMaterialCaja", $context) ? $context["aguaMaterialCaja"] : (function () { throw new RuntimeError('Variable "aguaMaterialCaja" does not exist.', 340, $this->source); })()) == "concreto")) {
                echo "selected";
            }
            echo ">
                                                Concreto
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"ladrillo\"
                                \t\t\t\t";
            // line 344
            if (((isset($context["aguaMaterialCaja"]) || array_key_exists("aguaMaterialCaja", $context) ? $context["aguaMaterialCaja"] : (function () { throw new RuntimeError('Variable "aguaMaterialCaja" does not exist.', 344, $this->source); })()) == "ladrillo")) {
                echo "selected";
            }
            echo ">
                                                Ladrillo
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"termoplastico\"
                                \t\t\t\t";
            // line 348
            if (((isset($context["aguaMaterialCaja"]) || array_key_exists("aguaMaterialCaja", $context) ? $context["aguaMaterialCaja"] : (function () { throw new RuntimeError('Variable "aguaMaterialCaja" does not exist.', 348, $this->source); })()) == "termoplastico")) {
                echo "selected";
            }
            echo ">
                                                Termoplastico
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaEstadoCaja\">Estado de la caja</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 357
            $context["aguaEstadoCaja"] = "";
            // line 358
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 358)) {
                $context["aguaEstadoCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 358, $this->source); })()), "contrato", [], "any", false, false, false, 358), "CTO_AGU_EST_CAJ", [], "any", false, false, false, 358);
                // line 359
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 359), "aguaEstadoCaja", [], "any", true, true, false, 359)) {
                // line 360
                echo "                                \t    ";
                $context["aguaEstadoCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 360, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 360), "aguaEstadoCaja", [], "any", false, false, false, 360);
            }
            // line 361
            echo "                                \t<select name=\"aguaEstadoCaja\" class=\"f_minwidth300\" id=\"cmbAguaEstadoCaja\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"buena\"
                                \t\t\t\t";
            // line 364
            if (((isset($context["aguaEstadoCaja"]) || array_key_exists("aguaEstadoCaja", $context) ? $context["aguaEstadoCaja"] : (function () { throw new RuntimeError('Variable "aguaEstadoCaja" does not exist.', 364, $this->source); })()) == "buena")) {
                echo "selected";
            }
            echo ">
                                                Buena
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"sucia\"
                                \t\t\t\t";
            // line 368
            if (((isset($context["aguaEstadoCaja"]) || array_key_exists("aguaEstadoCaja", $context) ? $context["aguaEstadoCaja"] : (function () { throw new RuntimeError('Variable "aguaEstadoCaja" does not exist.', 368, $this->source); })()) == "sucia")) {
                echo "selected";
            }
            echo ">
                                                Sucia
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"mal estado\"
                                \t\t\t\t";
            // line 372
            if (((isset($context["aguaEstadoCaja"]) || array_key_exists("aguaEstadoCaja", $context) ? $context["aguaEstadoCaja"] : (function () { throw new RuntimeError('Variable "aguaEstadoCaja" does not exist.', 372, $this->source); })()) == "mal estado")) {
                echo "selected";
            }
            echo ">
                                                Mal estado
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaMaterialTapa\">Material de la tapa</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 381
            $context["aguaMaterialTapa"] = "";
            // line 382
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 382)) {
                $context["aguaMaterialTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 382, $this->source); })()), "contrato", [], "any", false, false, false, 382), "CTO_AGU_MAT_TAP", [], "any", false, false, false, 382);
                // line 383
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 383), "aguaMaterialTapa", [], "any", true, true, false, 383)) {
                // line 384
                echo "                                \t    ";
                $context["aguaMaterialTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 384, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 384), "aguaMaterialTapa", [], "any", false, false, false, 384);
            }
            // line 385
            echo "                                \t<select name=\"aguaMaterialTapa\" class=\"f_minwidth300\" id=\"cmbAguaMaterialTapa\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"concreto\"
                                \t\t\t\t";
            // line 388
            if (((isset($context["aguaMaterialTapa"]) || array_key_exists("aguaMaterialTapa", $context) ? $context["aguaMaterialTapa"] : (function () { throw new RuntimeError('Variable "aguaMaterialTapa" does not exist.', 388, $this->source); })()) == "concreto")) {
                echo "selected";
            }
            echo ">
                                                Concreto
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"ladrillo\"
                                \t\t\t\t";
            // line 392
            if (((isset($context["aguaMaterialTapa"]) || array_key_exists("aguaMaterialTapa", $context) ? $context["aguaMaterialTapa"] : (function () { throw new RuntimeError('Variable "aguaMaterialTapa" does not exist.', 392, $this->source); })()) == "ladrillo")) {
                echo "selected";
            }
            echo ">
                                                Ladrillo
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"fierro\"
                                \t\t\t\t";
            // line 396
            if (((isset($context["aguaMaterialTapa"]) || array_key_exists("aguaMaterialTapa", $context) ? $context["aguaMaterialTapa"] : (function () { throw new RuntimeError('Variable "aguaMaterialTapa" does not exist.', 396, $this->source); })()) == "fierro")) {
                echo "selected";
            }
            echo ">
                                                Fierro
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"termoplastico\"
                                \t\t\t\t";
            // line 400
            if (((isset($context["aguaMaterialTapa"]) || array_key_exists("aguaMaterialTapa", $context) ? $context["aguaMaterialTapa"] : (function () { throw new RuntimeError('Variable "aguaMaterialTapa" does not exist.', 400, $this->source); })()) == "termoplastico")) {
                echo "selected";
            }
            echo ">
                                                Termoplastico
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"no tiene\"
                                \t\t\t\t";
            // line 404
            if (((isset($context["aguaMaterialTapa"]) || array_key_exists("aguaMaterialTapa", $context) ? $context["aguaMaterialTapa"] : (function () { throw new RuntimeError('Variable "aguaMaterialTapa" does not exist.', 404, $this->source); })()) == "no tiene")) {
                echo "selected";
            }
            echo ">
                                                No Tiene
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaEstadoTapa\">Estado de la tapa</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 413
            $context["aguaEstadoTapa"] = "";
            // line 414
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 414)) {
                $context["aguaEstadoTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 414, $this->source); })()), "contrato", [], "any", false, false, false, 414), "CTO_AGU_EST_TAP", [], "any", false, false, false, 414);
                // line 415
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 415), "aguaEstadoTapa", [], "any", true, true, false, 415)) {
                // line 416
                echo "                                \t    ";
                $context["aguaEstadoTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 416, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 416), "aguaEstadoTapa", [], "any", false, false, false, 416);
            }
            // line 417
            echo "                                \t<select name=\"aguaEstadoTapa\" class=\"f_minwidth300\" id=\"cmbAguaEstadoTapa\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"buena\"
                                \t\t\t\t";
            // line 420
            if (((isset($context["aguaEstadoTapa"]) || array_key_exists("aguaEstadoTapa", $context) ? $context["aguaEstadoTapa"] : (function () { throw new RuntimeError('Variable "aguaEstadoTapa" does not exist.', 420, $this->source); })()) == "buena")) {
                echo "selected";
            }
            echo ">
                                                Buena
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"sellada\"
                                \t\t\t\t";
            // line 424
            if (((isset($context["aguaEstadoTapa"]) || array_key_exists("aguaEstadoTapa", $context) ? $context["aguaEstadoTapa"] : (function () { throw new RuntimeError('Variable "aguaEstadoTapa" does not exist.', 424, $this->source); })()) == "sellada")) {
                echo "selected";
            }
            echo ">
                                                Sellada
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"mal estado\"
                                \t\t\t\t";
            // line 428
            if (((isset($context["aguaEstadoTapa"]) || array_key_exists("aguaEstadoTapa", $context) ? $context["aguaEstadoTapa"] : (function () { throw new RuntimeError('Variable "aguaEstadoTapa" does not exist.', 428, $this->source); })()) == "mal estado")) {
                echo "selected";
            }
            echo ">
                                                Mal estado
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                        </div>";
            // line 435
            echo "                        ";
        }
        // line 436
        echo "                        
                        
                        ";
        // line 438
        if ((isset($context["tieneDesague"]) || array_key_exists("tieneDesague", $context) ? $context["tieneDesague"] : (function () { throw new RuntimeError('Variable "tieneDesague" does not exist.', 438, $this->source); })())) {
            // line 439
            echo "                        ";
            // line 440
            echo "                        <div id=\"divNuevoContratoMasDetallesAlc\">
                            <h5 class=\"my-4 text-bold\">Servicio de Alcantarillado</h5>
                    \t\t<div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"inpAlcFechaConexion\">Fecha de conexion</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 445
            $context["alcFechaConexion"] = "";
            // line 446
            echo "                                \t";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 446)) {
                $context["alcFechaConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 446, $this->source); })()), "contrato", [], "any", false, false, false, 446), "CTO_ALC_FEC_INS", [], "any", false, false, false, 446);
                // line 447
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 447), "alcFechaConexion", [], "any", true, true, false, 447)) {
                // line 448
                echo "                                \t    ";
                $context["alcFechaConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 448, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 448), "alcFechaConexion", [], "any", false, false, false, 448);
            }
            // line 449
            echo "                                \t<input type=\"date\" class=\"f_minwidth300\" id=\"inpAlcFechaConexion\" name=\"alcFechaConexion\" 
                                \t\t\tvalue=\"";
            // line 450
            echo twig_escape_filter($this->env, (isset($context["alcFechaConexion"]) || array_key_exists("alcFechaConexion", $context) ? $context["alcFechaConexion"] : (function () { throw new RuntimeError('Variable "alcFechaConexion" does not exist.', 450, $this->source); })()), "html", null, true);
            echo "\">
                                </div>
                            </div>
                    \t\t<div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Característica de la conexion</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 456
            $context["alcConexionCaracteristica"] = "";
            // line 457
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 457)) {
                $context["alcConexionCaracteristica"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 457, $this->source); })()), "contrato", [], "any", false, false, false, 457), "CTO_ALC_CAR_CNX", [], "any", false, false, false, 457);
                // line 458
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 458), "alcConexionCaracteristica", [], "any", true, true, false, 458)) {
                // line 459
                echo "                                \t    ";
                $context["alcConexionCaracteristica"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 459, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 459), "alcConexionCaracteristica", [], "any", false, false, false, 459);
            }
            // line 460
            echo "                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionCaracteristica\" 
                                        \t\tid=\"inpAlcConexionCaracteristicaSC\" value=\"sin caja\"
                                        \t\t";
            // line 463
            if (((isset($context["alcConexionCaracteristica"]) || array_key_exists("alcConexionCaracteristica", $context) ? $context["alcConexionCaracteristica"] : (function () { throw new RuntimeError('Variable "alcConexionCaracteristica" does not exist.', 463, $this->source); })()) == "sin caja")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcConexionCaracteristicaSC\">Sin caja</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionCaracteristica\" 
                                        \t\tid=\"inpAlcConexionCaracteristicaCC\" value=\"con caja\"
                                        \t\t";
            // line 469
            if (((isset($context["alcConexionCaracteristica"]) || array_key_exists("alcConexionCaracteristica", $context) ? $context["alcConexionCaracteristica"] : (function () { throw new RuntimeError('Variable "alcConexionCaracteristica" does not exist.', 469, $this->source); })()) == "con caja")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcConexionCaracteristicaCC\">Con caja</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Tipo de conexion</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 477
            $context["alcTipoConexion"] = "";
            // line 478
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 478)) {
                $context["alcTipoConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 478, $this->source); })()), "contrato", [], "any", false, false, false, 478), "CTO_ALC_TIP_CNX", [], "any", false, false, false, 478);
                // line 479
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 479), "alcTipoConexion", [], "any", true, true, false, 479)) {
                // line 480
                echo "                                \t    ";
                $context["alcTipoConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 480, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 480), "alcTipoConexion", [], "any", false, false, false, 480);
            }
            // line 481
            echo "                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcTipoConexion\" 
                                        \t\tid=\"inpAlcTipoConexionCV\" value=\"convencional\"
                                        \t\t";
            // line 484
            if (((isset($context["alcTipoConexion"]) || array_key_exists("alcTipoConexion", $context) ? $context["alcTipoConexion"] : (function () { throw new RuntimeError('Variable "alcTipoConexion" does not exist.', 484, $this->source); })()) == "convencional")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcTipoConexionCV\">Convencional</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcTipoConexion\" 
                                        \t\tid=\"inpAlcTipoConexionCD\" value=\"condominial\"
                                        \t\t";
            // line 490
            if (((isset($context["alcTipoConexion"]) || array_key_exists("alcTipoConexion", $context) ? $context["alcTipoConexion"] : (function () { throw new RuntimeError('Variable "alcTipoConexion" does not exist.', 490, $this->source); })()) == "condominial")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcTipoConexionCD\">Condominial</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Diametro de la conexion</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 498
            $context["alcConexionDiametro"] = "";
            // line 499
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 499)) {
                $context["alcConexionDiametro"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 499, $this->source); })()), "contrato", [], "any", false, false, false, 499), "CTO_ALC_DTO_CNX", [], "any", false, false, false, 499);
                // line 500
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 500), "alcConexionDiametro", [], "any", true, true, false, 500)) {
                // line 501
                echo "                                \t    ";
                $context["alcConexionDiametro"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 501, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 501), "alcConexionDiametro", [], "any", false, false, false, 501);
            }
            // line 502
            echo "                                \t<div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionDiametro\" 
                                        \t\tid=\"inpAlcConexionDiametro4\" value=\"4\"
                                        \t\t";
            // line 505
            if (((isset($context["alcConexionDiametro"]) || array_key_exists("alcConexionDiametro", $context) ? $context["alcConexionDiametro"] : (function () { throw new RuntimeError('Variable "alcConexionDiametro" does not exist.', 505, $this->source); })()) == "4")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcConexionDiametro4\">4\"</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionDiametro\" 
                                        \t\tid=\"inpAlcConexionDiametro6\" value=\"6\"
                                        \t\t";
            // line 511
            if (((isset($context["alcConexionDiametro"]) || array_key_exists("alcConexionDiametro", $context) ? $context["alcConexionDiametro"] : (function () { throw new RuntimeError('Variable "alcConexionDiametro" does not exist.', 511, $this->source); })()) == "6")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcConexionDiametro6\">6\"</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Diametro de la red</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 519
            $context["alcDiametroRed"] = "";
            // line 520
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 520)) {
                $context["alcDiametroRed"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 520, $this->source); })()), "contrato", [], "any", false, false, false, 520), "CTO_ALC_DTO_RED", [], "any", false, false, false, 520);
                // line 521
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 521), "alcDiametroRed", [], "any", true, true, false, 521)) {
                // line 522
                echo "                                \t    ";
                $context["alcDiametroRed"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 522, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 522), "alcDiametroRed", [], "any", false, false, false, 522);
            }
            // line 523
            echo "                                \t<div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                        \t\tid=\"inpAlcDiametroRed4\" value=\"4\"
                                        \t\t";
            // line 526
            if (((isset($context["alcDiametroRed"]) || array_key_exists("alcDiametroRed", $context) ? $context["alcDiametroRed"] : (function () { throw new RuntimeError('Variable "alcDiametroRed" does not exist.', 526, $this->source); })()) == "4")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcDiametroRed4\">4\"</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                        \t\tid=\"inpAlcDiametroRed6\" value=\"6\"
                                        \t\t";
            // line 532
            if (((isset($context["alcDiametroRed"]) || array_key_exists("alcDiametroRed", $context) ? $context["alcDiametroRed"] : (function () { throw new RuntimeError('Variable "alcDiametroRed" does not exist.', 532, $this->source); })()) == "6")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcDiametroRed6\">6\"</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                        \t\tid=\"inpAlcDiametroRed8\" value=\"8\"
                                        \t\t";
            // line 538
            if (((isset($context["alcDiametroRed"]) || array_key_exists("alcDiametroRed", $context) ? $context["alcDiametroRed"] : (function () { throw new RuntimeError('Variable "alcDiametroRed" does not exist.', 538, $this->source); })()) == "8")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcDiametroRed8\">8\"</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                        \t\tid=\"inpAlcDiametroRed10\" value=\"10\"
                                        \t\t";
            // line 544
            if (((isset($context["alcDiametroRed"]) || array_key_exists("alcDiametroRed", $context) ? $context["alcDiametroRed"] : (function () { throw new RuntimeError('Variable "alcDiametroRed" does not exist.', 544, $this->source); })()) == "10")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcDiametroRed10\">10\"</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\">Material de conexion</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 552
            $context["alcMaterialConexion"] = "";
            // line 553
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 553)) {
                $context["alcMaterialConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 553, $this->source); })()), "contrato", [], "any", false, false, false, 553), "CTO_ALC_MAT_CNX", [], "any", false, false, false, 553);
                // line 554
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 554), "alcMaterialConexion", [], "any", true, true, false, 554)) {
                // line 555
                echo "                                \t    ";
                $context["alcMaterialConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 555, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 555), "alcMaterialConexion", [], "any", false, false, false, 555);
            }
            // line 556
            echo "                                \t<div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcMaterialConexion\" 
                                        \t\tid=\"inpAlcMaterialConexionPVC\" value=\"pvc\"
                                        \t\t";
            // line 559
            if (((isset($context["alcMaterialConexion"]) || array_key_exists("alcMaterialConexion", $context) ? $context["alcMaterialConexion"] : (function () { throw new RuntimeError('Variable "alcMaterialConexion" does not exist.', 559, $this->source); })()) == "pvc")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcMaterialConexionPVC\">PVC</label>
                                    </div>
                                    <div class=\"form-check form-check-inline\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"alcMaterialConexion\" 
                                        \t\tid=\"inpAlcMaterialConexionF\" value=\"fierro\"
                                        \t\t";
            // line 565
            if (((isset($context["alcMaterialConexion"]) || array_key_exists("alcMaterialConexion", $context) ? $context["alcMaterialConexion"] : (function () { throw new RuntimeError('Variable "alcMaterialConexion" does not exist.', 565, $this->source); })()) == "fierro")) {
                echo "checked";
            }
            echo ">
                                        <label class=\"form-check-label\" for=\"inpAlcMaterialConexionF\">Fierro</label>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcUbicacionCaja\">Ubicacion de la caja</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 573
            $context["alcUbicacionCaja"] = "";
            // line 574
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 574)) {
                $context["alcUbicacionCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 574, $this->source); })()), "contrato", [], "any", false, false, false, 574), "CTO_ALC_UBI_CAJ", [], "any", false, false, false, 574);
                // line 575
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 575), "alcUbicacionCaja", [], "any", true, true, false, 575)) {
                // line 576
                echo "                                \t    ";
                $context["alcUbicacionCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 576, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 576), "alcUbicacionCaja", [], "any", false, false, false, 576);
            }
            // line 577
            echo "                                \t<select name=\"alcUbicacionCaja\" class=\"f_minwidth300\" id=\"cmbAlcUbicacionCaja\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"vereda\"
                                \t\t\t\t";
            // line 580
            if (((isset($context["alcUbicacionCaja"]) || array_key_exists("alcUbicacionCaja", $context) ? $context["alcUbicacionCaja"] : (function () { throw new RuntimeError('Variable "alcUbicacionCaja" does not exist.', 580, $this->source); })()) == "vereda")) {
                echo "selected";
            }
            echo ">
                                                Vereda
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"jardin\"
                                \t\t\t\t";
            // line 584
            if (((isset($context["alcUbicacionCaja"]) || array_key_exists("alcUbicacionCaja", $context) ? $context["alcUbicacionCaja"] : (function () { throw new RuntimeError('Variable "alcUbicacionCaja" does not exist.', 584, $this->source); })()) == "jardin")) {
                echo "selected";
            }
            echo ">
                                                Jardin
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"interior casa\"
                                \t\t\t\t";
            // line 588
            if (((isset($context["alcUbicacionCaja"]) || array_key_exists("alcUbicacionCaja", $context) ? $context["alcUbicacionCaja"] : (function () { throw new RuntimeError('Variable "alcUbicacionCaja" does not exist.', 588, $this->source); })()) == "interior casa")) {
                echo "selected";
            }
            echo ">
                                                Interior casa
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"no tiene\"
                                \t\t\t\t";
            // line 592
            if (((isset($context["alcUbicacionCaja"]) || array_key_exists("alcUbicacionCaja", $context) ? $context["alcUbicacionCaja"] : (function () { throw new RuntimeError('Variable "alcUbicacionCaja" does not exist.', 592, $this->source); })()) == "no tiene")) {
                echo "selected";
            }
            echo ">
                                                No tiene
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcMaterialCaja\">Material de la caja</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 601
            $context["alcMaterialCaja"] = "";
            // line 602
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 602)) {
                $context["alcMaterialCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 602, $this->source); })()), "contrato", [], "any", false, false, false, 602), "CTO_ALC_MAT_CAJ", [], "any", false, false, false, 602);
                // line 603
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 603), "alcMaterialCaja", [], "any", true, true, false, 603)) {
                // line 604
                echo "                                \t    ";
                $context["alcMaterialCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 604, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 604), "alcMaterialCaja", [], "any", false, false, false, 604);
            }
            // line 605
            echo "                                \t<select name=\"alcMaterialCaja\" class=\"f_minwidth300\" id=\"cmbAlcMaterialCaja\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"concreto\"
                                \t\t\t\t";
            // line 608
            if (((isset($context["alcMaterialCaja"]) || array_key_exists("alcMaterialCaja", $context) ? $context["alcMaterialCaja"] : (function () { throw new RuntimeError('Variable "alcMaterialCaja" does not exist.', 608, $this->source); })()) == "concreto")) {
                echo "selected";
            }
            echo ">
                                                Concreto
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"ladrillo\"
                                \t\t\t\t";
            // line 612
            if (((isset($context["alcMaterialCaja"]) || array_key_exists("alcMaterialCaja", $context) ? $context["alcMaterialCaja"] : (function () { throw new RuntimeError('Variable "alcMaterialCaja" does not exist.', 612, $this->source); })()) == "ladrillo")) {
                echo "selected";
            }
            echo ">
                                                Ladrillo
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"termoplastico\"
                                \t\t\t\t";
            // line 616
            if (((isset($context["alcMaterialCaja"]) || array_key_exists("alcMaterialCaja", $context) ? $context["alcMaterialCaja"] : (function () { throw new RuntimeError('Variable "alcMaterialCaja" does not exist.', 616, $this->source); })()) == "termoplastico")) {
                echo "selected";
            }
            echo ">
                                                Termoplastico
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcEstadoCaja\">Estado de la caja</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 625
            $context["alcEstadoCaja"] = "";
            // line 626
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 626)) {
                $context["alcEstadoCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 626, $this->source); })()), "contrato", [], "any", false, false, false, 626), "CTO_ALC_EST_CAJ", [], "any", false, false, false, 626);
                // line 627
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 627), "alcEstadoCaja", [], "any", true, true, false, 627)) {
                // line 628
                echo "                                \t    ";
                $context["alcEstadoCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 628, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 628), "alcEstadoCaja", [], "any", false, false, false, 628);
            }
            // line 629
            echo "                                \t<select name=\"alcEstadoCaja\" class=\"f_minwidth300\" id=\"cmbAlcEstadoCaja\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"buena\"
                                \t\t\t\t";
            // line 632
            if (((isset($context["alcEstadoCaja"]) || array_key_exists("alcEstadoCaja", $context) ? $context["alcEstadoCaja"] : (function () { throw new RuntimeError('Variable "alcEstadoCaja" does not exist.', 632, $this->source); })()) == "buena")) {
                echo "selected";
            }
            echo ">
                                                Buena
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"sucia\"
                                \t\t\t\t";
            // line 636
            if (((isset($context["alcEstadoCaja"]) || array_key_exists("alcEstadoCaja", $context) ? $context["alcEstadoCaja"] : (function () { throw new RuntimeError('Variable "alcEstadoCaja" does not exist.', 636, $this->source); })()) == "sucia")) {
                echo "selected";
            }
            echo ">
                                                Sucia
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"mal estado\"
                                \t\t\t\t";
            // line 640
            if (((isset($context["alcEstadoCaja"]) || array_key_exists("alcEstadoCaja", $context) ? $context["alcEstadoCaja"] : (function () { throw new RuntimeError('Variable "alcEstadoCaja" does not exist.', 640, $this->source); })()) == "mal estado")) {
                echo "selected";
            }
            echo ">
                                                Mal estado
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcDimencionCaja\">Dimención de la caja</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 649
            $context["alcDimencionCaja"] = "";
            // line 650
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 650)) {
                $context["alcDimencionCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 650, $this->source); })()), "contrato", [], "any", false, false, false, 650), "CTO_ALC_DIM_CAJ", [], "any", false, false, false, 650);
                // line 651
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 651), "alcDimencionCaja", [], "any", true, true, false, 651)) {
                // line 652
                echo "                                \t    ";
                $context["alcDimencionCaja"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 652, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 652), "alcDimencionCaja", [], "any", false, false, false, 652);
            }
            // line 653
            echo "                                \t<select name=\"alcDimencionCaja\" class=\"f_minwidth300\" id=\"cmbAlcDimencionCaja\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"70x40 cm\"
                                \t\t\t\t";
            // line 656
            if (((isset($context["alcDimencionCaja"]) || array_key_exists("alcDimencionCaja", $context) ? $context["alcDimencionCaja"] : (function () { throw new RuntimeError('Variable "alcDimencionCaja" does not exist.', 656, $this->source); })()) == "70x40 cm")) {
                echo "selected";
            }
            echo ">
                                                70 x 40 cm
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"60x40 cm\"
                                \t\t\t\t";
            // line 660
            if (((isset($context["alcDimencionCaja"]) || array_key_exists("alcDimencionCaja", $context) ? $context["alcDimencionCaja"] : (function () { throw new RuntimeError('Variable "alcDimencionCaja" does not exist.', 660, $this->source); })()) == "60x40 cm")) {
                echo "selected";
            }
            echo ">
                                                60 x 40 cm
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"otro\"
                                \t\t\t\t";
            // line 664
            if (((isset($context["alcDimencionCaja"]) || array_key_exists("alcDimencionCaja", $context) ? $context["alcDimencionCaja"] : (function () { throw new RuntimeError('Variable "alcDimencionCaja" does not exist.', 664, $this->source); })()) == "otro")) {
                echo "selected";
            }
            echo ">
                                                Otro
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcMaterialTapa\">Material de la tapa</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 673
            $context["alcMaterialTapa"] = "";
            // line 674
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 674)) {
                $context["alcMaterialTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 674, $this->source); })()), "contrato", [], "any", false, false, false, 674), "CTO_ALC_MAT_TAP", [], "any", false, false, false, 674);
                // line 675
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 675), "alcMaterialTapa", [], "any", true, true, false, 675)) {
                // line 676
                echo "                                \t    ";
                $context["alcMaterialTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 676, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 676), "alcMaterialTapa", [], "any", false, false, false, 676);
            }
            // line 677
            echo "                                \t<select name=\"alcMaterialTapa\" class=\"f_minwidth300\" id=\"cmbAlcMaterialTapa\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"concreto\"
                                \t\t\t\t";
            // line 680
            if (((isset($context["alcMaterialTapa"]) || array_key_exists("alcMaterialTapa", $context) ? $context["alcMaterialTapa"] : (function () { throw new RuntimeError('Variable "alcMaterialTapa" does not exist.', 680, $this->source); })()) == "concreto")) {
                echo "selected";
            }
            echo ">
                                                Concreto
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"fierro\"
                                \t\t\t\t";
            // line 684
            if (((isset($context["alcMaterialTapa"]) || array_key_exists("alcMaterialTapa", $context) ? $context["alcMaterialTapa"] : (function () { throw new RuntimeError('Variable "alcMaterialTapa" does not exist.', 684, $this->source); })()) == "fierro")) {
                echo "selected";
            }
            echo ">
                                                Fierro
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"madera\"
                                \t\t\t\t";
            // line 688
            if (((isset($context["alcMaterialTapa"]) || array_key_exists("alcMaterialTapa", $context) ? $context["alcMaterialTapa"] : (function () { throw new RuntimeError('Variable "alcMaterialTapa" does not exist.', 688, $this->source); })()) == "madera")) {
                echo "selected";
            }
            echo ">
                                                Madera
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"no tiene\"
                                \t\t\t\t";
            // line 692
            if (((isset($context["alcMaterialTapa"]) || array_key_exists("alcMaterialTapa", $context) ? $context["alcMaterialTapa"] : (function () { throw new RuntimeError('Variable "alcMaterialTapa" does not exist.', 692, $this->source); })()) == "no tiene")) {
                echo "selected";
            }
            echo ">
                                                No Tiene
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcEstadoTapa\">Estado de la tapa</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 701
            $context["alcEstadoTapa"] = "";
            // line 702
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 702)) {
                $context["alcEstadoTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 702, $this->source); })()), "contrato", [], "any", false, false, false, 702), "CTO_ALC_EST_TAP", [], "any", false, false, false, 702);
                // line 703
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 703), "alcEstadoTapa", [], "any", true, true, false, 703)) {
                // line 704
                echo "                                \t    ";
                $context["alcEstadoTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 704, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 704), "alcEstadoTapa", [], "any", false, false, false, 704);
            }
            // line 705
            echo "                                \t<select name=\"alcEstadoTapa\" class=\"f_minwidth300\" id=\"cmbAlcEstadoTapa\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"buena\"
                                \t\t\t\t";
            // line 708
            if (((isset($context["alcEstadoTapa"]) || array_key_exists("alcEstadoTapa", $context) ? $context["alcEstadoTapa"] : (function () { throw new RuntimeError('Variable "alcEstadoTapa" does not exist.', 708, $this->source); })()) == "buena")) {
                echo "selected";
            }
            echo ">
                                                Buena
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"sellada\"
                                \t\t\t\t";
            // line 712
            if (((isset($context["alcEstadoTapa"]) || array_key_exists("alcEstadoTapa", $context) ? $context["alcEstadoTapa"] : (function () { throw new RuntimeError('Variable "alcEstadoTapa" does not exist.', 712, $this->source); })()) == "sellada")) {
                echo "selected";
            }
            echo ">
                                                Sellada
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"mal estado\"
                                \t\t\t\t";
            // line 716
            if (((isset($context["alcEstadoTapa"]) || array_key_exists("alcEstadoTapa", $context) ? $context["alcEstadoTapa"] : (function () { throw new RuntimeError('Variable "alcEstadoTapa" does not exist.', 716, $this->source); })()) == "mal estado")) {
                echo "selected";
            }
            echo ">
                                                Mal estado
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"no tiene\"
                                \t\t\t\t";
            // line 720
            if (((isset($context["alcEstadoTapa"]) || array_key_exists("alcEstadoTapa", $context) ? $context["alcEstadoTapa"] : (function () { throw new RuntimeError('Variable "alcEstadoTapa" does not exist.', 720, $this->source); })()) == "no tiene")) {
                echo "selected";
            }
            echo ">
                                                No tiene
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"form-group row\">
                            \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcMedidasTapa\">Medidas de la tapa</label>
                                <div class=\"col-12 col-md-9\">
                                \t";
            // line 729
            $context["alcMedidasTapa"] = "";
            // line 730
            echo "                        \t        ";
            if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 730)) {
                $context["alcMedidasTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 730, $this->source); })()), "contrato", [], "any", false, false, false, 730), "CTO_ALC_MED_TAP", [], "any", false, false, false, 730);
                // line 731
                echo "                                \t";
            } elseif (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 731), "alcMedidasTapa", [], "any", true, true, false, 731)) {
                // line 732
                echo "                                \t    ";
                $context["alcMedidasTapa"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 732, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 732), "alcMedidasTapa", [], "any", false, false, false, 732);
            }
            // line 733
            echo "                                \t<select name=\"alcMedidasTapa\" class=\"f_minwidth300\" id=\"cmbAlcMedidasTapa\"> 
                                \t\t<option value=\"-1\"></option>
                                    \t<option value=\"62x32 cm\"
                                \t\t\t\t";
            // line 736
            if (((isset($context["alcMedidasTapa"]) || array_key_exists("alcMedidasTapa", $context) ? $context["alcMedidasTapa"] : (function () { throw new RuntimeError('Variable "alcMedidasTapa" does not exist.', 736, $this->source); })()) == "62x32 cm")) {
                echo "selected";
            }
            echo ">
                                                62 x 32 cm
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"54x34 cm\"
                                \t\t\t\t";
            // line 740
            if (((isset($context["alcMedidasTapa"]) || array_key_exists("alcMedidasTapa", $context) ? $context["alcMedidasTapa"] : (function () { throw new RuntimeError('Variable "alcMedidasTapa" does not exist.', 740, $this->source); })()) == "54x34 cm")) {
                echo "selected";
            }
            echo ">
                                                54 x 34 cm
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"53x53 cm\"
                                \t\t\t\t";
            // line 744
            if (((isset($context["alcMedidasTapa"]) || array_key_exists("alcMedidasTapa", $context) ? $context["alcMedidasTapa"] : (function () { throw new RuntimeError('Variable "alcMedidasTapa" does not exist.', 744, $this->source); })()) == "53x53 cm")) {
                echo "selected";
            }
            echo ">
                                                53 x 53 cm
                        \t\t\t\t</option>
                        \t\t\t\t<option value=\"otro\"
                                \t\t\t\t";
            // line 748
            if (((isset($context["alcMedidasTapa"]) || array_key_exists("alcMedidasTapa", $context) ? $context["alcMedidasTapa"] : (function () { throw new RuntimeError('Variable "alcMedidasTapa" does not exist.', 748, $this->source); })()) == "otro")) {
                echo "selected";
            }
            echo ">
                                                Otro
                        \t\t\t\t</option>
                                    </select>
                                </div>
                            </div>
                        </div>";
            // line 755
            echo "                        ";
        }
        // line 756
        echo "                        
                \t</div>";
        // line 758
        echo "          \t\t</div>
          \t</div><!-- /.card-body -->
      \t</div>
  \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
          \t\t<div class=\"f_cardfooter f_cardfooteractions text-center\">
        \t\t\t<button type=\"submit\" class=\"f_button f_buttonaction\">Guardar cambios</button>
        \t\t\t<a href=\"";
        // line 766
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 766, $this->source); })()), "html", null, true);
        echo "/contrato/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 772
        echo "  
</div><!-- /.card -->
    
";
    }

    // line 777
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 778
        echo "
    ";
        // line 779
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formEditarContrato').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        f_select2(\"#cmbCalle\");
        
        
        if(\$(\"#chkContratoInicio\").prop('checked')){
    \t\t\$(\"#inpFechaInicio\").prop(\"disabled\", false);
    \t}else{
    \t\t\$(\"#inpFechaInicio\").val(\"\");
    \t\t\$(\"#inpFechaInicio\").prop(\"disabled\", true);
    \t}
        
        \$(\"#chkContratoInicio\").change(function(){
        \tif(\$(\"#chkContratoInicio\").prop('checked')){
        \t\t\$(\"#inpFechaInicio\").prop(\"disabled\", false);
        \t}else{
        \t\t\$(\"#inpFechaInicio\").val(\"\");
        \t\t\$(\"#inpFechaInicio\").prop(\"disabled\", true);
        \t}
        });
\t</script>
";
    }

    public function getTemplateName()
    {
        return "/administration/contrato/contratoEdit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1670 => 779,  1667 => 778,  1663 => 777,  1656 => 772,  1648 => 766,  1638 => 758,  1635 => 756,  1632 => 755,  1621 => 748,  1612 => 744,  1603 => 740,  1594 => 736,  1589 => 733,  1585 => 732,  1582 => 731,  1578 => 730,  1576 => 729,  1562 => 720,  1553 => 716,  1544 => 712,  1535 => 708,  1530 => 705,  1526 => 704,  1523 => 703,  1519 => 702,  1517 => 701,  1503 => 692,  1494 => 688,  1485 => 684,  1476 => 680,  1471 => 677,  1467 => 676,  1464 => 675,  1460 => 674,  1458 => 673,  1444 => 664,  1435 => 660,  1426 => 656,  1421 => 653,  1417 => 652,  1414 => 651,  1410 => 650,  1408 => 649,  1394 => 640,  1385 => 636,  1376 => 632,  1371 => 629,  1367 => 628,  1364 => 627,  1360 => 626,  1358 => 625,  1344 => 616,  1335 => 612,  1326 => 608,  1321 => 605,  1317 => 604,  1314 => 603,  1310 => 602,  1308 => 601,  1294 => 592,  1285 => 588,  1276 => 584,  1267 => 580,  1262 => 577,  1258 => 576,  1255 => 575,  1251 => 574,  1249 => 573,  1236 => 565,  1225 => 559,  1220 => 556,  1216 => 555,  1213 => 554,  1209 => 553,  1207 => 552,  1194 => 544,  1183 => 538,  1172 => 532,  1161 => 526,  1156 => 523,  1152 => 522,  1149 => 521,  1145 => 520,  1143 => 519,  1130 => 511,  1119 => 505,  1114 => 502,  1110 => 501,  1107 => 500,  1103 => 499,  1101 => 498,  1088 => 490,  1077 => 484,  1072 => 481,  1068 => 480,  1065 => 479,  1061 => 478,  1059 => 477,  1046 => 469,  1035 => 463,  1030 => 460,  1026 => 459,  1023 => 458,  1019 => 457,  1017 => 456,  1008 => 450,  1005 => 449,  1001 => 448,  998 => 447,  994 => 446,  992 => 445,  985 => 440,  983 => 439,  981 => 438,  977 => 436,  974 => 435,  963 => 428,  954 => 424,  945 => 420,  940 => 417,  936 => 416,  933 => 415,  929 => 414,  927 => 413,  913 => 404,  904 => 400,  895 => 396,  886 => 392,  877 => 388,  872 => 385,  868 => 384,  865 => 383,  861 => 382,  859 => 381,  845 => 372,  836 => 368,  827 => 364,  822 => 361,  818 => 360,  815 => 359,  811 => 358,  809 => 357,  795 => 348,  786 => 344,  777 => 340,  772 => 337,  768 => 336,  765 => 335,  761 => 334,  759 => 333,  745 => 324,  736 => 320,  727 => 316,  718 => 312,  713 => 309,  709 => 308,  706 => 307,  702 => 306,  700 => 305,  687 => 297,  676 => 291,  671 => 288,  667 => 287,  664 => 286,  660 => 285,  658 => 284,  645 => 276,  634 => 270,  629 => 267,  625 => 266,  622 => 265,  618 => 264,  616 => 263,  603 => 255,  592 => 249,  581 => 243,  576 => 240,  572 => 239,  569 => 238,  565 => 237,  563 => 236,  550 => 228,  539 => 222,  534 => 219,  530 => 218,  527 => 217,  523 => 216,  521 => 215,  508 => 207,  497 => 201,  492 => 198,  488 => 197,  485 => 196,  481 => 195,  479 => 194,  470 => 188,  467 => 187,  463 => 186,  460 => 185,  456 => 184,  454 => 183,  447 => 178,  445 => 177,  443 => 176,  439 => 174,  436 => 172,  433 => 171,  427 => 170,  422 => 169,  418 => 168,  413 => 167,  410 => 166,  408 => 165,  405 => 164,  402 => 163,  400 => 162,  389 => 154,  386 => 153,  381 => 152,  377 => 151,  375 => 150,  370 => 147,  362 => 143,  358 => 142,  355 => 141,  351 => 140,  349 => 139,  338 => 132,  334 => 131,  331 => 130,  329 => 129,  324 => 126,  322 => 125,  321 => 124,  318 => 123,  312 => 120,  307 => 119,  303 => 118,  301 => 117,  296 => 114,  290 => 110,  285 => 109,  281 => 108,  278 => 107,  274 => 106,  272 => 105,  263 => 99,  258 => 98,  254 => 97,  251 => 96,  247 => 95,  245 => 94,  236 => 88,  231 => 87,  227 => 86,  224 => 85,  220 => 84,  218 => 83,  209 => 77,  204 => 76,  199 => 75,  195 => 74,  193 => 73,  184 => 67,  179 => 66,  174 => 65,  170 => 64,  168 => 63,  158 => 57,  153 => 56,  151 => 55,  144 => 54,  142 => 53,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/contrato/contratoEdit.twig", "/home/franco/proyectos/php/jass/resources/views/administration/contrato/contratoEdit.twig");
    }
}
