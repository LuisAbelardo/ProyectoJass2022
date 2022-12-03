<?php namespace App\Traits;

trait Pagination
{
    /**
     * Trait para obtener datos necesarios para una paginacion
     * @param int $cantidadRegistros
     * @param int $paginaActual
     * @return array
     */
    public function paginate(int $cantidadRegistros, int $paginaActual) 
    {
        $registrosPorTabla = 25; // Cantidad de registros que se mostraran en la tabla
        $paginaCantidad = ($cantidadRegistros > 0) ? ceil($cantidadRegistros / $registrosPorTabla) : 0;
        $paginaActual = ($paginaActual > $paginaCantidad || $paginaActual < 1) ? 1 : $paginaActual;
        $paginaActual = ($cantidadRegistros > 0) ? $paginaActual : 0;
        
        $paginationData = [];
        $paginationData['paginaCantidad'] = (int) $paginaCantidad;          // Cantidad total de paginas
        $paginationData['paginaCantidadRegistros'] = $cantidadRegistros;    // Cantidad total de resgistros encontrados
        $paginationData['paginaActual'] = $paginaActual;
        $paginationData['paginaSiguiente'] = ($paginaActual >= $paginaCantidad) ? -1 : $paginaActual + 1;
        $paginationData['paginaAnterior'] = ($paginaActual <= 1) ? -1 : $paginaActual - 1;
        $paginationData['paginaOffset'] = ($cantidadRegistros > 0) ? $registrosPorTabla * ($paginaActual - 1) : 0;
        $paginationData['paginaLimit'] = $registrosPorTabla;
        $paginationData['title'] = '';
        $paginationData['data'] = [];
        
        return $paginationData;
    }
}