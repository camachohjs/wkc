<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class DashboardAlumno extends Component
{
    #[Title('Dashboard')]
    #[Layout('components.layouts.layout')]

    public function render()
    {
        return view('livewire.dashboard-alumno');
    }

    public function proximosEventos(){
        return redirect('proximos-eventos');
    }
}
