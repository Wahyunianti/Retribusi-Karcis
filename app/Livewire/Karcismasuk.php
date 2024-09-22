<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User as UserKelola;
use App\Models\Masuk;
use App\Models\Jenis;
use App\Models\Kolektor;
use FPDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

require (app_path('Libraries/fpdf.php'));
class Karcismasuk extends Component
{
    public $users_id;
    public $nomor;


    public $penyetok;
    public $penerima;

    public $jenis_id;
    public $tgl_masuk;
    public $jml;
    public $harga_jml;

    public $ttl;
    public $satuan;
    public $file_foto;
    public $oldFile;
    public $downloadUrl;

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

    public $datau;
    public $ctKcs;



    use WithFileUploads;
    use WithPagination;

    protected $rules = [
        'penyetok' => 'required',
        'penerima' => 'required',
        'tgl_masuk' => 'required',
        'jenis_id' => 'required',
        'jml' => 'required|numeric',
        'satuan' => 'required',
        'file_foto' => 'nullable|file|max:2048',

    ];

    protected $pesan = [
        'penyetok.required' => 'Nama Penyetok harus diisi',
        'penerima.required' => 'Nama Penerima harus diisi',
        'tgl_masuk.required' => 'Tanggal harus diisi',
        'jenis_id.required' => 'Jenis harus diisi',
        'jml.required' => 'Jumlah harus diisi',
        'jml.numeric' => 'Jumlah harus angka',
        'satuan.required' => 'Satuan harus diisi',
        'file_foto.max' => 'Ukuran file maksimal 2MB'
    ];

    public function render()
    {
        $karcis = $this->getData();

        return view('livewire.karcismasuk', [
            'karcis' => $karcis,
        ]);
    }

    public function mount()
    {
        $this->datau = Kolektor::all();
        $this->jenis = Jenis::all();
        $this->ctKcs = Masuk::count();
    }

    public function getData()
    {
        $query = Masuk::with('jenis')
            ->where(function ($query) {
                $query->where('penyetok', 'like', '%' . $this->search . '%')
                    ->orWhere('tgl_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('jml', 'like', '%' . $this->search . '%');
            });

        if ($this->role) {
            $query->where('jenis_id', $this->role);
        }

        $query->orderBy('tgl_masuk', 'desc');

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

        Masuk::create([
            'penyetok' => $this->penyetok,
            'tgl_masuk' => $this->tgl_masuk,
            'jenis_id' => $this->jenis_id,
            'jml' => $this->harga_jml,
            'total' => $this->ttl,
            'users_id' => $this->penerima,
            'nomor' => $this->nomor,
            'file' => $filePath,
        ]);

        session()->flash('message', 'Data berhasil disimpan');

        $this->resetForm();
    }


    public function edit($id)
    {
        $krc = Masuk::findOrFail($id);
        $this->editUserId = $id;
        $this->penyetok = $krc->penyetok;
        $this->penerima = $krc->users_id;
        $this->tgl_masuk = $krc->tgl_masuk;
        $this->jenis_id = $krc->jenis_id;
        $this->jemnis = $krc->jenis->nama;
        $this->totall = $krc->total;
        $this->nomor = $krc->nomor;
        $this->jml = $krc->jml;
        $this->oldFile = $krc->file ? Storage::url($krc->file) : null;
        $this->satuan = 'lembar';
        $this->editMode = true;
    }

    public function cetak_satuan()
    {
        $this->downloadUrl = route('cetak-masuk', ['id' => $this->editUserId]);
    }

    public function update()
    {
        $this->validate($this->rules, $this->pesan);

        $kcr = Masuk::findOrFail($this->editUserId);

        $jenis = Jenis::find($this->jenis_id);
        if ($this->satuan == 'buku') {
            $this->harga_jml = $this->jml * 100;
        } else {
            $this->harga_jml = $this->jml;
        }
        $this->ttl = $this->harga_jml * $jenis->harga;

        $filePath = $this->file_foto ? $filePath = $this->file_foto->store('file', 'public') : $kcr->file;

        $kcr->update([
            'penyetok' => $this->penyetok,
            'tgl_masuk' => $this->tgl_masuk,
            'jenis_id' => $this->jenis_id,
            'jml' => $this->harga_jml,
            'total' => $this->ttl,
            'users_id' => $this->penerima,
            'nomor' => $this->nomor
        ]);

        if ($this->file_foto) {
            if ($kcr->file) {
                Storage::disk('public')->delete($kcr->file);
            }
            $kcr->update(['file' => $filePath]);
        }

        session()->flash('message', 'Data berhasil disimpan');


        $this->resetForm();
    }

    public function deleteUser($userId)
    {
        $user = Masuk::find($userId);
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
        $this->penyetok = '';
        $this->penerima = '';
        $this->tgl_masuk = '';
        $this->jenis_id = '';
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
