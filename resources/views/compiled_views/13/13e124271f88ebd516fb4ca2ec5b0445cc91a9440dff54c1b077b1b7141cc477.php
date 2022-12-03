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

/* /administration/recibo/reciboNews.twig */
class __TwigTemplate_c2afd332971b587c4b23133f3f7eaa001a3b99eea8759d5129b64a6269cf4df4 extends \Twig\Template
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
        list($context["menuLItem"], $context["menuLLink"]) =         ["recibo", "generar"];
        // line 3
        $this->parent = $this->loadTemplate("administration/templateAdministration.twig", "/administration/recibo/reciboNews.twig", 3);
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
        echo "\t<form class=\"f_inputflat\" id=\"formNuevosRecibos\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 9, $this->source); })()), "html", null, true);
        echo "/recibo/create\">
\t
      \t<div class=\"row\">
      \t\t<div class=\"col-12\">
      \t\t\t<div class=\"f_cardheader\">
      \t\t\t\t<div class=\"\"> 
                    \t<i class=\"fas fa-file-invoice mr-3\"></i>Generar recibos
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
      \t\t\t\t
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbMes\">Mes</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 55
        $context["mes"] = "";
        // line 56
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "mes", [], "any", true, true, false, 56)) {
            $context["mes"] = twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 56, $this->source); })()), "mes", [], "any", false, false, false, 56);
            // line 57
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevosRecibos", [], "any", true, true, false, 57)) {
            // line 58
            echo "                        \t    ";
            $context["mes"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 58, $this->source); })()), "formNuevosRecibos", [], "any", false, false, false, 58), "mes", [], "any", false, false, false, 58);
        }
        // line 59
        echo "                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpMes\" name=\"mes\" required disabled value='";
        echo twig_escape_filter($this->env, (isset($context["mes"]) || array_key_exists("mes", $context) ? $context["mes"] : (function () { throw new RuntimeError('Variable "mes" does not exist.', 59, $this->source); })()), "html", null, true);
        echo "'>
                        </div>
                    </div>
                    <div class=\"form-group row\">
                    \t<label class=\"col-12 col-md-3 col-lg-2 f_fieldrequired\" for=\"cmbYear\">Año</label>
                        <div class=\"col-12 col-md-9 col-lg-10\">
                        \t";
        // line 65
        $context["year"] = "";
        // line 66
        echo "                        \t";
        if (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "year", [], "any", true, true, false, 66)) {
            $context["year"] = twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 66, $this->source); })()), "year", [], "any", false, false, false, 66);
            // line 67
            echo "                        \t";
        } elseif (twig_get_attribute($this->env, $this->source, ($context["data"] ?? null), "formNuevosRecibos", [], "any", true, true, false, 67)) {
            // line 68
            echo "                        \t    ";
            $context["year"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["data"]) || array_key_exists("data", $context) ? $context["data"] : (function () { throw new RuntimeError('Variable "data" does not exist.', 68, $this->source); })()), "formNuevosRecibos", [], "any", false, false, false, 68), "year", [], "any", false, false, false, 68);
        }
        // line 69
        echo "                        \t<input type=\"text\" class=\"f_minwidth300\" id=\"inpYear\" name=\"year\" required disabled value='";
        echo twig_escape_filter($this->env, (isset($context["year"]) || array_key_exists("year", $context) ? $context["year"] : (function () { throw new RuntimeError('Variable "year" does not exist.', 69, $this->source); })()), "html", null, true);
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
        \t\t\t<button type=\"button\" class=\"f_button f_buttonaction\" id=\"btnOpenModalGR\" data-toggle=\"modal\" data-target=\"#modalGenerarRecibos\">
  \t\t\t\t\t\tGenerar recibos
  \t\t\t\t\t\t<span><i class=\"fas fa-spinner f_classforrotatespinner d-none\" id=\"spinnerGenerarRecibos\"></i></span>
\t\t\t\t\t</button>
        \t\t\t<a href=\"";
        // line 84
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 84, $this->source); })()), "html", null, true);
        echo "/recibo/lista\" class=\"f_linkbtn f_linkbtnaction\">Cancelar</a>
    \t\t\t</div>
      \t\t</div>
      \t</div><!-- /.card-footer -->
  \t
  \t</form>";
        // line 90
        echo "  
