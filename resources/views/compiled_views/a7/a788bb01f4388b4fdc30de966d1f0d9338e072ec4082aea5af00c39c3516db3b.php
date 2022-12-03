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

/* /administration/tipousopredio/tipoUsoPredioEdit.twig */
class __TwigTemplate_68456d34316021bdf7f913105387c1265bc3473a5ae6b540aa7c36aa1b254886 extends \Twig\Template
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
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/tipousopredio/tipoUsoPredioEdit.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formEditarTipoUsoPredio\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/tipousopredio/update\">
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
          \t\t\t<div class=\"f_tabunactive\">
          \t\t\t\t<a href=\"";
        // line 54
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 54, $this->source); })()), "html", null, true);
        echo "/tipousopredio/nuevo\" class=\"f_link\">Nuevo</a>
          \t\t\t</div>
          \t\t\t<div class=\"f_tabactive\">
          \t\t\t\t<a href=\"#\" class=\"f_link\">Editar</a>
          \t\t\t</div>
              \t</div>
      \t\t</div>
      \t</div><!-- /.tabs de contenido -->
      \t
  
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t
      \t\t\t<div class=\"f_divwithbartop f_divwithbarbottom\">
      \t\t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_field\">Ref.</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 71
        $context["codigo"] = "";
        // line 72
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 72)) {
            $context["codigo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 72, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 72), "TUP_CODIGO", [], "any", false, false, false, 72);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 72, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 72), "TUP_CODIGO", [], "any", false, false, false, 72), "html", null, true);
            echo "
                        \t";
        } elseif (twig_get_attribute($this->env, $this->source,         // line 73
($context["data"] ?? null), "formEditarTipoUsoPredio", [], "any", true, true, false, 73)) {
            // line 74
            echo "                        \t    ";
            $context["codigo"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 74, $this->source); })()), "formEditarTipoUsoPredio", [], "any", false, false, false, 74), "codigo", [], "any", false, false, false, 74);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 74, $this->source); })()), "formEditarTipoUsoPredio", [], "any", false, false, false, 74), "codigo", [], "any", false, false, false, 74), "html", null, true);
        }
        // line 75
        echo "                        \t<input type=\"hidden\" class=\"f_minwidth300\" id=\"inpCodigo\" name=\"codigo\" value='";
        echo twig_escape_filter($this->env, (isset($context["codigo"]) || array_key_exists("codigo", $context) ? $context["codigo"] : (function () { throw new RuntimeError('Variable "codigo" does not exist.', 75, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpNombre\">Nombre</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 81
        $context["nombre"] = "";
        // line 82
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 82)) {
            $context["nombre"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 82, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 82), "TUP_NOMBRE", [], "any", false, false, false, 82);
            // line 83
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarTipoUsoPredio", [], "any", true, true, false, 83)) {
            $context["nombre"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 83, $this->source); })()), "formEditarTipoUsoPredio", [], "any", false, false, false, 83), "nombre", [], "any", false, false, false, 83);
        }
        // line 84
        echo "                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpNombre\" name=\"nombre\" value='";
        echo twig_escape_filter($this->env, (isset($context["nombre"]) || array_key_exists("nombre", $context) ? $context["nombre"] : (function () { throw new RuntimeError('Variable "nombre" does not exist.', 84, $this->source); })()), "html", null, true);
        echo "' required>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpTarifaAgua\">Tarifa Agua</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 90
        $context["tarifaAgua"] = "";
        // line 91
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 91)) {
            $context["tarifaAgua"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 91, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 91), "TUP_TARIFA_AGUA", [], "any", false, false, false, 91);
            // line 92
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarTipoUsoPredio", [], "any", true, true, false, 92)) {
            // line 93
            echo "                        \t    ";
            $context["tarifaAgua"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 93, $this->source); })()), "formEditarTipoUsoPredio", [], "any", false, false, false, 93), "tarifaAgua", [], "any", false, false, false, 93);
        }
        // line 94
        echo "                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpTarifaAgua\" name=\"tarifaAgua\" value='";
        echo twig_escape_filter($this->env, (isset($context["tarifaAgua"]) || array_key_exists("tarifaAgua", $context) ? $context["tarifaAgua"] : (function () { throw new RuntimeError('Variable "tarifaAgua" does not exist.', 94, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpTarifaDesague\">Tarifa Desague</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 100
        $context["TarifaDesague"] = "";
        // line 101
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 101)) {
            $context["TarifaDesague"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 101, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 101), "TUP_TARIFA_DESAGUE", [], "any", false, false, false, 101);
            // line 102
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarTipoUsoPredio", [], "any", true, true, false, 102)) {
            // line 103
            echo "                        \t    ";
            $context["TarifaDesague"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 103, $this->source); })()), "formEditarTipoUsoPredio", [], "any", false, false, false, 103), "tarifaDesague", [], "any", false, false, false, 103);
        }
        // line 104
        echo "                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpTarifaDesague\" name=\"tarifaDesague\" value='";
        echo twig_escape_filter($this->env, (isset($context["TarifaDesague"]) || array_key_exists("TarifaDesague", $context) ? $context["TarifaDesague"] : (function () { throw new RuntimeError('Variable "TarifaDesague" does not exist.', 104, $this->source); })()), "html", null, true);
        echo "' required>
                        </div>
                    </div>
