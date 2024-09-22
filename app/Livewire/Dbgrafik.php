<?php

namespace App\Livewire;

use Livewire\Component;

class Dbgrafik extends Component
{
    public $data = [];

    public function mount()
    {
        $this->data = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            'values' => [50, 120, 70, 180, 90],
        ];
    }

    public function render()
    {
        return view('livewire.dbgrafik', [
            'data' => $this->data,
        ]);
    }
}
