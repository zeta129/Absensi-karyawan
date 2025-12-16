<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaceEmbedding extends Model
{
    protected $table = 'face_embeddings';
    protected $guarded = [];
    protected $casts = [
        'embedding' => 'array',
    ];
}
