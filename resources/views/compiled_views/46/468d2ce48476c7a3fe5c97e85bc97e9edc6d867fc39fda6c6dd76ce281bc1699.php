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
class __TwigTemplate_93cbcdc232f0582a6fa170fc4805f0d33de060affed672512bbde29c33f81ec0 extends \Twig\Template
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
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\" for=\"txaObservacion\">Observación</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 148
        $context["observacion"] = "";
        // line 149
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 149), "observacion", [], "any", true, true, false, 149)) {
            // line 150
            echo "                        \t    ";
            $context["observacion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 150, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 150), "observacion", [], "any", false, false, false, 150);
        }
        // line 151
        echo "                        \t<textarea class=\"f_minwidth400\" id=\"txaObservacion\" rows=\"2\" maxlength=\"256\" 
                        \t\t\t\tname=\"observacion\">";
        // line 152
        echo twig_escape_filter($this->env, (isset($context["observacion"]) || array_key_exists("observacion", $context) ? $context["observacion"] : (function () { throw new RuntimeError('Variable "observacion" does not exist.', 152, $this->source); })()), "html", null, true);
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
        // line 163
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 163, $this->source); })()), "html", null, true);
        echo "/contrato/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
      \t
      \t
      \t";
        // line 170
        echo "        <div class=\"modal fade f_modal\" id=\"modalMasDetalleServicio\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-lg\" role=\"document\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <span class=\"modal-title\">Detalle de servicios</span>
                    </div>
                    <div class=\"modal-body\">
                    
                    \t";
        // line 179
        echo "                    \t<div id=\"divNuevoContratoMasDetalles\">
                    \t
                            ";
        // line 182
        echo "                    \t\t<div id=\"divNuevoContratoMasDetallesAgua\">
                        \t\t<h5 style=\"color:#23878c\" class=\"mb-4\">Servicio de Agua</h5>
                        \t\t<div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"inpAguaFechaInstalacion\">Fecha de instalación</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t";
        // line 187
        $context["aguaFechaInstalacion"] = "";
        // line 188
        echo "                                    \t";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 188), "aguaFechaInstalacion", [], "any", true, true, false, 188)) {
            // line 189
            echo "                                    \t    ";
            $context["aguaFechaInstalacion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 189, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 189), "aguaFechaInstalacion", [], "any", false, false, false, 189);
        }
        // line 190
        echo "                                    \t<input type=\"date\" class=\"f_minwidth300\" id=\"inpAguaFechaInstalacion\" name=\"aguaFechaInstalacion\" 
                                    \t\t\tvalue=\"";
        // line 191
        echo twig_escape_filter($this->env, (isset($context["aguaFechaInstalacion"]) || array_key_exists("aguaFechaInstalacion", $context) ? $context["aguaFechaInstalacion"] : (function () { throw new RuntimeError('Variable "aguaFechaInstalacion" does not exist.', 191, $this->source); })()), "html", null, true);
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
        // line 200
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 200), "aguaConexionCaracteristica", [], "any", true, true, false, 200) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 201
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 201, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 201), "aguaConexionCaracteristica", [], "any", false, false, false, 201) == "sin caja"))) {
            // line 202
            echo "                                    \t\t\t\t\t";
            echo "checked";
            echo "
                                                    ";
        }
        // line 203
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaConexionCaracteristicaSC\">Sin caja</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionCaracteristica\" 
                                            \t\tid=\"inpAguaConexionCaracteristicaCC\" value=\"con caja\"
                                            \t\t";
        // line 209
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 209), "aguaConexionCaracteristica", [], "any", true, true, false, 209) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 210
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 210, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 210), "aguaConexionCaracteristica", [], "any", false, false, false, 210) == "con caja"))) {
            // line 211
            echo "                                    \t\t\t\t\t";
            echo "checked";
            echo "
                                                    ";
        }
        // line 212
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
        // line 223
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 223), "aguaConexionDiametro", [], "any", true, true, false, 223) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 224
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 224, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 224), "aguaConexionDiametro", [], "any", false, false, false, 224) == "1/2"))) {
            // line 225
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 226
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaConexionDiametro1-2\">1/2\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaConexionDiametro\" 
                                            \t\tid=\"inpAguaConexionDiametro3-4\" value=\"3/4\"
                                            \t\t";
        // line 232
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 232), "aguaConexionDiametro", [], "any", true, true, false, 232) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 233
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 233, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 233), "aguaConexionDiametro", [], "any", false, false, false, 233) == "3/4"))) {
            // line 234
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 235
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
        // line 246
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 246), "aguaDiametroRed", [], "any", true, true, false, 246) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 247
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 247, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 247), "aguaDiametroRed", [], "any", false, false, false, 247) == "2"))) {
            // line 248
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 249
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaDiametroRed2\">2\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaDiametroRed\" 
                                            \t\tid=\"inpAguaDiametroRed3\" value=\"3\"
                                            \t\t";
        // line 255
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 255), "aguaDiametroRed", [], "any", true, true, false, 255) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 256
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 256, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 256), "aguaDiametroRed", [], "any", false, false, false, 256) == "3"))) {
            // line 257
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 258
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaDiametroRed3\">3\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaDiametroRed\" 
                                            \t\tid=\"inpAguaDiametroRed4\" value=\"4\"
                                            \t\t";
        // line 264
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 264), "aguaDiametroRed", [], "any", true, true, false, 264) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 265
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 265, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 265), "aguaDiametroRed", [], "any", false, false, false, 265) == "4"))) {
            // line 266
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 267
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
        // line 278
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 278), "aguaMaterialConexion", [], "any", true, true, false, 278) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 279
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 279, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 279), "aguaMaterialConexion", [], "any", false, false, false, 279) == "pvc"))) {
            // line 280
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 281
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaMaterialConexionPVC\">PVC</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialConexion\" 
                                            \t\tid=\"inpAguaMaterialConexionF\" value=\"fierro\"
                                            \t\t";
        // line 287
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 287), "aguaMaterialConexion", [], "any", true, true, false, 287) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 288
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 288, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 288), "aguaMaterialConexion", [], "any", false, false, false, 288) == "fierro"))) {
            // line 289
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 290
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
        // line 301
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 301), "aguaMaterialAbrazadera", [], "any", true, true, false, 301) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 302
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 302, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 302), "aguaMaterialAbrazadera", [], "any", false, false, false, 302) == "pvc"))) {
            // line 303
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 304
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAguaMaterialAbrazaderaPVC\">PVC</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"aguaMaterialAbrazadera\" 
                                            \t\tid=\"inpAguaMaterialAbrazaderaF\" value=\"fierro\"
                                            \t\t";
        // line 310
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 310), "aguaMaterialAbrazadera", [], "any", true, true, false, 310) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 311
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 311, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 311), "aguaMaterialAbrazadera", [], "any", false, false, false, 311) == "fierro"))) {
            // line 312
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 313
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
        // line 324
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 324), "aguaUbicacionCaja", [], "any", true, true, false, 324) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 325
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 325, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 325), "aguaUbicacionCaja", [], "any", false, false, false, 325) == "vereda"))) {
            // line 326
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 327
        echo ">
                                                    Vereda
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"jardin\"
                                    \t\t\t\t";
        // line 331
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 331), "aguaUbicacionCaja", [], "any", true, true, false, 331) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 332
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 332, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 332), "aguaUbicacionCaja", [], "any", false, false, false, 332) == "jardin"))) {
            // line 333
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 334
        echo ">
                                                    Jardin
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"interior casa\"
                                    \t\t\t\t";
        // line 338
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 338), "aguaUbicacionCaja", [], "any", true, true, false, 338) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 339
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 339, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 339), "aguaUbicacionCaja", [], "any", false, false, false, 339) == "interior casa"))) {
            // line 340
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 341
        echo ">
                                                    Interior casa
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 345
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 345), "aguaUbicacionCaja", [], "any", true, true, false, 345) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 346
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 346, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 346), "aguaUbicacionCaja", [], "any", false, false, false, 346) == "no tiene"))) {
            // line 347
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 348
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
        // line 360
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 360), "aguaMaterialCaja", [], "any", true, true, false, 360) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 361
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 361, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 361), "aguaMaterialCaja", [], "any", false, false, false, 361) == "concreto"))) {
            // line 362
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 363
        echo ">
                                                    Concreto
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"ladrillo\"
                                    \t\t\t\t";
        // line 367
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 367), "aguaMaterialCaja", [], "any", true, true, false, 367) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 368
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 368, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 368), "aguaMaterialCaja", [], "any", false, false, false, 368) == "ladrillo"))) {
            // line 369
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 370
        echo ">
                                                    Ladrillo
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"termoplastico\"
                                    \t\t\t\t";
        // line 374
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 374), "aguaMaterialCaja", [], "any", true, true, false, 374) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 375
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 375, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 375), "aguaMaterialCaja", [], "any", false, false, false, 375) == "termoplastico"))) {
            // line 376
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 377
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
        // line 389
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 389), "aguaEstadoCaja", [], "any", true, true, false, 389) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 390
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 390, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 390), "aguaEstadoCaja", [], "any", false, false, false, 390) == "buena"))) {
            // line 391
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 392
        echo ">
                                                    Buena
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"sucia\"
                                    \t\t\t\t";
        // line 396
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 396), "aguaEstadoCaja", [], "any", true, true, false, 396) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 397
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 397, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 397), "aguaEstadoCaja", [], "any", false, false, false, 397) == "sucia"))) {
            // line 398
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 399
        echo ">
                                                    Sucia
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"mal estado\"
                                    \t\t\t\t";
        // line 403
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 403), "aguaEstadoCaja", [], "any", true, true, false, 403) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 404
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 404, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 404), "aguaEstadoCaja", [], "any", false, false, false, 404) == "mal estado"))) {
            // line 405
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 406
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
        // line 418
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 418), "aguaMaterialTapa", [], "any", true, true, false, 418) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 419
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 419, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 419), "aguaMaterialTapa", [], "any", false, false, false, 419) == "concreto"))) {
            // line 420
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 421
        echo ">
                                                    Concreto
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"ladrillo\"
                                    \t\t\t\t";
        // line 425
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 425), "aguaMaterialTapa", [], "any", true, true, false, 425) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 426
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 426, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 426), "aguaMaterialTapa", [], "any", false, false, false, 426) == "ladrillo"))) {
            // line 427
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 428
        echo ">
                                                    Ladrillo
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"fierro\"
                                    \t\t\t\t";
        // line 432
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 432), "aguaMaterialTapa", [], "any", true, true, false, 432) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 433
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 433, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 433), "aguaMaterialTapa", [], "any", false, false, false, 433) == "fierro"))) {
            // line 434
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 435
        echo ">
                                                    Fierro
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"termoplastico\"
                                    \t\t\t\t";
        // line 439
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 439), "aguaMaterialTapa", [], "any", true, true, false, 439) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 440
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 440, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 440), "aguaMaterialTapa", [], "any", false, false, false, 440) == "termoplastico"))) {
            // line 441
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 442
        echo ">
                                                    Termoplastico
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 446
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 446), "aguaMaterialTapa", [], "any", true, true, false, 446) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 447
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 447, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 447), "aguaMaterialTapa", [], "any", false, false, false, 447) == "no tiene"))) {
            // line 448
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 449
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
        // line 461
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 461), "aguaEstadoTapa", [], "any", true, true, false, 461) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 462
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 462, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 462), "aguaEstadoTapa", [], "any", false, false, false, 462) == "buena"))) {
            // line 463
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 464
        echo ">
                                                    Buena
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"sellada\"
                                    \t\t\t\t";
        // line 468
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 468), "aguaEstadoTapa", [], "any", true, true, false, 468) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 469
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 469, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 469), "aguaEstadoTapa", [], "any", false, false, false, 469) == "sellada"))) {
            // line 470
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 471
        echo ">
                                                    Sellada
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"mal estado\"
                                    \t\t\t\t";
        // line 475
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 475), "aguaEstadoTapa", [], "any", true, true, false, 475) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 476
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 476, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 476), "aguaEstadoTapa", [], "any", false, false, false, 476) == "mal estado"))) {
            // line 477
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 478
        echo ">
                                                    Mal estado
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                            </div>";
        // line 485
        echo "                            
                            
                            ";
        // line 488
        echo "                            <div id=\"divNuevoContratoMasDetallesAlc\">
                                <h5 style=\"color:#23878c\" class=\"my-4\">Servicio de Alcantarillado</h5>
                        \t\t<div class=\"form-group row\">
                                \t<label class=\"col-12 col-md-3 f_field\" for=\"inpAlcFechaConexion\">Fecha de conexion</label>
                                    <div class=\"col-12 col-md-9\">
                                    \t";
        // line 493
        $context["alcFechaConexion"] = "";
        // line 494
        echo "                                    \t";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 494), "alcFechaConexion", [], "any", true, true, false, 494)) {
            // line 495
            echo "                                    \t    ";
            $context["alcFechaConexion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 495, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 495), "alcFechaConexion", [], "any", false, false, false, 495);
        }
        // line 496
        echo "                                    \t<input type=\"date\" class=\"f_minwidth300\" id=\"inpAlcFechaConexion\" name=\"alcFechaConexion\" 
                                    \t\t\tvalue=\"";
        // line 497
        echo twig_escape_filter($this->env, (isset($context["alcFechaConexion"]) || array_key_exists("alcFechaConexion", $context) ? $context["alcFechaConexion"] : (function () { throw new RuntimeError('Variable "alcFechaConexion" does not exist.', 497, $this->source); })()), "html", null, true);
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
        // line 506
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 506), "alcConexionCaracteristica", [], "any", true, true, false, 506) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 507
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 507, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 507), "alcConexionCaracteristica", [], "any", false, false, false, 507) == "sin caja"))) {
            // line 508
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 509
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcConexionCaracteristicaSC\">Sin caja</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionCaracteristica\" 
                                            \t\tid=\"inpAlcConexionCaracteristicaCC\" value=\"con caja\"
                                            \t\t";
        // line 515
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 515), "alcConexionCaracteristica", [], "any", true, true, false, 515) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 516
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 516, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 516), "alcConexionCaracteristica", [], "any", false, false, false, 516) == "con caja"))) {
            // line 517
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 518
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
        // line 529
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 529), "alcTipoConexion", [], "any", true, true, false, 529) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 530
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 530, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 530), "alcTipoConexion", [], "any", false, false, false, 530) == "convencional"))) {
            // line 531
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 532
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcTipoConexionCV\">Convencional</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcTipoConexion\" 
                                            \t\tid=\"inpAlcTipoConexionCD\" value=\"condominial\"
                                            \t\t";
        // line 538
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 538), "alcTipoConexion", [], "any", true, true, false, 538) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 539
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 539, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 539), "alcTipoConexion", [], "any", false, false, false, 539) == "condominial"))) {
            // line 540
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 541
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
        // line 552
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 552), "alcConexionDiametro", [], "any", true, true, false, 552) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 553
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 553, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 553), "alcConexionDiametro", [], "any", false, false, false, 553) == "4"))) {
            // line 554
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 555
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcConexionDiametro4\">4\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcConexionDiametro\" 
                                            \t\tid=\"inpAlcConexionDiametro6\" value=\"6\"
                                            \t\t";
        // line 561
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 561), "alcConexionDiametro", [], "any", true, true, false, 561) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 562
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 562, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 562), "alcConexionDiametro", [], "any", false, false, false, 562) == "6"))) {
            // line 563
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 564
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
        // line 575
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 575), "alcDiametroRed", [], "any", true, true, false, 575) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 576
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 576, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 576), "alcDiametroRed", [], "any", false, false, false, 576) == "4"))) {
            // line 577
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 578
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcDiametroRed4\">4\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                            \t\tid=\"inpAlcDiametroRed6\" value=\"6\"
                                            \t\t";
        // line 584
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 584), "alcDiametroRed", [], "any", true, true, false, 584) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 585
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 585, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 585), "alcDiametroRed", [], "any", false, false, false, 585) == "6"))) {
            // line 586
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 587
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcDiametroRed6\">6\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                            \t\tid=\"inpAlcDiametroRed8\" value=\"8\"
                                            \t\t";
        // line 593
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 593), "alcDiametroRed", [], "any", true, true, false, 593) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 594
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 594, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 594), "alcDiametroRed", [], "any", false, false, false, 594) == "8"))) {
            // line 595
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 596
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcDiametroRed8\">8\"</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcDiametroRed\" 
                                            \t\tid=\"inpAlcDiametroRed10\" value=\"10\"
                                            \t\t";
        // line 602
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 602), "alcDiametroRed", [], "any", true, true, false, 602) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 603
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 603, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 603), "alcDiametroRed", [], "any", false, false, false, 603) == "10"))) {
            // line 604
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 605
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
        // line 616
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 616), "alcMaterialConexion", [], "any", true, true, false, 616) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 617
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 617, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 617), "alcMaterialConexion", [], "any", false, false, false, 617) == "pvc"))) {
            // line 618
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 619
        echo ">
                                            <label class=\"form-check-label\" for=\"inpAlcMaterialConexionPVC\">PVC</label>
                                        </div>
                                        <div class=\"form-check form-check-inline\">
                                            <input class=\"form-check-input\" type=\"radio\" name=\"alcMaterialConexion\" 
                                            \t\tid=\"inpAlcMaterialConexionF\" value=\"fierro\"
                                            \t\t";
        // line 625
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 625), "alcMaterialConexion", [], "any", true, true, false, 625) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 626
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 626, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 626), "alcMaterialConexion", [], "any", false, false, false, 626) == "fierro"))) {
            // line 627
            echo "                                \t\t\t\t\t";
            echo "checked";
            echo "
                                                ";
        }
        // line 628
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
        // line 639
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 639), "alcUbicacionCaja", [], "any", true, true, false, 639) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 640
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 640, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 640), "alcUbicacionCaja", [], "any", false, false, false, 640) == "vereda"))) {
            // line 641
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 642
        echo ">
                                                    Vereda
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"jardin\"
                                    \t\t\t\t";
        // line 646
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 646), "alcUbicacionCaja", [], "any", true, true, false, 646) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 647
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 647, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 647), "alcUbicacionCaja", [], "any", false, false, false, 647) == "jardin"))) {
            // line 648
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 649
        echo ">
                                                    Jardin
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"interior casa\"
                                    \t\t\t\t";
        // line 653
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 653), "alcUbicacionCaja", [], "any", true, true, false, 653) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 654
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 654, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 654), "alcUbicacionCaja", [], "any", false, false, false, 654) == "interior casa"))) {
            // line 655
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 656
        echo ">
                                                    Interior casa
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 660
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 660), "alcUbicacionCaja", [], "any", true, true, false, 660) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 661
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 661, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 661), "alcUbicacionCaja", [], "any", false, false, false, 661) == "no tiene"))) {
            // line 662
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 663
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
        // line 675
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 675), "alcMaterialCaja", [], "any", true, true, false, 675) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 676
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 676, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 676), "alcMaterialCaja", [], "any", false, false, false, 676) == "concreto"))) {
            // line 677
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 678
        echo ">
                                                    Concreto
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"ladrillo\"
                                    \t\t\t\t";
        // line 682
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 682), "alcMaterialCaja", [], "any", true, true, false, 682) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 683
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 683, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 683), "alcMaterialCaja", [], "any", false, false, false, 683) == "ladrillo"))) {
            // line 684
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 685
        echo ">
                                                    Ladrillo
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"termoplastico\"
                                    \t\t\t\t";
        // line 689
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 689), "alcMaterialCaja", [], "any", true, true, false, 689) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 690
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 690, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 690), "alcMaterialCaja", [], "any", false, false, false, 690) == "termoplastico"))) {
            // line 691
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 692
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
        // line 704
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 704), "alcEstadoCaja", [], "any", true, true, false, 704) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 705
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 705, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 705), "alcEstadoCaja", [], "any", false, false, false, 705) == "buena"))) {
            // line 706
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 707
        echo ">
                                                    Buena
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"sucia\"
                                    \t\t\t\t";
        // line 711
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 711), "alcEstadoCaja", [], "any", true, true, false, 711) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 712
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 712, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 712), "alcEstadoCaja", [], "any", false, false, false, 712) == "sucia"))) {
            // line 713
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 714
        echo ">
                                                    Sucia
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"mal estado\"
                                    \t\t\t\t";
        // line 718
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 718), "alcEstadoCaja", [], "any", true, true, false, 718) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 719
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 719, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 719), "alcEstadoCaja", [], "any", false, false, false, 719) == "mal estado"))) {
            // line 720
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 721
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
        // line 733
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 733), "alcDimencionCaja", [], "any", true, true, false, 733) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 734
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 734, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 734), "alcDimencionCaja", [], "any", false, false, false, 734) == "70x40 cm"))) {
            // line 735
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 736
        echo ">
                                                    70 x 40 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"60x40 cm\"
                                    \t\t\t\t";
        // line 740
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 740), "alcDimencionCaja", [], "any", true, true, false, 740) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 741
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 741, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 741), "alcDimencionCaja", [], "any", false, false, false, 741) == "60x40 cm"))) {
            // line 742
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 743
        echo ">
                                                    60 x 40 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"otro\"
                                    \t\t\t\t";
        // line 747
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 747), "alcDimencionCaja", [], "any", true, true, false, 747) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 748
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 748, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 748), "alcDimencionCaja", [], "any", false, false, false, 748) == "otro"))) {
            // line 749
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 750
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
        // line 762
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 762), "alcMaterialTapa", [], "any", true, true, false, 762) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 763
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 763, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 763), "alcMaterialTapa", [], "any", false, false, false, 763) == "concreto"))) {
            // line 764
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 765
        echo ">
                                                    Concreto
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"fierro\"
                                    \t\t\t\t";
        // line 769
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 769), "alcMaterialTapa", [], "any", true, true, false, 769) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 770
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 770, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 770), "alcMaterialTapa", [], "any", false, false, false, 770) == "fierro"))) {
            // line 771
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 772
        echo ">
                                                    Fierro
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"madera\"
                                    \t\t\t\t";
        // line 776
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 776), "alcMaterialTapa", [], "any", true, true, false, 776) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 777
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 777, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 777), "alcMaterialTapa", [], "any", false, false, false, 777) == "madera"))) {
            // line 778
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 779
        echo ">
                                                    Madera
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 783
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 783), "alcMaterialTapa", [], "any", true, true, false, 783) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 784
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 784, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 784), "alcMaterialTapa", [], "any", false, false, false, 784) == "no tiene"))) {
            // line 785
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 786
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
        // line 798
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 798), "alcEstadoTapa", [], "any", true, true, false, 798) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 799
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 799, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 799), "alcEstadoTapa", [], "any", false, false, false, 799) == "buena"))) {
            // line 800
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 801
        echo ">
                                                    Buena
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"sellada\"
                                    \t\t\t\t";
        // line 805
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 805), "alcEstadoTapa", [], "any", true, true, false, 805) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 806
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 806, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 806), "alcEstadoTapa", [], "any", false, false, false, 806) == "sellada"))) {
            // line 807
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 808
        echo ">
                                                    Sellada
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"mal estado\"
                                    \t\t\t\t";
        // line 812
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 812), "alcEstadoTapa", [], "any", true, true, false, 812) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 813
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 813, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 813), "alcEstadoTapa", [], "any", false, false, false, 813) == "mal estado"))) {
            // line 814
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 815
        echo ">
                                                    Mal estado
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"no tiene\"
                                    \t\t\t\t";
        // line 819
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 819), "alcEstadoTapa", [], "any", true, true, false, 819) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 820
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 820, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 820), "alcEstadoTapa", [], "any", false, false, false, 820) == "no tiene"))) {
            // line 821
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 822
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
        // line 834
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 834), "alcMedidasTapa", [], "any", true, true, false, 834) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 835
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 835, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 835), "alcMedidasTapa", [], "any", false, false, false, 835) == "62x32 cm"))) {
            // line 836
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 837
        echo ">
                                                    62 x 32 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"54x34 cm\"
                                    \t\t\t\t";
        // line 841
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 841), "alcMedidasTapa", [], "any", true, true, false, 841) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 842
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 842, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 842), "alcMedidasTapa", [], "any", false, false, false, 842) == "54x34 cm"))) {
            // line 843
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 844
        echo ">
                                                    54 x 34 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"53x53 cm\"
                                    \t\t\t\t";
        // line 848
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 848), "alcMedidasTapa", [], "any", true, true, false, 848) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 849
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 849, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 849), "alcMedidasTapa", [], "any", false, false, false, 849) == "53x53 cm"))) {
            // line 850
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 851
        echo ">
                                                    53 x 53 cm
                            \t\t\t\t</option>
                            \t\t\t\t<option value=\"otro\"
                                    \t\t\t\t";
        // line 855
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoContrato", [], "any", false, true, false, 855), "alcMedidasTapa", [], "any", true, true, false, 855) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 856
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 856, $this->source); })()), "formNuevoContrato", [], "any", false, false, false, 856), "alcMedidasTapa", [], "any", false, false, false, 856) == "otro"))) {
            // line 857
            echo "                                    \t\t\t\t\t";
            echo "selected";
            echo "
                                                    ";
        }
        // line 858
        echo ">
                                                    Otro
                            \t\t\t\t</option>
                                        </select>
                                    </div>
                                </div>
                            </div>";
        // line 865
        echo "                            
                    \t</div>";
        // line 867
        echo "                    </div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnCancelarMasDetallesServ\" data-dismiss=\"modal\">Cancelar</button>
                        <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnGuardarMasDetallesServ\" data-dismiss=\"modal\">Hecho</button>
                    </div>
                </div>
            </div>
        </div>";
        // line 875
        echo "  \t
  \t</form>";
        // line 877
        echo "  
