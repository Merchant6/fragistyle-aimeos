<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditedProduct extends Model
{
    use HasFactory;

    protected $table = 'edited_products';
    protected $fillable = [
        'user_id',
        'ref_id',
        'file_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
