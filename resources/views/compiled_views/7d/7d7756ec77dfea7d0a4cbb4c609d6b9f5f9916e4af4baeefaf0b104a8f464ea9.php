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

/* /administration/contrato/contratoNew.twig */
class __TwigTemplate_87426ded7b561ee8b110d2c3faa9910ccebfc5ae17bd414269ce6eac1d72a830 extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["contrato", "nuevo"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/contrato/contratoNew.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formNuevoContrato\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/contrato/create\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-file-contract mr-3\"></i>Nuevo contrato
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
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpPredio\">Código de predio</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpPredio\" name=\"predio\" required
                        \t\t\tvalue='";
        // line 56
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 56), "predio", [], "any", true, true, false, 56)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 56, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 56), "predio", [], "any", false, false, false, 56), "html", null, true);
        }
        echo "'>
                \t\t\t<button type=\"button\" class=\"f_btnflat\" name=\"btnBuscarPredio\" id=\"btnBuscarPredio\">
                \t\t\t\t<span class=\"fas fa-search\"></span>
            \t\t\t\t</button>
                \t\t\t<span><i class=\"fas fa-spinner f_classforrotatespinner d-none\" id=\"spinnerBuscarPredio\"></i></span>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpPredioCalle\">Predio calle</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth400\" id=\"inpPredioCalle\" name=\"predioCalle\" required disabled value=''>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpPredioDireccion\">Predio dirección</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth400\" id=\"inpPredioDireccion\" name=\"predioDireccion\" required disabled value=''>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpClienteDocumento\">Cliente DNI / RUC</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpClienteDocumento\" name=\"clienteDocumento\" required disabled value=''>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"inpClienteNombre\">Cliente nombre</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth400\" id=\"inpClienteNombre\" name=\"clienteNombre\" required disabled value=''>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbTipoUsoPredio\">Tipo de uso del predio</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"tipoUsoPredio\" class=\"f_minwidth300\" id=\"cmbTipoUsoPredio\" required>
                            \t";
        // line 91
        $context["selectedTipoUsoPredio"] = false;
        // line 92
        echo "                                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 92, $this->source); })()), "tiposUsoPredio", [], "any", false, false, false, 92));
        foreach ($context['_seq'] as $context["_key"] => $context["tipoUsoPredio"]) {
            // line 93
            echo "                                \t<option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_CODIGO", [], "any", false, false, false, 93), "html", null, true);
            echo "\"
                            \t\t\t\t";
            // line 94
            if ((( !(isset($context["selectedTipoUsoPredio"]) || array_key_exists("selectedTipoUsoPredio", $context) ? $context["selectedTipoUsoPredio"] : (function () { throw new RuntimeError('Variable "selectedTipoUsoPredio" does not exist.', 94, $this->source); })()) && twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", true, true, false, 94)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 95
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 95, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 95), "tipoUsoPredio", [], "any", false, false, false, 95) == twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_CODIGO", [], "any", false, false, false, 95)))) {
                // line 96
                echo "                            \t\t\t\t\t";
                echo "selected";
                $context["selectedTipoUsoPredio"] = true;
                // line 97
                echo "                                            ";
            }
            echo ">
                        \t\t\t\t";
            // line 98
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoUsoPredio"], "TUP_NOMBRE", [], "any", false, false, false, 98), "html", null, true);
            echo "
                    \t\t\t\t</option>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tipoUsoPredio'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 101
        echo "                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbServicios\">Servicios</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<i class=\"fas fa-cube\"></i>
                            <select name=\"servicios[]\" class=\"f_minwidth400\" id=\"cmbServicios\" multiple required>
                            ";
        // line 109
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 109, $this->source); })()), "servicios", [], "any", false, false, false, 109));
        foreach ($context['_seq'] as $context["_key"] => $context["servicio"]) {
            // line 110
            echo "                            \t<option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_CODIGO", [], "any", false, false, false, 110), "html", null, true);
            echo "\"
                        \t\t\t\t";
            // line 111
            if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", true, true, false, 111) && twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 111), "servicios", [], "any", true, true, false, 111))) {
                // line 112
                echo "                    \t\t\t\t        ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 112, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 112), "servicios", [], "any", false, false, false, 112));
                foreach ($context['_seq'] as $context["_key"] => $context["selectedServicio"]) {
                    // line 113
                    echo "                    \t\t\t\t        \t";
                    if (($context["selectedServicio"] == twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_CODIGO", [], "any", false, false, false, 113))) {
                        // line 114
                        echo "                    \t\t\t\t        \t    ";
                        echo "selected";
                        echo "
                    \t\t\t\t        \t";
                    }
                    // line 116
                    echo "                    \t\t\t\t        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['selectedServicio'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 117
                echo "                    \t\t\t\t\t";
            }
            echo ">
                    \t\t\t\t";
            // line 118
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["servicio"], "SRV_NOMBRE", [], "any", false, false, false, 118), "html", null, true);
            echo "
                \t\t\t\t</option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['servicio'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 121
        echo "                            </select>
                            <button type=\"button\" class=\"f_btngenerico\" style=\"padding:4px 8px; font-size:.8rem\" 
                            \t\tdata-toggle=\"modal\" data-target=\"#modalMasDetalleServicio\" id=\"btnMasDetalleServicios\">
                \t\t\t\tMás detalles
            \t\t\t\t</button>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbEstadoContrato\">Estado contrato</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<select name=\"estado\" class=\"f_minwidth300\" id=\"cmbEstadoContrato\" required>
                            \t<option value=\"";
        // line 132
        echo 0;
        echo "\"
                        \t\t\t\t";
        // line 133
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", true, true, false, 133) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 134
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 134, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 134), "estado", [], "any", false, false, false, 134) == 0))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 135
        echo "TRAMITE";
        echo "
                \t\t\t\t</option>
                \t\t\t\t<option value=\"";
        // line 137
        echo 1;
        echo "\"
                        \t\t\t\t";
        // line 138
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", true, true, false, 138) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 139
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 139, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 139), "estado", [], "any", false, false, false, 139) == 1))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 140
        echo "ACTIVO";
        echo "
\t\t\t\t\t\t\t\t</option>
\t\t\t\t\t\t\t\t<option value=\"";
        // line 142
        echo 5;
        echo "\"
