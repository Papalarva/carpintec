<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use HasUuids;

    // Le decimos a Eloquent que no es un ID numérico autoincrementable
    public $incrementing = false;
    protected $keyType = 'string';
}