</div><!-- /.card -->


";
        // line 95
        echo "<div class=\"modal fade f_modal\" id=\"modalGenerarRecibos\" tabindex=\"-1\" role=\"dialog\" data-backdrop=\"static\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <span class=\"modal-title\">Recibos</span>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                \t<span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
            <div class=\"modal-body\">
            \t<i class=\"fas fa-info-circle text-secondary mr-1\"></i><span>¿Está seguro de querer generar los recibos?</span>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"f_btnactionmodal\" id=\"btnGenerarRecibos\">Si</button>
                <button type=\"button\" class=\"f_btnactionmodal\" data-dismiss=\"modal\">No</button>
            </div>
        </div>
    </div>
</div>";
        // line 114
        echo "
";
    }

    // line 117
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 118
        echo "
    ";
        // line 119
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
  
\t<script type=\"text/javascript\">
\t\t\$('#formNuevosRecibos').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        
        \$(\"#btnGenerarRecibos\").click(function(){
        \t
        \t\$(\"#btnOpenModalGR\").attr(\"disabled\", true);
    \t\tvar spinnerGenerarRecibos = \$(\"#spinnerGenerarRecibos\");
    \t\tspinnerGenerarRecibos.removeClass(\"d-none\");
    \t\t\$('#modalGenerarRecibos').modal('hide');
    \t
    \t\t\$.ajax({
                method: \"POST\",
                url: \"";
        // line 138
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 138, $this->source); })()), "html", null, true);
        echo "/recibo/create\",
                dataType: \"json\",
                data: {},
                complete: function(){
                \tspinnerGenerarRecibos.addClass(\"d-none\");
                \t\$(\"#btnOpenModalGR\").attr(\"disabled\", false);
                },
    \t\t\terror: function(jqXHR ){
    \t\t\t\t
    \t\t\t\tvar msjRta = \"Ocurrio un error inesperado, vuelva a intentarlo\";
    \t\t\t\tif(jqXHR.responseJSON != undefined){
    \t\t\t\t\tmsjRta = '<ul class=\"pl-3\">';
    \t\t\t\t\tdataDetalle = jqXHR.responseJSON.estadoDetalle;
    \t\t\t\t\tfor (const property in dataDetalle) {
                          msjRta += \"<li>\" + dataDetalle[property] + \"</li>\";
                        }
                        msjRta += \"</ul>\";
    \t\t\t\t}
    \t\t\t
    \t\t\t\t\$(document).Toasts('create', {
                    \tclass: 'bg-danger',
                        title: 'Respuesta de solicitud',
                        position: 'topRight',
                        autohide: true,
\t\t\t\t\t\tdelay: 8000,
                        body: msjRta
                     })
    \t\t\t},
    \t\t\tsuccess: function(respons){
    \t\t\t\t\$(document).Toasts('create', {
                    \tclass: 'bg-success',
                        title: 'Respuesta de solicitud',
                        position: 'topRight',
                        autohide: true,
\t\t\t\t\t\tdelay: 8000,
                        body: \"Recibos facturados exitosamente\"
                     })
    \t\t\t}
            });
        });
\t</script>
";
    }

    public function getTemplateName()
    {
        return "/administration/recibo/reciboNews.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  267 => 138,  245 => 119,  242 => 118,  238 => 117,  233 => 114,  213 => 95,  207 => 90,  199 => 84,  180 => 69,  176 => 68,  173 => 67,  169 => 66,  167 => 65,  157 => 59,  153 => 58,  150 => 57,  146 => 56,  144 => 55,  131 => 44,  125 => 39,  122 => 38,  113 => 36,  108 => 35,  106 => 34,  100 => 32,  97 => 31,  94 => 30,  91 => 29,  88 => 28,  85 => 27,  82 => 26,  79 => 25,  76 => 24,  58 => 9,  54 => 6,  50 => 5,  45 => 3,  43 => 1,  36 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/recibo/reciboNews.twig", "/home/franco/proyectos/php/jass/resources/views/administration/recibo/reciboNews.twig");
    }
}
