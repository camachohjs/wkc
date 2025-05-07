<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Formulario de Inscripción</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
            font-size: 10px;
            width: 210mm;
            height: 297mm;
        }

        .section {
            width: 45%;
            box-sizing: border-box;
            padding: 4mm;
            height: 48%;
            float: left;
        }

        .page {
            page-break-after: always;
        }

        .table-layout {
            width: 100%;
            height: 98%;
            table-layout: fixed;
        }

        .table-layout td {
            vertical-align: top;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .container {
            padding: 20px;
        }

        .header,
        .content,
        .footer {
            width: 100%;
            text-align: center;
            padding: 5mm 0;
        }

        .header img {
            max-width: 100px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            /* border: 1px solid #000; */
            text-align: left;
            width: 100%;
        }

        table td {
            padding: 4px;
            vertical-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .title-cell {
            background: #f2f2f2;
        }

        .checkbox,
        .grade {
            margin: 0 10px;
            vertical-align: middle;
        }

        .checkbox-square {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 1px solid #000;
            margin-right: 2.5px;
            text-align: center;
        }

        .footer {
            text-align: center;
            position: relative;
            height: 50px;
        }

        .footer img {
            max-width: 50px;
            position: absolute;
            right: 0;
            bottom: 0;
        }

        .firma {
            border-top: 1px solid #000;
            margin-top: 30px;
            padding-top: 2px;
            text-align: center;
            width: 90%;
            margin: 0px auto;
        }

        .espacio {
            min-height: 18px;
            width: 100%;
        }

        .numero {
            width: 15px;
            display: inline-block;
            text-align: right;
            margin-right: 5px;
        }

        .justifcado {
            display: flex;
            justify-content: space-around;
        }

        .full-width {
            width: 100%;
        }

        .izq {
            text-align: left;
        }

        .derecha {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .borde {
            border: 1px solid #000;
        }

        .opacar {
            opacity: 0.5;
        }

        .desempate {
            text-align: center;
            vertical-align: middle;
            border: none;
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -o-transform: rotate(-90deg);
        }

        .logo {
            width: 50%;
        }

        .w-10 {
            width: 10px;
        }

        .w-60 {
            width: 60px;
        }

        .w-20 {
            width: 20px;
        }

        .w-30 {
            width: 30px;
        }

        .amarillo {
            background-color: #EBC010;
        }
    </style>
</head>

<body>
    @foreach (array_chunk($competidores->all(), 4) as $competidorPage)
        <div class="page">
            <table class="table-layout">
                <tr>
                    @php
                        $rows = array_chunk($competidorPage, 2);
                        while (count($rows) < 2) {
                            $rows[] = [];
                        }
                    @endphp
                    @foreach ($rows as $row)
                        <td>
                            @foreach ($row as $competidor)
                                {{-- @dd($competidor) --}}
                                <div>
                                    <div class="header">
                                        <table>
                                            <tr>
                                                <th><img src="{{ public_path('Img/KARATE.png') }}" alt="Logo"
                                                        class="logo"></th>
                                                <th>
                                                    <h4>DATOS GENERALES</h4>
                                                </th>
                                                <th class="center"
                                                    style=" border: solid black; border-width: 0 0 1px 1px;">
                                                    <b>{{ $competidor['division'] }}</b><br>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>

                                    <table>
                                        <tr>
                                            <td colspan="3">
                                                Nombre:
                                                <b>{{ ucfirst($competidor['competidor']->nombre) . ' ' . ucfirst($competidor['competidor']->apellidos) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="full-width">
                                                Edad: <b>{{ $competidor['edad'] }}</b>
                                            </td>
                                            <td class="full-width">
                                                Sexo:
                                                <div
                                                    class="checkbox-square {{ $competidor['competidor']->genero == 'masculino' ? 'amarillo' : '' }}">
                                                    M</div>
                                                <div
                                                    class="checkbox-square {{ $competidor['competidor']->genero == 'femenino' ? 'amarillo' : '' }}">
                                                    F</div>
                                            </td>
                                            <td class="full-width">
                                                Grado:
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="full-width izq">
                                                Escuela:
                                                <b>{{ ucfirst($competidor['competidor']->escuelas->first()->nombre ?? '________________') }}</b>
                                            </td>
                                            <td class="full-width derecha" colspan="2">
                                                Sensei:
                                                <b>
                                                    @if ($competidor['competidor']->maestros != null)
                                                        {{ $competidor['competidor']->maestros->map(fn($maestro) => ucfirst($maestro->nombre) . ' ' . ucfirst($maestro->apellidos))->implode(', ') }}
                                                    @else
                                                        ________________
                                                    @endif
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="full-width izq">
                                                Equipo: __________________
                                            </td>
                                            <td class="full-width derecha" colspan="2">
                                                Coach: __________________
                                            </td>
                                        </tr>
                                    </table>
                                    <br><br><br>
                                    <table>
                                        <tr>
                                            <td>
                                                1.
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td rowspan="6" style="text-align: center; vertical-align: middle;">
                                                <h2 class="desempate">DESEMPATE</h2>
                                            </td>
                                            <td>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td rowspan="2" class="center borde" style="width: 35%;">
                                                Pagado<br>
                                                {{-- <img src="{{ public_path('Img/KARATE.png') }}" alt="Logo" class="opacar" style="width: 60px;"> --}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                2.
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                3.
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td rowspan="2" class="center borde" style="width: 35%;">
                                                Secuencia<br>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                4.
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                5.
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td rowspan="2" class="center borde" style="width: 35%;">
                                                Lugar ganado<br>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                            <td>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                                <div class="checkbox-square"></div>
                                            </td>
                                        </tr>
                                    </table>
                                    <br><br><br><br><br>
                                    <div class="footer">
                                        <div class="firma">
                                            Firma de deslinde
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @php
                                $missing = 2 - count($row);
                                while ($missing-- > 0) {
                                    echo "<div class='competidor-vacio'> </div>";
                                }
                            @endphp
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>
    @endforeach
</body>

</html>
