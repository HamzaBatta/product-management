<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'product_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($createdAt) => (Carbon::parse($createdAt))
                ->setTimezone('Asia/Riyadh')
                ->format('Y-m-d H:i:s'),
        );
    }
    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($updatedAt) => (Carbon::parse($updatedAt))
                ->setTimezone('Asia/Riyadh')
                ->format('Y-m-d H:i:s'),

        );
    }
}
