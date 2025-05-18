<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    use HasFactory;

    protected $table = 'card';
    protected $fillable = [
        'user_id',
        'module',
        'topic',
        'question',
        'answer',
        'category',
        'difficulty',
        'is_correct',
        'is_bookmarked'
    ];
    protected $casts = [
        'user_id' => 'integer',
        'is_correct' => 'boolean',
        'is_bookmarked' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
