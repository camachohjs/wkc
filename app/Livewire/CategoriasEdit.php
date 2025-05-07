<?php
namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Forma;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class CategoriasEdit extends Component
{
    public $id, $formas = [], $secciones, $nombre, $descripcion, $edad_minima, $edad_maxima, $peso_minimo, $peso_maximo, $cinta, $genero, $division, $categoria, $formaSeleccionada;
    #[Title('Categorias')]
    #[Layout('components.layouts.layout')]

    public function resetInputFields()
    {
        $this->id = null;
        $this->nombre = '';
        $this->edad_minima = '';
        $this->edad_maxima = '';
        $this->peso_maximo = '';
        $this->peso_minimo = '';
        $this->cinta = null;
        $this->genero = '';
        $this->division = '';
    }

    public function mount()
    {
        $this->id = request()->route('id');

        if ($this->id) {
            $categoria = Categoria::findOrFail($this->id);

            $this->nombre = $categoria->nombre;
            $this->edad_minima = $categoria->edad_minima;
            $this->edad_maxima = $categoria->edad_maxima;
            $this->peso_maximo = $categoria->peso_maximo;
            $this->peso_minimo = $categoria->peso_minimo;
            $this->genero = $categoria->genero;
            $this->division = $categoria->division;
            $this->formaSeleccionada = $categoria->forma_id;
            $this->cinta = $categoria->cinta ?? $categoria->cintas->pluck('cinta')->toArray();
        }
        $this->formas = Forma::all();
    }

    public function store()
    {
        /* dd($this->formaSeleccionada); */
        $this->validate([
            'nombre' => 'required',
            'edad_minima' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'edad_maxima' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'peso_minimo' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'peso_maximo' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'formaSeleccionada' => 'nullable|numeric',
            'division' => 'required|string',
            'cinta' => 'nullable|array',
        ]);

        $data = [
            'nombre' => $this->nombre,
            'edad_minima' => $this->edad_minima,
            'edad_maxima' => $this->edad_maxima,
            'peso_minimo' => $this->peso_minimo,
            'peso_maximo' => $this->peso_maximo,
            'forma_id' => $this->formaSeleccionada,
            'genero' => $this->genero,
            'cinta' => NULL,
            'division' => $this->division,
        ];

        $categoria = Categoria::updateOrCreate(['id' => $this->id], $data);

        foreach ($this->cinta as $cinta) {
            $categoria->cintas()->create(['cinta' => $cinta]);
        }

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', $this->id ? 'Categoria actualizada correctamente.' : 'Categoria creada correctamente.');

        $this->resetInputFields();
        return redirect('/categorias');
    }

    public function render()
    {
        return view('livewire.categorias-edit');
    }
}
