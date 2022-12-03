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

/* /administration/partials/menuLeft.twig */
class __TwigTemplate_075934191de5aeda73ba2845a1be0bf7fb8e0101c431f80347c16a84fa7cac2b extends \Twig\Template
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
        // line 2
        $context["typeUser"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["SESSION"]) || array_key_exists("SESSION", $context) ? $context["SESSION"] : (function () { throw new RuntimeError('Variable "SESSION" does not exist.', 2, $this->source); })()), "jass_user", [], "array", false, false, false, 2), "rol_nombre", [], "array", false, false, false, 2);
        // line 3
        echo "
";
        // line 5
        echo "<li class=\"nav-item ";
        if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 5, $this->source); })()) == "home"))) {
            echo "menu-is-openenig menu-open";
        }
        echo "\">
    <a href=\"";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 6, $this->source); })()), "html", null, true);
        echo "/home\" class=\"nav-link \">
      <i class=\"nav-icon fas fa-chart-line\"></i>
      <p>Inicio</p>
    </a>
</li>


<li class=\"nav-header\"><b>TESORERIA</b></li>

";
        // line 16
        echo "
<li class=\"nav-item ";
        // line 17
        if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 17, $this->source); })()) == "montoadicionalrecibo"))) {
            echo "menu-is-openenig menu-open";
        }
        echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-file-invoice-dollar\"></i>
      <p>
        Monto adicional recibo
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
        ";
        // line 26
        if (((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 26, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 26, $this->source); })()) == "TESORERO")) || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 26, $this->source); })()) == "AUXILIAR"))) {
            // line 27
            echo "        <li class=\"nav-item\">
            <a href=\"";
            // line 28
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 28, $this->source); })()), "html", null, true);
            echo "/montoadicionalrecibo/nuevo\" 
            \tclass=\"nav-link ";
            // line 29
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 29, $this->source); })()) == "nuevo"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Nuevo</p>
            </a>
        </li>";
        }
        // line 34
        echo "        
      \t<li class=\"nav-item\">
        \t<a href=\"";
        // line 36
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 36, $this->source); })()), "html", null, true);
        echo "/montoadicionalrecibo/lista\" 
        \t\tclass=\"nav-link ";
        // line 37
        if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 37, $this->source); })()) == "lista"))) {
            echo "active";
        }
        echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
    </ul>
</li>



";
        // line 48
        echo "
<li class=\"nav-item ";
        // line 49
        if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 49, $this->source); })()) == "recibo"))) {
            echo "menu-is-openenig menu-open";
        }
        echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-file-invoice\"></i>
      <p>
        Recibo
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
      \t<li class=\"nav-item\">
        \t<a href=\"";
        // line 59
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 59, $this->source); })()), "html", null, true);
        echo "/recibo/lista\" 
        \t\tclass=\"nav-link ";
        // line 60
        if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 60, $this->source); })()) == "lista"))) {
            echo "active";
        }
        echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
     \t
     \t";
        // line 66
        if ((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 66, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 66, $this->source); })()) == "AUXILIAR"))) {
            // line 67
            echo "     \t<li class=\"nav-item\">
            <a href=\"";
            // line 68
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 68, $this->source); })()), "html", null, true);
            echo "/recibo/generar\" 
            \tclass=\"nav-link ";
            // line 69
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 69, $this->source); })()) == "generar"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Generar recibos</p>
            </a>
        </li>";
        }
        // line 73
        echo " 
        
        ";
        // line 75
        if ((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 75, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 75, $this->source); })()) == "AUXILIAR"))) {
            // line 76
            echo "     \t<li class=\"nav-item\">
        \t<a href=\"";
            // line 77
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 77, $this->source); })()), "html", null, true);
            echo "/recibo/impresionmasiva\" 
        \t\tclass=\"nav-link ";
            // line 78
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 78, $this->source); })()) == "impresionmasiva"))) {
                echo "active";
            }
            echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Impresión masiva</p>
        \t</a>
     \t</li>";
        }
        // line 82
        echo " 
   
 \t\t ";
        // line 84
        if ((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 84, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 84, $this->source); })()) == "AUXILIAR"))) {
            // line 85
            echo "\t\t <li class=\"nav-item\">
        \t<a href=\"";
            // line 86
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 86, $this->source); })()), "html", null, true);
            echo "/financiamiento/lista\" 
        \t\tclass=\"nav-link ";
            // line 87
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 87, $this->source); })()) == "financiamientos"))) {
                echo "active";
            }
            echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Financiamientos</p>
        \t</a>
     \t</li>";
        }
        // line 92
        echo "    </ul>
