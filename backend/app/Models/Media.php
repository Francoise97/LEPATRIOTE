<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'model_type',
        'model_id',
        'collection_name',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
