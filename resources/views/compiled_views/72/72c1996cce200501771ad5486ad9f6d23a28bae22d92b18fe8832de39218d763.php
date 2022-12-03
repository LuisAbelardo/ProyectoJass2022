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

/* /administration/reporte/recibo.twig */
class __TwigTemplate_f7d9ebbea56024017d01f7afda28b35d40ea2527b2f0de05a73f686f85f60506 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <meta name=\"viewport\" content=\"width=device-width, user-scalable=1.0, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
  <title>";
        // line 7
        echo twig_escape_filter($this->env, (isset($context["SITE_NAME"]) || array_key_exists("SITE_NAME", $context) ? $context["SITE_NAME"] : (function () { throw new RuntimeError('Variable "SITE_NAME" does not exist.', 7, $this->source); })()), "html", null, true);
        echo "</title>
  <link rel=\"stylesheet\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 8, $this->source); })()), "html", null, true);
        echo "/css/recibo_style.css\">
  
</head>
<body>
    <header class=\"clearfix\" >
        <div id=\"logo\">
            <img src=\"";
        // line 14
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 14, $this->source); })()), "html", null, true);
        echo "/img/logo.png\">
        </div>
        <div id=\"company\">
            <h2 class=\"name\">JUNTA ADMINISTRADORA DE SERVICIO DE SANEAMIENTO</h2>
            <div>MZ. D1 LT. 62 - CHOSICA DEL NORTE</div>
            <div>TELF. 074 - 431993</div>
            <div>R.U.C. 20479771856</div>
        </div>
    </header>
    <main>
    
    ";
        // line 25
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 25, $this->source); })()), "reciboDatos", [], "any", false, false, false, 25))) {
            // line 26
            echo "    \t";
            $context["datosGenerales"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 26, $this->source); })()), "reciboDatos", [], "any", false, false, false, 26), 0, [], "array", false, false, false, 26);
            // line 27
            echo "    \t<div id=\"details\" class=\"clearfix\">
            <div id=\"client\">
                <div class=\"to\">Su código es: ";
            // line 29
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 29, $this->source); })()), "CONTRATO", [], "any", false, false, false, 29), "html", null, true);
            echo "</div>
                <h2 class=\"l_cliente\">";
            // line 30
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 30, $this->source); })()), "CLIENTE", [], "any", false, false, false, 30), "html", null, true);
            echo "</h2>
                <div class=\"address\">";
            // line 31
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 31, $this->source); })()), "DIRECCION", [], "any", false, false, false, 31), "html", null, true);
            echo "</div>
            </div>
            <div id=\"invoice\">
                <h1 class=\"\">";
            // line 34
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 34, $this->source); })()), "PERIODO", [], "any", false, false, false, 34), "html", null, true);
            echo "</h1>
                <div class=\"date l_normal\">Recibo N°: ";
            // line 35
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 35, $this->source); })()), "CODIGO", [], "any", false, false, false, 35), "html", null, true);
            echo "</div>
                <div class=\"date l_normal\">Ultimo día de Pago: ";
            // line 36
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 36, $this->source); })()), "ULT_DIA_PAGO", [], "any", false, false, false, 36), "html", null, true);
            echo "</div>
                ";
            // line 37
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 37, $this->source); })()), "FECHA_CORTE", [], "any", false, false, false, 37))) {
                // line 38
                echo "                \t<div class=\"date l_corte\">Fecha de Corte: ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 38, $this->source); })()), "FECHA_CORTE", [], "any", false, false, false, 38), "html", null, true);
                echo "</div>
                ";
            }
            // line 40
            echo "            </div>
        </div>
    ";
        }
        // line 43
        echo "        
        <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <thead>
                <tr>
                    <th class=\"no\">#</th>
                    <th class=\"desc\">DETALLE DE LA FACTURACIÓN</th>
                    <th class=\"total\">IMPORTE</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 53
        $context["countDetalle"] = 0;
        // line 54
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 54, $this->source); })()), "reciboDetalle", [], "any", false, false, false, 54));
        foreach ($context['_seq'] as $context["_key"] => $context["detalle"]) {
            // line 55
            echo "                \t";
            if ((twig_get_attribute($this->env, $this->source, $context["detalle"], "AGREGAR_MONTO", [], "any", false, false, false, 55) == "0")) {
                // line 56
                echo "                    \t";
                $context["countDetalle"] = ((isset($context["countDetalle"]) || array_key_exists("countDetalle", $context) ? $context["countDetalle"] : (function () { throw new RuntimeError('Variable "countDetalle" does not exist.', 56, $this->source); })()) + 1);
                // line 57
                echo "                        <tr>
                            <td class=\"no\">";
                // line 58
                echo twig_escape_filter($this->env, (isset($context["countDetalle"]) || array_key_exists("countDetalle", $context) ? $context["countDetalle"] : (function () { throw new RuntimeError('Variable "countDetalle" does not exist.', 58, $this->source); })()), "html", null, true);
                echo "</td>
                            <td class=\"desc\">";
                // line 59
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["detalle"], "DESCRIPCION", [], "any", false, false, false, 59), "html", null, true);
                echo "</td>
                            <td class=\"total\">S/. ";
                // line 60
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["detalle"], "MONTO", [], "any", false, false, false, 60), 2, ".", ","), "html", null, true);
                echo "</td>
                        </tr>
                    ";
            }
            // line 63
            echo "                 ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['detalle'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 64
        echo "                 
                 ";
        // line 65
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 65, $this->source); })()), "reciboDatos", [], "any", false, false, false, 65))) {
            // line 66
            echo "    \t            ";
            $context["detalleMonto"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 66, $this->source); })()), "reciboDatos", [], "any", false, false, false, 66), 0, [], "array", false, false, false, 66);
            // line 67
            echo "    \t            
    \t            ";
            // line 69
            echo "    \t            ";
            $context["montoIgv"] = 0;
            // line 70
            echo "    \t            ";
            if ((twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 70, $this->source); })()), "IGV", [], "any", false, false, false, 70) != 0)) {
                // line 71
                echo "    \t                ";
                $context["montoIgv"] = (twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 71, $this->source); })()), "MNTO_CONSUMO", [], "any", false, false, false, 71) * (twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 71, $this->source); })()), "IGV", [], "any", false, false, false, 71) / 100));
                // line 72
                echo "\t                ";
            }
            // line 73
            echo "\t                
