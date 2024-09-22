<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Jenis;
use App\Models\Area;



class Keluar extends Model
{

    protected $table = 'karcis_keluar';
    protected $fillable = [
        'id',
        'kolektor',
        'tgl_ambil',
        'jenis_id',
        'jml',
        'total',
        'area_id',
        'users_id',
        'file',
        'nomor',
    ];

    public function jenis(): BelongsTo {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    public function area(): BelongsTo {
        return $this->belongsTo(Area::class, 'area_id');
    }

}
