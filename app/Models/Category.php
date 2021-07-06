<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];
    protected $appends = ['type_name'];
    public $timestamps = false;

    const CATEGORY_TYPES = [
        ['value' => 'Доход', 'id' => '0'],
        ['value' => 'Расход', 'id' => '1']
    ];
    const CREDIT = '0';
    const DEBIT = '1';

    const DEFAULT_CATEGORY = [
        ['name' => 'Заработная плата',  'type' => '0'],
        ['name' => 'Иные доходы',       'type' => '0'],
        ['name' => 'Продукты питания',  'type' => '1'],
        ['name' => 'Транспорт',         'type' => '1'],
        ['name' => 'Мобильная связь',   'type' => '1'],
        ['name' => 'Интернет',          'type' => '1'],
        ['name' => 'Развлечения',       'type' => '1'],
        ['name' => 'Другое',            'type' => '1'],
    ];

    static public function getDefaultCategoryWithUserId($user_id): array
    {
        $result = [];
        foreach (self::DEFAULT_CATEGORY as $category) {
            array_push(
                $result,
                ['name' => $category['name'], 'type' => $category['type'], 'user_id' => $user_id]
            );
        }
        return $result;
    }

    public function getTypeNameAttribute(): string
    {
        return $this->type === '0' ? 'Доход' : 'Расход';
    }

    public function operations(): hasMany
    {
        return $this->hasMany(Operation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