\t                ";
            // line 75
            echo "\t                ";
            $context["subtotal"] = (twig_get_attribute($this->env, $this->source, (isset($context["detalleMonto"]) || array_key_exists("detalleMonto", $context) ? $context["detalleMonto"] : (function () { throw new RuntimeError('Variable "detalleMonto" does not exist.', 75, $this->source); })()), "MONTO_TOTAL", [], "any", false, false, false, 75) - (isset($context["montoIgv"]) || array_key_exists("montoIgv", $context) ? $context["montoIgv"] : (function () { throw new RuntimeError('Variable "montoIgv" does not exist.', 75, $this->source); })()));
            // line 76
            echo "    \t            
    \t            <tr>
                        <td colspan=\"2\" style=\"background-color:#FFF;\">SUBTOTAL:</td>
                        <td style=\"background-color:#FFF;\">S/. ";
            // line 79
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, (isset($context["subtotal"]) || array_key_exists("subtotal", $context) ? $context["subtotal"] : (function () { throw new RuntimeError('Variable "subtotal" does not exist.', 79, $this->source); })()), 2, ".", ","), "html", null, true);
            echo "</td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" style=\"background-color:#FFF;\">IGV(";
            // line 82
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 82, $this->source); })()), "IGV", [], "any", false, false, false, 82), "html", null, true);
            echo "%):</td>
                        <td style=\"background-color:#FFF;\">S/. ";
            // line 83
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, (isset($context["montoIgv"]) || array_key_exists("montoIgv", $context) ? $context["montoIgv"] : (function () { throw new RuntimeError('Variable "montoIgv" does not exist.', 83, $this->source); })()), 2, ".", ","), "html", null, true);
            echo "</td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" style=\"background-color:#FFF;\">TOTAL ";
            // line 86
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 86, $this->source); })()), "PERIODO", [], "any", false, false, false, 86), "html", null, true);
            echo ":</td>
                        <td style=\"background-color:#FFF;\">S/. ";
            // line 87
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["detalleMonto"]) || array_key_exists("detalleMonto", $context) ? $context["detalleMonto"] : (function () { throw new RuntimeError('Variable "detalleMonto" does not exist.', 87, $this->source); })()), "MONTO_TOTAL", [], "any", false, false, false, 87), 2, ".", ","), "html", null, true);
            echo "</td>
                    </tr>
