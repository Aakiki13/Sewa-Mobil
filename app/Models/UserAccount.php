<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Useraccount extends Model
{
    use HasFactory;

    protected $table = 'users'; // Nama tabel yang ingin dihubungkan

    protected $fillable = [
        'name',
        'phone.number',
        'email',
        'roles',
        'profile_photo_path',
        'password',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    // Get first photo from photos
    // public function getThumbnailAttribute() // thumbnail
    // {
    //     if ($this->photos) {
    //         return Storage::url(json_decode($this->photos)[0]);
    //     }

    //     return 'https://via.placeholder.com/800x600';
    // }
}
