<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasificacionesController;
use App\Http\Controllers\PDFController;
use App\Livewire\{
    AgregarEscuela, AlumnoHistorico, Areas, AreasCategorias, AreasCategoriasDivisiones, AreasCategoriasKatas,
    AtletasGrid, AtletasList, AñadirRanking, Blog, Calificacion, Categorias, CategoriasEdit, CategoriasTorneo, Clasificaciones,
    CombinarSensei, CompetidorEdit, Register, Login, Menu, Panel, Torneos, Competidores, CrearAlumno, Credenciales,
    DashboardAlumno, EditarRegistro, Escuelas, EscuelasEdit, Eventos, Formas, FormasEdit, Ganador, InscribirAdmin, Inscritos,
    MiRanking, MisCompetidores, MisPuntos, MisTorneos, PantallaCombate, PantallaCombateAdmin, PantallaKatas,
    PasswordForm, ProfesorEdit, Profesores, ProximosEventos, ProximosEventosMaestros, Puntuar, Ranking,
    RecuperarPassword, RegistrarAdmin, RegistroAlumno, RegistroMaestro, ResultadoKatas, Resultados, SenseiHistorico, TorneoDetalle,
    TorneosEdit
};
use Illuminate\Support\Facades\App;

App::setLocale("es");

Route::get('/', Menu::class);

