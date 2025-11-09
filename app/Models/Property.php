<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'properties';
    protected $fillable = [
        'organisation',
        'property_type',
        'parent_property_id',
        'uprn',
        'address',
        'town',
        'postcode',
        'live'
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class,'model');
    }
}
