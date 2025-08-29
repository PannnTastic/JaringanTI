<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penyerapan extends Model
{
    /** @use HasFactory<\Database\Factories\PenyerapanFactory> */
    use HasFactory,SoftDeletes;

    protected $table = 'penyerapan';
    protected $primaryKey = 'id_penyerapan';

    protected $guarded = [];

    public function substation()
    {
        return $this->belongsTo(Substation::class, 'substation_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'id_penyerapan', 'id_penyerapan');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
