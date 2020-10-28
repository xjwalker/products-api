<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App\Models
 * @property int id
 * @property string title
 * @property double price
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'price'];
}
