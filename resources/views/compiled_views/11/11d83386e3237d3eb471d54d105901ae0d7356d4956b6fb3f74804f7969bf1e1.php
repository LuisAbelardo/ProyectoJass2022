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

/* /administration/ingreso/ingresoNewOtros.twig */
class __TwigTemplate_6124a64afc47731b7996e093c4025ef41ec99c2d7c2bb48f44d7f905f03a4477 extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["ingreso", "otros"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/ingreso/ingresoNewOtros.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formNuevoIngreso\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/ingreso/otros/create\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-money-bill mr-3\"></i>Nuevo ingreso
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
                    
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpMontoTotal\">Monto</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                            ";
        // line 55
        $context["montoTotal"] = "";
        // line 56
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", true, true, false, 56)) {
            // line 57
            echo "                        \t    ";
            $context["montoTotal"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 57, $this->source); })()), "formNuevoIngreso", [], "any", false, false, false, 57), "montoTotal", [], "any", false, false, false, 57);
        }
        // line 58
        echo "                        \t<div style=\"background-color:#b9ceac\" class=\"d-inline-block\">
                        \t\t<span class=\"pl-1\">S/. </span>
                        \t\t<input type=\"text\" class=\"f_minwidth100\" id=\"inpMontoTotal\" name=\"montoTotal\" required 
                        \t\t\tstyle=\"background-color:#b9ceac\" value='";
        // line 61
        echo twig_escape_filter($this->env, (isset($context["montoTotal"]) || array_key_exists("montoTotal", $context) ? $context["montoTotal"] : (function () { throw new RuntimeError('Variable "montoTotal" does not exist.', 61, $this->source); })()), "html", null, true);
        echo "'>
                        \t</div>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2  f_fieldrequired\" for=\"inpMontoRecibido\">Monto recibido</label>
                        <div class=\"col-12 col-md-9\">
                            ";
        // line 68
        $context["montoRecibido"] = "";
        // line 69
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", true, true, false, 69)) {
            // line 70
            echo "                        \t    ";
            $context["montoRecibido"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 70, $this->source); })()), "formNuevoIngreso", [], "any", false, false, false, 70), "montoRecibido", [], "any", false, false, false, 70);
        }
        // line 71
        echo "                        \t<div style=\"background-color:#b9ceac\" class=\"d-inline-block\">
                        \t\t<span class=\"pl-1\">S/. </span>
                        \t\t<input type=\"text\" class=\"f_minwidth100\" id=\"inpMontoRecibido\" name=\"montoRecibido\" required 
                        \t\t\tstyle=\"background-color:#b9ceac\" value='";
        // line 74
        echo twig_escape_filter($this->env, (isset($context["montoRecibido"]) || array_key_exists("montoRecibido", $context) ? $context["montoRecibido"] : (function () { throw new RuntimeError('Variable "montoRecibido" does not exist.', 74, $this->source); })()), "html", null, true);
        echo "'>
                        \t</div>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2  f_fieldrequired\" for=\"cmbComprobaneTipo\">Comprobante</label>
                        <div class=\"col-12 col-md-9\">
                        \t<select name=\"comprobanteTipo\" class=\"f_minwidth200\" id=\"cmbComprobanteTipo\" required>
                            \t<option value=\"";
        // line 82
        echo 1;
        echo "\"
                        \t\t\t\t";
        // line 83
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", true, true, false, 83) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 84
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 84, $this->source); })()), "formNuevoIngreso", [], "any", false, false, false, 84), "comprobanteTipo", [], "any", false, false, false, 84) == 1))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 85
        echo "TICKED";
        echo "
                \t\t\t\t</option>
                \t\t\t\t<option value=\"";
        // line 87
        echo 2;
        echo "\"
                        \t\t\t\t";
        // line 88
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", true, true, false, 88) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 89
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 89, $this->source); })()), "formNuevoIngreso", [], "any", false, false, false, 89), "comprobanteTipo", [], "any", false, false, false, 89) == 2))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 90
        echo "BOLETA";
        echo "
                \t\t\t\t</option>
                \t\t\t\t<option value=\"";
        // line 92
        echo 3;
        echo "\"
                        \t\t\t\t";
        // line 93
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", true, true, false, 93) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 94
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 94, $this->source); })()), "formNuevoIngreso", [], "any", false, false, false, 94), "comprobanteTipo", [], "any", false, false, false, 94) == 3))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 95
        echo "FACTURA";
        echo "
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2  f_fieldrequired\" for=\"inpComprobanteNro\">Nro. Comprobante</label>
                        <div class=\"col-12 col-md-9\">
                            ";
        // line 103
        $context["comprobanteNro"] = "";
        // line 104
        echo "                        \t";
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", true, true, false, 104) && twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", false, true, false, 104), "comprobanteNro", [], "any", true, true, false, 104))) {
            // line 105
            echo "                        \t    ";
            $context["comprobanteNro"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 105, $this->source); })()), "formNuevoIngreso", [], "any", false, false, false, 105), "comprobanteNro", [], "any", false, false, false, 105);
        }
        // line 106
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpComprobanteNro\" name=\"comprobanteNro\" required 
                        \t\t\tvalue='";
        // line 107
        echo twig_escape_filter($this->env, (isset($context["comprobanteNro"]) || array_key_exists("comprobanteNro", $context) ? $context["comprobanteNro"] : (function () { throw new RuntimeError('Variable "comprobanteNro" does not exist.', 107, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2  f_fieldrequired\" for=\"cmbCaja\">Caja</label>
                        <div class=\"col-12 col-md-9\">
                            <select name=\"caja\" class=\"f_minwidth200\" id=\"cmbCaja\" required>
                                ";
        // line 114
        $context["selectedCaja"] = false;
        // line 115
        echo "                                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 115, $this->source); })()), "cajas", [], "any", false, false, false, 115));
        foreach ($context['_seq'] as $context["_key"] => $context["caja"]) {
            // line 116
            echo "                                \t<option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_CODIGO", [], "any", false, false, false, 116), "html", null, true);
            echo "\"
                            \t\t\t\t";
            // line 117
            if ((( !(isset($context["selectedCaja"]) || array_key_exists("selectedCaja", $context) ? $context["selectedCaja"] : (function () { throw new RuntimeError('Variable "selectedCaja" does not exist.', 117, $this->source); })()) && twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", true, true, false, 117)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 118
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 118, $this->source); })()), "formNuevoIngreso", [], "any", false, false, false, 118), "caja", [], "any", false, false, false, 118) == twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_CODIGO", [], "any", false, false, false, 118)))) {
                // line 119
                echo "                            \t\t\t\t\t";
                echo "selected";
                $context["selectedCaja"] = true;
                // line 120
                echo "                                            ";
            }
            echo ">
                        \t\t\t\t";
            // line 121
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_NOMBRE", [], "any", false, false, false, 121), "html", null, true);
            echo "
                    \t\t\t\t</option>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['caja'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 124
        echo "                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpDescripcion\">Descripción</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 130
        $context["descripcion"] = "";
        // line 131
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoIngreso", [], "any", false, true, false, 131), "descripcion", [], "any", true, true, false, 131)) {
            // line 132
            echo "                        \t    ";
            $context["descripcion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 132, $this->source); })()), "formNuevoIngreso", [], "any", false, false, false, 132), "descripcion", [], "any", false, false, false, 132);
        }
        // line 133
        echo "                        \t<textarea class=\"f_minwidth400\" id=\"inpDescripcion\" rows=\"2\" maxlength=\"256\" 
                        \t\t\t\tname=\"descripcion\" required>";
        // line 134
        echo twig_escape_filter($this->env, (isset($context["descripcion"]) || array_key_exists("descripcion", $context) ? $context["descripcion"] : (function () { throw new RuntimeError('Variable "descripcion" does not exist.', 134, $this->source); })()), "html", null, true);
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
        \t\t\t<button type=\"submit\" class=\"f_button f_buttonaction\">Guardar ingreso</button>
        \t\t\t<a href=\"";
        // line 146
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 146, $this->source); })()), "html", null, true);
        echo "/ingreso/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 152
        echo "  
</div><!-- /.card -->

";
    }

    // line 157
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 158
        echo "
    ";
        // line 159
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formNuevoIngreso').keypress(function(e) {
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
        return "/administration/ingreso/ingresoNewOtros.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  359 => 159,  356 => 158,  352 => 157,  345 => 152,  337 => 146,  322 => 134,  319 => 133,  315 => 132,  312 => 131,  310 => 130,  302 => 124,  293 => 121,  288 => 120,  284 => 119,  282 => 118,  281 => 117,  276 => 116,  271 => 115,  269 => 114,  259 => 107,  256 => 106,  252 => 105,  249 => 104,  247 => 103,  236 => 95,  230 => 94,  229 => 93,  225 => 92,  220 => 90,  214 => 89,  213 => 88,  209 => 87,  204 => 85,  198 => 84,  197 => 83,  193 => 82,  182 => 74,  177 => 71,  173 => 70,  170 => 69,  168 => 68,  158 => 61,  153 => 58,  149 => 57,  146 => 56,  144 => 55,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/ingreso/ingresoNewOtros.twig", "/home/franco/proyectos/php/jass/resources/views/administration/ingreso/ingresoNewOtros.twig");
    }
}
