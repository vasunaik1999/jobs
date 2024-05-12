<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'title',
        'company_name',
        'location',
        'description',
        'keywords',
        'isPublished',
    ];

    protected $casts = [
        'keywords' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
