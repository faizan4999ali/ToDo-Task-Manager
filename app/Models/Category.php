<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Table name
    protected $table = 'category';
    // Fillable fields
    protected $fillable = ['name', 'user_id'];
    // Relationship with Task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    
    }
}
