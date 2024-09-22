<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Jenis;
use App\Models\Area;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;



class RuterController extends Controller
{

    public function datauser()
    {
        return view('Admin.datapengguna');
    }

    public function dataprofil()
    {
        return view('Admin.editprofil');
    }

    public function datakolektor()
    {
        return view('Admin.datakolektor');
    }

    public function datajeniskarcis()
    {
        return view('Admin.datajeniskarcis');
    }

    public function dataareakarcis()
    {
        return view('Admin.dataareakarcis');
    }    

    public function datakarcisin()
    {
        return view('Admin.datakarcismasuk');
    }

    public function datakarcisout()
    {
        return view('Admin.datakarciskeluar');
    }

    public function datakarcisstok()
    {
        return view('Admin.datakarcisstok');
    }

    public function datalaporan()
    {
        $jenis = Jenis::all();
        $area = Area::all();

        $months = [
            ['id' => '01', 'nama' => 'Januari'],
            ['id' => '02', 'nama' => 'Februari'],
            ['id' => '03', 'nama' => 'Maret'],
            ['id' => '04', 'nama' => 'April'],
            ['id' => '05', 'nama' => 'Mei'],
            ['id' => '06', 'nama' => 'Juni'],
            ['id' => '07', 'nama' => 'Juli'],
            ['id' => '08', 'nama' => 'Agustus'],
            ['id' => '09', 'nama' => 'September'],
            ['id' => '10', 'nama' => 'Oktober'],
            ['id' => '11', 'nama' => 'November'],
            ['id' => '12', 'nama' => 'Desember'],
        ];

        return view('Admin.datalaporan', compact('jenis', 'area', 'months'));
    }

    public function editUser(Request $request){
        $message = [
            'nama.required' => 'Nama harus diisi',
            'username.required' => 'Username harus diisi',
            'bagian.required' => 'Bagian harus diisi',
            'nip.required' => 'NIP harus diisi',
            'no_telp.required' => 'Nomor Telepon harus diisi',
            'no_telp.numeric' => 'Nomor Telepon harus angka',
            'status.required' => 'Status harus diisi'
        ];

        $this->validate($request, [
            'nama' => 'required',
            'username' => 'required',
            'bagian' => 'required',
            'nip' => 'required',
            'no_telp' => 'required|numeric',
            'status' => 'required',
        ], $message);

        $user = auth()->user();

        $data = User::find($user->id);
        $data->nama = $request->nama;
        $data->nip = $request->nip;
        $data->username = $request->username;
        $data->no_telp = $request->no_telp;
        $data->status = $request->status;
        $data->bagian = $request->bagian;

        if ($request->has('password') && !empty($request->password))  {
            $data->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            $oldFileName = $data->foto;
            if ($oldFileName && Storage::exists('public/' . $oldFileName)) {
                Storage::delete('public/' . $oldFileName);
            }
    
            $file = $request->file('foto');
            $fileName =  'photos/' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/', $fileName);
    
            $data->update(['foto' => $fileName]);
        }
        $data->save();

        return redirect()->back()->with('message', 'Data berhasil disimpan');
    }

}