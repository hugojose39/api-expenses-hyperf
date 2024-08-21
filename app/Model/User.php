<?php

namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\Database\Model\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected ?string $table = 'users';

    protected array $fillable = [
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
}