Route::get('/register', Register::class)->name('register');
Route::get('/login', Login::class)->name('login');
Route::get('/recuperar-password', RecuperarPassword::class)->name('recuperar-password');
Route::get('/recuperar-password/{token}-{email}', PasswordForm::class)->name('password.reset');
Route::get('/registro-alumno/{torneo_id}', RegistroAlumno::class)->name('registro-alumno');
Route::get('/aficionado', Clasificaciones::class)->name('clasificaciones');
Route::get('/eventos', Eventos::class)->name('eventos');
Route::get('/atletas-list', AtletasList::class)->name('atletas.list');
Route::get('/atletas-grid', AtletasGrid::class)->name('atletas.grid');
Route::get('/generate-pdf/{participanteId}/{torneoId}', [PDFController::class, 'generatePDF'])->name('generate-pdf');
Route::get('/blog', Blog::class)->name('blog');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [Login::class, 'logout'])->name('logout');
    Route::get('/panel', Panel::class)->name('panel');

    // Rutas para el rol admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/torneos', Torneos::class)->name('torneos');
        Route::get('/torneos-edit/{id?}', TorneosEdit::class)->name('torneos-edit');
        Route::get('/competidores', Competidores::class)->name('competidores');
        Route::get('/escuelas', Escuelas::class)->name('escuelas');
        Route::get('/escuelas-edit/{id?}', EscuelasEdit::class)->name('escuelas-edit');
        Route::get('/categorias', Categorias::class)->name('categorias');
        Route::get('/categorias-edit/{id?}', CategoriasEdit::class)->name('categorias-edit');
        Route::get('/inscritos/{id}', Inscritos::class)->name('inscritos');
        Route::get('/inscritos/peso/{id}', Calificacion::class)->name('calificacion');
        Route::get('/inscritos/puntuar/{id}', Puntuar::class)->name('puntuar');
        Route::get('/resultados/{id}', Resultados::class)->name('resultados');
        Route::get('/categorias-torneo/{id}', CategoriasTorneo::class)->name('categorias-torneo');
        Route::get('/profesores', Profesores::class)->name('profesores');
        Route::get('/profesor-edit/{id?}', ProfesorEdit::class)->name('profesor-edit');
        Route::get('/formas', Formas::class)->name('formas');
        Route::get('/formas-edit/{id?}', FormasEdit::class)->name('formas-edit');
        Route::get('/alumno-historico', AlumnoHistorico::class)->name('alumno-historico');
        Route::get('/sensei-historico', SenseiHistorico::class)->name('sensei-historico');
        Route::get('/combinar-sensei/{id}', CombinarSensei::class)->name('combinar-sensei');
        Route::get('/credenciales/{torneoId}', Credenciales::class)->name('credenciales');
        Route::get('/inscribir-admin/{id}', InscribirAdmin::class)->name('inscribir-admin');
        Route::get('/registrar-admin/{torneo_id}/{id_participante?}', RegistrarAdmin::class)->name('registrar-admin');
        Route::get('/añadir-ranking/', AñadirRanking::class)->name('añadir-ranking');
        Route::get('/esperando-descarga/{id}', [Inscritos::class, 'esperandoDescarga'])->name('esperando-descarga');
    });

    // Rutas para el rol torneo user y admin
    Route::middleware(['role:torneo user|admin'])->group(function () {
        Route::get('/iniciar-torneo/{torneoId}/fecha/{fechaId}', Areas::class)->name('areas');
        Route::get('/iniciar-torneo/{torneoId}/fecha/{fechaId}/area/{areaId}', AreasCategorias::class)->name('areas-categorias');
        Route::get('/iniciar-torneo/{torneoId}/fecha/{fechaId}/area/{areaId}/categoria/{categoriaId}', AreasCategoriasDivisiones::class)->name('areas-categorias-divisiones');
        Route::get('/iniciar-torneo/{torneoId}/fecha/{fechaId}/area/{areaId}/categoria/{categoriaId}/kata', AreasCategoriasKatas::class)->name('areas-categorias-katas');
        Route::get('/combate/{id}', PantallaCombateAdmin::class)->name('pantalla-combate-admin');
        Route::get('/combate-publico/{id}', PantallaCombate::class)->name('pantalla-combate');
        Route::get('/pantalla-katas/{id}', PantallaKatas::class)->name('pantalla-katas');
        Route::get('/ganador/{id}/{ganadorId?}', Ganador::class)->name('ganador');
        Route::get('/resultado-katas/{id}', ResultadoKatas::class)->name('resultado-katas');
    });

    // Rutas para el rol torneo user con verificación de área
    Route::middleware(['role:torneo user|admin', 'check.area.access'])->group(function () {
        Route::get('/iniciar-torneo/{torneoId}/fecha/{fechaId}', Areas::class)->name('areas');
        Route::get('/iniciar-torneo/{torneoId}/fecha/{fechaId}/area/{areaId}', AreasCategorias::class)->name('areas-categorias');
        Route::get('/iniciar-torneo/{torneoId}/fecha/{fechaId}/area/{areaId}/categoria/{categoriaId}', AreasCategoriasDivisiones::class)->name('areas-categorias-divisiones');
        Route::get('/iniciar-torneo/{torneoId}/fecha/{fechaId}/area/{areaId}/categoria/{categoriaId}/kata', AreasCategoriasKatas::class)->name('areas-categorias-katas');
    });

    //Rutas para superivisor(maestro) y admin
    Route::middleware(('role:supervisor|admin'))->group(function (){
        Route::get('/competidor-edit/{id?}', CompetidorEdit::class)->name('competidor-edit');
    });

    // Rutas para el rol supervisor
    Route::middleware(['role:supervisor'])->group(function () {
        Route::get('/mis-competidores', MisCompetidores::class)->name('mis-competidores');
        Route::get('/proximos-eventos/{id?}', ProximosEventosMaestros::class)->name('proximos-eventos-maestros');
        Route::get('/registro-maestro/{torneo_id}/{competidor_id?}', RegistroMaestro::class)->name('registro-maestro');
        Route::get('/crear-competidor', CrearAlumno::class)->name('crear-competidor');
        Route::get('/editar-registro/{id}', EditarRegistro::class)->name('editar-registro');
    });

    // Rutas accesibles para todos los usuarios autenticados
    Route::get('/dashboard', DashboardAlumno::class)->name('dashboard-alumno');
    Route::get('/dashboard/proximos-eventos', ProximosEventos::class)->name('proximos-eventos');
    Route::get('/mis-torneos', MisTorneos::class)->name('mis-torneos');
    Route::get('/dashboard/mis-puntos', MisPuntos::class)->name('mi-puntos');
    Route::get('/ranking', Ranking::class)->name('ranking');
});

Route::group([
    'prefix' => '/clasificaciones',
    'as' => 'clasificaciones.'
], function(){
    Route::get('/', [ClasificacionesController::class, 'index'])->name('index');
});

Route::get('/torneo-{id}', TorneoDetalle::class)->name('torneo-detalle');
