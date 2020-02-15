<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password',
  ];

  protected $casts = [
    'is_admin' => 'boolean',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];

  public function address()
  {
      return $this->hasOne(UserAddress::class);
  }

  public function favoriteProducts()
  {
      return $this->belongsToMany(Product::class, 'user_favorite_products')
          ->withTimestamps()
          ->orderBy('user_favorite_products.created_at', 'desc');
  }

  public function cartItems()
  {
      return $this->hasMany(CartItem::class);
  }
public function isAdmin()
{
  return $this->is_admin; // поле is_admin в таблице users
}
}
