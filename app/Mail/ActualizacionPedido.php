<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActualizacionPedido extends Mailable
{
    use Queueable, SerializesModels;
    public $pedido;
    public $estadoAnterior;

    public function __construct($pedido, $estadoAnterior)
    {
        $this->pedido = $pedido;
        $this->estadoAnterior = $estadoAnterior;
    }

    public function build()
    {
        return $this->subject('Actualización de tu Pedido #' . $this->pedido->codigo_pedido)
                    ->view('emails.pedido-estado-cambiado');
    }
}