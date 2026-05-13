<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'contact_phone',
        'contact_email',
        'address',
        'primary_color',
        'secondary_color',
        'hero_title',
        'hero_subtitle',
        'cover_image',
        'logo',
        'is_active',
        'notify_on_new_order',
        'notification_emails',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'notify_on_new_order' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
