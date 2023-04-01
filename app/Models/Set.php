<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sets';

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'name',
    ];
}
