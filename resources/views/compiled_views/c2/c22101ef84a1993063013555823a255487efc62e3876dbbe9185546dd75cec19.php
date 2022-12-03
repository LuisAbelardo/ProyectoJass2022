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

/* administration/templateAdministration.twig */
class __TwigTemplate_00bee0aab087eb0529e8ebaee272039820cae5f7e4c0478aa99fd3d7c4e83e42 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'links' => [$this, 'block_links'],
            'main_header_left' => [$this, 'block_main_header_left'],
            'content' => [$this, 'block_content'],
            'pag_head' => [$this, 'block_pag_head'],
            'pag_head_title' => [$this, 'block_pag_head_title'],
            'pag_head_floatRight' => [$this, 'block_pag_head_floatRight'],
            'content_main' => [$this, 'block_content_main'],
            'footer' => [$this, 'block_footer'],
            'right_sidebar' => [$this, 'block_right_sidebar'],
            'scripts' => [$this, 'block_scripts'],
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
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <meta name=\"viewport\" content=\"width=device-width, user-scalable=1.0, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
  <title>";
        // line 7
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
  ";
        // line 8
        $this->displayBlock('links', $context, $blocks);
        // line 22
        echo "</head>
<body class=\"layout-fixed hold-transition sidebar-mini text-sm\">
  <!-- Site wrapper -->
  <div class=\"wrapper\">
    <!-- Navbar -->
    <nav class=\"main-header navbar navbar-expand navbar-white navbar-light\">
      <!-- Left navbar links -->
      <ul class=\"navbar-nav\">
        <li class=\"nav-item\">
          <a class=\"nav-link\" data-widget=\"pushmenu\" href=\"#\" role=\"button\"><i class=\"fas fa-bars\"></i></a>
        </li>
        
        <li class=\"nav-item d-none d-sm-inline-block\">
          ";
        // line 35
        $this->displayBlock('main_header_left', $context, $blocks);
        // line 38
        echo "        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class=\"navbar-nav ml-auto\">
        ";
        // line 66
        echo "<!-- Messages Dropdown Menu -->
        <!-- Fin-Messages Dropdown Menu -->
        
        ";
        // line 94
        echo "        
        <li class=\"nav-item\">
          <a class=\"nav-link\" data-widget=\"fullscreen\" href=\"#\" role=\"button\">
            <i class=\"fas fa-expand-arrows-alt\"></i>
          </a>
        </li>
        <li class=\"nav-item\">
          <a class=\"nav-link\" data-widget=\"control-sidebar\" data-slide=\"true\" href=\"#\" role=\"button\">
            <i class=\"fas fa-th-large\"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class=\"main-sidebar sidebar-dark-primary elevation-4\">
      <!-- Brand Logo -->
      <a href=\"#\" class=\"brand-link\">
        <img src=\"";
        // line 113
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 113, $this->source); })()), "html", null, true);
        echo "/img/logo.png\"
            alt=\"AdminLTE Logo\"
            class=\"brand-image img-circle elevation-3\"
            style=\"opacity: .8\">
        <span class=\"brand-text font-weight-light\">";
        // line 117
        echo twig_escape_filter($this->env, (isset($context["SITE_NAME"]) || array_key_exists("SITE_NAME", $context) ? $context["SITE_NAME"] : (function () { throw new RuntimeError('Variable "SITE_NAME" does not exist.', 117, $this->source); })()), "html", null, true);
        echo "</span>
      </a>

      <!-- Sidebar -->
      <div class=\"sidebar\">
      \t";
        // line 132
        echo "
        <!-- Sidebar Menu -->
        <nav class=\"mt-2\">
          <ul class=\"nav nav-pills nav-sidebar flex-column nav-compact\" data-widget=\"treeview\" role=\"menu\" data-accordion=\"false\">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
