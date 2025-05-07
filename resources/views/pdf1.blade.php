<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fichas del torneo</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .table2{
            text-align: center;
        }
        td, th {
            padding: 5px;
            border: 0px solid #ddd;
        }
        .footer {
            font-size: 12px;
            text-align: center;
            position: absolute;
            bottom: 10px;
            width: 100%;
        }
        .content-table td {
            padding: 10px;
            border: none;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
    </style>
</head>
<body>
    @foreach($competidores as $competidorInfo)
        <div class="header">
            <img src="{{ public_path('Img/KARATE.png') }}" alt="Logo" class="logo">
            <h1>TORNEO {{ ucfirst($torneo->nombre) }}</h1>
            <h2>Forma de registro</h2>
        </div>

        <table class="content-table">
            <tr>
                <td>Nombre:</td>
                <td><b>{{ ucfirst($competidorInfo['competidor']->nombre).' '.ucfirst($competidorInfo['competidor']->apellidos) }}</b></td>
                <td>M/F: <b>{{ substr(ucfirst($competidorInfo['competidor']->genero),0,1) }}</b> &nbsp;&nbsp; Grado: <b>{{ ucfirst($competidorInfo['competidor']->cinta) }}</b></td>
            </tr>
            <tr>
                <td>Dirección:</td>
                <td>______________________</td>
                <td>Ciudad:  ___________  &nbsp;&nbsp; Estado:  ___________  &nbsp;&nbsp; CP:  ___________</td>
            </tr>
            <tr>
                <td>Teléfono:</td>
                <td><b>{{ $competidorInfo['competidor']->telefono }}</b></td>
                <td>Edad:  <b>{{ $competidorInfo['edad'] }}</b> &nbsp;&nbsp; Fecha de nacimiento:  <b>{{ $competidorInfo['competidor']->fec }}</b></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><b>{{ $competidorInfo['competidor']->email }}</b></td>
                <td>Escuela: <b>{{ ucfirst($competidorInfo['competidor']->escuelas->first()->nombre ?? '________________') }}</b></td>
            </tr>
            <tr>
                <td>Teléfono escuela:</td>
                <td>______________________</td>
                <td>Profesor: <b>{{ ucfirst($competidorInfo['competidor']->escuelas->first()->maestros->first()->nombre ?? '________________').' '.ucfirst($competidorInfo['competidor']->escuelas->first()->maestros->first()->apellidos ?? '') }}</b></td>
            </tr>
            <tr>
                <td>Dirección:</td>
                <td>______________________</td>
                <td>Ciudad:  ___________   Equipo:  ___________</td>
            </tr>
        </table>

        <h3>Categorías:</h3>
        <table>
            <tr>
                <th></th>
                <th class="table2">PREREGISTRO ANTES 15</th>
                <th class="table2">REGISTRO DESPUES 16</th>
                <th class="table2"></th>
                <th class="table2"></th>
                <th class="table2"></th>
                <th class="table2"></th>
            </tr>
            <tr>
                <td>BASICA(KATAY/OCOMBATE)</td>
                <td class="table2">900</td>
                <td class="table2">950</td>
                <td class="table2">X</td>
                <td class="table2">1</td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td>CATEGORIAADICIONAL</td>
                <td class="table2">200</td>
                <td class="table2">250</td>
                <td class="table2">X</td>
                <td class="table2"></td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td>C.ESPECIALOCUARTETAS</td>
                <td class="table2">900</td>
                <td class="table2">950</td>
                <td class="table2">X</td>
                <td class="table2"></td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td>C.ESPECIALYCUARTETAS</td>
                <td class="table2">1300</td>
                <td class="table2">1350</td>
                <td class="table2">X</td>
                <td class="table2"></td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td>C.ESPECIALMAS1CATEGORIA</td>
                <td class="table2">1100</td>
                <td class="table2">1150</td>
                <td class="table2">X</td>
                <td class="table2"></td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td>C.ESPECIALMAS2CATEGORIA</td>
                <td class="table2">1250</td>
                <td class="table2">1300</td>
                <td class="table2">X</td>
                <td class="table2"></td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td>EQUIPOS</td>
                <td class="table2">800</td>
                <td class="table2">850</td>
                <td class="table2">X</td>
                <td class="table2"></td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td>PASEDECOACH</td>
                <td class="table2">200</td>
                <td class="table2">200</td>
                <td class="table2">X</td>
                <td class="table2"></td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td>PASEDEESPECTADOR</td>
                <td class="table2">50</td>
                <td class="table2">50</td>
                <td class="table2">X</td>
                <td class="table2"></td>
                <td class="table2">=</td>
                <td class="table2">_____</td>
            </tr>
            <tr>
                <td class="table2"></td>
                <td class="table2"></td>
                <td class="table2"></td>
                <td class="table2"></td>
                <td class="table2">TOTAL</td>
                <td class="table2">$</td>
                <td class="table2">_____</td>
            </tr>
        </table>
        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