</li>



";
        // line 98
        echo "
<li class=\"nav-item ";
        // line 99
        if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 99, $this->source); })()) == "ingreso"))) {
            echo "menu-is-openenig menu-open";
        }
        echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-money-bill\"></i>
      <p>
        Ingreso
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
        ";
        // line 108
        if (((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 108, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 108, $this->source); })()) == "TESORERO")) || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 108, $this->source); })()) == "COBRANZAS"))) {
            // line 109
            echo "        <li class=\"nav-item\">
            <a href=\"";
            // line 110
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 110, $this->source); })()), "html", null, true);
            echo "/ingreso/otros/nuevo\" 
            \tclass=\"nav-link ";
            // line 111
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 111, $this->source); })()) == "nuevo"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Ingreso otros</p>
            </a>
        </li>";
        }
        // line 116
        echo "        
      \t<li class=\"nav-item\">
        \t<a href=\"";
        // line 118
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 118, $this->source); })()), "html", null, true);
        echo "/ingreso/lista\" 
        \t\tclass=\"nav-link ";
        // line 119
        if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 119, $this->source); })()) == "lista"))) {
            echo "active";
        }
        echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
    </ul>
</li>


";
        // line 129
        echo "
<li class=\"nav-item ";
        // line 130
        if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 130, $this->source); })()) == "egreso"))) {
            echo "menu-is-openenig menu-open";
        }
        echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-money-bill\"></i>
      <p>
        Egreso
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
        ";
        // line 139
        if (((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 139, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 139, $this->source); })()) == "TESORERO")) || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 139, $this->source); })()) == "AUXILIAR"))) {
            // line 140
            echo "        <li class=\"nav-item\">
            <a href=\"";
            // line 141
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 141, $this->source); })()), "html", null, true);
            echo "/egreso/nuevo\" 
            \tclass=\"nav-link ";
            // line 142
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 142, $this->source); })()) == "nuevo"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Nuevo</p>
            </a>
        </li>";
        }
        // line 147
        echo "        
      \t<li class=\"nav-item\">
        \t<a href=\"";
        // line 149
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 149, $this->source); })()), "html", null, true);
        echo "/egreso/lista\" 
        \t\tclass=\"nav-link ";
        // line 150
        if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 150, $this->source); })()) == "lista"))) {
            echo "active";
        }
        echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
    </ul>
</li>



";
        // line 161
        if ((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 161, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 161, $this->source); })()) == "AUXILIAR"))) {
            // line 162
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 162, $this->source); })()) == "transferencia"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
    <a href=\"";
            // line 163
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 163, $this->source); })()), "html", null, true);
            echo "/transferencia/nuevo\" class=\"nav-link \">
      <i class=\"nav-icon fas fa-exchange-alt\"></i>
      <p>Transferencia</p>
    </a>
</li>";
        }
        // line 168
        echo "


";
        // line 172
        echo "
<li class=\"nav-item ";
        // line 173
        if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 173, $this->source); })()) == "proyecto"))) {
            echo "menu-is-openenig menu-open";
        }
        echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-project-diagram\"></i>
      <p>
        Proyecto
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
    \t";
        // line 182
        if ((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 182, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 182, $this->source); })()) == "AUXILIAR"))) {
            // line 183
            echo "      \t<li class=\"nav-item\">
        \t<a href=\"";
            // line 184
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 184, $this->source); })()), "html", null, true);
            echo "/proyecto/nuevo\" 
        \t\tclass=\"nav-link ";
            // line 185
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 185, $this->source); })()) == "nuevo"))) {
                echo "active";
            }
            echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Nuevo Proyecto</p>
        \t</a>
     \t</li>";
        }
        // line 190
        echo "     \t
     \t<li class=\"nav-item\">
        \t<a href=\"";
        // line 192
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 192, $this->source); })()), "html", null, true);
        echo "/proyecto/lista\" 
        \t\tclass=\"nav-link ";
        // line 193
        if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 193, $this->source); })()) == "lista"))) {
            echo "active";
        }
        echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
     \t
     \t<li class=\"nav-item\">
        \t<a href=\"";
        // line 200
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 200, $this->source); })()), "html", null, true);
        echo "/cuotaextraordinaria/lista\" 
        \t\tclass=\"nav-link ";
        // line 201
        if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 201, $this->source); })()) == "cuotas"))) {
            echo "active";
        }
        echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Cuotas</p>
        \t</a>
     \t</li>
    </ul>
