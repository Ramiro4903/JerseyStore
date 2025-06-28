<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pie de Página</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }

        .content {
            flex: 1;
        }

        .footer {
            background-color: rgb(77, 134, 187);
            color: white;
            text-align: center;
            padding: 15px 20px;
            font-size: 16px;
        }

        .footer span {
            display: inline-block;
            margin: 0 10px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .footer span:hover {
            color: lightgreen;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Contenido principal de la página -->
    </div>
    <footer class="footer">
        <span>casadelosjerseys@gmail.com</span> | 
        <span>Todos los Derechos Reservados</span> | 
        <span>Política de Privacidad</span> | 
        <span>Términos y Condiciones</span>
    </footer>
</body>
</html>
