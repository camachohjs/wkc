<?php

namespace App\Livewire;

use Livewire\Component;

class Clasificaciones extends Component
{
    public $activeTab = 'perfil';

    public function setActiveTab($tabName)
    {
        $this->activeTab = $tabName;
    }
    
    public function render()
    {
        return view('livewire.clasificaciones');
    }
}
