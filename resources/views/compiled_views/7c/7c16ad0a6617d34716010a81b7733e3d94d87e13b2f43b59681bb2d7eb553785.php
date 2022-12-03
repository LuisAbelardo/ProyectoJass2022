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

/* /administration/reporte/ticketIngreso.twig */
class __TwigTemplate_7f77026caee87cfd697139483764b531f6b645372fe2284da79109c06cbc17f4 extends \Twig\Template
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
        echo "
<!DOCTYPE html>
<html>
<head>
<meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, user-scalable=1.0, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <title>";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["SITE_NAME"]) || array_key_exists("SITE_NAME", $context) ? $context["SITE_NAME"] : (function () { throw new RuntimeError('Variable "SITE_NAME" does not exist.', 8, $this->source); })()), "html", null, true);
        echo "</title>
    <link rel=\"stylesheet\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/css/ticket_style.css\">
</head>
<body>
\t<header class=\"t_center\">
        <div id=\"logo\">
            <img src=\"";
        // line 14
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 14, $this->source); })()), "html", null, true);
        echo "/img/logo.png\" style=\"width:50px\">
            <div class=\"t_center\">MZ. D1 LT. 62 </div>
            <div class=\"t_center\">CHOSICA DEL NORTE - LA VICTORIA - CHICLAYO</div>
            <div class=\"t_center\">TELF. 074 - 431993</div>
            <div class=\"t_center\">RUC 20479771856</div>
        </div>
    </header>
    <br>
    ";
        // line 22
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 22, $this->source); })()), "datosTicket", [], "any", false, false, false, 22))) {
            // line 23
            echo "    \t";
            $context["datosGenerales"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 23, $this->source); })()), "datosTicket", [], "any", false, false, false, 23), 0, [], "array", false, false, false, 23);
            // line 24
            echo "        <main>
            <div class=\"t_center name\">RECIBO DE INGRESO</div>
            <div class=\"t_center num\"> ";
            // line 26
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 26, $this->source); })()), "CODIGO", [], "any", false, false, false, 26), "html", null, true);
            echo "</div>
            <div class=\"t_right\">OP.: ";
            // line 27
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 27, $this->source); })()), "FEC_EMISION", [], "any", false, false, false, 27), "html", null, true);
            echo "</div>
            
            ";
            // line 29
            if ((twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 29, $this->source); })()), "ingresoTipo", [], "any", false, false, false, 29) == "RBO")) {
                // line 30
                echo "                <div class=\"datos\">
                    <div>CO. CLIENTE: ";
                // line 31
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 31, $this->source); })()), "COD_CLIENTE", [], "any", false, false, false, 31), "html", null, true);
                echo "</div>
                    <div>NOMBRES: ";
                // line 32
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 32, $this->source); })()), "NOMBRES", [], "any", false, false, false, 32), "html", null, true);
                echo "</div>
                    <div>CONCEPTO: ";
                // line 33
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 33, $this->source); })()), "SERVICIO", [], "any", false, false, false, 33), "html", null, true);
                echo "</div>
                    <div>PERIODO: ";
                // line 34
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 34, $this->source); })()), "REF", [], "any", false, false, false, 34), "html", null, true);
                echo "</div>
                </div>
                </br>
                <table class=\"t_detalles\">
                    <tr>    
                        <td>OP. GRAVADA</td>
                        <td>: S/. **********</td>
                        <td class=\"total\">";
                // line 41
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, (twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 41, $this->source); })()), "CANTIDAD", [], "any", false, false, false, 41) - twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 41, $this->source); })()), "IGV", [], "any", false, false, false, 41)), 2, ".", ","), "html", null, true);
                echo "</td>
                    </tr>
                    <tr>    
                        <td>I.G.V.</td>
                        <td>: S/. **********</td>
                        <td class=\"total\">";
                // line 46
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 46, $this->source); })()), "IGV", [], "any", false, false, false, 46), 2, ".", ","), "html", null, true);
                echo "</td>
                    </tr>
                    <tr>    
                        <td>TOTAL A PAGAR</td>
                        <td>: S/. **********</td>
                        <td class=\"total\">";
                // line 51
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 51, $this->source); })()), "CANTIDAD", [], "any", false, false, false, 51), 2, ".", ","), "html", null, true);
                echo "</td>
                    </tr>
                </table>

            ";
            } elseif ((twig_get_attribute($this->env, $this->source,             // line 55
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 55, $this->source); })()), "ingresoTipo", [], "any", false, false, false, 55) == "CUE")) {
                // line 56
                echo "                <div class=\"datos\">
                    <div>USUARIO: ";
                // line 57
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 57, $this->source); })()), "COD_CONTRATO", [], "any", false, false, false, 57), "html", null, true);
                echo "</div>
                    <div>NOMBRES: ";
                // line 58
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 58, $this->source); })()), "NOMBRES", [], "any", false, false, false, 58), "html", null, true);
                echo "</div>
                    <div>CONCEPTO: PROYECTO - ";
                // line 59
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 59, $this->source); })()), "PROYECTO", [], "any", false, false, false, 59), "html", null, true);
                echo "</div>
                    <div>CUOTA: ";
                // line 60
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 60, $this->source); })()), "REF", [], "any", false, false, false, 60), "html", null, true);
                echo "</div>
                </div>
                </br>
                <table class=\"t_detalles\">
                    <tr>    
                        <td>TOTAL A PAGAR</td>
                        <td>: S/. **********</td>
                        <td class=\"total\">";
                // line 67
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 67, $this->source); })()), "CANTIDAD", [], "any", false, false, false, 67), 2, ".", ","), "html", null, true);
                echo "</td>
                    </tr>
                </table>
        \t";
            } elseif ((twig_get_attribute($this->env, $this->source,             // line 70
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 70, $this->source); })()), "ingresoTipo", [], "any", false, false, false, 70) == "OTRO")) {
                // line 71
                echo "                <div class=\"datos\">
                    <div>CONCEPTO: ";
                // line 72
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 72, $this->source); })()), "CONCEPTO", [], "any", false, false, false, 72), "html", null, true);
                echo "</div>
                </div>
                </br>
                <table class=\"t_detalles\">
                    <tr>    
                        <td>TOTAL A PAGAR</td>
                        <td>: S/. **********</td>
                        <td class=\"total\">";
                // line 79
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 79, $this->source); })()), "CANTIDAD", [], "any", false, false, false, 79), 2, ".", ","), "html", null, true);
                echo "</td>
                    </tr>
                </table>
        \t";
            }
            // line 83
            echo "        \t
                <table class=\"t_operacion\">
                <tr>    
                    <td colspan=\"3\">SON: ";
            // line 86
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 86, $this->source); })()), "cantidadLetras", [], "any", false, false, false, 86), "html", null, true);
            echo "</td>
                </tr>
                <tr>    
                    <td>PAGA CON</td>
                    <td>: S/. </td>
                    <td class=\"qty\">";
            // line 91
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 91, $this->source); })()), "MNTO_RECIBIDO", [], "any", false, false, false, 91), 2, ".", ","), "html", null, true);
            echo "</td>
                </tr>
                <tr>    
                    <td>VUELTO</td>
                    <td>: S/. </td>
                    <td class=\"qty\">";
            // line 96
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, (twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 96, $this->source); })()), "MNTO_RECIBIDO", [], "any", false, false, false, 96) - twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 96, $this->source); })()), "CANTIDAD", [], "any", false, false, false, 96)), 2, ".", ","), "html", null, true);
            echo "</td>
                </tr>
                <tr>    
                    <td>TIPO DE PAGO</td>
                    <td colspan=\"2\">: ";
            // line 100
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 100, $this->source); })()), "TIPO_PAGO", [], "any", false, false, false, 100), "html", null, true);
            echo "</td>
                </tr>
                <tr>    
                    <td>CAJERO</td>
                    <td colspan=\"2\">: ";
            // line 104
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 104, $this->source); })()), "CAJERO", [], "any", false, false, false, 104), "html", null, true);
            echo "</td>
                </tr>
            </table>
        </main>
        <footer>
            <p>NOTA: Este ticket es constancia del pago efectuado.</p>
        </footer>
    ";
        }
        // line 112
        echo "</body>
</html>";
    }

    public function getTemplateName()
    {
        return "/administration/reporte/ticketIngreso.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  238 => 112,  227 => 104,  220 => 100,  213 => 96,  205 => 91,  197 => 86,  192 => 83,  185 => 79,  175 => 72,  172 => 71,  170 => 70,  164 => 67,  154 => 60,  150 => 59,  146 => 58,  142 => 57,  139 => 56,  137 => 55,  130 => 51,  122 => 46,  114 => 41,  104 => 34,  100 => 33,  96 => 32,  92 => 31,  89 => 30,  87 => 29,  82 => 27,  78 => 26,  74 => 24,  71 => 23,  69 => 22,  58 => 14,  50 => 9,  46 => 8,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/reporte/ticketIngreso.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\reporte\\ticketIngreso.twig");
    }
}