\t\t\t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpTarifaAmbos\">Tarifa Ambos</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 110
        $context["TarifaAmbos"] = "";
        // line 111
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 111)) {
            $context["TarifaAmbos"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 111, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 111), "TUP_TARIFA_AMBOS", [], "any", false, false, false, 111);
            // line 112
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarTipoUsoPredio", [], "any", true, true, false, 112)) {
            // line 113
            echo "                        \t    ";
            $context["TarifaAmbos"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 113, $this->source); })()), "formEditarTipoUsoPredio", [], "any", false, false, false, 113), "tarifaAmbos", [], "any", false, false, false, 113);
        }
        // line 114
        echo "                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpTarifaAmbos\" name=\"tarifaAmbos\" value='";
        echo twig_escape_filter($this->env, (isset($context["TarifaAmbos"]) || array_key_exists("TarifaAmbos", $context) ? $context["TarifaAmbos"] : (function () { throw new RuntimeError('Variable "TarifaAmbos" does not exist.', 114, $this->source); })()), "html", null, true);
        echo "' required>
                        </div>
                    </div>
\t\t\t\t\t<div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"inpTarifaManten\">Tarifa Mantenimiento</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 120
        $context["TarifaManten"] = "";
        // line 121
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 121)) {
            $context["TarifaManten"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 121, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 121), "TUP_TARIFA_MANTENIMIENTO", [], "any", false, false, false, 121);
            // line 122
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarTipoUsoPredio", [], "any", true, true, false, 122)) {
            // line 123
            echo "                        \t    ";
            $context["TarifaManten"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 123, $this->source); })()), "formEditarTipoUsoPredio", [], "any", false, false, false, 123), "tarifaManten", [], "any", false, false, false, 123);
        }
        // line 124
        echo "                        \t<input type=\"text\" class=\"f_minwidth150\" id=\"inpTarifaManten\" name=\"tarifaManten\" value='";
        echo twig_escape_filter($this->env, (isset($context["TarifaManten"]) || array_key_exists("TarifaManten", $context) ? $context["TarifaManten"] : (function () { throw new RuntimeError('Variable "TarifaManten" does not exist.', 124, $this->source); })()), "html", null, true);
        echo "' required>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbTipoPredio\">Tipo Predio</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                            <select name=\"tipoPredio\" class=\"f_minwidth200\" id=\"cmbTipoPredio\" required>
                            \t";
        // line 131
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tiposPredio", [], "any", true, true, false, 131)) {
            // line 132
            echo "                            \t\t";
            $context["selectedTipoPredio"] = false;
            // line 133
            echo "                            \t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 133, $this->source); })()), "tiposPredio", [], "any", false, false, false, 133));
            foreach ($context['_seq'] as $context["_key"] => $context["tipoPredio"]) {
                // line 134
                echo "                                    \t<option value=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 134), "html", null, true);
                echo "\"
                                    \t\t\t";
                // line 135
                if ( !(isset($context["selectedTipoPredio"]) || array_key_exists("selectedTipoPredio", $context) ? $context["selectedTipoPredio"] : (function () { throw new RuntimeError('Variable "selectedTipoPredio" does not exist.', 135, $this->source); })())) {
                    // line 136
                    echo "                                    \t\t\t\t";
                    if ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "tipoUsoPredio", [], "any", true, true, false, 136) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 136, $this->source); })()), "tipoUsoPredio", [], "any", false, false, false, 136), "TIP_CODIGO", [], "any", false, false, false, 136) == twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 136)))) {
                        // line 137
                        echo "                                    \t\t\t\t\t";
                        echo "selected";
                        $context["selectedTipoPredio"] = true;
                        // line 138
                        echo "                                \t\t\t\t\t";
                    } elseif ((twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formEditarTipoUsoPredio", [], "any", true, true, false, 138) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,                     // line 139
(isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 139, $this->source); })()), "formEditarTipoUsoPredio", [], "any", false, false, false, 139), "tipoPredio", [], "any", false, false, false, 139) == twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_CODIGO", [], "any", false, false, false, 139)))) {
                        // line 140
                        echo "                        \t\t\t\t\t            ";
                        echo "selected";
                        $context["selectedTipoPredio"] = true;
                    }
                    // line 141
                    echo "            \t\t\t\t\t            \t";
                }
                echo ">
                            \t\t\t\t";
                // line 142
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tipoPredio"], "TIP_NOMBRE", [], "any", false, false, false, 142), "html", null, true);
                echo "
                        \t\t\t\t</option>
                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tipoPredio'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 145
            echo "                            \t";
        }
        // line 146
        echo "                            </select>
                        </div>
                    </div>
                    
      \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-body -->
  \t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
          \t\t<div class=\"f_cardfooter f_cardfooteractions text-center\">
        \t\t\t<button type=\"submit\" class=\"f_button f_buttonaction\">Guardar cambios</button>
        \t\t\t<a href=\"";
        // line 158
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 158, $this->source); })()), "html", null, true);
        echo "/tipousopredio/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 164
        echo "  
