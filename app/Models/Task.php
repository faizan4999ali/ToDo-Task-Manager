<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'user_id','priority','status','category_id','due_date','attachment'];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
