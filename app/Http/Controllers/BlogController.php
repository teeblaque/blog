<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class BlogController extends Controller
{
    public function getSingle($slug){

    	//fetch from database base on slug
    	$post = Post::where('slug', '=', $slug)->first();
    	//return the view and pass the variable
    	return view('blog.single')->withPost($post);
    }
}