\t\t\t";
        // line 138
        $this->loadTemplate("/administration/partials/menuLeft.twig", "administration/templateAdministration.twig", 138)->display($context);
        // line 139
        echo "          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class=\"content-wrapper\">
      ";
        // line 148
        $this->displayBlock('content', $context, $blocks);
        // line 182
        echo "    </div>
    <!-- /.content-wrapper -->

    <footer class=\"main-footer\">
      ";
        // line 186
        $this->displayBlock('footer', $context, $blocks);
        // line 192
        echo "    </footer>

    <!-- Control Sidebar -->
    <aside class=\"control-sidebar\">
      <!-- Control sidebar content goes here -->
      <div class=\"card-logout p-3\">
        <h1 class=\"card-logout__title h6 text-bold border-bottom pb-2\">Mi Cuenta</h1>
        <div class=\"d-flex pt-2\">
          <div class=\"card-logout__img\"><img src=\"";
        // line 200
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["SESSION"]) || array_key_exists("SESSION", $context) ? $context["SESSION"] : (function () { throw new RuntimeError('Variable "SESSION" does not exist.', 200, $this->source); })()), "jass_user", [], "array", false, false, false, 200), "imageUrl", [], "array", false, false, false, 200), "html", null, true);
        echo "\" alt=\"\"></div>
          <div class=\"pl-2\">
            <span class=\"card-logout__user text-sm text-bold\">";
        // line 202
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["SESSION"]) || array_key_exists("SESSION", $context) ? $context["SESSION"] : (function () { throw new RuntimeError('Variable "SESSION" does not exist.', 202, $this->source); })()), "jass_user", [], "array", false, false, false, 202), "nombre", [], "array", false, false, false, 202) . " ") . twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["SESSION"]) || array_key_exists("SESSION", $context) ? $context["SESSION"] : (function () { throw new RuntimeError('Variable "SESSION" does not exist.', 202, $this->source); })()), "jass_user", [], "array", false, false, false, 202), "apellidos", [], "array", false, false, false, 202)), "html", null, true);
        echo "</span><br/>
            <span class=\"text-sm\">(";
        // line 203
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["SESSION"]) || array_key_exists("SESSION", $context) ? $context["SESSION"] : (function () { throw new RuntimeError('Variable "SESSION" does not exist.', 203, $this->source); })()), "jass_user", [], "array", false, false, false, 203), "rol_nombre", [], "array", false, false, false, 203), "html", null, true);
        echo ")</span>
            <ul class=\"card-logout__options pt-1\">
              <li><i class=\"fas fa-sign-out-alt mr-1\"></i><a href=\"";
        // line 205
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 205, $this->source); })()), "html", null, true);
        echo "/logout\">Salir</a></li>
            </ul>
          </div>
        </div>
      </div>

      ";
        // line 211
        $this->displayBlock('right_sidebar', $context, $blocks);
        // line 214
        echo "    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  ";
        // line 219
        $this->displayBlock('scripts', $context, $blocks);
        // line 283
        echo "</body>
</html>
";
    }

    // line 7
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["SITE_NAME"]) || array_key_exists("SITE_NAME", $context) ? $context["SITE_NAME"] : (function () { throw new RuntimeError('Variable "SITE_NAME" does not exist.', 7, $this->source); })()), "html", null, true);
        echo " ";
    }

    // line 8
    public function block_links($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 9
        echo "    <!-- Favicon -->
    <link rel=\"shortcut icon\" type=\"image/png\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 10, $this->source); })()), "html", null, true);
        echo "/img/favicon.ico\"/>
    <!-- Font Awesome -->
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css\" integrity=\"sha512-L7MWcK7FNPcwNqnLdZq86lTHYLdQqZaz5YcAgE+5cnGmlw8JT03QB2+oxL100UeB6RlzZLUxCGSS4/++mNZdxw==\" crossorigin=\"anonymous\" />
    <!-- Bootstrap -->
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css\" integrity=\"sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l\" crossorigin=\"anonymous\">
    <!-- AdminLTE -->
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css\">
    <!-- Select2 -->
    <link href=\"https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css\" rel=\"stylesheet\" />
    <!-- Custom Theme -->
    <link rel=\"stylesheet\" href=\"";
        // line 20
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 20, $this->source); })()), "html", null, true);
        echo "/css/private.css\">
  ";
    }

    // line 35
    public function block_main_header_left($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 36
        echo "          
          ";
    }

    // line 148
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 149
        echo "        <!-- Content Header (Page header) -->
        <section class=\"content-header\">
          <div class=\"container-fluid\">
            <div class=\"row mb-2\">
            \t";
        // line 153
        $this->displayBlock('pag_head', $context, $blocks);
        // line 163
        echo "            </div>
          </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class=\"content\">
        \t<div class=\"container-fluid\">
        \t\t<div class=\"row\">
        \t\t\t<div class=\"col-12\">
                        ";
        // line 172
        $this->displayBlock('content_main', $context, $blocks);
        // line 176
        echo "        \t\t\t</div>
        \t\t</div>
        \t</div>
        </section>
        <!-- /.content -->
      ";
    }

    // line 153
    public function block_pag_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 154
        echo "                  <div class=\"col-sm-6\">
                    <h1>";
        // line 155
        $this->displayBlock('pag_head_title', $context, $blocks);
        echo "</h1>
                  </div>
                  <div class=\"col-sm-6\">
                    <ol class=\"breadcrumb float-sm-right\">
                      ";
        // line 159
        $this->displayBlock('pag_head_floatRight', $context, $blocks);
        // line 160
        echo "                    </ol>
                  </div>
                ";
    }

    // line 155
    public function block_pag_head_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo " ";
    }

    // line 159
    public function block_pag_head_floatRight($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "  ";
    }

    // line 172
    public function block_content_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 173
        echo "                        <!-- Default box -->
                        
                        ";
    }

    // line 186
    public function block_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 187
        echo "          <div class=\"float-right d-none d-sm-block\">
            <b>JASS</b>
          </div>
          <strong>Copyright &copy; 2020</strong> All rights reserved.
        ";
    }

    // line 211
    public function block_right_sidebar($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 212
        echo "        
      ";
    }

    // line 219
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 220
        echo "    <!-- JQuery -->
    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\"></script>
    <!-- Bootstrap -->
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns\" crossorigin=\"anonymous\"></script>
    <!-- AdminLTE -->
    <script src=\"https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js\"></script>
    <!-- Select2 -->
    <script src=\"https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js\"></script>
    ";
        // line 232
        echo "    ";
        // line 233
        echo "    <script type=\"text/javascript\">
