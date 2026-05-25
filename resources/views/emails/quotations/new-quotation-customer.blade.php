<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización recibida</title>
</head>
<body style="background-color: #f9fafb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 40px 20px; margin: 0; -webkit-font-smoothing: antialiased;">

    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; border: 1px solid #f3f4f6; overflow: hidden; margin: 0 auto;">
        <tr>
            <td style="background-color: #78350f; padding: 40px 20px; text-align: center;">
                <h1 style="color: #ffffff; font-family: Georgia, 'Times New Roman', serif; font-size: 28px; font-weight: normal; margin: 0; letter-spacing: 4px; text-transform: uppercase;">CARPINTEC</h1>
                <p style="color: #fffbeb; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin: 10px 0 0 0; opacity: 0.8;">Cotización recibida</p>
            </td>
        </tr>

        <tr>
            <td style="padding: 40px 30px; color: #111827;">
                <h2 style="font-size: 20px; font-weight: bold; margin-top: 0; margin-bottom: 20px; font-family: Georgia, 'Times New Roman', serif;">Hola, {{ $quotation->customer?->user?->first_name ?? 'cliente' }}.</h2>

                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-bottom: 24px;">
                    Hemos recibido tu cotización <strong>{{ $quotation->subject }}</strong> correctamente. Nuestro equipo la revisará y te responderá lo antes posible.
                </p>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                    <tr>
                        <td style="font-size: 14px; line-height: 1.8; color: #374151;">
                            <strong>Asunto:</strong> {{ $quotation->subject }}<br>
                            <strong>Estado actual:</strong> {{ $quotation->status->label() }}
                        </td>
                    </tr>
                </table>

                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin: 0;">
                    Te enviaremos una respuesta en cuanto tengamos tu propuesta lista.
                </p>

                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-top: 40px; margin-bottom: 0;">
                    Gracias por confiar en Carpintec.<br>
                    <strong style="color: #111827;">Carpintec</strong>
                </p>
            </td>
        </tr>
    </table>

</body>
</html>