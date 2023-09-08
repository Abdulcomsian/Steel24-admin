<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\lots;
use App\Models\FavLots;


class Customer extends Authenticatable  implements JWTSubject
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password', 'isApproved', 'contactNo', 'adharNo', 'GSTNo', 'PanNo', 'address', 'city', 'state', 'pincode', 'compnyName', 'gst_img',
        'pan_img',
        'aadhar_img', 'googleId',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function lots()
    // {
    //     return $this->belongsToMany(lots::class, 'user_lot', 'customer_id', 'lot_id')
    //         ->withTimestamps();
    // }
    public function lots()
    {
        return $this->belongsToMany(lots::class, 'user_lot', 'customer_id', 'lot_id')
            ->using(FavLots::class)
            ->withTimestamps();
    }

    public function autoBids()
    {
        return $this->hasMany(AutoBid::class, 'customerId');
    }

    public function bids()
    {
        return $this->hasMany(BidsOfLots::class, 'customerId');
    }

    public function engageLot()
    {
        return $this->belongsToMany(lots::class , 'lot_participants' , 'customer_id' , 'lot_id');
    }

    public function favlots()
    {
        return $this->belongsTo(lots::class , 'lot_id' ,'id');
    }

}
