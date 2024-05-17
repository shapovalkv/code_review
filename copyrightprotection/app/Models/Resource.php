<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class Resource extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'resource_posts';

    protected $fillable = [
        'title',
        'content',
        'status',
        'featured_image_url',
        'author_id',
        'posted_at',
        'slug'
    ];

    const DRAFT = 'draft';
    const PUBLISH = 'publish';

    const STATUSES = [
        self::DRAFT => 'Draft',
        self::PUBLISH => 'Publish',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($resource) {
            $resource->slug = Str::slug($resource->title, '-');
        });
    }
}
