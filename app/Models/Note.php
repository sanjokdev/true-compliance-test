<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;
    protected $table = 'notes';
    protected $fillable = [
        'model_type',
        'model_id',
        'note',
    ];

    public function notable()
    {
        return $this->morphTo();
    }
}
