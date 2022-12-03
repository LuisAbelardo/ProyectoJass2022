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

/* /administration/tipousopredio/tipoUsoPredioNew.twig */
class __TwigTemplate_a5629fe2c9109dfc452b81427b70c4d7604b5767faa404cd43b8fe4c3e6f1ea6 extends \Twig\Template
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
        $context["menuLItem"] = "tipousopredio";
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/tipousopredio/tipoUsoPredioNew.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formNuevoTipoUsoPredio\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/tipousopredio/create\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-warehouse mr-3\"></i>Tipos de uso de predio
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
          \t\t<div class=\"f_tabs\">
          \t\t\t<div class=\"f_tabunactive\">
          \t\t\t\t<a href=\"";
        // line 51
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 51, $this->source); })()), "html", null, true);
        echo "/tipousopredio/lista\" class=\"f_link\">Lista</a>
          \t\t\t</div>
          \t\t\t<div class=\"f_tabactive\">
          \t\t\t\t<a href=\"#\" class=\"f_link\">Nuevo</a>
          \t\t\t</div>
              \t</div>
      \t\t</div>
      \t</div><!-- /.tabs de contenido -->
      \t
  
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t
      \t\t\t<div class=\"f_divwithbartop f_divwithbarbottom\">
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpNombre\">Nombre</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpNombre\" name=\"nombre\" required
                        \t\t\tvalue='";
        // line 69
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoTipoUsoPredio", [], "any", false, true, false, 69), "nombre", [], "any", true, true, false, 69)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 69, $this->source); })()), "formNuevoTipoUsoPredio", [], "any", false, false, false, 69), "nombre", [], "any", false, false, false, 69), "html", null, true);
        }
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpTarifaAgua\">Tarifa Agua</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"number\" step=\"0.01\" class=\"f_minwidth150\" id=\"inpTarifaAgua\" name=\"tarifaAgua\" required
                        \t\t\tvalue='";
        // line 76
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoTipoUsoPredio", [], "any", false, true, false, 76), "tarifaAgua", [], "any", true, true, false, 76)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 76, $this->source); })()), "formNuevoTipoUsoPredio", [], "any", false, false, false, 76), "tarifaAgua", [], "any", false, false, false, 76), "html", null, true);
        }
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpTarifaDesague\">Tarifa Desague</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"number\" step=\"0.01\" class=\"f_minwidth150\" id=\"inpTarifaDesague\" name=\"tarifaDesague\" required
                        \t\t\tvalue='";
        // line 83
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoTipoUsoPredio", [], "any", false, true, false, 83), "tarifaDesague", [], "any", true, true, false, 83)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 83, $this->source); })()), "formNuevoTipoUsoPredio", [], "any", false, false, false, 83), "tarifaDesague", [], "any", false, false, false, 83), "html", null, true);
        }
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbTipoPredio\">Tipo de Predio</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                            <select name=\"tipoPredio\" class=\"f_minwidth300\" id=\"cmbTipoPredio\" required>
                            \t";
        // line 90
        $context["selectedTipoPredio"] = false;
        // line 91
        echo "                                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 91, $this->source); })()), "tiposPredio", [], "any", false, false, false, 91));
        foreach ($context['_seq'] as $context["_key"] => $context["tipoPredio"]) {
            // line 92
            echo "                                \t<option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 92), "html", null, true);
            echo "\"
                            \t\t\t\t";
            // line 93
            if ((( !(isset($context["selectedTipoPredio"]) || array_key_exists("selectedTipoPredio", $context) ? $context["selectedTipoPredio"] : (function () { throw new RuntimeError('Variable "selectedTipoPredio" does not exist.', 93, $this->source); })()) && twig_get_attribute($this->env, $this->source,             // line 94
($context["data"] ?? null), "formNuevoTipoUsoPredio", [], "any", true, true, false, 94)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 95
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 95, $this->source); })()), "formNuevoTipoUsoPredio", [], "any", false, false, false, 95), "tipoPredio", [], "any", false, false, false, 95) == twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 95)))) {
                // line 96
                echo "                            \t\t\t\t\t";
                echo "selected";
                $context["selectedTipoPredio"] = true;
                // line 97
                echo "                        \t\t\t\t\t";
            }
            echo ">
                        \t\t\t\t";
            // line 98
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_NOMBRE", [], "any", false, false, false, 98), "html", null, true);
            echo "
                    \t\t\t\t</option>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tipoPredio'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 101
        echo "                            </select>
                        </div>
                    </div>
