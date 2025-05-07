<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Generando PDF...</title>
    @if (cache("torneo_zip_$id"))
        <meta http-equiv="refresh"
            content="2;url={{ asset('storage/Torneo-' . \App\Models\Torneo::find($id)?->nombre . '.zip') }}">
    @else
        <meta http-equiv="refresh" content="5">
    @endif

    <style>
        body {
            font-family: sans-serif;
            padding: 2rem;
            background-color: #f9f9f9;
            text-align: center;
        }

        .mensaje {
            padding-top: 10%;
            padding-left: 10%;
            padding-right: 10%;
            padding-bottom: 5%;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .exito {
            color: green;
            font-weight: bold;
        }

        .boton {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.6rem 1.2rem;
            background-color: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .boton:hover {
            background-color: #059669;
        }
    </style>
</head>

<body>

    @if (cache("torneo_zip_$id"))
        <p class="mensaje exito">✅ El archivo ha sido descargado. Ya puedes cerrar esta ventana.</p>
        <a href="{{ route('inscritos', $id) }}" class="boton">Volver a Inscritos</a>
    @else
        <p class="mensaje">⏳ Generando tu archivo, por favor espera...</p>
    @endif

</body>

</html>
