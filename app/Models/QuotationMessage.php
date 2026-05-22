<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // <-- IMPORTANTE
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class QuotationMessage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasUuids; 

    protected $fillable = ['quotation_id', 'sender_type', 'message'];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}