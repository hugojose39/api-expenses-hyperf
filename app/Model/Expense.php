<?php

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

class Expense extends Model
{
    use SoftDeletes;

    protected ?string $table = 'expenses';

    protected array $fillable = [
        'id',
        'card_id',
        'amount',
        'description',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