</li>


";
        // line 211
        if (((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 211, $this->source); })()) == "ADMINISTRADOR")) {
            // line 212
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 212, $this->source); })()) == "igv"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
    <a href=\"";
            // line 213
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 213, $this->source); })()), "html", null, true);
            echo "/igv/editar\" class=\"nav-link \">
      <i class=\"nav-icon fas fa-percent\"></i>
      <p>Igv</p>
    </a>
</li>
";
        }
        // line 219
        echo "

";
        // line 221
        if ((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 221, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 221, $this->source); })()) == "AUXILIAR"))) {
            // line 222
            echo "
<li class=\"nav-header\"><b>GESTIÓN</b></li>

";
            // line 226
            echo "
<li class=\"nav-item ";
            // line 227
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 227, $this->source); })()) == "contrato"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-file-contract\"></i>
      <p>
        Contrato
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
        <li class=\"nav-item\">
            <a href=\"";
            // line 237
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 237, $this->source); })()), "html", null, true);
            echo "/contrato/nuevo\" 
            \tclass=\"nav-link ";
            // line 238
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 238, $this->source); })()) == "nuevo"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Nuevo</p>
            </a>
        </li>
      \t<li class=\"nav-item\">
        \t<a href=\"";
            // line 244
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 244, $this->source); })()), "html", null, true);
            echo "/contrato/lista\" 
        \t\tclass=\"nav-link ";
            // line 245
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 245, $this->source); })()) == "lista"))) {
                echo "active";
            }
            echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
    </ul>
</li>



";
            // line 256
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 256, $this->source); })()) == "cliente"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-users\"></i>
      <p>
        Clientes
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
        <li class=\"nav-item\">
            <a href=\"";
            // line 266
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 266, $this->source); })()), "html", null, true);
            echo "/cliente/nuevo/natural\" 
            \tclass=\"nav-link ";
            // line 267
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 267, $this->source); })()) == "nuevo"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Nuevo</p>
            </a>
        </li>
      \t<li class=\"nav-item\">
        \t<a href=\"";
            // line 273
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 273, $this->source); })()), "html", null, true);
            echo "/cliente/lista\" 
        \t\tclass=\"nav-link ";
            // line 274
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 274, $this->source); })()) == "lista"))) {
                echo "active";
            }
            echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
    </ul>
</li>

";
            // line 283
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 283, $this->source); })()) == "predio"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-house-user\"></i>
      <p>
        Predios
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
        <li class=\"nav-item\">
            <a href=\"";
            // line 293
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 293, $this->source); })()), "html", null, true);
            echo "/predio/nuevo\" 
            \tclass=\"nav-link ";
            // line 294
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 294, $this->source); })()) == "nuevo"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Nuevo</p>
            </a>
        </li>
      \t<li class=\"nav-item\">
        \t<a href=\"";
            // line 300
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 300, $this->source); })()), "html", null, true);
            echo "/predio/lista\" 
        \t\tclass=\"nav-link ";
            // line 301
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 301, $this->source); })()) == "lista"))) {
                echo "active";
            }
            echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
    </ul>
</li>

";
            // line 310
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 310, $this->source); })()) == "servicio"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
    <a href=\"";
            // line 311
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 311, $this->source); })()), "html", null, true);
            echo "/servicio/lista\" class=\"nav-link\">
    \t<i class=\"nav-icon fas fa-cube\"></i>
    \t<p>Servicio</p>
    </a>
