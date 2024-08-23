<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Database\Model\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected ?string $table = 'users';

    protected array $fillable = [
        'id',
        'uuid',
        'name',
        'email',
        'password',
        'type',
    ];

    protected array $hidden = [
        'password',
        'remember_token',
    ];

    protected array $casts = [
        'uuid' => 'string',
    ];

    protected array $dates = ['deleted_at'];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
