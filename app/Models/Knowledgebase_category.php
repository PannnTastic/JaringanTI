<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knowledgebase_category extends Model
{
    /** @use HasFactory<\Database\Factories\KnowledgebaseCategoryFactory> */
    use HasFactory;

    protected $table = 'knowledgebase_category';
    protected $guarded = [];
    protected $primaryKey = 'kbc_id';

    public function knowledgebase()
    {
        return $this->hasMany(Knowledgebase::class, 'kbc_id', 'kbc_id');
    }
}
