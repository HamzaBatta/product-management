<?php

namespace App\Models;

use DateTimeZone;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\Date;

class Product extends Model {

    use HasFactory;

    protected $fillable = [
        'name',
        'expire_date',
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

    public function images() : HasMany{
        return $this->hasMany(Image::class);
    }


    public function createdAt() : Attribute {
        return Attribute::make(
            get: fn($createdAt) => (Carbon::parse($createdAt))
                ->setTimezone('Asia/Riyadh')
                ->format('Y-m-d H:i:s')
        );
    }
    public function expireDate() : Attribute {
        return Attribute::make(
            get: fn($expireDate) => (Carbon::parse($expireDate))
                ->setTimezone('Asia/Riyadh')
                ->format('Y-m-d')

        );
    }
    public function updatedAt() : Attribute {
        return Attribute::make(
            get: fn($updatedAt) => (Carbon::parse($updatedAt))
                ->setTimezone('Asia/Riyadh')
                ->format('Y-m-d H:i:s')
        );
    }


        public function getExpirationDate(){
            $today = Carbon::now()->setTimezone('Asia/Riyadh');
            $expire_date = Carbon::parse($this->expire_date);
            $daysUntilExpiration = $today->diffInDays($expire_date, false);

            if($daysUntilExpiration <= 0) {

                return 0;
            }

            return $daysUntilExpiration;
        }
    public function getPrice(){

        $daysUntilExpiration = $this->getExpirationDate();

        if($daysUntilExpiration > 60 ){
            return $this->price ;
        }elseif($daysUntilExpiration >= 30){
            return $this->price * 0.7;
        }elseif($daysUntilExpiration >= 15){
            return $this->price * 0.5;
        }else {
            return $this->price * 0.3 ;
        }
    }
}
