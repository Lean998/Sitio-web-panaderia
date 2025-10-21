<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5E8C7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #5C3A21, #3B2415);
            color: #F5E8C7;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
            color: #3B2415;
        }
        .codigo-retiro {
            background-color: #F2E1B9;
            border: 3px dashed #5C3A21;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        .codigo-retiro .label {
            font-size: 14px;
            color: #A47148;
            margin-bottom: 10px;
        }
        .codigo-retiro .codigo {
            font-size: 36px;
            font-weight: bold;
            color: #5C3A21;
            letter-spacing: 8px;
        }
        .info-box {
            background-color: #F5E8C7;
            padding: 15px;
            border-left: 4px solid #A47148;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #5C3A21;
            color: #F5E8C7;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            background-color: #F2E1B9;
            padding: 20px;
            text-align: center;
            color: #A47148;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th {
            background-color: #5C3A21;
            color: #F5E8C7;
            padding: 10px;
            text-align: left;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #F2E1B9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🥖 El Funito</h1>
        </div>
        
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