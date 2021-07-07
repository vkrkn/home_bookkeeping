<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'sum', 'user_id', 'date', 'comment'];
    protected $hidden = ['category_id'];
    protected $appends = ['category'];

    const AMOUNT_MULTIPLE = 100;


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryAttribute()
    {
        return Category::find($this->category_id);
    }
}
