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

/* /administration/reporte/ticketEgreso.twig */
class __TwigTemplate_954f35980afb10240a7b1590a3b1718dd08801e4775696a0885ce5e524f9415e extends \Twig\Template
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
<html lang=\"en\">
<head>
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, user-scalable=1.0, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <title>";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["SITE_NAME"]) || array_key_exists("SITE_NAME", $context) ? $context["SITE_NAME"] : (function () { throw new RuntimeError('Variable "SITE_NAME" does not exist.', 6, $this->source); })()), "html", null, true);
        echo "</title>
    <link rel=\"stylesheet\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 7, $this->source); })()), "html", null, true);
        echo "/css/ticket_style.css\">
</head>
<body> 
    <header class=\"t_center\">
        <div id=\"logo\">
            <img src=\"";
        // line 12
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 12, $this->source); })()), "html", null, true);
        echo "/img/logo.png\" style=\"width:50px\">
            <div class=\"t_center\">MZ. D1 LT. 62 </div>
            <div class=\"t_center\">CHOSICA DEL NORTE - LA VICTORIA - CHICLAYO</div>
            <div class=\"t_center\">TELF. 074 - 431993</div>
            <div class=\"t_center\">RUC 20479771856</div>
        </div>
    </header>
    <br>
    ";
        // line 20
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 20, $this->source); })()), "datosTicket", [], "any", false, false, false, 20))) {
            // line 21
            echo "        ";
            $context["datosGenerales"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 21, $this->source); })()), "datosTicket", [], "any", false, false, false, 21), 0, [], "array", false, false, false, 21);
            // line 22
            echo "        <main>
            <div class=\"t_center name\">RECIBO DE EGRESO</div>
            <div class=\"t_center num\">";
            // line 24
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 24, $this->source); })()), "REF", [], "any", false, false, false, 24), "html", null, true);
            echo "</div>
            <div class=\"t_right\">OP.: ";
            // line 25
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 25, $this->source); })()), "FEC_EMISION", [], "any", false, false, false, 25), "html", null, true);
            echo "</div>
            <div class=\"datos\">
                <div>NRO. COMPROBANTE: ";
            // line 27
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 27, $this->source); })()), "CODIGO", [], "any", false, false, false, 27), "html", null, true);
            echo "</div>
                <div>RECIBO DE: ";
            // line 28
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 28, $this->source); })()), "CAJERO", [], "any", false, false, false, 28), "html", null, true);
            echo "</div>
                <div>LA SUMA DE: ";
            // line 29
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 29, $this->source); })()), "cantidadLetras", [], "any", false, false, false, 29), "html", null, true);
            echo "</div>
                <div>CONCEPTO: ";
            // line 30
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 30, $this->source); })()), "CONCEPTO", [], "any", false, false, false, 30), "html", null, true);
            echo "</div>
            </div>
            </br>
            <table class=\"t_detalles\">
                <tr>    
                    <td>TOTAL </td>
                    <td>: S/. **********</td>
                    <td class=\"total\">";
            // line 37
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 37, $this->source); })()), "CANTIDAD", [], "any", false, false, false, 37), 2, ".", ","), "html", null, true);
            echo "</td>
                </tr>
                <tr>    
                <td>DOC. EMITIDO</td>
                <td colspan=\"2\">: ";
            // line 41
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["datosGenerales"]) || array_key_exists("datosGenerales", $context) ? $context["datosGenerales"] : (function () { throw new RuntimeError('Variable "datosGenerales" does not exist.', 41, $this->source); })()), "TIPO_EMISION", [], "any", false, false, false, 41), "html", null, true);
            echo "</td>
            </tr>
            </table>
        </main>
        <footer>
            <p>NOTA: Este ticket es constancia de la operación efectuado.</p>
        </footer>
    ";
        }
        // line 49
        echo "</body>
</html>";
    }

    public function getTemplateName()
    {
        return "/administration/reporte/ticketEgreso.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  125 => 49,  114 => 41,  107 => 37,  97 => 30,  93 => 29,  89 => 28,  85 => 27,  80 => 25,  76 => 24,  72 => 22,  69 => 21,  67 => 20,  56 => 12,  48 => 7,  44 => 6,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/reporte/ticketEgreso.twig", "/home/franco/proyectos/php/jass/resources/views/administration/reporte/ticketEgreso.twig");
    }
}
