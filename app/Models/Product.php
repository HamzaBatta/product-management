<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model {

    use HasFactory;

    protected $fillable = [
            'name',
            'expire_date',
            'image',
            'phone_number',
            'category',
            'price',
            'quantity',
            'user_id'
        ];

    public static array $categories = ['food', 'electronics', 'clothing'];



    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function reactions() : HasMany{
        return $this->hasMany(Reaction::class);
    }

    public function comments() : HasMany{
        return $this->hasMany(Comment::class);
    }

}
