<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = ['order_id', 'amount', 'method', 'reference', 'paid_at', 'notes'];

    public const METHOD_LABELS = [
        'cash'     => '💵 Espèces',
        'virement' => '🏦 Virement',
        'revolut'  => '💳 Revolut',
        'stripe'   => '⚡ Stripe',
        'cheque'   => '📝 Chèque',
    ];

    protected function casts(): array
    {
        return [
            'amount'  => 'decimal:2',
            'paid_at' => 'date',
        ];
    }

    public function getMethodLabelAttribute(): string
    {
        return self::METHOD_LABELS[$this->method] ?? $this->method;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
