<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User as UserKelola;
use App\Models\Masuk;
use App\Models\Keluar;
use App\Models\Kolektor;
use App\Models\Jenis;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Karciskeluar extends Component
{
    public $users_id;
    public $nomor;

    public $area_id;
    public $kolektor;
    public $penyerah;
    public $jenis_id;
    public $tgl_ambil;
    public $jml;
    public $harga_jml;

    public $ttl;
    public $satuan;
    public $file_foto;
    public $oldFile;

    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted = null;
    public $editMode = false;
    public $editUserId;
    public $selectedUsers = [];
    public $selectAll = false;

    public $search = '';
    public $role = '';
    public $jenis;
    public $jemnis;
    public $totall;
    public $amrea;
    public $area;
    public $datau;
    public $datak;
    public $ctKcs;
    public $downloadUrl;

    

    use WithFileUploads;
    use WithPagination;

    protected $rules = [
        'kolektor' => 'required',
        'penyerah' => 'required',
        'tgl_ambil' => 'required',
        'jenis_id' => 'required',
        'jml' => 'required|numeric',
        'satuan' => 'required',
        'area_id' => 'required',
        'file_foto' => 'nullable|file|max:2048',

    ];

    protected $pesan = [
        'kolektor.required' => 'Nama Kolektor harus diisi',
        'penyerah.required' => 'Nama Penyerah harus diisi',
        'tgl_ambil.required' => 'Tanggal harus diisi',
        'jenis_id.required' => 'Jenis harus diisi',
        'jml.required' => 'Jumlah harus diisi',
        'jml.numeric' => 'Jumlah harus angka',
        'area_id.required' => 'Area harus diisi',
        'satuan.required' => 'Satuan harus diisi',
        'file_foto.max' => 'Ukuran file maksimal 2MB'
    ];

    public function render()
    {
        $karcis = $this->getData();

        return view('livewire.karciskeluar', [
            'karcis' => $karcis,
        ]);
    }

    public function mount()
    {
        $this->datak = Kolektor::all();
        $this->datau = Kolektor::all();

        $this->jenis = Jenis::all();
        $this->area = Area::all();

        $this->ctKcs = Keluar::count();

    }

    public function getData()
    {
        $query = Keluar::with('jenis', 'area')
            ->where(function ($query) {
                $query->where('kolektor', 'like', '%' . $this->search . '%')
                      ->orWhere('tgl_ambil', 'like', '%' . $this->search . '%')
                      ->orWhere('jml', 'like', '%' . $this->search . '%')
                      ->orWhereHas('area', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            });
    
        if ($this->role) {
            $query->where('jenis_id', $this->role);
        }
    
        $query->orderBy('tgl_ambil', 'desc');
    
        return $query->paginate(5);
    }



    public function updated($propertyName)
    {
        if ($propertyName == 'jml' || $propertyName == 'satuan') {
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $jenis = Jenis::find($this->jenis_id);
        if ($this->satuan == 'buku') {
            $this->harga_jml = $this->jml * 100;
        } else {
            $this->harga_jml = $this->jml;
        }
        $this->ttl = $this->harga_jml * $jenis->harga;
    }


    public function store()
    {
        $this->validate($this->rules, $this->pesan);
    
        $filePath = $this->file_foto ? $this->file_foto->store('file', 'public') : null;
    
        Keluar::create([
            'kolektor' => $this->kolektor,
            'tgl_ambil' => $this->tgl_ambil,
            'jenis_id' => $this->jenis_id,
            'jml' => $this->harga_jml,
            'nomor' => $this->nomor,
            'total' => $this->ttl,
            'area_id' => $this->area_id,
            'users_id' => $this->penyerah,
            'file' => $filePath,
        ]);
    
        session()->flash('message', 'Data berhasil disimpan');
    
        $this->resetForm();
    }

    
    public function edit($id)
    {
        $krc = Keluar::findOrFail($id);
        $this->editUserId = $id;
        $this->kolektor = $krc->kolektor;
        $this->penyerah = $krc->users_id;
        $this->area_id = $krc->area_id;
        $this->tgl_ambil = $krc->tgl_ambil;
        $this->nomor = $krc->nomor;
        $this->jenis_id = $krc->jenis_id;
        $this->jml = $krc->jml;
        $this->oldFile = $krc->file ? Storage::url($krc->file) : null;
        $this->satuan = 'lembar';
        $this->jemnis = $krc->jenis->nama;
        $this->totall = $krc->total;
        $this->amrea = $krc->area->nama;
        $this->editMode = true;

    }

    public function cetak_satuan()
    {
        $this->downloadUrl = route('cetak-keluar', ['id' => $this->editUserId]);
    }
    
    public function update()
    {
        $this->validate($this->rules, $this->pesan);
    
        $kcr = Keluar::findOrFail($this->editUserId);



        $jenis = Jenis::find($this->jenis_id);
        if ($this->satuan == 'buku') {
            $this->harga_jml = $this->jml * 100;
        } else {
            $this->harga_jml = $this->jml;
        }
        $this->ttl = $this->harga_jml * $jenis->harga;
    
        $filePath = $this->file_foto ? $filePath = $this->file_foto->store('file', 'public') : $kcr->file;
    
        $kcr->update([
            'kolektor' => $this->kolektor,
            'tgl_ambil' => $this->tgl_ambil,
            'jenis_id' => $this->jenis_id,
            'jml' => $this->harga_jml,
            'nomor' => $this->nomor,
            'total' => $this->ttl,
            'area_id' => $this->area_id,
            'users_id' => $this->penyerah
        ]);

        if ($this->file_foto) {
            if ($kcr->file) {
                Storage::disk('public')->delete($kcr->file);
            }
            $kcr->update([ 'file' => $filePath]);

        }

        session()->flash('message', 'Data berhasil disimpan');

    
        $this->resetForm();
    }

    public function deleteUser($userId)
    {
        $user = Keluar::find($userId);
        if ($user) {
            if ($user->file) {
                Storage::disk('public')->delete($user->file);
            }
            $user->delete();

        }
        session()->flash('message', 'Data berhasil dihapus');

        $this->resetPage();
    }
        public function resetForm()
    {
        $this->penyerah = '';
        $this->kolektor = '';
        $this->tgl_ambil = '';
        $this->jenis_id = '';
        $this->area_id = '';
        $this->jml = '';
        $this->nomor = '';
        $this->satuan = '';
        $this->file_foto = null;
        $this->oldFoto = null;
        $this->editMode = false;
        $this->editUserId = null;
        $this->search = '';
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
