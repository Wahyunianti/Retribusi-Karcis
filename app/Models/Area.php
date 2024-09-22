<?php

namespace App\Models;

use App\Models\Keluar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    
       protected $table = 'area';
       protected $fillable = ['nama'];

       public function keluar(): HasMany {
        return $this->hasMany(Keluar::class);
    }

}
