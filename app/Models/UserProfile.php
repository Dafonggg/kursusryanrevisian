<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'whatsapp_opt_in',
        'address',
        'kk_path',
        'ktp_path',
        'photo_path',
        'avatar_url',
    ];

    protected $casts = [
        'whatsapp_opt_in' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get avatar URL with fallback priority:
     * 1. photo_path (uploaded photo) - if file exists
     * 2. avatar_url (Google avatar URL)
     * 3. Default avatar
     */
    public function getAvatarUrl()
    {
        // Check if photo_path exists and is accessible
        if ($this->photo_path) {
            $photoPath = storage_path('app/public/' . $this->photo_path);
            if (file_exists($photoPath)) {
                return asset('storage/' . $this->photo_path);
            }
        }
        
        // Fallback to Google avatar URL
        if ($this->avatar_url) {
            return $this->avatar_url;
        }
        
        // Default avatar
        return asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
    }
}
