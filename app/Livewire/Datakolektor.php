<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User as UserKelola;
use App\Models\Area;
use App\Models\Kolektor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Datakolektor extends Component
{
    public $nama;

    public $area_id;
    public $masa;
    public $nip;
    public $status;
    public $keterangan;
    public $oldFile;
    public $ctUser;
    public $file_foto;
    public $area;



    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted = null;
    public $editMode = false;
    public $editUserId;
    public $selectedUsers = [];
    public $selectedAreas = [];
    public $selectAll = false;

    public $search = '';
    public $role = '';
    

    use WithFileUploads;
    use WithPagination;

    protected $rules = [
        'nama' => 'required',
        'masa' => 'required',
        'nip' => 'required',
        'status' => 'required',
        'keterangan' => 'required',
        'file_foto' => 'nullable|file|max:2048',
    ];

    protected $pesan = [
        'nama.required' => 'Nama harus diisi',
        'masa.required' => 'Masa harus diisi',
        'nip.required' => 'NIP harus diisi',
        'status.required' => 'Status harus diisi',
        'keterangan.required' => 'Keterangan harus diisi',
        'file_foto.max' => 'Ukuran file maksimal 2MB'
    ];

    public function render()
    {
        $users = $this->getData();

        return view('livewire.datakolektor', [
            'users' => $users,
        ]);
    }

    public function mount()
    {
        $this->ctUser = Kolektor::count();
        $this->area = Area::all();

    }

    public function addArea($area)
    {
        if (!in_array($area, $this->selectedAreas)) {
            $this->selectedAreas[] = $area;
        }
    }

    public function removeArea($area)
    {
        $this->selectedAreas = array_filter($this->selectedAreas, function($selectedArea) use ($area) {
            return $selectedArea !== $area;
        });
    }


    public function deleteUser($userId)
    {
        $user = Kolektor::find($userId);
        if ($user) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->delete();
        }

        session()->flash('message', 'Data berhasil dihapus');

        $this->resetPage();
    }
    

    public function getData()
    {
        $query = Kolektor::query();

        $users = $query->where(function($q) {
            $q->where('nama', 'like', '%' . $this->search . '%')
              ->orWhere('nip', 'like', '%' . $this->search . '%')
              ->orWhere('status', 'like', '%' . $this->search . '%')
              ->orWhere('masa', 'like', '%' . $this->search . '%')
              ->orWhere('area', 'like', '%' . $this->search . '%');
            })->paginate(5);

        return $users;
    }

    public function resetForm()
    {
        $this->nama = '';
        $this->masa = '';
        $this->nip = '';
        $this->status = '';
        $this->selectedAreas = [];
        $this->keterangan = '';
        $this->file_foto = null;
        $this->oldFoto = null;
        $this->editMode = false;
        $this->editUserId = null;
        $this->search = '';
    }

    public function store()
    {

        $this->validate($this->rules, $this->pesan);
        $selectedAreasString = implode('|', $this->selectedAreas);

    
        $filePath = $this->file_foto ? $this->file_foto->store('file', 'public') : null;

        Kolektor::create([
            'nama' => $this->nama,
            'area' => $selectedAreasString,
            'masa' => $this->masa,
            'nip' => $this->nip,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
            'file' => $filePath,
        ]);

        session()->flash('message', 'Data berhasil disimpan');

        $this->resetForm();
    }

    public function edit($id)
    {
        $user = Kolektor::findOrFail($id);
        $this->editUserId = $id;
        $this->nama = $user->nama;
        $this->nip = $user->nip;
        $this->masa = $user->masa;
        $this->keterangan = $user->keterangan;
        $this->status = $user->status;
        $this->oldFile = $user->file ? Storage::url($user->file) : null;
        $this->editMode = true;
    }

    public function update()
    {

        $this->validate($this->rules, $this->pesan);

        $selectedAreasString = implode('|', $this->selectedAreas);
    
        $filePath = $this->file_foto ? $this->file_foto->store('file', 'public') : null;

        $user = Kolektor::findOrFail($this->editUserId);


        
        $user->update([
            'nama' => $this->nama,
            'masa' => $this->masa,
            'nip' => $this->nip,
            'status' => $this->status,
            'keterangan' => $this->keterangan,           
        ]);

        if ($this->selectedAreas) {
            $user->update(['area' => $selectedAreasString]);
        }

        
        if ($this->file_foto) {
            if ($user->file) {
                Storage::disk('public')->delete($user->file);
            }
            $user->update([ 'file' => $filePath]);
        }


        session()->flash('message', 'Data berhasil disimpan');

        $this->resetForm();
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