\t\t\$(\".alert-dismissible.alert-success\").fadeTo(2000, 1).slideUp(800, function(){
            \$(\".alert-dismissible.alert-success\").alert('close');
        });
\t</script>
\t
\t";
        // line 240
        echo "\t<script type=\"text/javascript\">
\t\tfunction f_select2(selector){
    \t\t\$(selector).select2({
                dir: 'ltr',
                width: 'resolve',
                minimumInputLength: 0,
                containerCssClass: ':all:',
    \t\t\tselectionCssClass: ':all:',
                dropdownCssClass: 'ui-dialog',
                language: {
                    noResults: function() {                 
                        return \"No hay resultado\";        
                    },
                    searching: function() {
                        return \"Buscando..\";
                    }
                },
                templateResult: function (data, container) {
                    if (data.element) { \$(container).addClass(\$(data.element).attr(\"class\")); }
                    if (data.id == -1 && \$(data.element).attr(\"data-html\") == undefined) {
                        return '&nbsp;';
                    }
                    if (\$(data.element).attr(\"data-html\") != undefined) return htmlEntityDecodeJs(\$(data.element).attr(\"data-html\"));
                    return data.text;
                },
                templateSelection: function (selection) {
                    if (selection.id == -1) return '<span class=\"placeholder\">'+selection.text+'</span>';
                    return selection.text;
                },
                escapeMarkup: function(markup) {
                    return markup;
                }
            });
        }
\t</script>";
        // line 275
        echo "\t
\t<script type=\"text/javascript\">
\t\t\$(\".classfortooltip\").tooltip({
\t\t\ttemplate: '<div class=\"tooltip f_tooltip\" role=\"tooltip\"><div class=\"arrow\"></div><div class=\"tooltip-inner\"></div></div>',
\t\t\tplacement: 'bottom'
\t\t});
\t</script>
  ";
    }

    public function getTemplateName()
    {
        return "administration/templateAdministration.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  413 => 275,  377 => 240,  369 => 233,  367 => 232,  357 => 220,  353 => 219,  348 => 212,  344 => 211,  336 => 187,  332 => 186,  326 => 173,  322 => 172,  315 => 159,  308 => 155,  302 => 160,  300 => 159,  293 => 155,  290 => 154,  286 => 153,  277 => 176,  275 => 172,  264 => 163,  262 => 153,  256 => 149,  252 => 148,  247 => 36,  243 => 35,  237 => 20,  224 => 10,  221 => 9,  217 => 8,  208 => 7,  202 => 283,  200 => 219,  193 => 214,  191 => 211,  182 => 205,  177 => 203,  173 => 202,  168 => 200,  158 => 192,  156 => 186,  150 => 182,  148 => 148,  137 => 139,  135 => 138,  127 => 132,  119 => 117,  112 => 113,  91 => 94,  86 => 66,  79 => 38,  77 => 35,  62 => 22,  60 => 8,  56 => 7,  48 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "administration/templateAdministration.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\templateAdministration.twig");
    }
}
