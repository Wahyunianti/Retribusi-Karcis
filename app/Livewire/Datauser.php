<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User as UserKelola;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Datauser extends Component
{
    public $nama;
    public $username;
    public $password;
    public $bagian;
    public $nip;
    public $no_telp;
    public $status;
    public $role_id;
    public $foto;
    public $oldFoto;
    public $ctUser;


    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted = null;
    public $editMode = false;
    public $editUserId;
    public $selectedUsers = [];
    public $selectAll = false;

    public $search = '';
    public $role = '';
    

    use WithFileUploads;
    use WithPagination;

    protected $rules = [
        'nama' => 'required',
        'username' => 'required',
        'bagian' => 'required',
        'nip' => 'required',
        'no_telp' => 'required|numeric',
        'status' => 'required',
        'role_id' => 'required',
    ];

    protected $pesan = [
        'nama.required' => 'Nama harus diisi',
        'username.required' => 'Username harus diisi',
        'bagian.required' => 'Bagian harus diisi',
        'nip.required' => 'NIP harus diisi',
        'no_telp.required' => 'Nomor Telepon harus diisi',
        'no_telp.numeric' => 'Nomor Telepon harus angka',
        'status.required' => 'Status harus diisi',
        'role_id.required' => 'Level harus diisi'
    ];

    public function render()
    {
        $users = $this->getData();

        return view('livewire.datauser', [
            'users' => $users,
        ]);
    }

    public function mount()
    {
        $this->ctUser = UserKelola::count();
    }

    public function deleteUser($userId)
    {
        $user = UserKelola::find($userId);
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
        $query = UserKelola::query();

        if ($this->role == '1') {
            $query->where('role_id', 1);
        } elseif ($this->role == '2') {
            $query->where('role_id', 2);
        }

        $users = $query->where(function($q) {
            $q->where('nama', 'like', '%' . $this->search . '%')
              ->orWhere('nip', 'like', '%' . $this->search . '%')
              ->orWhere('no_telp', 'like', '%' . $this->search . '%')
              ->orWhere('bagian', 'like', '%' . $this->search . '%');
            })->paginate(5);

        return $users;
    }

    public function resetForm()
    {
        $this->nama = '';
        $this->username = '';
        $this->password = '';
        $this->bagian = '';
        $this->nip = '';
        $this->no_telp = '';
        $this->status = '';
        $this->role_id = '';
        $this->foto = null;
        $this->oldFoto = null;
        $this->editMode = false;
        $this->editUserId = null;
        $this->search = '';
    }

    public function store()
    {

        $this->validate($this->rules, $this->pesan);

        $photoPath = $this->foto ? $this->foto->store('photos', 'public') : null;

        UserKelola::create([
            'nama' => $this->nama,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'bagian' => $this->bagian,
            'nip' => $this->nip,
            'no_telp' => $this->no_telp,
            'status' => $this->status,
            'role_id' => $this->role_id,
            'foto' => $photoPath,
        ]);

        session()->flash('message', 'Data berhasil disimpan');

        $this->resetForm();
    }

    public function edit($id)
    {
        $user = UserKelola::findOrFail($id);
        $this->editUserId = $id;
        $this->nama = $user->nama;
        $this->username = $user->username;
        $this->password = '';
        $this->bagian = $user->bagian;
        $this->nip = $user->nip;
        $this->no_telp = $user->no_telp;
        $this->status = $user->status;
        $this->role_id = $user->role_id;
        $this->oldFoto = $user->foto;
        $this->editMode = true;
    }

    public function update()
    {

        $this->validate($this->rules, $this->pesan);

        $user = UserKelola::findOrFail($this->editUserId);

        $user->update([
            'nama' => $this->nama,
            'username' => $this->username,
            'bagian' => $this->bagian,
            'nip' => $this->nip,
            'no_telp' => $this->no_telp,
            'status' => $this->status,
            'role_id' => $this->role_id,
        ]);

        if ($this->password) {
            $user->update(['password' => bcrypt($this->password)]);
        }

        if ($this->foto) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->update(['foto' => $this->foto->store('photos', 'public')]);
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
