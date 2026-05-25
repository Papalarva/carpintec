<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso denegado | Carpintec</title>
    <style>
        :root {
            color-scheme: light;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(120, 53, 15, 0.12), transparent 34%),
                radial-gradient(circle at bottom right, rgba(251, 191, 36, 0.14), transparent 30%),
                #f9fafb;
            color: #111827;
        }
        .card {
            width: min(640px, calc(100vw - 32px));
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(229, 231, 235, 0.9);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(17, 24, 39, 0.08);
            padding: 40px 32px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 999px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        h1 {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: clamp(2rem, 5vw, 3rem);
            margin: 0 0 12px;
            letter-spacing: -0.03em;
        }
        p {
            margin: 0 auto;
            max-width: 32rem;
            line-height: 1.7;
            color: #4b5563;
            font-size: 1rem;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 28px;
        }
        a {
            text-decoration: none;
            border-radius: 14px;
            padding: 14px 22px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 0.75rem;
            transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease;
        }
        a:hover { transform: translateY(-1px); }
        .primary {
            background: #78350f;
            color: #fff;
            box-shadow: 0 10px 25px rgba(120, 53, 15, 0.18);
        }
        .secondary {
            background: #fff;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="badge">403</div>
        <h1>Acceso denegado</h1>
        <p>No tienes permisos para ver esta página. Si crees que esto es un error, contacta al equipo de soporte o vuelve al inicio.</p>
        <div class="actions">
            <a class="primary" href="{{ route('home') }}">Ir al inicio</a>
            <a class="secondary" href="{{ route('contact.index') }}">Contactar soporte</a>
        </div>
    </main>
</body>
</html>