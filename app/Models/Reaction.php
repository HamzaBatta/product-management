<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reaction extends Model {

    use HasFactory;

    protected $fillable
        = [
            'type',
            'user_id',
            'product_id'
        ];

    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }

    public function product(): BelongsTo {
        return $this->belongsTo( Product::class );
    }

}
