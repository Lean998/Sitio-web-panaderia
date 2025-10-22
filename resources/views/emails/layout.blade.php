<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #A0522D; text-align: center; }
        .info-box { background: #F5F5DC; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .codigo-retiro { text-align: center; margin: 20px 0; }
        .codigo { font-size: 24px; font-weight: bold; color: #2F4F4F; background: #F5F5DC; padding: 10px; border-radius: 5px; }
        .label { font-size: 16px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #A0522D; color: #fff; }
        .button { display: inline-block; padding: 10px 20px; background: #A0522D; color: #fff; text-decoration: none; border-radius: 5px; margin: 15px 0; }
        .button:hover { background: #8B4513; }
        @media (max-width: 600px) { table, th, td { font-size: 14px; padding: 8px; } .button { width: 100%; text-align: center; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            @yield('content')
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no responder.</p>
            <p>&copy; {{ date('Y') }} El Funito. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>