</li>

";
            // line 318
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 318, $this->source); })()) == "calle"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
\t<a href=\"";
            // line 319
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 319, $this->source); })()), "html", null, true);
            echo "/calle/lista\" class=\"nav-link \">
      \t<i class=\"nav-icon fas fa-road\"></i>
      \t<p>Calle</p>
\t</a>
</li>

";
            // line 326
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 326, $this->source); })()) == "sector"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
\t<a href=\"";
            // line 327
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 327, $this->source); })()), "html", null, true);
            echo "/sector/lista\" class=\"nav-link\">
      \t<i class=\"nav-icon fas fa-puzzle-piece \"></i>
      \t<p>Sector</p>
\t</a>
</li>

";
            // line 334
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 334, $this->source); })()) == "tipopredio"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
\t<a href=\"";
            // line 335
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 335, $this->source); })()), "html", null, true);
            echo "/tipopredio/lista\" class=\"nav-link\">
      \t<i class=\"nav-icon fas fa-home\"></i>
      \t<p>Tipo Predio</p>
\t</a>
</li>

";
            // line 342
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 342, $this->source); })()) == "tipousopredio"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
\t<a href=\"";
            // line 343
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 343, $this->source); })()), "html", null, true);
            echo "/tipousopredio/lista\" class=\"nav-link\">
      \t<i class=\"nav-icon fas fa-warehouse\"></i>
      \t<p>Tipo Uso Predio</p>
\t</a>
</li>
";
        }
        // line 349
        echo "


<li class=\"nav-header\"><b>REPORTES</b></li>

";
        // line 355
        echo "<li class=\"nav-item ";
        if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 355, $this->source); })()) == "reporteconsolidado"))) {
            echo "menu-is-openenig menu-open";
        }
        echo "\">
\t<a href=\"#\" class=\"nav-link\">
      \t<i class=\"nav-icon fas fa-file-pdf\"></i>
      \t<p>Consolidado
      \t\t<i class=\"right fas fa-angle-right\"></i>
  \t\t</p>
\t</a>
\t<ul class=\"nav nav-treeview\">
        <li class=\"nav-item\">
            <a href=\"";
        // line 364
        echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 364, $this->source); })()), "html", null, true);
        echo "/reporte/arqueodiario\"
            \tclass=\"nav-link ";
        // line 365
        if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 365, $this->source); })()) == "arqueodiario"))) {
            echo "active";
        }
        echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Rep. Diario</p>
            </a>
        </li>
        
        ";
        // line 371
        if ((((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 371, $this->source); })()) == "ADMINISTRADOR") || ((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 371, $this->source); })()) == "TESORERO"))) {
            // line 372
            echo "        <li class=\"nav-item\">
            <a href=\"";
            // line 373
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 373, $this->source); })()), "html", null, true);
            echo "/reporte/arqueosemanal\" 
            \tclass=\"nav-link ";
            // line 374
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 374, $this->source); })()) == "arqueosemanal"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Rep. Semanal</p>
            </a>
        </li>
        
      \t<li class=\"nav-item\">
        \t<a href=\"";
            // line 381
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 381, $this->source); })()), "html", null, true);
            echo "/reporte/arqueomensual\" 
        \t\tclass=\"nav-link ";
            // line 382
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 382, $this->source); })()) == "arqueomensual"))) {
                echo "active";
            }
            echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Rep. Mensual</p>
        \t</a>
     \t</li>";
        }
        // line 387
        echo "    </ul>
</li>



