<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Jenis;


class Masuk extends Model
{

    protected $table = 'karcis_masuk';
    protected $fillable = [
        'id',
        'penyetok',
        'tgl_masuk',
        'jenis_id',
        'jml',
        'total',
        'users_id',
        'file',
        'nomor',
    ];

    public function jenis(): BelongsTo {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }


}