\t            ";
        }
        // line 90
        echo "                 
                 ";
        // line 91
        $context["agregarMontoTotal"] = 0;
        // line 92
        echo "                 ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 92, $this->source); })()), "reciboDetalle", [], "any", false, false, false, 92));
        foreach ($context['_seq'] as $context["_key"] => $context["detalle"]) {
            // line 93
            echo "                \t";
            if ((twig_get_attribute($this->env, $this->source, $context["detalle"], "AGREGAR_MONTO", [], "any", false, false, false, 93) == "1")) {
                // line 94
                echo "                    \t";
                $context["countDetalle"] = ((isset($context["countDetalle"]) || array_key_exists("countDetalle", $context) ? $context["countDetalle"] : (function () { throw new RuntimeError('Variable "countDetalle" does not exist.', 94, $this->source); })()) + 1);
                // line 95
                echo "                        <tr>
                            <td class=\"no\">";
                // line 96
                echo twig_escape_filter($this->env, (isset($context["countDetalle"]) || array_key_exists("countDetalle", $context) ? $context["countDetalle"] : (function () { throw new RuntimeError('Variable "countDetalle" does not exist.', 96, $this->source); })()), "html", null, true);
                echo "</td>
                            <td class=\"desc\">";
                // line 97
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["detalle"], "DESCRIPCION", [], "any", false, false, false, 97), "html", null, true);
                echo "</td>
                            <td class=\"total\">S/. ";
                // line 98
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["detalle"], "MONTO", [], "any", false, false, false, 98), 2, ".", ","), "html", null, true);
                echo "</td>
                        </tr>
                        ";
                // line 100
                $context["agregarMontoTotal"] = ((isset($context["agregarMontoTotal"]) || array_key_exists("agregarMontoTotal", $context) ? $context["agregarMontoTotal"] : (function () { throw new RuntimeError('Variable "agregarMontoTotal" does not exist.', 100, $this->source); })()) + twig_get_attribute($this->env, $this->source, $context["detalle"], "MONTO", [], "any", false, false, false, 100));
                // line 101
                echo "                    ";
            }
            // line 102
            echo "                 ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['detalle'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 103
        echo "                
            </tbody>
            <tfoot>
            \t";
        // line 106
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 106, $this->source); })()), "reciboDatos", [], "any", false, false, false, 106))) {
            // line 107
            echo "    \t            ";
            $context["detalleMonto"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 107, $this->source); })()), "reciboDatos", [], "any", false, false, false, 107), 0, [], "array", false, false, false, 107);
            // line 108
            echo "    \t            ";
            $context["subtotal"] = (twig_get_attribute($this->env, $this->source, (isset($context["detalleMonto"]) || array_key_exists("detalleMonto", $context) ? $context["detalleMonto"] : (function () { throw new RuntimeError('Variable "detalleMonto" does not exist.', 108, $this->source); })()), "MONTO_TOTAL", [], "any", false, false, false, 108) / 1.1799999999999999378275106209912337362766265869140625);
            // line 109
            echo "                    <tr>
                        <td colspan=\"2\">TOTAL A PAGAR:</td>
                        <td>S/. ";
            // line 111
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, (twig_get_attribute($this->env, $this->source, (isset($context["detalleMonto"]) || array_key_exists("detalleMonto", $context) ? $context["detalleMonto"] : (function () { throw new RuntimeError('Variable "detalleMonto" does not exist.', 111, $this->source); })()), "MONTO_TOTAL", [], "any", false, false, false, 111) + (isset($context["agregarMontoTotal"]) || array_key_exists("agregarMontoTotal", $context) ? $context["agregarMontoTotal"] : (function () { throw new RuntimeError('Variable "agregarMontoTotal" does not exist.', 111, $this->source); })())), 2, ".", ","), "html", null, true);
            echo "</td>
                    </tr>
