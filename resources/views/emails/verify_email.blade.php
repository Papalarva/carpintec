<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo electrónico</title>
</head>
<body style="background-color: #f9fafb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 40px 20px; margin: 0; -webkit-font-smoothing: antialiased;">

    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; border: 1px solid #f3f4f6; overflow: hidden; margin: 0 auto; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        
        <tr>
            <td style="background-color: #78350f; padding: 40px 20px; text-align: center;">
                <h1 style="color: #ffffff; font-family: Georgia, 'Times New Roman', serif; font-size: 28px; font-weight: normal; margin: 0; letter-spacing: 4px; text-transform: uppercase;">CARPINTEC</h1>
                <p style="color: #fffbeb; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin: 10px 0 0 0; opacity: 0.8;">Verificación de Seguridad</p>
            </td>
        </tr>

        <tr>
            <td style="padding: 40px 30px; color: #111827;">
                <h2 style="font-size: 20px; font-weight: bold; margin-top: 0; margin-bottom: 20px; font-family: Georgia, 'Times New Roman', serif;">Hola, {{ $user->first_name ?? 'Usuario' }}</h2>
                
                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-bottom: 24px;">
                    Estás a un solo paso de unirte a Carpintec. Para garantizar la seguridad de tu cuenta y proteger tus datos, necesitamos verificar que esta dirección de correo te pertenece.
                </p>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 32px; margin-bottom: 32px;">
                    <tr>
                        <td align="center">
                            <a href="{{ $url }}" style="background-color: #78350f; color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; display: inline-block;">
                                Verificar Mi Correo
                            </a>
                        </td>
                    </tr>
                </table>

                <p style="font-size: 14px; line-height: 1.6; color: #6b7280; margin-bottom: 0;">
                    Si no creaste una cuenta en Carpintec, puedes ignorar este correo de forma segura. El enlace expirará automáticamente.
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding: 0 30px 30px 30px;">
                <div style="border-top: 1px solid #f3f4f6; padding-top: 20px;">
                    <p style="font-size: 12px; color: #9ca3af; line-height: 1.6; margin: 0; word-break: break-all;">
                        ¿Problemas con el botón? Copia y pega esta URL en tu navegador:<br>
                        <a href="{{ $url }}" style="color: #78350f; text-decoration: underline;">{{ $url }}</a>
                    </p>
                </div>
            </td>
        </tr>

        <tr>
            <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #f3f4f6;">
                <p style="font-size: 11px; color: #9ca3af; margin: 0; font-weight: bold;">
                    &copy; {{ date('Y') }} Carpintec. Todos los derechos reservados.
                </p>
            </td>
        </tr>
    </table>
    
    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="40"></td></tr>
    </table>
</body>
</html>