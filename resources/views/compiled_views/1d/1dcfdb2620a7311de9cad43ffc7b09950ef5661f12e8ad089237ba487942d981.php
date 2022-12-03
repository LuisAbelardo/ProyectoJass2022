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

/* administration/access/login.twig */
class __TwigTemplate_bb82e9b97b38c3d6c1d8c083c3728405f7373bf20ce446baa90e233812d712a9 extends \Twig\Template
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
  <!-- Favicon -->
  <link rel=\"shortcut icon\" type=\"image/png\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/img/favicon.ico\"/>
  <!-- Font Awesome -->
  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css\" integrity=\"sha512-L7MWcK7FNPcwNqnLdZq86lTHYLdQqZaz5YcAgE+5cnGmlw8JT03QB2+oxL100UeB6RlzZLUxCGSS4/++mNZdxw==\" crossorigin=\"anonymous\" />
  <!-- Bootstrap -->
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\" integrity=\"sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh\" crossorigin=\"anonymous\">
  <!-- AdminLTE -->
  <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css\">
  <!-- Custom Theme -->
  <link rel=\"stylesheet\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 17, $this->source); })()), "html", null, true);
        echo "/css/private.css\">
  
  
  <style type=\"text/css\">
    /* Estilos para logo de empresa desarrolladora */
  \t.creador{
  \t\t font-size: .65rem;
  \t}
    /* Estilos para el nombre de la empresa */
    .empresa{
      font-size: .45em;
      color: #c0c0c0;
    }
  </style>
  
</head>
<body class=\"hold-transition login-page\" style=\"background-color:#155db1\">
  <div class=\"login-box\">
    <div class=\"login-logo\">
      <img src=\"";
        // line 36
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 36, $this->source); })()), "html", null, true);
        echo "/img/logo.png\" width=\"80\" height=\"80\"/>
      <div class=\"empresa\">JUNTA ADMINISTRADORA DE SERVICIOS DE SANEAMIENTO</div>
    </div>
    <!-- /.login-logo -->
    <div class=\"card pb-4 mt-4\">
      <div class=\"card-body login-card-body text-xs\">
        <p class=\"login-box-msg f-color-co h5\">Autentificación</p>

        ";
        // line 45
        echo "        ";
        $context["classAlert"] = "";
        // line 46
        echo "        ";
        if (twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 46, $this->source); })()))) {
            // line 47
            echo "        \t";
            $context["classAlert"] = "d-none";
            // line 48
            echo "        ";
        } elseif (((isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 48, $this->source); })()) >= 400)) {
            // line 49
            echo "            ";
            $context["classAlert"] = "alert-danger";
            // line 50
            echo "        ";
        }
        // line 51
        echo "        <div class=\"alert ";
        echo twig_escape_filter($this->env, (isset($context["classAlert"]) || array_key_exists("classAlert", $context) ? $context["classAlert"] : (function () { throw new RuntimeError('Variable "classAlert" does not exist.', 51, $this->source); })()), "html", null, true);
        echo " alert-dismissible fade show\" id=\"f_alertsContainer\" role=\"alert\">
          <ul class=\"mb-0\" id=\"f_alertsUl\">
            ";
        // line 53
        if ( !twig_test_empty((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 53, $this->source); })()))) {
            // line 54
            echo "              ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["estadoDetalle"]) || array_key_exists("estadoDetalle", $context) ? $context["estadoDetalle"] : (function () { throw new RuntimeError('Variable "estadoDetalle" does not exist.', 54, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["msj"]) {
                // line 55
                echo "                <li>";
                echo twig_escape_filter($this->env, $context["msj"], "html", null, true);
                echo "</li>
              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['msj'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 57
            echo "            ";
        }
        // line 58
        echo "          </ul>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\" id=\"f_alertsDismiss\">
            <span aria-hidden=\"true\">&times;</span>
          </button>
        </div>
        ";
        // line 64
        echo "
        <form id=\"formLogin\" class=\"f-form\" action=\"";
        // line 65
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 65, $this->source); })()), "html", null, true);
        echo "/login/verify\" method=\"POST\">
          <div class=\"input-group mb-3\">
            <input type=\"text\" id=\"inpUsuario\" class=\"form-control text-sm\" placeholder=\"Usuario\" name=\"user\" required>
            <div class=\"input-group-append\">
              <div class=\"input-group-text\">
                <span class=\"fas fa-user\"></span>
              </div>
            </div>
          </div>
          <div class=\"input-group mb-3\">
            <input type=\"password\" id=\"inpPassword\" class=\"form-control text-sm\" placeholder=\"Password\" name=\"password\" required>
            <div class=\"input-group-append\">
              <div class=\"input-group-text\">
                <span class=\"fas fa-key\"></span>
              </div>
            </div>
          </div>
          <div class=\"row text-xs d-flex align-items-center\">
            <div class=\"col-5 text-center\">
                <button type=\"submit\" id=\"btnEnviarLogin\" 
                \t\tclass=\"btn btn-default text-sm px-5\">
                \tEntrar</button>
            </div>
            <div class=\"col-7 text-right\">
              <a class=\"f_link \" href=\"";
        // line 89
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 89, $this->source); })()), "html", null, true);
        echo "/recuperarpassword\">Olvidaste tu contraseña?</a>
            </div>
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->
  
  <div class=\"text-small text-muted text-center mt-4 creador\">
  \t<span><b>BUNKER ERP</b></span>
  \t<div>DESARROLLO DE APLICACIONES</div>
  \t<div>E-mail: informes@bunker.com</div>
  \t<div>J.A.S.S V1.0.0</div>
  </div>

  <!-- JQuery -->
  <script src=\"https://code.jquery.com/jquery-3.5.1.slim.min.js\" integrity=\"sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj\" crossorigin=\"anonymous\"></script>
  <!-- Bootstrap -->
  <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns\" crossorigin=\"anonymous\"></script>
  <!-- AdminLTE -->
  <script src=\"https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js\"></script>
  <!-- Commons -->
  <script src=\"";
        // line 113
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 113, $this->source); })()), "html", null, true);
        echo "/js/commons.js\"></script>
  <!-- Custom Theme -->
  <script src=\"";
        // line 115
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 115, $this->source); })()), "html", null, true);
        echo "/js/private.js\"></script>
  
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "administration/access/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  206 => 115,  201 => 113,  174 => 89,  147 => 65,  144 => 64,  137 => 58,  134 => 57,  125 => 55,  120 => 54,  118 => 53,  112 => 51,  109 => 50,  106 => 49,  103 => 48,  100 => 47,  97 => 46,  94 => 45,  83 => 36,  61 => 17,  50 => 9,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "administration/access/login.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\access\\login.twig");
    }
}
