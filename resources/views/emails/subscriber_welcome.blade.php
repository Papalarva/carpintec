<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Carpintec</title>
</head>
<body style="background-color: #f9fafb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 40px 20px; margin: 0; -webkit-font-smoothing: antialiased;">

    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; border: 1px solid #f3f4f6; overflow: hidden; margin: 0 auto;">
        
        <tr>
            <td style="background-color: #78350f; padding: 40px 20px; text-align: center;">
                <h1 style="color: #ffffff; font-family: Georgia, 'Times New Roman', serif; font-size: 28px; font-weight: normal; margin: 0; letter-spacing: 4px; text-transform: uppercase;">CARPINTEC</h1>
                <p style="color: #fffbeb; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin: 10px 0 0 0; opacity: 0.8;">Taller de Diseño y Manufactura</p>
            </td>
        </tr>

        <tr>
            <td style="padding: 40px 30px; color: #111827;">
                <h2 style="font-size: 20px; font-weight: bold; margin-top: 0; margin-bottom: 20px; font-family: Georgia, 'Times New Roman', serif;">¡Hola!</h2>
                
                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-bottom: 24px;">
                    Gracias por suscribirte al newsletter oficial de <strong>Carpintec</strong>. Nos emociona mucho tenerte en nuestra comunidad.
                </p>
                
                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-bottom: 32px;">
                    A partir de ahora, serás de los primeros en conocer nuestras nuevas colecciones, acceder a promociones exclusivas y descubrir el trabajo artesanal que hay detrás de cada pieza en nuestro taller.
                </p>

                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center">
                            <a href="{{ url('/') }}" style="background-color: #78350f; color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; display: inline-block;">
                                Descubrir Colecciones
                            </a>
                        </td>
                    </tr>
                </table>

                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-top: 40px; margin-bottom: 0;">
                    Saludos cálidos,<br>
                    <strong style="color: #111827;">El equipo de Carpintec</strong>
                </p>
            </td>
        </tr>

        <tr>
            <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #f3f4f6;">
                <p style="font-size: 11px; color: #9ca3af; margin: 0 0 10px 0; line-height: 1.5;">
                    Estás recibiendo este correo porque te suscribiste a nuestro boletín en Carpintec.<br>
                    Prometemos no llenar tu bandeja de entrada con spam, solo enviamos información valiosa.
                </p>
                <p style="font-size: 11px; color: #9ca3af; margin: 0; font-weight: bold;">
                    &copy; {{ date('Y') }} Carpintec. Todos los derechos reservados.
                </p>
            </td>
        </tr>
        
    </table>
    
    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td height="40"></td>
        </tr>
    </table>

</body>
</html>