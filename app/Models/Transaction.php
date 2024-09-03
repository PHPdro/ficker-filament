<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Balance;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted() 
    {
        static::creating(function($model) {
            $model->user_id = auth()->id();

            if ($model->type == 'expense') {
                $model->value = $model->value * -1;
            }

            $month = substr($model->date, 5, 2);

            $balance = Balance::where('user_id', auth()->id())
                ->whereMonth('date', $month)
                ->first();
            
            if (!is_null($balance)) {
                $balance->update([
                    'amount' => $balance->amount += $model->value,
                ]);
            } else {
                Balance::create([
                    'user_id' => auth()->id(),
                    'amount' => $model->value,
                    'date' => $model->date,
                ]);
            }
        });

        static::updating(function($model) {
            if ($model->type == 'expense') {
                $model->value = $model->value * -1;
            }

            $month = substr($model->date, 5, 2);

            $balance = Balance::where('user_id', auth()->id())
                ->whereMonth('date', $month)
                ->first();
            
            if (!is_null($balance)) {
                $balance->update([
                    'amount' => $balance->amount += $model->value,
                ]);
            } else {
                Balance::create([
                    'user_id' => auth()->id(),
                    'amount' => $model->value,
                    'date' => $model->date,
                ]);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