\t            ";
        }
        // line 114
        echo "            </tfoot>
        </table>
        
        
        ";
        // line 118
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 118, $this->source); })()), "reciboOtros", [], "any", false, false, false, 118))) {
            // line 119
            echo "        <table class= \"t_otros\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> 
            <thead> 
                <tr> 
                    <th class=\"desc\">OTROS</th> 
                </tr> 
            </thead> 
            <tbody> 
                ";
            // line 126
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 126, $this->source); })()), "reciboOtros", [], "any", false, false, false, 126));
            foreach ($context['_seq'] as $context["_key"] => $context["otro"]) {
                // line 127
                echo "                \t<tr> 
                \t\t<td class=\"desc\">";
                // line 128
                if ( !twig_test_empty($context["otro"])) {
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["otro"], "OTROS", [], "any", false, false, false, 128), "html", null, true);
                }
                echo "</td>
                \t</tr> 
            \t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['otro'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 131
            echo "            </tbody>   
        </table>
        ";
        }
        // line 134
        echo "        
        <div id=\"notices\">
            <div>ALTO:</div>
            <div class=\"notice\">NO DESPERDICIE EL AGUA, NO BOTÉ BASURA AL DESAGUE: PROVOCAN ATOROS EN LA RED.</div>
        </div>
        <br>
        <div id=\"thanks\">Muchas Gracias!</div>
    </main>
    <footer>
        SI USTED TIENE DOS MESES IMPAGOS, ESTA SUJETO AL CORTE DE SERVICIO SIN PREVIO AVISO.
    </footer>
</body>
</html>

";
    }

    public function getTemplateName()
    {
        return "/administration/reporte/recibo.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  334 => 134,  329 => 131,  318 => 128,  315 => 127,  311 => 126,  302 => 119,  300 => 118,  294 => 114,  288 => 111,  284 => 109,  281 => 108,  278 => 107,  276 => 106,  271 => 103,  265 => 102,  262 => 101,  260 => 100,  255 => 98,  251 => 97,  247 => 96,  244 => 95,  241 => 94,  238 => 93,  233 => 92,  231 => 91,  228 => 90,  222 => 87,  218 => 86,  212 => 83,  208 => 82,  202 => 79,  197 => 76,  194 => 75,  191 => 73,  188 => 72,  185 => 71,  182 => 70,  179 => 69,  176 => 67,  173 => 66,  171 => 65,  168 => 64,  162 => 63,  156 => 60,  152 => 59,  148 => 58,  145 => 57,  142 => 56,  139 => 55,  134 => 54,  132 => 53,  120 => 43,  115 => 40,  109 => 38,  107 => 37,  103 => 36,  99 => 35,  95 => 34,  89 => 31,  85 => 30,  81 => 29,  77 => 27,  74 => 26,  72 => 25,  58 => 14,  49 => 8,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/reporte/recibo.twig", "/home/franco/proyectos/php/jass/resources/views/administration/reporte/recibo.twig");
    }
}
