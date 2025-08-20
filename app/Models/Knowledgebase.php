<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Knowledgebase extends Model
{
    /** @use HasFactory<\Database\Factories\KnowledgebaseFactory> */
    use HasFactory,SoftDeletes;

    protected $guarded  = [];

    protected $table = 'knowledgebase';

    protected $primaryKey = 'kb_id';

    public function category()
    {
        return $this->belongsTo(Field::class, 'field_id', 'field_id');
    }

}
