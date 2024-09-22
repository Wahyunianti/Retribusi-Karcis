<?php

namespace App\Models;

use App\Models\Masuk;
use App\Models\Keluar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;
    
       protected $table = 'jenis';
       protected $fillable = ['nama', 'harga'];

       public function masuk(): HasMany {
           return $this->hasMany(Masuk::class);
       }

       public function keluar(): HasMany {
        return $this->hasMany(Keluar::class);
    }
}
