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

/* 404.twig */
class __TwigTemplate_ea9aa721e11e56e29ebda59182761a6ce727892339c60b000cc9ac56d2a0c274 extends \Twig\Template
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
<html lang=\"es\">

<head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <title>";
        // line 7
        echo twig_escape_filter($this->env, (isset($context["SITE_NAME"]) || array_key_exists("SITE_NAME", $context) ? $context["SITE_NAME"] : (function () { throw new RuntimeError('Variable "SITE_NAME" does not exist.', 7, $this->source); })()), "html", null, true);
        echo "</title>
\t<!-- Favicon -->
    <link rel=\"shortcut icon\" type=\"image/png\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/img/favicon.ico\"/>
\t<!-- Google font -->
\t<link href=\"https://fonts.googleapis.com/css?family=Passion+One\" rel=\"stylesheet\">
\t<!-- Font Awesome -->
\t<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css\" integrity=\"sha512-L7MWcK7FNPcwNqnLdZq86lTHYLdQqZaz5YcAgE+5cnGmlw8JT03QB2+oxL100UeB6RlzZLUxCGSS4/++mNZdxw==\" crossorigin=\"anonymous\" />
\t<!-- Bootstrap -->
\t<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css\" integrity=\"sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l\" crossorigin=\"anonymous\">
\t<!-- Custom Theme -->
\t<link rel=\"stylesheet\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 17, $this->source); })()), "html", null, true);
        echo "/css/private.css\">
</head>

<body>
\t<div id=\"notfound\">
\t\t<div class=\"notfound\">
\t\t\t<div class=\"notfound-404\">
\t\t\t\t<h1>:(</h1>
\t\t\t</div>
\t\t\t<h2 class=\"notfound__header\">404 - Página no encontrada</h2>
\t\t\t<p>
                La página que está buscando podría haberse eliminado, 
                cambiado su nombre o no está disponible temporalmente.
            </p>
\t\t\t<a href=\"javascript:history.back()\" class=\"f_linkbtn\">Regresar</a>
\t\t</div>
\t</div>
</html>";
    }

    public function getTemplateName()
    {
        return "404.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 17,  50 => 9,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "404.twig", "/home/franco/proyectos/php/jass/resources/views/404.twig");
    }
}
