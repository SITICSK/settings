<?php

namespace Sitic\Settings\Http\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sitic\Settings\Http\Traits\Uuids;

class Setting extends Model
{
    use Uuids, SoftDeletes, HasFactory, Sluggable;

    protected $fillable = ['setting_id', 'title', 'description'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function settingItems() {
        return $this->hasMany(SettingItem::class, 'setting_id');
    }
}
