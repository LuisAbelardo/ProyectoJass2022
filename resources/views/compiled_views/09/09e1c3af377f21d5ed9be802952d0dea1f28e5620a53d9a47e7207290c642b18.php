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

/* /administration/egreso/egresoNew.twig */
class __TwigTemplate_46ee8b85ec923867cd833e6b07beabb324cbf01eed1e284b51458a9b7080ab12 extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["egreso", "nuevo"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/egreso/egresoNew.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formNuevoEgreso\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/egreso/create\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-money-bill mr-3\"></i>Nuevo egreso
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
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpMonto\">Monto</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                            ";
        // line 55
        $context["monto"] = "";
        // line 56
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", true, true, false, 56)) {
            // line 57
            echo "                        \t    ";
            $context["monto"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 57, $this->source); })()), "formNuevoEgreso", [], "any", false, false, false, 57), "monto", [], "any", false, false, false, 57);
        }
        // line 58
        echo "                        \t<div style=\"background-color:#b9ceac\" class=\"d-inline-block\">
                        \t\t<span class=\"pl-1\">S/. </span>
                        \t\t<input type=\"text\" class=\"f_minwidth100\" id=\"inpMonto\" name=\"monto\" required 
                        \t\t\tstyle=\"background-color:#b9ceac\" value='";
        // line 61
        echo twig_escape_filter($this->env, (isset($context["monto"]) || array_key_exists("monto", $context) ? $context["monto"] : (function () { throw new RuntimeError('Variable "monto" does not exist.', 61, $this->source); })()), "html", null, true);
        echo "'>
                        \t</div>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2  f_fieldrequired\" for=\"cmbComprobaneTipo\">Comprobante</label>
                        <div class=\"col-12 col-md-9\">
                        \t<select name=\"comprobanteTipo\" class=\"f_minwidth200\" id=\"cmbComprobanteTipo\" required>
                            \t<option value=\"1\"
                        \t\t\t\t";
        // line 70
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", true, true, false, 70) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 71
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 71, $this->source); })()), "formNuevoEgreso", [], "any", false, false, false, 71), "comprobanteTipo", [], "any", false, false, false, 71) == 1))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 72
        echo "TICKED";
        echo "
                \t\t\t\t</option>
                \t\t\t\t<option value=\"2\"
                        \t\t\t\t";
        // line 75
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", true, true, false, 75) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 76
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 76, $this->source); })()), "formNuevoEgreso", [], "any", false, false, false, 76), "comprobanteTipo", [], "any", false, false, false, 76) == 2))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 77
        echo "BOLETA";
        echo "
                \t\t\t\t</option>
                \t\t\t\t<option value=\"3\"
                        \t\t\t\t";
        // line 80
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", true, true, false, 80) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 81
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 81, $this->source); })()), "formNuevoEgreso", [], "any", false, false, false, 81), "comprobanteTipo", [], "any", false, false, false, 81) == 3))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 82
        echo "FACTURA";
        echo "
                \t\t\t\t</option>
                \t\t\t\t<option value=\"4\"
                        \t\t\t\t";
        // line 85
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", true, true, false, 85) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 86
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 86, $this->source); })()), "formNuevoEgreso", [], "any", false, false, false, 86), "comprobanteTipo", [], "any", false, false, false, 86) == 4))) {
            echo "selected";
        }
        echo ">
                    \t\t\t\t";
        // line 87
        echo "SIN COMPROBANTE";
        echo "
                \t\t\t\t</option>
                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2  f_fieldrequired\" for=\"inpComprobanteNro\">Nro. Comprobante</label>
                        <div class=\"col-12 col-md-9\">
                            ";
        // line 95
        $context["comprobanteNro"] = "";
        // line 96
        echo "                        \t";
        if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", true, true, false, 96) && twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", false, true, false, 96), "comprobanteNro", [], "any", true, true, false, 96))) {
            // line 97
            echo "                        \t    ";
            $context["comprobanteNro"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 97, $this->source); })()), "formNuevoEgreso", [], "any", false, false, false, 97), "comprobanteNro", [], "any", false, false, false, 97);
        }
        // line 98
        echo "                        \t<input type=\"text\" class=\"f_minwidth200\" id=\"inpComprobanteNro\" name=\"comprobanteNro\" required 
                        \t\t\tvalue='";
        // line 99
        echo twig_escape_filter($this->env, (isset($context["comprobanteNro"]) || array_key_exists("comprobanteNro", $context) ? $context["comprobanteNro"] : (function () { throw new RuntimeError('Variable "comprobanteNro" does not exist.', 99, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2  f_fieldrequired\" for=\"cmbCaja\">Caja</label>
                        <div class=\"col-12 col-md-9\">
                            <select name=\"caja\" class=\"f_minwidth200\" id=\"cmbCaja\" required>
                                ";
        // line 106
        $context["selectedCaja"] = false;
        // line 107
        echo "                                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 107, $this->source); })()), "cajas", [], "any", false, false, false, 107));
        foreach ($context['_seq'] as $context["_key"] => $context["caja"]) {
            // line 108
            echo "                                \t<option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_CODIGO", [], "any", false, false, false, 108), "html", null, true);
            echo "\"
                            \t\t\t\t";
            // line 109
            if ((( !(isset($context["selectedCaja"]) || array_key_exists("selectedCaja", $context) ? $context["selectedCaja"] : (function () { throw new RuntimeError('Variable "selectedCaja" does not exist.', 109, $this->source); })()) && twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", true, true, false, 109)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 110
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 110, $this->source); })()), "formNuevoEgreso", [], "any", false, false, false, 110), "caja", [], "any", false, false, false, 110) == twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_CODIGO", [], "any", false, false, false, 110)))) {
                // line 111
                echo "                            \t\t\t\t\t";
                echo "selected";
                $context["selectedCaja"] = true;
                // line 112
                echo "                                            ";
            }
            echo ">
                        \t\t\t\t";
            // line 113
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["caja"], "CAJ_NOMBRE", [], "any", false, false, false, 113), "html", null, true);
            echo "
                    \t\t\t\t</option>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['caja'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 116
        echo "                            </select>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpDescripcion\">Descripción</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 122
        $context["descripcion"] = "";
        // line 123
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoEgreso", [], "any", false, true, false, 123), "descripcion", [], "any", true, true, false, 123)) {
            // line 124
            echo "                        \t    ";
            $context["descripcion"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 124, $this->source); })()), "formNuevoEgreso", [], "any", false, false, false, 124), "descripcion", [], "any", false, false, false, 124);
        }
        // line 125
        echo "                        \t<textarea class=\"f_minwidth400\" id=\"inpDescripcion\" rows=\"2\" maxlength=\"256\" 
                        \t\t\t\tname=\"descripcion\" required>";
        // line 126
        echo twig_escape_filter($this->env, (isset($context["descripcion"]) || array_key_exists("descripcion", $context) ? $context["descripcion"] : (function () { throw new RuntimeError('Variable "descripcion" does not exist.', 126, $this->source); })()), "html", null, true);
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
        \t\t\t<button type=\"submit\" class=\"f_button f_buttonaction\">Guardar egreso</button>
        \t\t\t<a href=\"";
        // line 138
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 138, $this->source); })()), "html", null, true);
        echo "/egreso/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 144
        echo "  
