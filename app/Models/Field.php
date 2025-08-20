<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    
    protected $table = 'field';
    protected $guarded = [];
    protected $primaryKey = 'field_id';

    public function knowledgebase()
    {
        return $this->hasMany(Knowledgebase::class, 'field_id', 'field_id');
    }
}