";
        // line 392
        if (((isset($context["typeUser"]) || array_key_exists("typeUser", $context) ? $context["typeUser"] : (function () { throw new RuntimeError('Variable "typeUser" does not exist.', 392, $this->source); })()) == "ADMINISTRADOR")) {
            // line 393
            echo "
<li class=\"nav-header\"><b>CONFIGURACIÓN</b></li>

";
            // line 397
            echo "<li class=\"nav-item ";
            if (((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context)) && ((isset($context["menuLItem"]) || array_key_exists("menuLItem", $context) ? $context["menuLItem"] : (function () { throw new RuntimeError('Variable "menuLItem" does not exist.', 397, $this->source); })()) == "usuario"))) {
                echo "menu-is-openenig menu-open";
            }
            echo "\">
    <a href=\"#\" class=\"nav-link\">
      <i class=\"nav-icon fas fa-user-shield\"></i>
      <p>
        Usuarios
        <i class=\"right fas fa-angle-right\"></i>
      </p>
    </a>
    <ul class=\"nav nav-treeview\">
        <li class=\"nav-item\">
            <a href=\"";
            // line 407
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 407, $this->source); })()), "html", null, true);
            echo "/usuario/nuevo\" 
            \tclass=\"nav-link ";
            // line 408
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 408, $this->source); })()) == "nuevo"))) {
                echo "active";
            }
            echo "\">
            \t<i class=\"far fa-circle nav-icon invisible\"></i>
            \t<p>Nuevo</p>
            </a>
        </li>
      \t<li class=\"nav-item\">
        \t<a href=\"";
            // line 414
            echo twig_escape_filter($this->env, (isset($context["PUBLIC_PATH"]) || array_key_exists("PUBLIC_PATH", $context) ? $context["PUBLIC_PATH"] : (function () { throw new RuntimeError('Variable "PUBLIC_PATH" does not exist.', 414, $this->source); })()), "html", null, true);
            echo "/usuario/lista\" 
        \t\tclass=\"nav-link ";
            // line 415
            if (((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context)) && ((isset($context["menuLLink"]) || array_key_exists("menuLLink", $context) ? $context["menuLLink"] : (function () { throw new RuntimeError('Variable "menuLLink" does not exist.', 415, $this->source); })()) == "lista"))) {
                echo "active";
            }
            echo "\">
          \t<i class=\"far fa-circle nav-icon invisible\"></i>
          \t<p>Lista</p>
        \t</a>
     \t</li>
    </ul>
</li>
";
        }
        // line 423
        echo "

    

";
    }

    public function getTemplateName()
    {
        return "/administration/partials/menuLeft.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  829 => 423,  816 => 415,  812 => 414,  801 => 408,  797 => 407,  781 => 397,  776 => 393,  774 => 392,  767 => 387,  757 => 382,  753 => 381,  741 => 374,  737 => 373,  734 => 372,  732 => 371,  721 => 365,  717 => 364,  702 => 355,  695 => 349,  686 => 343,  679 => 342,  670 => 335,  663 => 334,  654 => 327,  647 => 326,  638 => 319,  631 => 318,  622 => 311,  615 => 310,  602 => 301,  598 => 300,  587 => 294,  583 => 293,  567 => 283,  554 => 274,  550 => 273,  539 => 267,  535 => 266,  519 => 256,  504 => 245,  500 => 244,  489 => 238,  485 => 237,  470 => 227,  467 => 226,  462 => 222,  460 => 221,  456 => 219,  447 => 213,  440 => 212,  438 => 211,  424 => 201,  420 => 200,  408 => 193,  404 => 192,  400 => 190,  390 => 185,  386 => 184,  383 => 183,  381 => 182,  367 => 173,  364 => 172,  359 => 168,  351 => 163,  344 => 162,  342 => 161,  327 => 150,  323 => 149,  319 => 147,  309 => 142,  305 => 141,  302 => 140,  300 => 139,  286 => 130,  283 => 129,  269 => 119,  265 => 118,  261 => 116,  251 => 111,  247 => 110,  244 => 109,  242 => 108,  228 => 99,  225 => 98,  218 => 92,  208 => 87,  204 => 86,  201 => 85,  199 => 84,  195 => 82,  185 => 78,  181 => 77,  178 => 76,  176 => 75,  172 => 73,  162 => 69,  158 => 68,  155 => 67,  153 => 66,  142 => 60,  138 => 59,  123 => 49,  120 => 48,  105 => 37,  101 => 36,  97 => 34,  87 => 29,  83 => 28,  80 => 27,  78 => 26,  64 => 17,  61 => 16,  49 => 6,  42 => 5,  39 => 3,  37 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "/administration/partials/menuLeft.twig", "C:\\xampp\\htdocs\\jass\\resources\\views\\administration\\partials\\menuLeft.twig");
    }
}
