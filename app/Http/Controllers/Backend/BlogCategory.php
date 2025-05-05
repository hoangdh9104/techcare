<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BlogCategory extends Model
 {
     use HasFactory;
 
     public function blogs()
     {
         return $this->hasMany(Blog::class); 
     }
 }