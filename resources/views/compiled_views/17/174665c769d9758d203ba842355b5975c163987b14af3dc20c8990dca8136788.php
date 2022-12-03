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

/* /administration/ingreso/ingresoNewPagoRecibo.twig */
class __TwigTemplate_29fa8eeb3cdedb098fcc4413a3f36d05e36ea759eb86c492b3d2b2b781cab362 extends \Twig\Template
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
        $context["menuLItem"] = "ingreso";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/ingreso/ingresoNewPagoRecibo.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formNuevoPagoRecibo\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/ingreso/recibo/create\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-file-contract mr-3\"></i>Pago de recibo
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
      \t
  \t\t<div class=\"f_divwithbartop f_divwithbarbottom pr-3\">
          \t<div class=\"row\">
          \t\t<div class=\"col-sm-12 col-md-8 col-xl-7\">
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpCliente\">Cliente DNI / RUC</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 54
        $context["clienteDocumento"] = "";
        // line 55
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 55)) {
            $context["clienteDocumento"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 55, $this->source); })()), "cliente", [], "any", false, false, false, 55), "CLI_DOCUMENTO", [], "any", false, false, false, 55);
            // line 56
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 56)) {
            // line 57
            echo "                        \t    ";
            $context["clienteDocumento"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 57, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 57), "clienteDocumento", [], "any", false, false, false, 57);
        }
        // line 58
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpClienteDocumento\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["clienteDocumento"]) || array_key_exists("clienteDocumento", $context) ? $context["clienteDocumento"] : (function () { throw new RuntimeError('Variable "clienteDocumento" does not exist.', 58, $this->source); })()), "html", null, true);
        echo "'>
                \t\t\t<input type=\"hidden\" name=\"clienteDocumento\" value='";
        // line 59
        echo twig_escape_filter($this->env, (isset($context["clienteDocumento"]) || array_key_exists("clienteDocumento", $context) ? $context["clienteDocumento"] : (function () { throw new RuntimeError('Variable "clienteDocumento" does not exist.', 59, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpClienteNombre\">Cliente nombre</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 65
        $context["clienteNombre"] = "";
        // line 66
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cliente", [], "any", true, true, false, 66)) {
            $context["clienteNombre"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 66, $this->source); })()), "cliente", [], "any", false, false, false, 66), "CLI_NOMBRES", [], "any", false, false, false, 66);
            // line 67
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 67)) {
            // line 68
            echo "                        \t    ";
            $context["clienteNombre"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 68, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 68), "clienteNombre", [], "any", false, false, false, 68);
        }
        // line 69
        echo "                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpClienteNombre\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["clienteNombre"]) || array_key_exists("clienteNombre", $context) ? $context["clienteNombre"] : (function () { throw new RuntimeError('Variable "clienteNombre" does not exist.', 69, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"clienteNombre\" value='";
        // line 70
        echo twig_escape_filter($this->env, (isset($context["clienteNombre"]) || array_key_exists("clienteNombre", $context) ? $context["clienteNombre"] : (function () { throw new RuntimeError('Variable "clienteNombre" does not exist.', 70, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpClienteNombre\">Predio dirección</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 76
        $context["predioDireccion"] = "";
        // line 77
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "predio", [], "any", true, true, false, 77)) {
            $context["predioDireccion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 77, $this->source); })()), "predio", [], "any", false, false, false, 77), "PRE_DIRECCION", [], "any", false, false, false, 77);
            // line 78
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 78)) {
            // line 79
            echo "                        \t    ";
            $context["predioDireccion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 79, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 79), "predioDireccion", [], "any", false, false, false, 79);
        }
        // line 80
        echo "                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpPredioDireccion\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["predioDireccion"]) || array_key_exists("predioDireccion", $context) ? $context["predioDireccion"] : (function () { throw new RuntimeError('Variable "predioDireccion" does not exist.', 80, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"predioDireccion\" value='";
        // line 81
        echo twig_escape_filter($this->env, (isset($context["predioDireccion"]) || array_key_exists("predioDireccion", $context) ? $context["predioDireccion"] : (function () { throw new RuntimeError('Variable "predioDireccion" does not exist.', 81, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpReciboCodigo\">Recibo ref.</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 87
        $context["reciboCodigo"] = "";
        // line 88
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "recibo", [], "any", true, true, false, 88)) {
            $context["reciboCodigo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 88, $this->source); })()), "recibo", [], "any", false, false, false, 88), "RBO_CODIGO", [], "any", false, false, false, 88);
            // line 89
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 89)) {
            // line 90
            echo "                        \t    ";
            $context["reciboCodigo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 90, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 90), "reciboCodigo", [], "any", false, false, false, 90);
        }
        // line 91
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpReciboCodigo\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["reciboCodigo"]) || array_key_exists("reciboCodigo", $context) ? $context["reciboCodigo"] : (function () { throw new RuntimeError('Variable "reciboCodigo" does not exist.', 91, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"reciboCodigo\" value='";
        // line 92
        echo twig_escape_filter($this->env, (isset($context["reciboCodigo"]) || array_key_exists("reciboCodigo", $context) ? $context["reciboCodigo"] : (function () { throw new RuntimeError('Variable "reciboCodigo" does not exist.', 92, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpReciboPeriodo\">Periodo</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 98
        $context["reciboPeriodo"] = "";
        // line 99
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "recibo", [], "any", true, true, false, 99)) {
            $context["reciboPeriodo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 99, $this->source); })()), "recibo", [], "any", false, false, false, 99), "RBO_PERIODO", [], "any", false, false, false, 99);
            // line 100
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 100)) {
            // line 101
            echo "                        \t    ";
            $context["reciboPeriodo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 101, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 101), "reciboPeriodo", [], "any", false, false, false, 101);
        }
        // line 102
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpReciboPeriodo\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["reciboPeriodo"]) || array_key_exists("reciboPeriodo", $context) ? $context["reciboPeriodo"] : (function () { throw new RuntimeError('Variable "reciboPeriodo" does not exist.', 102, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"reciboPeriodo\" value='";
        // line 103
        echo twig_escape_filter($this->env, (isset($context["reciboPeriodo"]) || array_key_exists("reciboPeriodo", $context) ? $context["reciboPeriodo"] : (function () { throw new RuntimeError('Variable "reciboPeriodo" does not exist.', 103, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpReciboEstado\">Estado</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 109
        $context["reciboEstado"] = "";
        // line 110
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "recibo", [], "any", true, true, false, 110)) {
            $context["reciboEstado"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 110, $this->source); })()), "recibo", [], "any", false, false, false, 110), "RBO_ESTADO", [], "any", false, false, false, 110);
            // line 111
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 111)) {
            // line 112
            echo "                        \t    ";
            $context["reciboEstado"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 112, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 112), "reciboEstado", [], "any", false, false, false, 112);
        }
        // line 113
        echo "                        \t    
                    \t    ";
        // line 114
        if ((isset($context["reciboEstado"]) || array_key_exists("reciboEstado", $context))) {
            // line 115
            echo "                          \t\t";
            if (((isset($context["reciboEstado"]) || array_key_exists("reciboEstado", $context) ? $context["reciboEstado"] : (function () { throw new RuntimeError('Variable "reciboEstado" does not exist.', 115, $this->source); })()) == 1)) {
                // line 116
                echo "                                \t<span class=\"badge badge-warning\">";
                echo "Pendiente";
                echo "</span>
                                ";
            } elseif ((            // line 117
(isset($context["reciboEstado"]) || array_key_exists("reciboEstado", $context) ? $context["reciboEstado"] : (function () { throw new RuntimeError('Variable "reciboEstado" does not exist.', 117, $this->source); })()) == 2)) {
                // line 118
                echo "                                \t<span class=\"badge badge-success\">";
                echo "Pagado";
                echo "</span>
                                ";
            } elseif ((            // line 119
(isset($context["reciboEstado"]) || array_key_exists("reciboEstado", $context) ? $context["reciboEstado"] : (function () { throw new RuntimeError('Variable "reciboEstado" does not exist.', 119, $this->source); })()) == 3)) {
                // line 120
                echo "                                \t<span class=\"badge badge-info\">";
                echo "Refinanciado";
                echo "</span>
                                ";
            }
            // line 122
            echo "                          \t";
        }
        // line 123
        echo "                        \t<input type=\"hidden\" name=\"reciboEstado\" value='";
        echo twig_escape_filter($this->env, (isset($context["reciboEstado"]) || array_key_exists("reciboEstado", $context) ? $context["reciboEstado"] : (function () { throw new RuntimeError('Variable "reciboEstado" does not exist.', 123, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpReciboUltimoDiaPago\">Ultimo día de pago</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 129
        $context["reciboUltDiaPago"] = "";
        // line 130
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "recibo", [], "any", true, true, false, 130)) {
            // line 131
            echo "                        \t    ";
            $context["reciboUltDiaPago"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 131, $this->source); })()), "recibo", [], "any", false, false, false, 131), "RBO_ULT_DIA_PAGO", [], "any", false, false, false, 131);
            // line 132
            echo "                        \t    ";
            if ( !twig_test_empty((isset($context["reciboUltDiaPago"]) || array_key_exists("reciboUltDiaPago", $context) ? $context["reciboUltDiaPago"] : (function () { throw new RuntimeError('Variable "reciboUltDiaPago" does not exist.', 132, $this->source); })()))) {
                $context["reciboUltDiaPago"] = twig_date_format_filter($this->env, (isset($context["reciboUltDiaPago"]) || array_key_exists("reciboUltDiaPago", $context) ? $context["reciboUltDiaPago"] : (function () { throw new RuntimeError('Variable "reciboUltDiaPago" does not exist.', 132, $this->source); })()), "d/m/Y");
            }
            // line 133
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 133)) {
            // line 134
            echo "                        \t    ";
            $context["reciboUltDiaPago"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 134, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 134), "reciboUltDiaPago", [], "any", false, false, false, 134);
        }
        // line 135
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpReciboUltDiaPago\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["reciboUltDiaPago"]) || array_key_exists("reciboUltDiaPago", $context) ? $context["reciboUltDiaPago"] : (function () { throw new RuntimeError('Variable "reciboUltDiaPago" does not exist.', 135, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"reciboUltDiaPago\" value='";
        // line 136
        echo twig_escape_filter($this->env, (isset($context["reciboUltDiaPago"]) || array_key_exists("reciboUltDiaPago", $context) ? $context["reciboUltDiaPago"] : (function () { throw new RuntimeError('Variable "reciboUltDiaPago" does not exist.', 136, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpReciboFechaCorte\">Fecha corte</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 142
        $context["reciboFechaCorte"] = "";
        // line 143
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "recibo", [], "any", true, true, false, 143)) {
            // line 144
            echo "                        \t    ";
            $context["reciboFechaCorte"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 144, $this->source); })()), "recibo", [], "any", false, false, false, 144), "RBO_FECHA_CORTE", [], "any", false, false, false, 144);
            // line 145
            echo "                                ";
            if ( !twig_test_empty((isset($context["reciboFechaCorte"]) || array_key_exists("reciboFechaCorte", $context) ? $context["reciboFechaCorte"] : (function () { throw new RuntimeError('Variable "reciboFechaCorte" does not exist.', 145, $this->source); })()))) {
                $context["reciboFechaCorte"] = twig_date_format_filter($this->env, (isset($context["reciboFechaCorte"]) || array_key_exists("reciboFechaCorte", $context) ? $context["reciboFechaCorte"] : (function () { throw new RuntimeError('Variable "reciboFechaCorte" does not exist.', 145, $this->source); })()), "d/m/Y");
            }
            // line 146
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 146)) {
            // line 147
            echo "                        \t    ";
            $context["reciboFechaCorte"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 147, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 147), "reciboFechaCorte", [], "any", false, false, false, 147);
        }
        // line 148
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpReciboFechaCorte\" disabled value='";
        echo twig_escape_filter($this->env, (isset($context["reciboFechaCorte"]) || array_key_exists("reciboFechaCorte", $context) ? $context["reciboFechaCorte"] : (function () { throw new RuntimeError('Variable "reciboFechaCorte" does not exist.', 148, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"reciboFechaCorte\" value='";
        // line 149
        echo twig_escape_filter($this->env, (isset($context["reciboFechaCorte"]) || array_key_exists("reciboFechaCorte", $context) ? $context["reciboFechaCorte"] : (function () { throw new RuntimeError('Variable "reciboFechaCorte" does not exist.', 149, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-xl-2 f_field\" for=\"inpMontoTotal\">Total a pagar</label>
                        <div class=\"col-12 col-md-9 col-xl-10\">
                        \t";
        // line 155
        $context["montoTotal"] = "";
        // line 156
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "recibo", [], "any", true, true, false, 156)) {
            $context["montoTotal"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 156, $this->source); })()), "recibo", [], "any", false, false, false, 156), "RBO_MNTO_TOTAL", [], "any", false, false, false, 156);
            // line 157
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 157)) {
            // line 158
            echo "                        \t    ";
            $context["montoTotal"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 158, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 158), "montoTotal", [], "any", false, false, false, 158);
        }
        // line 159
        echo "                    \t    <span>S/. </span>
                        \t<input type=\"text\" class=\"f_minwidth100\" id=\"inpMontoTotal\" disabled value='";
        // line 160
        echo twig_escape_filter($this->env, (isset($context["montoTotal"]) || array_key_exists("montoTotal", $context) ? $context["montoTotal"] : (function () { throw new RuntimeError('Variable "montoTotal" does not exist.', 160, $this->source); })()), "html", null, true);
        echo "'>
                        \t<input type=\"hidden\" name=\"montoTotal\" value='";
        // line 161
        echo twig_escape_filter($this->env, (isset($context["montoTotal"]) || array_key_exists("montoTotal", $context) ? $context["montoTotal"] : (function () { throw new RuntimeError('Variable "montoTotal" does not exist.', 161, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
          \t\t</div>
          \t\t
          \t\t<div class=\"col-sm-12 col-md-4 col-xl-5\">
          \t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 f_fieldrequired\" for=\"inpMontoRecibido\">Monto recibido</label>
                        <div class=\"col-12\">
                            ";
        // line 170
        $context["montoRecibido"] = "";
        // line 171
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 171)) {
            // line 172
            echo "                        \t    ";
            $context["montoRecibido"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 172, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 172), "montoRecibido", [], "any", false, false, false, 172);
        }
        // line 173
        echo "                        \t<div style=\"background-color:#b9ceac\" class=\"d-inline-block\">
                        \t\t<span class=\"pl-1\">S/. </span>
                        \t\t<input type=\"text\" class=\"f_minwidth100\" id=\"inpMontoRecibido\" name=\"montoRecibido\" required 
                        \t\t\tstyle=\"background-color:#b9ceac\" value='";
        // line 176
        echo twig_escape_filter($this->env, (isset($context["montoRecibido"]) || array_key_exists("montoRecibido", $context) ? $context["montoRecibido"] : (function () { throw new RuntimeError('Variable "montoRecibido" does not exist.', 176, $this->source); })()), "html", null, true);
        echo "'>
                        \t</div>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 f_fieldrequired\" for=\"cmbComprobaneTipo\">Comprobante</label>
                        <div class=\"col-12\">
                        \t<select name=\"comprobanteTipo\" class=\"f_minwidth200\" id=\"cmbComprobanteTipo\" required>
                            \t<option value=\"";
        // line 184
        echo 1;
        echo "\"
                        \t\t\t\t";
        // line 185
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 185) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 186
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 186, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 186), "comprobanteTipo", [], "any", false, false, false, 186) == 1))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 187
        echo "TICKED";
        echo "
                \t\t\t\t</option>
                \t\t\t\t<option value=\"";
        // line 189
        echo 2;
        echo "\"
                        \t\t\t\t";
        // line 190
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 190) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 191
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 191, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 191), "comprobanteTipo", [], "any", false, false, false, 191) == 2))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 192
        echo "BOLETA";
        echo "
                \t\t\t\t</option>
                \t\t\t\t<option value=\"";
        // line 194
        echo 3;
        echo "\"
                        \t\t\t\t";
        // line 195
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 195) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 196
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 196, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 196), "comprobanteTipo", [], "any", false, false, false, 196) == 3))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 197
        echo "FACTURA";
        echo "
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 f_fieldrequired\" for=\"inpComprobanteNro\">Nro. Comprobante</label>
                        <div class=\"col-12\">
                            ";
        // line 205
        $context["comprobanteNro"] = "";
        // line 206
        echo "                        \t";
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 206) && twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", false, true, false, 206), "comprobanteNro", [], "any", true, true, false, 206))) {
            // line 207
            echo "                        \t    ";
            $context["comprobanteNro"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 207, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 207), "comprobanteNro", [], "any", false, false, false, 207);
        }
        // line 208
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpComprobanteNro\" name=\"comprobanteNro\" required 
                        \t\t\tvalue='";
        // line 209
        echo twig_escape_filter($this->env, (isset($context["comprobanteNro"]) || array_key_exists("comprobanteNro", $context) ? $context["comprobanteNro"] : (function () { throw new RuntimeError('Variable "comprobanteNro" does not exist.', 209, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 f_fieldrequired\" for=\"cmbCaja\">Caja</label>
                        <div class=\"col-12\">
                            <select name=\"caja\" class=\"f_minwidth200\" id=\"cmbCaja\" required>
                            \t";
        // line 216
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "cajas", [], "any", true, true, false, 216)) {
            // line 217
            echo "                                    ";
            $context["selectedCaja"] = false;
            // line 218
            echo "                                    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 218, $this->source); })()), "cajas", [], "any", false, false, false, 218));
            foreach ($context['_seq'] as $context["_key"] => $context["caja"]) {
                // line 219
                echo "                                    \t<option value=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_CODIGO", [], "any", false, false, false, 219), "html", null, true);
                echo "\"
                                \t\t\t\t";
                // line 220
                if ((( !(isset($context["selectedCaja"]) || array_key_exists("selectedCaja", $context) ? $context["selectedCaja"] : (function () { throw new RuntimeError('Variable "selectedCaja" does not exist.', 220, $this->source); })()) && twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoPagoRecibo", [], "any", true, true, false, 220)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,                 // line 221
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 221, $this->source); })()), "formNuevoPagoRecibo", [], "any", false, false, false, 221), "caja", [], "any", false, false, false, 221) == twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_CODIGO", [], "any", false, false, false, 221)))) {
                    // line 222
                    echo "                                \t\t\t\t\t";
                    echo "selected";
                    $context["selectedCaja"] = true;
                    // line 223
                    echo "                                                ";
                }
                echo ">
                            \t\t\t\t";
                // line 224
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_NOMBRE", [], "any", false, false, false, 224), "html", null, true);
                echo "
                        \t\t\t\t</option>
                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['caja'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 227
            echo "                                ";
        }
        // line 228
        echo "                            </select>
                        </div>
                    </div>
          \t\t</div>
          \t\t
          \t</div>
      \t</div><!-- /.card-body -->
  \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardfooter f_cardfooteractions text-center\">
      \t\t\t\t";
        // line 239
        list($context["styleBtnPagarRecibo"], $context["titleBtnPagarRecibo"], $context["typeBtnPagarRecibo"]) =         ["f_buttonaction", "", "submit"];
        // line 240
        echo "    \t\t\t\t";
        if (((isset($context["reciboEstado"]) || array_key_exists("reciboEstado", $context) ? $context["reciboEstado"] : (function () { throw new RuntimeError('Variable "reciboEstado" does not exist.', 240, $this->source); })()) == 2)) {
            // line 241
            echo "    \t\t\t\t    ";
            $context["styleBtnPagarRecibo"] = "f_buttonactionrefused";
            // line 242
            echo "    \t\t\t\t    ";
            $context["titleBtnPagarRecibo"] = "Desactivado porque el recibo ya está pagado";
            // line 243
            echo "    \t\t\t\t    ";
            $context["typeBtnPagarRecibo"] = "button";
            // line 244
            echo "                    ";
        } elseif (((isset($context["reciboEstado"]) || array_key_exists("reciboEstado", $context) ? $context["reciboEstado"] : (function () { throw new RuntimeError('Variable "reciboEstado" does not exist.', 244, $this->source); })()) == 3)) {
            // line 245
            echo "    \t\t\t\t    ";
            $context["styleBtnPagarRecibo"] = "f_buttonactionrefused";
            // line 246
            echo "    \t\t\t\t    ";
            $context["titleBtnPagarRecibo"] = "Desactivado porque el recibo fue refinanciado";
            // line 247
            echo "    \t\t\t\t    ";
            $context["typeBtnPagarRecibo"] = "button";
            // line 248
            echo "    \t\t\t    ";
        }
        // line 249
        echo "        \t\t\t<button type=\"";
        echo twig_escape_filter($this->env, (isset($context["typeBtnPagarRecibo"]) || array_key_exists("typeBtnPagarRecibo", $context) ? $context["typeBtnPagarRecibo"] : (function () { throw new RuntimeError('Variable "typeBtnPagarRecibo" does not exist.', 249, $this->source); })()), "html", null, true);
        echo "\" class=\"f_button ";
        echo twig_escape_filter($this->env, (isset($context["styleBtnPagarRecibo"]) || array_key_exists("styleBtnPagarRecibo", $context) ? $context["styleBtnPagarRecibo"] : (function () { throw new RuntimeError('Variable "styleBtnPagarRecibo" does not exist.', 249, $this->source); })()), "html", null, true);
        echo " classfortooltip\"
        \t\t\t\t\t title=\"";
        // line 250
        echo twig_escape_filter($this->env, (isset($context["titleBtnPagarRecibo"]) || array_key_exists("titleBtnPagarRecibo", $context) ? $context["titleBtnPagarRecibo"] : (function () { throw new RuntimeError('Variable "titleBtnPagarRecibo" does not exist.', 250, $this->source); })()), "html", null, true);
        echo "\">Registra pago</button>
        \t\t\t<a href=\"";
        // line 251
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 251, $this->source); })()), "html", null, true);
        echo "/recibo/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 257
        echo "  
