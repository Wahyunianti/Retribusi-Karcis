<?php

namespace App\Models;

use App\Models\Masuk;
use App\Models\Keluar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Kolektor extends Model
{
    use HasFactory;
    
       protected $table = 'data_kolektor';
       protected $fillable = ['nama', 'nip', 'status', 'area', 'masa', 'file', 'keterangan'];

}
