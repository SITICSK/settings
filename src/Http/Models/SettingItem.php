<?php

namespace Sitic\Settings\Http\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sitic\Settings\Http\Traits\Uuids;

class SettingItem extends Model
{
    use Uuids, SoftDeletes, HasFactory;

    protected $fillable = ['title', 'name', 'type', 'value', 'setting_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'value' => 'array'
    ];

    public function setting() {
        return $this->belongsTo(Setting::class, 'setting_id');
    }
}
