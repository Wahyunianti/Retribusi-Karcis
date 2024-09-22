<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User as UserKelola;
use App\Models\Masuk;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Areakarcis extends Component
{
    public $nama;
    public $ctArea;



    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted = null;
    public $editMode = false;
    public $editUserId;
    public $selectedUsers = [];
    public $selectAll = false;
    
    use WithPagination;

    protected $rules = [
        'nama' => 'required',

    ];

    protected $pesan = [
        'nama.required' => 'Nama harus diisi',
    ];

    public function render()
    {
        $karcis = $this->getData();

        return view('livewire.areakarcis', [
            'karcis' => $karcis,
        ]);
    }

    public function getData()
    {
        $query = Area::paginate(5);
        return $query;
    }

    public function mount()
    {
        $this->ctArea = Area::count();
    }


    public function store()
    {
        $this->validate($this->rules, $this->pesan);
    
        Area::create([
            'nama' => $this->nama,
        ]);
    
        session()->flash('message', 'Data berhasil disimpan');
    
        $this->resetForm();
    }

    
    public function edit($id)
    {
        $krc = Area::findOrFail($id);
        $this->editUserId = $id;
        $this->nama = $krc->nama;
        $this->editMode = true;

    }
    
    public function update()
    {
        $this->validate($this->rules, $this->pesan);
    
        $kcr = Area::findOrFail($this->editUserId);

        $kcr->update([
            'nama' => $this->nama,
        ]);
        session()->flash('message', 'Data berhasil disimpan');
    
        $this->resetForm();
    }

    public function deleteUser($userId)
    {
        $user = Area::find($userId);
        if ($user->keluar()->exists()) {
            session()->flash('message', 'Data tidak bisa dihapus karena memiliki relasi! edit saja');
            return;
        } 

        $user->delete();

        session()->flash('message', 'Data berhasil dihapus');

        $this->resetPage();
    }




    public function resetForm()
    {
        $this->nama = '';
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

    }
}
