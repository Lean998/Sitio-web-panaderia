<?php
// app/Services/FavoritosService.php

namespace App\Services;

use App\Models\Favoritos;
use App\Models\FavoritoItem;
use App\Models\Producto;
use Exception;

class FavoritosService
{
    protected Favoritos $favoritos;
    protected string $sessionId;

    public function __construct()
    {
        $this->sessionId = session()->getId();
        $this->favoritos = Favoritos::getFavoritosPorSession($this->sessionId)->load('items.producto');
        session()->put('favoritos_id', $this->favoritos->id);
    }

    /**
     * Obtener favoritos en formato array
     */
    public function getFavoritos(): array
    {
        // Verificar si hay en sesión (cache)
        $favoritosCache = session()->get('favoritos_array');
        
        if ($favoritosCache === null) {
            $favoritosCache = $this->favoritos->fresh(['items.producto'])->getProductosArray();
            session()->put('favoritos_array', $favoritosCache);
        }
        
        return $favoritosCache;
    }

    /**
     * Obtener modelo de favoritos
     */
    public function getFavoritosModel(): Favoritos
    {
        return $this->favoritos->fresh(['items.producto']);
    }

    /**
     * Agregar producto a favoritos
     */
    public function agregarAFavoritos(Producto $producto): void
    {
        if ($this->favoritos->tieneProducto($producto->id)) {
            throw new Exception("Ya agregaste {$producto->nombre} a tu lista de favoritos.");
        }

        $this->favoritos->agregarProducto($producto);
        $this->actualizarCache();
    }

    /**
     * Eliminar producto de favoritos
     */
    public function eliminarProducto($productoId): bool
    {
        if (!$this->favoritos->tieneProducto($productoId)) {
            throw new Exception("El producto no se encuentra en favoritos.");
        }

        $this->favoritos->removerProducto($productoId);
        $this->actualizarCache();
        
        return true;
    }

    /**
     * Vaciar todos los favoritos
     */
    public function vaciarFavoritos(): void
    {
        $this->favoritos->vaciar();
        $this->actualizarCache();
    }

    /**
     * Toggle favorito (agregar/quitar)
     */
    public function toggle(Producto $producto): bool
    {
        $agregado = $this->favoritos->toggle($producto);
        $this->actualizarCache();
        
        return $agregado;
    }

    /**
     * Verificar si un producto está en favoritos
     */
    public function esFavorito(int $productoId): bool
    {
        return $this->favoritos->tieneProducto($productoId);
    }

    /**
     * Actualizar cache en sesión
     */
    protected function actualizarCache(): void
    {
        $favoritosArray = $this->favoritos->fresh(['items.producto'])->getProductosArray();
        session()->put('favoritos_array', $favoritosArray);
    }

    /**
     * Obtener cantidad de favoritos
     */
    public function getCantidad(): int
    {
        return $this->favoritos->cantidad;
    }

    /**
     * Verificar si está vacío
     */
    public function estaVacio(): bool
    {
        return $this->favoritos->items->isEmpty();
    }
    /**
     * Mostrar el top 10 de productos más agregados a favoritos
     */
    public function getProductosMasAgregados(){
        return FavoritoItem::getMasAgregados();
    }
}