<?php

namespace App\Services;

use App\Models\Favoritos;
use App\Models\Producto;
use Exception;
class FavoritosService
{
    protected string $sessionId;
    public function __construct(){
        $this->sessionId = session()->getId();

        if (!session()->has('favoritos')) {
            Favoritos::firstOrCreate(['session_id' => $this->sessionId], ['productos' => json_encode([])]);
            session()->put('favoritos', []);
        }
    }
    public function getFavoritos(): array
    {
        return Favoritos::getFavoritos($this->sessionId);
    }
    public function agregarAFavoritos(Producto $producto): void
    {
        $favoritos = $this->getFavoritos();

        if (isset($favoritos[$producto->id])) {
            throw new Exception("Ya agregaste {$producto->nombre} a tu lista de favoritos.");
        }

        $favoritos[$producto->id] = [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio' => $producto->precio,
            'imagen' => $producto->imagen
        ];

        $this->actualizarFavoritos($favoritos);
    }
    public function eliminarProducto($productoId): void
    {
        $favoritos = $this->getFavoritos();

        if (!isset($favoritos[$productoId])) {
            throw new Exception("El producto no se encuentra en favoritos.");
        }

        unset($favoritos[$productoId]);
        $this->actualizarFavoritos($favoritos);
    }
    public function vaciarFavoritos(): void
    {
        $favoritos = [];
        $this->actualizarFavoritos($favoritos);
    }
    private function actualizarFavoritos(array $favoritos): void
    {
        session()->put('favoritos', $favoritos);
        Favoritos::actualizarFavoritos($this->sessionId, $favoritos);
    }

}