\t\t\t\t\t\t\t\t\t";
        // line 143
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", true, true, false, 143) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 144
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 144, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 144), "estado", [], "any", false, false, false, 144) == 5))) {
            echo "selected";
        }
        echo ">
\t\t\t\t\t\t\t\t\t";
        // line 145
        echo "PASIVO";
        echo "
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"txaObservacion\">Observación</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 153
        $context["observacion"] = "";
        // line 154
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 154), "observacion", [], "any", true, true, false, 154)) {
            // line 155
            echo "                        \t    ";
            $context["observacion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 155, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 155), "observacion", [], "any", false, false, false, 155);
        }
        // line 156
        echo "                        \t<textarea class=\"f_minwidth400\" id=\"txaObservacion\" rows=\"2\" maxlength=\"256\" 
                        \t\t\t\tname=\"observacion\">";
        // line 157
        echo twig_escape_filter($this->env, (isset($context["observacion"]) || array_key_exists("observacion", $context) ? $context["observacion"] : (function () { throw new RuntimeError('Variable "observacion" does not exist.', 157, $this->source); })()), "html", null, true);
        echo "</textarea>
                        </div>
                    </div>
      \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-body -->
  \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardfooter f_cardfooteractions text-center\">
        \t\t\t<button type=\"submit\" class=\"f_button f_buttonaction\">Guardar</button>
        \t\t\t<a href=\"";
        // line 168
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 168, $this->source); })()), "html", null, true);
        echo "/contrato/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
      \t
      \t
      \t";
        // line 175
        echo "        <div class=\"modal fade f_modal\" id=\"modalMasDetalleServicio\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-lg\" role=\"document\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <span class=\"modal-title\">Detalle de servicios</span>
                    </div>
                    <div class=\"modal-body\">
                    
                    \t";
        // line 184
        echo "                    \t<div id=\"divNuevoContratoMasDetalles\">
                    \t
                            ";
        // line 187
        echo "                    \t\t<div id=\"divNuevoContratoMasDetallesAgua\">
                        \t\t<h5 style=\"color:#23878c\" class=\"mb-4\">Servicio de Agua</h5>
                        \t\t<div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"inpAguaFechaInstalacion\">Fecha de instalación</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t";
        // line 192
        $context["aguaFechaInstalacion"] = "";
        // line 193
        echo "                                    \t";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 193), "aguaFechaInstalacion", [], "any", true, true, false, 193)) {
            // line 194
            echo "                                    \t    ";
            $context["aguaFechaInstalacion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 194, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 194), "aguaFechaInstalacion", [], "any", false, false, false, 194);
        }
        // line 195
        echo "                                    \t<input type=\"date\" class=\"f_minwidth300\" id=\"inpAguaFechaInstalacion\" name=\"aguaFechaInstalacion\" 
                                    \t\t\tvalue=\"";
        // line 196
        echo twig_escape_filter($this->env, (isset($context["aguaFechaInstalacion"]) || array_key_exists("aguaFechaInstalacion", $context) ? $context["aguaFechaInstalacion"] : (function () { throw new RuntimeError('Variable "aguaFechaInstalacion" does not exist.', 196, $this->source); })()), "html", null, true);
        echo "\">
                                    </div>
                                </div>
                        \t\t<div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Característica de la conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionCaracteristica\" 
                                            \t\tid=\"inpAguaConexionCaracteristicaSC\" value=\"sin caja\"
                                            \t\t";
        // line 205
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 205), "aguaConexionCaracteristica", [], "any", true, true, false, 205) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 206
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 206, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 206), "aguaConexionCaracteristica", [], "any", false, false, false, 206) == "sin caja"))) {
            // line 207
            echo "                                    \t\t\t\t\t";
            echo "checked";
            echo "
                                                    ";
        }
        // line 208
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaConexionCaracteristicaSC\">Sin caja</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionCaracteristica\" 
                                            \t\tid=\"inpAguaConexionCaracteristicaCC\" value=\"con caja\"
                                            \t\t";
        // line 214
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 214), "aguaConexionCaracteristica", [], "any", true, true, false, 214) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 215
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 215, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 215), "aguaConexionCaracteristica", [], "any", false, false, false, 215) == "con caja"))) {
            // line 216
            echo "                                    \t\t\t\t\t";
            echo "checked";
            echo "
                                                    ";
        }
        // line 217
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaConexionCaracteristicaCC\">Con caja</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Diametro de la conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionDiametro\" 
                                            \t\tid=\"inpAguaConexionDiametro1-2\" value=\"1/2\"
                                            \t\t";
        // line 228
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 228), "aguaConexionDiametro", [], "any", true, true, false, 228) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 229
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 229, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 229), "aguaConexionDiametro", [], "any", false, false, false, 229) == "1/2"))) {
            // line 230
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 231
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaConexionDiametro1-2\">1/2\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionDiametro\" 
                                            \t\tid=\"inpAguaConexionDiametro3-4\" value=\"3/4\"
                                            \t\t";
        // line 237
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 237), "aguaConexionDiametro", [], "any", true, true, false, 237) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 238
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 238, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 238), "aguaConexionDiametro", [], "any", false, false, false, 238) == "3/4"))) {
            // line 239
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 240
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaConexionDiametro3-4\">3/4\"</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Diametro de la red</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaDiametroRed\" 
                                            \t\tid=\"inpAguaDiametroRed2\" value=\"2\"
                                            \t\t";
        // line 251
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 251), "aguaDiametroRed", [], "any", true, true, false, 251) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 252
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 252, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 252), "aguaDiametroRed", [], "any", false, false, false, 252) == "2"))) {
            // line 253
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 254
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaDiametroRed2\">2\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaDiametroRed\" 
                                            \t\tid=\"inpAguaDiametroRed3\" value=\"3\"
                                            \t\t";
        // line 260
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 260), "aguaDiametroRed", [], "any", true, true, false, 260) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 261
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 261, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 261), "aguaDiametroRed", [], "any", false, false, false, 261) == "3"))) {
            // line 262
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 263
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaDiametroRed3\">3\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaDiametroRed\" 
                                            \t\tid=\"inpAguaDiametroRed4\" value=\"4\"
                                            \t\t";
        // line 269
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 269), "aguaDiametroRed", [], "any", true, true, false, 269) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 270
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 270, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 270), "aguaDiametroRed", [], "any", false, false, false, 270) == "4"))) {
            // line 271
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 272
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaDiametroRed4\">4\"</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Material de conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialConexion\" 
                                            \t\tid=\"inpAguaMaterialConexionPVC\" value=\"pvc\"
                                            \t\t";
        // line 283
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 283), "aguaMaterialConexion", [], "any", true, true, false, 283) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 284
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 284, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 284), "aguaMaterialConexion", [], "any", false, false, false, 284) == "pvc"))) {
            // line 285
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 286
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaMaterialConexionPVC\">PVC</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialConexion\" 
                                            \t\tid=\"inpAguaMaterialConexionF\" value=\"fierro\"
                                            \t\t";
        // line 292
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 292), "aguaMaterialConexion", [], "any", true, true, false, 292) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 293
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 293, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 293), "aguaMaterialConexion", [], "any", false, false, false, 293) == "fierro"))) {
            // line 294
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 295
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaMaterialConexionF\">Fierro</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Material de abrazadera</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialAbrazadera\" 
                                            \t\tid=\"inpAguaMaterialAbrazaderaPVC\" value=\"pvc\"
                                            \t\t";
        // line 306
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 306), "aguaMaterialAbrazadera", [], "any", true, true, false, 306) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 307
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 307, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 307), "aguaMaterialAbrazadera", [], "any", false, false, false, 307) == "pvc"))) {
            // line 308
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 309
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaMaterialAbrazaderaPVC\">PVC</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialAbrazadera\" 
                                            \t\tid=\"inpAguaMaterialAbrazaderaF\" value=\"fierro\"
                                            \t\t";
        // line 315
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 315), "aguaMaterialAbrazadera", [], "any", true, true, false, 315) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 316
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 316, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 316), "aguaMaterialAbrazadera", [], "any", false, false, false, 316) == "fierro"))) {
            // line 317
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 318
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaMaterialAbrazaderaF\">Fierro</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaUbicacionCaja\">Ubicacion de la caja</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"aguaUbicacionCaja\" class=\"f_minwidth300\" id=\"cmbAguaUbicacionCaja\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"vereda\"
                                    \t\t\t\t";
        // line 329
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 329), "aguaUbicacionCaja", [], "any", true, true, false, 329) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 330
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 330, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 330), "aguaUbicacionCaja", [], "any", false, false, false, 330) == "vereda"))) {
            // line 331
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 332
        echo ">
                                                    Vereda
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"jardin\"
                                    \t\t\t\t";
        // line 336
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 336), "aguaUbicacionCaja", [], "any", true, true, false, 336) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 337
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 337, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 337), "aguaUbicacionCaja", [], "any", false, false, false, 337) == "jardin"))) {
            // line 338
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 339
        echo ">
                                                    Jardin
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"interior casa\"
                                    \t\t\t\t";
        // line 343
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 343), "aguaUbicacionCaja", [], "any", true, true, false, 343) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 344
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 344, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 344), "aguaUbicacionCaja", [], "any", false, false, false, 344) == "interior casa"))) {
            // line 345
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 346
        echo ">
                                                    Interior casa
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 350
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 350), "aguaUbicacionCaja", [], "any", true, true, false, 350) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 351
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 351, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 351), "aguaUbicacionCaja", [], "any", false, false, false, 351) == "no tiene"))) {
            // line 352
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 353
        echo ">
                                                    No tiene
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaMaterialCaja\">Material de la caja</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"aguaMaterialCaja\" class=\"f_minwidth300\" id=\"cmbAguaMaterialCaja\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"concreto\"
                                    \t\t\t\t";
        // line 365
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 365), "aguaMaterialCaja", [], "any", true, true, false, 365) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 366
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 366, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 366), "aguaMaterialCaja", [], "any", false, false, false, 366) == "concreto"))) {
            // line 367
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 368
        echo ">
                                                    Concreto
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"ladrillo\"
                                    \t\t\t\t";
        // line 372
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 372), "aguaMaterialCaja", [], "any", true, true, false, 372) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 373
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 373, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 373), "aguaMaterialCaja", [], "any", false, false, false, 373) == "ladrillo"))) {
            // line 374
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 375
        echo ">
                                                    Ladrillo
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"termoplastico\"
                                    \t\t\t\t";
        // line 379
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 379), "aguaMaterialCaja", [], "any", true, true, false, 379) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 380
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 380, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 380), "aguaMaterialCaja", [], "any", false, false, false, 380) == "termoplastico"))) {
            // line 381
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 382
        echo ">
                                                    Termoplastico
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaEstadoCaja\">Estado de la caja</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"aguaEstadoCaja\" class=\"f_minwidth300\" id=\"cmbAguaEstadoCaja\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"buena\"
                                    \t\t\t\t";
        // line 394
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 394), "aguaEstadoCaja", [], "any", true, true, false, 394) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 395
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 395, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 395), "aguaEstadoCaja", [], "any", false, false, false, 395) == "buena"))) {
            // line 396
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 397
        echo ">
                                                    Buena
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"sucia\"
                                    \t\t\t\t";
        // line 401
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 401), "aguaEstadoCaja", [], "any", true, true, false, 401) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 402
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 402, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 402), "aguaEstadoCaja", [], "any", false, false, false, 402) == "sucia"))) {
            // line 403
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 404
        echo ">
                                                    Sucia
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"mal estado\"
                                    \t\t\t\t";
        // line 408
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 408), "aguaEstadoCaja", [], "any", true, true, false, 408) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 409
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 409, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 409), "aguaEstadoCaja", [], "any", false, false, false, 409) == "mal estado"))) {
            // line 410
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 411
        echo ">
                                                    Mal estado
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaMaterialTapa\">Material de la tapa</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"aguaMaterialTapa\" class=\"f_minwidth300\" id=\"cmbAguaMaterialTapa\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"concreto\"
                                    \t\t\t\t";
        // line 423
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 423), "aguaMaterialTapa", [], "any", true, true, false, 423) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 424
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 424, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 424), "aguaMaterialTapa", [], "any", false, false, false, 424) == "concreto"))) {
            // line 425
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 426
        echo ">
                                                    Concreto
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"ladrillo\"
                                    \t\t\t\t";
        // line 430
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 430), "aguaMaterialTapa", [], "any", true, true, false, 430) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 431
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 431, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 431), "aguaMaterialTapa", [], "any", false, false, false, 431) == "ladrillo"))) {
            // line 432
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 433
        echo ">
                                                    Ladrillo
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"fierro\"
                                    \t\t\t\t";
        // line 437
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 437), "aguaMaterialTapa", [], "any", true, true, false, 437) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 438
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 438, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 438), "aguaMaterialTapa", [], "any", false, false, false, 438) == "fierro"))) {
            // line 439
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 440
        echo ">
                                                    Fierro
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"termoplastico\"
                                    \t\t\t\t";
        // line 444
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 444), "aguaMaterialTapa", [], "any", true, true, false, 444) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 445
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 445, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 445), "aguaMaterialTapa", [], "any", false, false, false, 445) == "termoplastico"))) {
            // line 446
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 447
        echo ">
                                                    Termoplastico
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 451
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 451), "aguaMaterialTapa", [], "any", true, true, false, 451) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 452
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 452, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 452), "aguaMaterialTapa", [], "any", false, false, false, 452) == "no tiene"))) {
            // line 453
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 454
        echo ">
                                                    No Tiene
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAguaEstadoTapa\">Estado de la tapa</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"aguaEstadoTapa\" class=\"f_minwidth300\" id=\"cmbAguaEstadoTapa\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"buena\"
                                    \t\t\t\t";
        // line 466
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 466), "aguaEstadoTapa", [], "any", true, true, false, 466) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 467
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 467, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 467), "aguaEstadoTapa", [], "any", false, false, false, 467) == "buena"))) {
            // line 468
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 469
        echo ">
                                                    Buena
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"sellada\"
                                    \t\t\t\t";
        // line 473
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 473), "aguaEstadoTapa", [], "any", true, true, false, 473) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 474
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 474, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 474), "aguaEstadoTapa", [], "any", false, false, false, 474) == "sellada"))) {
            // line 475
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 476
        echo ">
                                                    Sellada
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"mal estado\"
                                    \t\t\t\t";
        // line 480
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 480), "aguaEstadoTapa", [], "any", true, true, false, 480) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 481
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 481, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 481), "aguaEstadoTapa", [], "any", false, false, false, 481) == "mal estado"))) {
            // line 482
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 483
        echo ">
                                                    Mal estado
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                            </div>";
        // line 490
        echo "                            
                            
                            ";
        // line 493
        echo "                            <div id=\"divNuevoContratoMasDetallesAlc\">
                                <h5 style=\"color:#23878c\" class=\"my-4\">Servicio de Alcantarillado</h5>
                        \t\t<div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"inpAlcFechaConexion\">Fecha de conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t";
        // line 498
        $context["alcFechaConexion"] = "";
        // line 499
        echo "                                    \t";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 499), "alcFechaConexion", [], "any", true, true, false, 499)) {
            // line 500
            echo "                                    \t    ";
            $context["alcFechaConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 500, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 500), "alcFechaConexion", [], "any", false, false, false, 500);
        }
        // line 501
        echo "                                    \t<input type=\"date\" class=\"f_minwidth300\" id=\"inpAlcFechaConexion\" name=\"alcFechaConexion\" 
                                    \t\t\tvalue=\"";
        // line 502
        echo twig_escape_filter($this->env, (isset($context["alcFechaConexion"]) || array_key_exists("alcFechaConexion", $context) ? $context["alcFechaConexion"] : (function () { throw new RuntimeError('Variable "alcFechaConexion" does not exist.', 502, $this->source); })()), "html", null, true);
        echo "\">
                                    </div>
                                </div>
                        \t\t<div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Característica de la conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionCaracteristica\" 
                                            \t\tid=\"inpAlcConexionCaracteristicaSC\" value=\"sin caja\"
                                            \t\t";
        // line 511
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 511), "alcConexionCaracteristica", [], "any", true, true, false, 511) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 512
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 512, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 512), "alcConexionCaracteristica", [], "any", false, false, false, 512) == "sin caja"))) {
            // line 513
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 514
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcConexionCaracteristicaSC\">Sin caja</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionCaracteristica\" 
                                            \t\tid=\"inpAlcConexionCaracteristicaCC\" value=\"con caja\"
                                            \t\t";
        // line 520
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 520), "alcConexionCaracteristica", [], "any", true, true, false, 520) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 521
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 521, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 521), "alcConexionCaracteristica", [], "any", false, false, false, 521) == "con caja"))) {
            // line 522
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 523
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcConexionCaracteristicaCC\">Con caja</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Tipo de conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcTipoConexion\" 
                                            \t\tid=\"inpAlcTipoConexionCV\" value=\"convencional\"
                                            \t\t";
        // line 534
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 534), "alcTipoConexion", [], "any", true, true, false, 534) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 535
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 535, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 535), "alcTipoConexion", [], "any", false, false, false, 535) == "convencional"))) {
            // line 536
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 537
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcTipoConexionCV\">Convencional</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcTipoConexion\" 
                                            \t\tid=\"inpAlcTipoConexionCD\" value=\"condominial\"
                                            \t\t";
        // line 543
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 543), "alcTipoConexion", [], "any", true, true, false, 543) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 544
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 544, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 544), "alcTipoConexion", [], "any", false, false, false, 544) == "condominial"))) {
            // line 545
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 546
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcTipoConexionCD\">Condominial</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Diametro de la conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionDiametro\" 
                                            \t\tid=\"inpAlcConexionDiametro4\" value=\"4\"
                                            \t\t";
        // line 557
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 557), "alcConexionDiametro", [], "any", true, true, false, 557) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 558
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 558, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 558), "alcConexionDiametro", [], "any", false, false, false, 558) == "4"))) {
            // line 559
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 560
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcConexionDiametro4\">4\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionDiametro\" 
                                            \t\tid=\"inpAlcConexionDiametro6\" value=\"6\"
                                            \t\t";
        // line 566
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 566), "alcConexionDiametro", [], "any", true, true, false, 566) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 567
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 567, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 567), "alcConexionDiametro", [], "any", false, false, false, 567) == "6"))) {
            // line 568
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 569
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcConexionDiametro6\">6\"</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Diametro de la red</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                            \t\tid=\"inpAlcDiametroRed4\" value=\"4\"
                                            \t\t";
        // line 580
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 580), "alcDiametroRed", [], "any", true, true, false, 580) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 581
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 581, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 581), "alcDiametroRed", [], "any", false, false, false, 581) == "4"))) {
            // line 582
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 583
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcDiametroRed4\">4\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                            \t\tid=\"inpAlcDiametroRed6\" value=\"6\"
                                            \t\t";
        // line 589
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 589), "alcDiametroRed", [], "any", true, true, false, 589) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 590
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 590, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 590), "alcDiametroRed", [], "any", false, false, false, 590) == "6"))) {
            // line 591
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 592
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcDiametroRed6\">6\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                            \t\tid=\"inpAlcDiametroRed8\" value=\"8\"
                                            \t\t";
        // line 598
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 598), "alcDiametroRed", [], "any", true, true, false, 598) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 599
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 599, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 599), "alcDiametroRed", [], "any", false, false, false, 599) == "8"))) {
            // line 600
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 601
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcDiametroRed8\">8\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                            \t\tid=\"inpAlcDiametroRed10\" value=\"10\"
                                            \t\t";
        // line 607
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 607), "alcDiametroRed", [], "any", true, true, false, 607) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 608
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 608, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 608), "alcDiametroRed", [], "any", false, false, false, 608) == "10"))) {
            // line 609
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 610
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcDiametroRed10\">10\"</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\">Material de conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcMaterialConexion\" 
                                            \t\tid=\"inpAlcMaterialConexionPVC\" value=\"pvc\"
                                            \t\t";
        // line 621
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 621), "alcMaterialConexion", [], "any", true, true, false, 621) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 622
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 622, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 622), "alcMaterialConexion", [], "any", false, false, false, 622) == "pvc"))) {
            // line 623
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 624
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcMaterialConexionPVC\">PVC</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcMaterialConexion\" 
                                            \t\tid=\"inpAlcMaterialConexionF\" value=\"fierro\"
                                            \t\t";
        // line 630
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 630), "alcMaterialConexion", [], "any", true, true, false, 630) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 631
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 631, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 631), "alcMaterialConexion", [], "any", false, false, false, 631) == "fierro"))) {
            // line 632
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 633
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcMaterialConexionF\">Fierro</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcUbicacionCaja\">Ubicacion de la caja</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"alcUbicacionCaja\" class=\"f_minwidth300\" id=\"cmbAlcUbicacionCaja\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"vereda\"
                                    \t\t\t\t";
        // line 644
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 644), "alcUbicacionCaja", [], "any", true, true, false, 644) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 645
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 645, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 645), "alcUbicacionCaja", [], "any", false, false, false, 645) == "vereda"))) {
            // line 646
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 647
        echo ">
                                                    Vereda
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"jardin\"
                                    \t\t\t\t";
        // line 651
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 651), "alcUbicacionCaja", [], "any", true, true, false, 651) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 652
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 652, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 652), "alcUbicacionCaja", [], "any", false, false, false, 652) == "jardin"))) {
            // line 653
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 654
        echo ">
                                                    Jardin
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"interior casa\"
                                    \t\t\t\t";
        // line 658
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 658), "alcUbicacionCaja", [], "any", true, true, false, 658) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 659
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 659, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 659), "alcUbicacionCaja", [], "any", false, false, false, 659) == "interior casa"))) {
            // line 660
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 661
        echo ">
                                                    Interior casa
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 665
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 665), "alcUbicacionCaja", [], "any", true, true, false, 665) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 666
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 666, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 666), "alcUbicacionCaja", [], "any", false, false, false, 666) == "no tiene"))) {
            // line 667
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 668
        echo ">
                                                    No tiene
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcMaterialCaja\">Material de la caja</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"alcMaterialCaja\" class=\"f_minwidth300\" id=\"cmbAlcMaterialCaja\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"concreto\"
                                    \t\t\t\t";
        // line 680
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 680), "alcMaterialCaja", [], "any", true, true, false, 680) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 681
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 681, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 681), "alcMaterialCaja", [], "any", false, false, false, 681) == "concreto"))) {
            // line 682
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 683
        echo ">
                                                    Concreto
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"ladrillo\"
                                    \t\t\t\t";
        // line 687
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 687), "alcMaterialCaja", [], "any", true, true, false, 687) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 688
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 688, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 688), "alcMaterialCaja", [], "any", false, false, false, 688) == "ladrillo"))) {
            // line 689
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 690
        echo ">
                                                    Ladrillo
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"termoplastico\"
                                    \t\t\t\t";
        // line 694
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 694), "alcMaterialCaja", [], "any", true, true, false, 694) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 695
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 695, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 695), "alcMaterialCaja", [], "any", false, false, false, 695) == "termoplastico"))) {
            // line 696
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 697
        echo ">
                                                    Termoplastico
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcEstadoCaja\">Estado de la caja</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"alcEstadoCaja\" class=\"f_minwidth300\" id=\"cmbAlcEstadoCaja\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"buena\"
                                    \t\t\t\t";
        // line 709
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 709), "alcEstadoCaja", [], "any", true, true, false, 709) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 710
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 710, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 710), "alcEstadoCaja", [], "any", false, false, false, 710) == "buena"))) {
            // line 711
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 712
        echo ">
                                                    Buena
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"sucia\"
                                    \t\t\t\t";
        // line 716
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 716), "alcEstadoCaja", [], "any", true, true, false, 716) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 717
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 717, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 717), "alcEstadoCaja", [], "any", false, false, false, 717) == "sucia"))) {
            // line 718
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 719
        echo ">
                                                    Sucia
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"mal estado\"
                                    \t\t\t\t";
        // line 723
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 723), "alcEstadoCaja", [], "any", true, true, false, 723) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 724
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 724, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 724), "alcEstadoCaja", [], "any", false, false, false, 724) == "mal estado"))) {
            // line 725
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 726
        echo ">
                                                    Mal estado
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcDimencionCaja\">Dimención de la caja</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"alcDimencionCaja\" class=\"f_minwidth300\" id=\"cmbAlcDimencionCaja\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"70x40 cm\"
                                    \t\t\t\t";
        // line 738
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 738), "alcDimencionCaja", [], "any", true, true, false, 738) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 739
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 739, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 739), "alcDimencionCaja", [], "any", false, false, false, 739) == "70x40 cm"))) {
            // line 740
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 741
        echo ">
                                                    70 x 40 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"60x40 cm\"
                                    \t\t\t\t";
        // line 745
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 745), "alcDimencionCaja", [], "any", true, true, false, 745) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 746
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 746, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 746), "alcDimencionCaja", [], "any", false, false, false, 746) == "60x40 cm"))) {
            // line 747
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 748
        echo ">
                                                    60 x 40 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"otro\"
                                    \t\t\t\t";
        // line 752
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 752), "alcDimencionCaja", [], "any", true, true, false, 752) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 753
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 753, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 753), "alcDimencionCaja", [], "any", false, false, false, 753) == "otro"))) {
            // line 754
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 755
        echo ">
                                                    Otro
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcMaterialTapa\">Material de la tapa</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"alcMaterialTapa\" class=\"f_minwidth300\" id=\"cmbAlcMaterialTapa\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"concreto\"
                                    \t\t\t\t";
        // line 767
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 767), "alcMaterialTapa", [], "any", true, true, false, 767) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 768
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 768, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 768), "alcMaterialTapa", [], "any", false, false, false, 768) == "concreto"))) {
            // line 769
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 770
        echo ">
                                                    Concreto
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"fierro\"
                                    \t\t\t\t";
        // line 774
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 774), "alcMaterialTapa", [], "any", true, true, false, 774) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 775
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 775, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 775), "alcMaterialTapa", [], "any", false, false, false, 775) == "fierro"))) {
            // line 776
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 777
        echo ">
                                                    Fierro
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"madera\"
                                    \t\t\t\t";
        // line 781
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 781), "alcMaterialTapa", [], "any", true, true, false, 781) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 782
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 782, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 782), "alcMaterialTapa", [], "any", false, false, false, 782) == "madera"))) {
            // line 783
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 784
        echo ">
                                                    Madera
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 788
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 788), "alcMaterialTapa", [], "any", true, true, false, 788) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 789
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 789, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 789), "alcMaterialTapa", [], "any", false, false, false, 789) == "no tiene"))) {
            // line 790
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 791
        echo ">
                                                    No Tiene
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcEstadoTapa\">Estado de la tapa</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"alcEstadoTapa\" class=\"f_minwidth300\" id=\"cmbAlcEstadoTapa\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"buena\"
                                    \t\t\t\t";
        // line 803
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 803), "alcEstadoTapa", [], "any", true, true, false, 803) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 804
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 804, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 804), "alcEstadoTapa", [], "any", false, false, false, 804) == "buena"))) {
            // line 805
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 806
        echo ">
                                                    Buena
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"sellada\"
                                    \t\t\t\t";
        // line 810
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 810), "alcEstadoTapa", [], "any", true, true, false, 810) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 811
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 811, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 811), "alcEstadoTapa", [], "any", false, false, false, 811) == "sellada"))) {
            // line 812
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 813
        echo ">
                                                    Sellada
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"mal estado\"
                                    \t\t\t\t";
        // line 817
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 817), "alcEstadoTapa", [], "any", true, true, false, 817) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 818
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 818, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 818), "alcEstadoTapa", [], "any", false, false, false, 818) == "mal estado"))) {
            // line 819
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 820
        echo ">
                                                    Mal estado
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 824
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 824), "alcEstadoTapa", [], "any", true, true, false, 824) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 825
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 825, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 825), "alcEstadoTapa", [], "any", false, false, false, 825) == "no tiene"))) {
            // line 826
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 827
        echo ">
                                                    No tiene
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"cmbAlcMedidasTapa\">Medidas de la tapa</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t<select name=\"alcMedidasTapa\" class=\"f_minwidth300\" id=\"cmbAlcMedidasTapa\"> 
                                    \t\t<option value=\"-1\"></option>
                                        \t<option value=\"62x32 cm\"
                                    \t\t\t\t";
        // line 839
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 839), "alcMedidasTapa", [], "any", true, true, false, 839) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 840
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 840, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 840), "alcMedidasTapa", [], "any", false, false, false, 840) == "62x32 cm"))) {
            // line 841
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 842
        echo ">
                                                    62 x 32 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"54x34 cm\"
                                    \t\t\t\t";
        // line 846
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 846), "alcMedidasTapa", [], "any", true, true, false, 846) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 847
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 847, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 847), "alcMedidasTapa", [], "any", false, false, false, 847) == "54x34 cm"))) {
            // line 848
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 849
        echo ">
                                                    54 x 34 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"53x53 cm\"
                                    \t\t\t\t";
        // line 853
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 853), "alcMedidasTapa", [], "any", true, true, false, 853) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 854
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 854, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 854), "alcMedidasTapa", [], "any", false, false, false, 854) == "53x53 cm"))) {
            // line 855
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 856
        echo ">
                                                    53 x 53 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"otro\"
                                    \t\t\t\t";
        // line 860
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 860), "alcMedidasTapa", [], "any", true, true, false, 860) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 861
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 861, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 861), "alcMedidasTapa", [], "any", false, false, false, 861) == "otro"))) {
            // line 862
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 863
        echo ">
                                                    Otro
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                            </div>";
        // line 870
        echo "                            
                    \t</div>";
        // line 872
        echo "                    </div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnCancelarMasDetallesServ\" data-dismiss=\"modal\">Cancelar</button>
                        <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnGuardarMasDetallesServ\" data-dismiss=\"modal\">Hecho</button>
                    </div>
                </div>
            </div>
        </div>";
        // line 880
        echo "  \t
  \t</form>";
        // line 882
        echo "  
</div><!-- /.card -->



";
    }

    // line 889
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 890
        echo "
    ";
        // line 891
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formNuevoContrato').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        f_select2('#cmbServicios');
        f_select2('#cmbTipoUsoPredio');
        f_select2('#cmbEstadoContrato');
        
        f_select2('#cmbConexionCaracteristica');
        
        
        
        \$(\"#btnBuscarPredio\").click(function(){
        
        \t\$(\"#inpPredioCalle\").val(\"\");
\t\t\t\$(\"#inpPredioDireccion\").val(\"\");
\t\t\t\$(\"#inpClienteDocumento\").val(\"\");
\t\t\t\$(\"#inpClienteNombre\").val(\"\");
        \t
        \t\$(\"#inpPredio\").val(\$(\"#inpPredio\").val().trim());
        \tvar inpPredio = \$(\"#inpPredio\").val();
        \t
        \tif(inpPredio != \"\"){
        \t
        \t\tvar spinnerBuscarPredio = \$(\"#spinnerBuscarPredio\");
        \t\tspinnerBuscarPredio.removeClass(\"d-none\");
        \t
        \t\t\$.ajax({
                    method: \"GET\",
                    url: \"";
        // line 925
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 925, $this->source); })()), "html", null, true);
        echo "/predio/detalle/json/\"+inpPredio,
                    dataType: \"json\",
                    complete: function(){
                    \tspinnerBuscarPredio.addClass(\"d-none\");
                    },
        \t\t\terror: function(jqXHR){
        \t\t\t\tvar msj = \"\";
        \t\t\t\tif(jqXHR.status == 404){
        \t\t\t\t\tmsj = \"No se encontro el predio solicitado\";
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
        \t\t\t\t\$(\"#inpPredioCalle\").val(respons.data.calle.nombre);
        \t\t\t\t\$(\"#inpPredioDireccion\").val(respons.data.predio.direccion);
        \t\t\t\t\$(\"#inpClienteDocumento\").val(respons.data.cliente.documento);
        \t\t\t\t\$(\"#inpClienteNombre\").val(respons.data.cliente.nombre);
        \t\t\t}
                });
        \t}
        });
        
        
        \$(\"#btnCancelarMasDetallesServ\").click(function(){
        \tdocument.querySelectorAll('#divNuevoContratoMasDetalles input[type=\"date\"]').forEach(function(inp){inp.value='';});
        \tdocument.querySelectorAll('#divNuevoContratoMasDetalles input[type=\"radio\"]').forEach(function(inp){inp.checked=false;});
        \tdocument.querySelectorAll('#divNuevoContratoMasDetalles select').forEach(function(slt){slt.selectedIndex=-1;});
        });
        
        
        
        if(\$('#cmbServicios option:selected').length == 0){
        \t\$('#btnMasDetalleServicios').addClass(\"d-none\");
        }
        
        \$(\"#cmbServicios\").change(function(){
        \tif(\$('#cmbServicios option:selected').length == 0){
        \t\tdocument.querySelectorAll('#divNuevoContratoMasDetalles input[type=\"date\"]').forEach(function(inp){inp.value='';});
        \t\tdocument.querySelectorAll('#divNuevoContratoMasDetalles input[type=\"radio\"]').forEach(function(inp){inp.checked=false;});
        \t\tdocument.querySelectorAll('#divNuevoContratoMasDetalles select').forEach(function(slt){slt.selectedIndex=-1;});
        \t\t\$('#btnMasDetalleServicios').removeClass(\"d-inline-block\");
        \t\t\$('#btnMasDetalleServicios').addClass(\"d-none\");
        \t}else if(\$('#cmbServicios option:selected').length == 1){
        \t\t\$('#cmbServicios option:selected').each(function() {
                    if(\$(this).val() == 1){
                    \tdocument.querySelectorAll('#divNuevoContratoMasDetallesAlc input[type=\"date\"]').forEach(function(inp){inp.value='';});
                    \tdocument.querySelectorAll('#divNuevoContratoMasDetallesAlc input[type=\"radio\"]').forEach(function(inp){inp.checked=false;});
        \t\t\t\tdocument.querySelectorAll('#divNuevoContratoMasDetallesAlc select').forEach(function(slt){slt.selectedIndex=-1;});
        \t\t\t\t\$('#divNuevoContratoMasDetallesAlc').removeClass(\"d-block\");
        \t\t\t\t\$('#divNuevoContratoMasDetallesAlc').addClass(\"d-none\");
        \t\t\t\t\$('#divNuevoContratoMasDetallesAgua').removeClass(\"d-none\");
        \t\t\t\t\$('#divNuevoContratoMasDetallesAgua').addClass(\"d-block\");
                    }else if(\$(this).val() == 2){
                    \tdocument.querySelectorAll('#divNuevoContratoMasDetallesAgua input[type=\"date\"]').forEach(function(inp){inp.value='';});
                    \tdocument.querySelectorAll('#divNuevoContratoMasDetallesAgua input[type=\"radio\"]').forEach(function(inp){inp.checked=false;});
        \t\t\t\tdocument.querySelectorAll('#divNuevoContratoMasDetallesAgua select').forEach(function(slt){slt.selectedIndex=-1;});
        \t\t\t\t\$('#divNuevoContratoMasDetallesAgua').removeClass(\"d-block\");
        \t\t\t\t\$('#divNuevoContratoMasDetallesAgua').addClass(\"d-none\");
        \t\t\t\t\$('#divNuevoContratoMasDetallesAlc').removeClass(\"d-none\");
        \t\t\t\t\$('#divNuevoContratoMasDetallesAlc').addClass(\"d-block\");
                    }
                });
                \$('#btnMasDetalleServicios').removeClass(\"d-none\");
                \$('#btnMasDetalleServicios').addClass(\"d-inline-block\");
        \t}else if(\$('#cmbServicios option:selected').length == 2){
        \t\t\$('#divNuevoContratoMasDetallesAgua').removeClass(\"d-none\");
        \t\t\$('#divNuevoContratoMasDetallesAgua').addClass(\"d-block\");
        \t\t\$('#divNuevoContratoMasDetallesAlc').removeClass(\"d-none\");
        \t\t\$('#divNuevoContratoMasDetallesAlc').addClass(\"d-block\");
        \t\t\$('#btnMasDetalleServicios').removeClass(\"d-none\");
        \t\t\$('#btnMasDetalleServicios').addClass(\"d-inline-block\");
        \t}
        });
        
\t</script>
\t
";
    }

    public function getTemplateName()
    {
        return "/administration/contrato/contratoNew.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1693 => 925,  1656 => 891,  1653 => 890,  1649 => 889,  1640 => 882,  1637 => 880,  1628 => 872,  1625 => 870,  1617 => 863,  1611 => 862,  1609 => 861,  1608 => 860,  1602 => 856,  1596 => 855,  1594 => 854,  1593 => 853,  1587 => 849,  1581 => 848,  1579 => 847,  1578 => 846,  1572 => 842,  1566 => 841,  1564 => 840,  1563 => 839,  1549 => 827,  1543 => 826,  1541 => 825,  1540 => 824,  1534 => 820,  1528 => 819,  1526 => 818,  1525 => 817,  1519 => 813,  1513 => 812,  1511 => 811,  1510 => 810,  1504 => 806,  1498 => 805,  1496 => 804,  1495 => 803,  1481 => 791,  1475 => 790,  1473 => 789,  1472 => 788,  1466 => 784,  1460 => 783,  1458 => 782,  1457 => 781,  1451 => 777,  1445 => 776,  1443 => 775,  1442 => 774,  1436 => 770,  1430 => 769,  1428 => 768,  1427 => 767,  1413 => 755,  1407 => 754,  1405 => 753,  1404 => 752,  1398 => 748,  1392 => 747,  1390 => 746,  1389 => 745,  1383 => 741,  1377 => 740,  1375 => 739,  1374 => 738,  1360 => 726,  1354 => 725,  1352 => 724,  1351 => 723,  1345 => 719,  1339 => 718,  1337 => 717,  1336 => 716,  1330 => 712,  1324 => 711,  1322 => 710,  1321 => 709,  1307 => 697,  1301 => 696,  1299 => 695,  1298 => 694,  1292 => 690,  1286 => 689,  1284 => 688,  1283 => 687,  1277 => 683,  1271 => 682,  1269 => 681,  1268 => 680,  1254 => 668,  1248 => 667,  1246 => 666,  1245 => 665,  1239 => 661,  1233 => 660,  1231 => 659,  1230 => 658,  1224 => 654,  1218 => 653,  1216 => 652,  1215 => 651,  1209 => 647,  1203 => 646,  1201 => 645,  1200 => 644,  1187 => 633,  1181 => 632,  1179 => 631,  1178 => 630,  1170 => 624,  1164 => 623,  1162 => 622,  1161 => 621,  1148 => 610,  1142 => 609,  1140 => 608,  1139 => 607,  1131 => 601,  1125 => 600,  1123 => 599,  1122 => 598,  1114 => 592,  1108 => 591,  1106 => 590,  1105 => 589,  1097 => 583,  1091 => 582,  1089 => 581,  1088 => 580,  1075 => 569,  1069 => 568,  1067 => 567,  1066 => 566,  1058 => 560,  1052 => 559,  1050 => 558,  1049 => 557,  1036 => 546,  1030 => 545,  1028 => 544,  1027 => 543,  1019 => 537,  1013 => 536,  1011 => 535,  1010 => 534,  997 => 523,  991 => 522,  989 => 521,  988 => 520,  980 => 514,  974 => 513,  972 => 512,  971 => 511,  959 => 502,  956 => 501,  952 => 500,  949 => 499,  947 => 498,  940 => 493,  936 => 490,  928 => 483,  922 => 482,  920 => 481,  919 => 480,  913 => 476,  907 => 475,  905 => 474,  904 => 473,  898 => 469,  892 => 468,  890 => 467,  889 => 466,  875 => 454,  869 => 453,  867 => 452,  866 => 451,  860 => 447,  854 => 446,  852 => 445,  851 => 444,  845 => 440,  839 => 439,  837 => 438,  836 => 437,  830 => 433,  824 => 432,  822 => 431,  821 => 430,  815 => 426,  809 => 425,  807 => 424,  806 => 423,  792 => 411,  786 => 410,  784 => 409,  783 => 408,  777 => 404,  771 => 403,  769 => 402,  768 => 401,  762 => 397,  756 => 396,  754 => 395,  753 => 394,  739 => 382,  733 => 381,  731 => 380,  730 => 379,  724 => 375,  718 => 374,  716 => 373,  715 => 372,  709 => 368,  703 => 367,  701 => 366,  700 => 365,  686 => 353,  680 => 352,  678 => 351,  677 => 350,  671 => 346,  665 => 345,  663 => 344,  662 => 343,  656 => 339,  650 => 338,  648 => 337,  647 => 336,  641 => 332,  635 => 331,  633 => 330,  632 => 329,  619 => 318,  613 => 317,  611 => 316,  610 => 315,  602 => 309,  596 => 308,  594 => 307,  593 => 306,  580 => 295,  574 => 294,  572 => 293,  571 => 292,  563 => 286,  557 => 285,  555 => 284,  554 => 283,  541 => 272,  535 => 271,  533 => 270,  532 => 269,  524 => 263,  518 => 262,  516 => 261,  515 => 260,  507 => 254,  501 => 253,  499 => 252,  498 => 251,  485 => 240,  479 => 239,  477 => 238,  476 => 237,  468 => 231,  462 => 230,  460 => 229,  459 => 228,  446 => 217,  440 => 216,  438 => 215,  437 => 214,  429 => 208,  423 => 207,  421 => 206,  420 => 205,  408 => 196,  405 => 195,  401 => 194,  398 => 193,  396 => 192,  389 => 187,  385 => 184,  375 => 175,  366 => 168,  352 => 157,  349 => 156,  345 => 155,  342 => 154,  340 => 153,  329 => 145,  323 => 144,  322 => 143,  318 => 142,  313 => 140,  307 => 139,  306 => 138,  302 => 137,  297 => 135,  291 => 134,  290 => 133,  286 => 132,  273 => 121,  264 => 118,  259 => 117,  253 => 116,  247 => 114,  244 => 113,  239 => 112,  237 => 111,  232 => 110,  228 => 109,  218 => 101,  209 => 98,  204 => 97,  200 => 96,  198 => 95,  197 => 94,  192 => 93,  187 => 92,  185 => 91,  145 => 56,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/contrato/contratoNew.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\contrato\\contratoNew.twig");
    }
}