</div><!-- /.card -->


";
    }

    // line 263
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 264
        echo "
    ";
        // line 265
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formNuevoPagoRecibo').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        f_select2(\"#cmbComprobanteTipo\");
        f_select2(\"#cmbCaja\");
        
        
        if(\$(\"#cmbComprobanteTipo\").val() != 1){
    \t\t\$(\"#inpComprobanteNro\").prop(\"disabled\", false);
    \t}else{
    \t\t\$(\"#inpComprobanteNro\").val(\"\");
    \t\t\$(\"#inpComprobanteNro\").prop(\"disabled\", true);
    \t}
        
        \$(\"#cmbComprobanteTipo\").change(function(){
        \tif(\$(\"#cmbComprobanteTipo\").val() != 1){
        \t\t\$(\"#inpComprobanteNro\").prop(\"disabled\", false);
        \t}else{
        \t\t\$(\"#inpComprobanteNro\").val(\"\");
        \t\t\$(\"#inpComprobanteNro\").prop(\"disabled\", true);
        \t}
        });
        
\t</script>
";
    }

    public function getTemplateName()
    {
        return "/administration/ingreso/ingresoNewPagoRecibo.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  648 => 265,  645 => 264,  641 => 263,  633 => 257,  625 => 251,  621 => 250,  614 => 249,  611 => 248,  608 => 247,  605 => 246,  602 => 245,  599 => 244,  596 => 243,  593 => 242,  590 => 241,  587 => 240,  585 => 239,  572 => 228,  569 => 227,  560 => 224,  555 => 223,  551 => 222,  549 => 221,  548 => 220,  543 => 219,  538 => 218,  535 => 217,  533 => 216,  523 => 209,  520 => 208,  516 => 207,  513 => 206,  511 => 205,  500 => 197,  494 => 196,  493 => 195,  489 => 194,  484 => 192,  478 => 191,  477 => 190,  473 => 189,  468 => 187,  462 => 186,  461 => 185,  457 => 184,  446 => 176,  441 => 173,  437 => 172,  434 => 171,  432 => 170,  420 => 161,  416 => 160,  413 => 159,  409 => 158,  406 => 157,  402 => 156,  400 => 155,  391 => 149,  386 => 148,  382 => 147,  379 => 146,  374 => 145,  371 => 144,  368 => 143,  366 => 142,  357 => 136,  352 => 135,  348 => 134,  345 => 133,  340 => 132,  337 => 131,  334 => 130,  332 => 129,  322 => 123,  319 => 122,  313 => 120,  311 => 119,  306 => 118,  304 => 117,  299 => 116,  296 => 115,  294 => 114,  291 => 113,  287 => 112,  284 => 111,  280 => 110,  278 => 109,  269 => 103,  264 => 102,  260 => 101,  257 => 100,  253 => 99,  251 => 98,  242 => 92,  237 => 91,  233 => 90,  230 => 89,  226 => 88,  224 => 87,  215 => 81,  210 => 80,  206 => 79,  203 => 78,  199 => 77,  197 => 76,  188 => 70,  183 => 69,  179 => 68,  176 => 67,  172 => 66,  170 => 65,  161 => 59,  156 => 58,  152 => 57,  149 => 56,  145 => 55,  143 => 54,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/ingreso/ingresoNewPagoRecibo.twig", "/home/franco/proyectos/php/jass/resources/views/administration/ingreso/ingresoNewPagoRecibo.twig");
    }
}
