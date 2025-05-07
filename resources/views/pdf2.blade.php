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
            width: 14px;
            height: 14px;
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
                                            <td class="w-10">
                                                <b>1.______</b>
                                            </td>
                                            <td class="w-60 center">
                                                <b>
                                                    1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;&nbsp;
                                                </b>
                                            </td>
                                            <td class="w-20">
                                                <b>G/P</b>
                                            </td>
                                            <td rowspan="3" class="center borde w-20">
                                                Pagado<br>
                                                {{-- <img src="{{ public_path('Img/KARATE.png') }}" alt="Logo" class="opacar center" style="width: 60px;"> --}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-10">
                                                <b>2.______</b>
                                            </td>
                                            <td class="w-60 center">
                                                <b>
                                                    1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;&nbsp;
                                                </b>
                                            </td>
                                            <td class="w-20">
                                                <b>G/P</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-10">
                                                <b>3.______</b>
                                            </td>
                                            <td class="w-60 center">
                                                <b>
                                                    1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;&nbsp;
                                                </b>
                                            </td>
                                            <td class="w-20">
                                                <b>G/P</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-10">
                                                <b>4.______</b>
                                            </td>
                                            <td class="w-60 center">
                                                <b>
                                                    1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;&nbsp;
                                                </b>
                                            </td>
                                            <td class="w-20">
                                                <b>G/P</b>
                                            </td>
                                            <td rowspan="3" class="center borde w-20">
                                                Pesaje<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-10">
                                                <b>5.______</b>
                                            </td>
                                            <td class="w-60 center">
                                                <b>
                                                    1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;&nbsp;
                                                </b>
                                            </td>
                                            <td class="w-20">
                                                <b>G/P</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-10">
                                                <b>6.______</b>
                                            </td>
                                            <td class="w-60 center">
                                                <b>
                                                    1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;&nbsp;
                                                </b>
                                            </td>
                                            <td class="w-20">
                                                <b>G/P</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-10">
                                                <b>7.______</b>
                                            </td>
                                            <td class="w-60 center">
                                                <b>
                                                    1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;&nbsp;
                                                </b>
                                            </td>
                                            <td class="w-20">
                                                <b>G/P</b>
                                            </td>
                                            <td rowspan="3" class="center borde w-20">
                                                Secuencia<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-10">
                                                <b>8.______</b>
                                            </td>
                                            <td class="w-60 center">
                                                <b>
                                                    1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;&nbsp;
                                                </b>
                                            </td>
                                            <td class="w-20">
                                                <b>G/P</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="center" colspan="3">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="center" colspan="3">
                                                ___________________________<br>
                                                Firma de deslinde
                                            </td>
                                            <td rowspan="3" class="center borde w-20">
                                                Lugar ganado<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div
                                                    style="display: flex; justify-content: center;flex-direction: unset;width: 100%;align-items: center;">
                                                    <div class="center" style="flex: 1;display: inline-block;">
                                                        __________________________<br>
                                                        Firma del competidor<br>
                                                        &nbsp;
                                                    </div>
                                                    <div class="center" style="flex: 1;display: inline-block;">
                                                        __________________________<br>
                                                        Firma del responsable<br>
                                                        (en caso de no ser mayor de 18)
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
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