</div><!-- /.card -->

";
    }

    // line 149
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 150
        echo "
    ";
        // line 151
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formNuevoEgreso').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        f_select2(\"#cmbComprobanteTipo\");
        f_select2(\"#cmbCaja\");
        
        
        if(\$(\"#cmbComprobanteTipo\").val() != 4){
    \t\t\$(\"#inpComprobanteNro\").prop(\"disabled\", false);
    \t}else{
    \t\t\$(\"#inpComprobanteNro\").val(\"\");
    \t\t\$(\"#inpComprobanteNro\").prop(\"disabled\", true);
    \t}
        
        \$(\"#cmbComprobanteTipo\").change(function(){
        \tif(\$(\"#cmbComprobanteTipo\").val() != 4){
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
        return "/administration/egreso/egresoNew.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  339 => 151,  336 => 150,  332 => 149,  325 => 144,  317 => 138,  302 => 126,  299 => 125,  295 => 124,  292 => 123,  290 => 122,  282 => 116,  273 => 113,  268 => 112,  264 => 111,  262 => 110,  261 => 109,  256 => 108,  251 => 107,  249 => 106,  239 => 99,  236 => 98,  232 => 97,  229 => 96,  227 => 95,  216 => 87,  210 => 86,  209 => 85,  203 => 82,  197 => 81,  196 => 80,  190 => 77,  184 => 76,  183 => 75,  177 => 72,  171 => 71,  170 => 70,  158 => 61,  153 => 58,  149 => 57,  146 => 56,  144 => 55,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/egreso/egresoNew.twig", "/home/franco/proyectos/php/jass/resources/views/administration/egreso/egresoNew.twig");
    }
}
