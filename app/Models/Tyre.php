<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tyre extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function size()
    {
        return $this->belongsTo(TyreSize::class, 'size_id')->withDefault([
            'size' => 'n/a'
        ]);
    }

    public function brand()
    {
        return $this->belongsTo(TyreBrand::class, 'brand_id')->withDefault([
            'brand' => 'n/a'
        ]);
    }

    public function pattern()
    {
        return $this->belongsTo(Pattern::class, 'pattern_id')->withDefault([
            'pattern' => 'n/a'
        ]);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault([
            'supplier' => 'n/a'
        ]);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault([
            'name' => 'n/a'
        ]);
    }

    public function getLastTransaction()
    {
        if ($this->transactions()) {
            return $this->transactions()->orderBy('id', 'desc')->first();
        } else {
            return null;
        }
    }

    public function lastTransaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany();
    }
}