</div><!-- /.card -->



";
    }

    // line 884
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 885
        echo "
    ";
        // line 886
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
        // line 920
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 920, $this->source); })()), "html", null, true);
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
        return array (  1677 => 920,  1640 => 886,  1637 => 885,  1633 => 884,  1624 => 877,  1621 => 875,  1612 => 867,  1609 => 865,  1601 => 858,  1595 => 857,  1593 => 856,  1592 => 855,  1586 => 851,  1580 => 850,  1578 => 849,  1577 => 848,  1571 => 844,  1565 => 843,  1563 => 842,  1562 => 841,  1556 => 837,  1550 => 836,  1548 => 835,  1547 => 834,  1533 => 822,  1527 => 821,  1525 => 820,  1524 => 819,  1518 => 815,  1512 => 814,  1510 => 813,  1509 => 812,  1503 => 808,  1497 => 807,  1495 => 806,  1494 => 805,  1488 => 801,  1482 => 800,  1480 => 799,  1479 => 798,  1465 => 786,  1459 => 785,  1457 => 784,  1456 => 783,  1450 => 779,  1444 => 778,  1442 => 777,  1441 => 776,  1435 => 772,  1429 => 771,  1427 => 770,  1426 => 769,  1420 => 765,  1414 => 764,  1412 => 763,  1411 => 762,  1397 => 750,  1391 => 749,  1389 => 748,  1388 => 747,  1382 => 743,  1376 => 742,  1374 => 741,  1373 => 740,  1367 => 736,  1361 => 735,  1359 => 734,  1358 => 733,  1344 => 721,  1338 => 720,  1336 => 719,  1335 => 718,  1329 => 714,  1323 => 713,  1321 => 712,  1320 => 711,  1314 => 707,  1308 => 706,  1306 => 705,  1305 => 704,  1291 => 692,  1285 => 691,  1283 => 690,  1282 => 689,  1276 => 685,  1270 => 684,  1268 => 683,  1267 => 682,  1261 => 678,  1255 => 677,  1253 => 676,  1252 => 675,  1238 => 663,  1232 => 662,  1230 => 661,  1229 => 660,  1223 => 656,  1217 => 655,  1215 => 654,  1214 => 653,  1208 => 649,  1202 => 648,  1200 => 647,  1199 => 646,  1193 => 642,  1187 => 641,  1185 => 640,  1184 => 639,  1171 => 628,  1165 => 627,  1163 => 626,  1162 => 625,  1154 => 619,  1148 => 618,  1146 => 617,  1145 => 616,  1132 => 605,  1126 => 604,  1124 => 603,  1123 => 602,  1115 => 596,  1109 => 595,  1107 => 594,  1106 => 593,  1098 => 587,  1092 => 586,  1090 => 585,  1089 => 584,  1081 => 578,  1075 => 577,  1073 => 576,  1072 => 575,  1059 => 564,  1053 => 563,  1051 => 562,  1050 => 561,  1042 => 555,  1036 => 554,  1034 => 553,  1033 => 552,  1020 => 541,  1014 => 540,  1012 => 539,  1011 => 538,  1003 => 532,  997 => 531,  995 => 530,  994 => 529,  981 => 518,  975 => 517,  973 => 516,  972 => 515,  964 => 509,  958 => 508,  956 => 507,  955 => 506,  943 => 497,  940 => 496,  936 => 495,  933 => 494,  931 => 493,  924 => 488,  920 => 485,  912 => 478,  906 => 477,  904 => 476,  903 => 475,  897 => 471,  891 => 470,  889 => 469,  888 => 468,  882 => 464,  876 => 463,  874 => 462,  873 => 461,  859 => 449,  853 => 448,  851 => 447,  850 => 446,  844 => 442,  838 => 441,  836 => 440,  835 => 439,  829 => 435,  823 => 434,  821 => 433,  820 => 432,  814 => 428,  808 => 427,  806 => 426,  805 => 425,  799 => 421,  793 => 420,  791 => 419,  790 => 418,  776 => 406,  770 => 405,  768 => 404,  767 => 403,  761 => 399,  755 => 398,  753 => 397,  752 => 396,  746 => 392,  740 => 391,  738 => 390,  737 => 389,  723 => 377,  717 => 376,  715 => 375,  714 => 374,  708 => 370,  702 => 369,  700 => 368,  699 => 367,  693 => 363,  687 => 362,  685 => 361,  684 => 360,  670 => 348,  664 => 347,  662 => 346,  661 => 345,  655 => 341,  649 => 340,  647 => 339,  646 => 338,  640 => 334,  634 => 333,  632 => 332,  631 => 331,  625 => 327,  619 => 326,  617 => 325,  616 => 324,  603 => 313,  597 => 312,  595 => 311,  594 => 310,  586 => 304,  580 => 303,  578 => 302,  577 => 301,  564 => 290,  558 => 289,  556 => 288,  555 => 287,  547 => 281,  541 => 280,  539 => 279,  538 => 278,  525 => 267,  519 => 266,  517 => 265,  516 => 264,  508 => 258,  502 => 257,  500 => 256,  499 => 255,  491 => 249,  485 => 248,  483 => 247,  482 => 246,  469 => 235,  463 => 234,  461 => 233,  460 => 232,  452 => 226,  446 => 225,  444 => 224,  443 => 223,  430 => 212,  424 => 211,  422 => 210,  421 => 209,  413 => 203,  407 => 202,  405 => 201,  404 => 200,  392 => 191,  389 => 190,  385 => 189,  382 => 188,  380 => 187,  373 => 182,  369 => 179,  359 => 170,  350 => 163,  336 => 152,  333 => 151,  329 => 150,  326 => 149,  324 => 148,  313 => 140,  307 => 139,  306 => 138,  302 => 137,  297 => 135,  291 => 134,  290 => 133,  286 => 132,  273 => 121,  264 => 118,  259 => 117,  253 => 116,  247 => 114,  244 => 113,  239 => 112,  237 => 111,  232 => 110,  228 => 109,  218 => 101,  209 => 98,  204 => 97,  200 => 96,  198 => 95,  197 => 94,  192 => 93,  187 => 92,  185 => 91,  145 => 56,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/contrato/contratoNew.twig", "/home/franco/proyectos/php/jass/resources/views/administration/contrato/contratoNew.twig");
    }
}