\t\t\t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpTarifaAmbos\">Tarifa Ambos Servicios</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpTarifaAmbos\" name=\"tarifaAmbos\" required
                        \t\t\tvalue='";
        // line 108
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoTipoUsoPredio", [], "any", false, true, false, 108), "tarifaAmbos", [], "any", true, true, false, 108)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 108, $this->source); })()), "formNuevoTipoUsoPredio", [], "any", false, false, false, 108), "tarifaAmbos", [], "any", false, false, false, 108), "html", null, true);
        }
        echo "'>
                        </div>
                    </div>
\t\t\t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpTarifaManten\">Tarifa Mantenimiento</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpTarifaManten\" name=\"tarifaManten\" required
                        \t\t\tvalue='";
        // line 115
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevoTipoUsoPredio", [], "any", false, true, false, 115), "tarifaManten", [], "any", true, true, false, 115)) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 115, $this->source); })()), "formNuevoTipoUsoPredio", [], "any", false, false, false, 115), "tarifaManten", [], "any", false, false, false, 115), "html", null, true);
        }
        echo "'>
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
        // line 127
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 127, $this->source); })()), "html", null, true);
        echo "/tipousopredio/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 133
        echo "  
</div><!-- /.card -->

";
    }

    // line 138
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 139
        echo "
    ";
        // line 140
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
    
    <script src=\"https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js\"></script>
  
\t<script type=\"text/javascript\">
\t\t\$('#formNuevoTipoUsoPredio').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
\t\t
\t\tf_select2(\"#cmbTipoPredio\");


\t\tfunction getTarifaAmbosServicios(){
\t\t\tif(\$(\"#cmbTipoPredio\").val() != 1){
\t\t\t\tvar tarifaAgua = 0;
\t\t\t\tvar tarifaDesague = 0;
\t\t\t\tif(\$(\"#inpTarifaAgua\").val() != \"\"){tarifaAgua = parseFloat(\$(\"#inpTarifaAgua\").val())}
\t\t\t\tif(\$(\"#inpTarifaDesague\").val() != \"\"){tarifaDesague = parseFloat(\$(\"#inpTarifaDesague\").val())}
\t\t\t\t\$(\"#inpTarifaAmbos\").val(tarifaAgua + tarifaDesague);

\t\t\t}
\t\t}

\t\t\$(\"#inpTarifaAgua\").change(function(){
\t\t\tgetTarifaAmbosServicios();
\t\t});

\t\t\$(\"#inpTarifaDesague\").change(function(){
\t\t\tgetTarifaAmbosServicios();
\t\t});

\t\tif(\$(\"#cmbTipoPredio\").val() == 1){
    \t\t\$(\"#inpTarifaAmbos\").prop(\"disabled\", false);
\t\t\t\$(\"#inpTarifaManten\").prop(\"disabled\", false);
\t\t\t\$(\"#inpTarifaAmbos\").val(\"\");
    \t}else{
    \t\tgetTarifaAmbosServicios();
    \t\t\$(\"#inpTarifaAmbos\").prop(\"disabled\", true);
\t\t\t\$(\"#inpTarifaManten\").prop(\"disabled\", true);
\t\t\t\$(\"#inpTarifaManten\").val(\"0\");
    \t}
        
        \$(\"#cmbTipoPredio\").change(function(){
        \tif(\$(\"#cmbTipoPredio\").val() == 1){
        \t\t\$(\"#inpTarifaAmbos\").prop(\"disabled\", false);
\t\t\t\t\$(\"#inpTarifaManten\").prop(\"disabled\", false);
\t\t\t\t\$(\"#inpTarifaAmbos\").val(\"\");
        \t}else{
        \t\tgetTarifaAmbosServicios();
        \t\t\$(\"#inpTarifaAmbos\").prop(\"disabled\", true);
\t\t\t\t\$(\"#inpTarifaManten\").prop(\"disabled\", true);
\t\t\t\t\$(\"#inpTarifaManten\").val(\"0\");
        \t}
        });
\t</script>
";
    }

    public function getTemplateName()
    {
        return "/administration/tipousopredio/tipoUsoPredioNew.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  291 => 140,  288 => 139,  284 => 138,  277 => 133,  269 => 127,  252 => 115,  240 => 108,  231 => 101,  222 => 98,  217 => 97,  213 => 96,  211 => 95,  210 => 94,  209 => 93,  204 => 92,  199 => 91,  197 => 90,  185 => 83,  173 => 76,  161 => 69,  140 => 51,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/tipousopredio/tipoUsoPredioNew.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\tipousopredio\\tipoUsoPredioNew.twig");
    }
}
