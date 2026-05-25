<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de cotización</title>
</head>
<body style="background-color: #f9fafb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 40px 20px; margin: 0; -webkit-font-smoothing: antialiased;">

    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; border: 1px solid #f3f4f6; overflow: hidden; margin: 0 auto;">
        <tr>
            <td style="background-color: #78350f; padding: 40px 20px; text-align: center;">
                <h1 style="color: #ffffff; font-family: Georgia, 'Times New Roman', serif; font-size: 28px; font-weight: normal; margin: 0; letter-spacing: 4px; text-transform: uppercase;">CARPINTEC</h1>
                <p style="color: #fffbeb; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin: 10px 0 0 0; opacity: 0.8;">Actualización de cotización</p>
            </td>
        </tr>

        <tr>
            <td style="padding: 40px 30px; color: #111827;">
                <h2 style="font-size: 20px; font-weight: bold; margin-top: 0; margin-bottom: 20px; font-family: Georgia, 'Times New Roman', serif;">Hola, {{ $quotation->customer?->user?->first_name ?? 'cliente' }}.</h2>

                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-bottom: 24px;">
                    Tu cotización <strong>{{ $quotation->subject }}</strong> cambió de estado y ya puedes revisar la actualización en tu panel.
                </p>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                    <tr>
                        <td style="font-size: 14px; line-height: 1.8; color: #374151;">
                            <strong>Asunto:</strong> {{ $quotation->subject }}<br>
                            <strong>Estado actual:</strong> {{ $quotation->status->label() }}<br>
                            @if($quotation->estimated_price)
                                <strong>Presupuesto:</strong> ${{ number_format($quotation->estimated_price, 2) }}<br>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($quotation->response)
                    <p style="font-size: 13px; line-height: 1.7; color: #6b7280; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1.5px; font-weight: bold;">Mensaje del equipo</p>
                    <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin: 0 0 24px 0; white-space: pre-line;">{{ $quotation->response }}</p>
                @endif

                @if($quotation->status->value === 'approved')
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center">
                                <a href="{{ route('quotations.checkout', $quotation) }}" style="background-color: #78350f; color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; display: inline-block;">
                                    Continuar al Checkout
                                </a>
                            </td>
                        </tr>
                    </table>
                @endif

                @if($quotation->status->value === 'rejected')
                    <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-top: 8px; margin-bottom: 0;">
                        Si deseas más detalles o una nueva propuesta, responde este correo y con gusto te ayudamos.
                    </p>
                @endif

                <p style="font-size: 15px; line-height: 1.7; color: #4b5563; margin-top: 40px; margin-bottom: 0;">
                    Puedes ver el historial completo desde tu cuenta.<br>
                    <strong style="color: #111827;">Carpintec</strong>
                </p>
            </td>
        </tr>
    </table>

</body>
</html>