</div><!-- /.card -->
    
";
    }

    // line 169
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 170
        echo "
    ";
        // line 171
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formEditarTipoUsoPredio').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        f_select2(\"#cmbTipoPredio\");


\t\tfunction getTarifaAmbosServicios(){
\t\t\tif(\$(\"#cmbTipoPredio\").val() != 1){
\t\t\t\tvar tarifaAgua = 0;
\t\t\t\tvar tarifaDesague = 0;
\t\t\t\tif(\$(\"#inpTarifaAgua\").val() != \"\"){tarifaAgua = parseFloat(\$(\"#inpTarifaAgua\").val())}
\t\t\t\tif(\$(\"#inpTarifaDesague\").val() != \"\"){tarifaDesague = parseFloat(\$(\"#inpTarifaDesague\").val())}
\t\t\t\t\$(\"#inpTarifaAmbos\").val( tarifaAgua + tarifaDesague);
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
    \t}else{
    \t\tgetTarifaAmbosServicios();
    \t\t\$(\"#inpTarifaAmbos\").prop(\"disabled\", true);
\t\t\t\$(\"#inpTarifaManten\").prop(\"disabled\", true);
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
        \t}
        });
\t</script>
";
    }

    public function getTemplateName()
    {
        return "/administration/tipousopredio/tipoUsoPredioEdit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  392 => 171,  389 => 170,  385 => 169,  378 => 164,  370 => 158,  356 => 146,  353 => 145,  344 => 142,  339 => 141,  334 => 140,  332 => 139,  330 => 138,  326 => 137,  323 => 136,  321 => 135,  316 => 134,  311 => 133,  308 => 132,  306 => 131,  295 => 124,  291 => 123,  288 => 122,  284 => 121,  282 => 120,  272 => 114,  268 => 113,  265 => 112,  261 => 111,  259 => 110,  249 => 104,  245 => 103,  242 => 102,  238 => 101,  236 => 100,  226 => 94,  222 => 93,  219 => 92,  215 => 91,  213 => 90,  203 => 84,  198 => 83,  194 => 82,  192 => 81,  182 => 75,  177 => 74,  175 => 73,  168 => 72,  166 => 71,  146 => 54,  140 => 51,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/tipousopredio/tipoUsoPredioEdit.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\tipousopredio\\tipoUsoPredioEdit.twig");
    }
}
