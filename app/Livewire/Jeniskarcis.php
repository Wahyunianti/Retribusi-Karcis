<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User as UserKelola;
use App\Models\Masuk;
use App\Models\Jenis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Jeniskarcis extends Component
{
    public $nama;
    public $harga;

    public $ctJenis;

    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted = null;
    public $editMode = false;
    public $editUserId;
    public $selectedUsers = [];
    public $selectAll = false;
    
    use WithPagination;

    protected $rules = [
        'nama' => 'required',
        'harga' => 'required|numeric',

    ];

    protected $pesan = [
        'nama.required' => 'Nama harus diisi',
        'harga.required' => 'Harga harus diisi',
        'harga.numeric' => 'Harga harus angka',
    ];

    public function render()
    {
        $karcis = $this->getData();

        return view('livewire.jeniskarcis', [
            'karcis' => $karcis,
        ]);
    }

    public function getData()
    {
        $query = Jenis::paginate(5);
        return $query;
    }

    public function mount()
    {
        $this->ctJenis = Jenis::count();
    }


    public function store()
    {
        $this->validate($this->rules, $this->pesan);
    
        Jenis::create([
            'nama' => $this->nama,
            'harga' => $this->harga
        ]);
    
        session()->flash('message', 'Data berhasil disimpan');
    
        $this->resetForm();
    }

    
    public function edit($id)
    {
        $krc = Jenis::findOrFail($id);
        $this->editUserId = $id;
        $this->nama = $krc->nama;
        $this->harga = $krc->harga;
        $this->editMode = true;

    }
    
    public function update()
    {
        $this->validate($this->rules, $this->pesan);
    
        $kcr = Jenis::findOrFail($this->editUserId);

        $kcr->update([
            'nama' => $this->nama,
            'harga' => $this->harga
        ]);

        session()->flash('message', 'Data berhasil disimpan');

    
        $this->resetForm();
    }

    public function deleteUser($userId)
    {
        $jenis = Jenis::find($userId);
        if ($jenis->masuk()->exists() || $jenis->keluar()->exists()) {
            session()->flash('message', 'Data tidak bisa dihapus karena memiliki relasi! edit saja');
            return;
        }
        $jenis->delete();

        session()->flash('message', 'Data berhasil dihapus');

        $this->resetPage();
    }
    
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = $this->getData()->pluck('id')->map(function ($id) {
                return (string) $id;
            })->toArray();
        } else {
            $this->resetSelected();
        }
    }
    
    public function deleteSelected()
    {
        if (count($this->selectedUsers) > 0) {
            $users = $this->getData()->whereIn('id', $this->selectedUsers);
            if ($users->masuk()->exists() || $users->keluar()->exists()) {
                session()->flash('message', 'Data tidak bisa dihapus karena memiliki relasi! edit saja');
                return;
            }                
            $users->delete();
        } else {
            session()->flash('message', 'Data berhasil dihapus.');

        }
    
        $this->resetSelected();
        $this->resetPage();

    }
    
    private function resetSelected()
    {
        $this->selectedUsers = [];
        $this->selectAll = false;
    }



    public function resetForm()
    {
        $this->nama = '';
        $this->harga = '';
        $this->editMode = false;
        $this->editUserId = null;
    }

    public function cancel()
    {
        $this->resetForm();
    }

    public function searchData()
    {
        $this->resetPage();
        $this->resetSelected();

    }
}
