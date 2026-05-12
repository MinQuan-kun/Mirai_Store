<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_ids',
        'description',
        'price',
        'image',
        'download_link',
        'publisher',
        'platforms',
        'languages',
        'sold_count',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'sold_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, null, 'category_ids', '_id');
    }

    public function getPrimaryCategoryAttribute()
    {
        return $this->categories->first();
    }

    public function getPriceFormatAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' đ';
    }

    public function getDirectDownloadLinkAttribute()
    {
        // (Giữ nguyên code xử lý link drive của bạn)
        $url = $this->download_link;
        if (strpos($url, 'drive.google.com') !== false) {
            preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches);
            if (isset($matches[1])) {
                return "https://drive.google.com/uc?export=download&id=" . $matches[1];
            }
        }
        return $url;
    }
}
