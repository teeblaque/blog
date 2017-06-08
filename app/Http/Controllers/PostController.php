<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use Session;
use Image;
use Storage;
use App\Category;

class PostController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){

        //create a variable and store all the blog post in it
        $posts = Post::orderBy('id', 'desc')->paginate(5);
        //return a view and pass in the above variable
        return view('posts.index')->withPosts($posts);

    }

    public function create(){

        $categories = Category::all();
        $tags = Tag::all();
    	return view('posts.create')->withCategories($categories)->withTags($tags);
    }

    public function store(Request $request){

        // dd($request);
        //validate the data
        $this->validate($request, array(
                'title' => 'required|max:255',
                'slug' => 'required|alpha_dash|max:255|min:5|unique:posts,slug',
                'category_id' => 'required|integer',
                'body' => 'required',
                'featured_image' => 'sometimes|image'
            ));

        //store into database
        $post = new Post;

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->body = $request->body;

        //save our image
        if ($request->hasFile('featured_image')) {
          $image = $request->file('featured_image');
          $fileName = time().'.'.$image->getClientOriginalExtension();
          $location = public_path('images/'. $fileName);
          Image::make($image)->resize(800, 400)->save($location);

          $post->image = $fileName;
        }


        $post->save();

        $post->tags()->sync($request->tags, false);

        Session::flash('success', 'The blog post was successfully saved!!!');

        //redirect to another page

        return redirect()->route('posts.index', $post->id);
    }

    public function show($id){

        $post =Post::find($id);
        return view('posts.show')->withPost($post);
    }

    public function edit($id){

        //find the post in the database
        $post = Post::find($id);

        $categories = Category::all();
        $cats = [];
        foreach ($categories as $category) {
            $cats[$category->id] = $category->name;
        }
        $tags = Tag::all();
        $tags2 = [];
        foreach ($tags as $tag) {
            $tags2[$tag->id] = $tag->name;
        }
        //return the view and pass the variable
        return view('posts.edit')->withPost($post)->withCategories($cats)->withTags($tags2);

    }

    public function update(Request $request, $id){

        //validate the data before using
        $post = Post::find($id);
        // if ($request->input('slug') == $post->slug) {
        //     $this->validate($request, array(
        //         'title' => 'required|max:255',
        //         'body' => 'required'
        //     ));
        // }
        // else {

            $this->validate($request, array(
                'title' => 'required|max:255',
                'slug' => "required|alpha_dash|max:255|min:5|unique:posts,slug, $id",
                'category_id' => 'required|integer',
                'body' => 'required',
                'featured_image' => 'image'
            ));
        // }



        //save the data to the database
        $post = Post::find($id);

        $post->title = $request->input('title');
        $post->slug = $request->input('slug');
        $post->category_id = $request->input('category_id');
        $post->body = $request->input('body');

        if ($request->hasFile('featured_image')) {
          //add new photo
          $image = $request->file('featured_image');
          $fileName = time().'.'.$image->getClientOriginalExtension();
          $location = public_path('images/'. $fileName);
          Image::make($image)->resize(800, 400)->save($location);
          $oldFileName = $post->image;

          $post->image = $fileName;

          //update the db
          $post->image = $fileName;

          //delete the old photo
          //you can delete with this method without editing config/fileSystems
        //  File::delete(public_path('images/'.$oldFileName));

        //  or by editing config/fileSystems
        Storage::delete($oldFileName);
        }

        $post->save();

        if (isset($request->tags)) {
            $post->tags()->sync($request->tags, true);
        }
        else{
            $post->tags()->sync(array());
        }


        //set flash data with sucess message
        Session::flash('success', 'The post was successfully updated.');

        //redirect with flash data to posts.show
        return redirect()->route('posts.index', $post->id);

    }

    public function destroy($id){

        //find the post in the to delete in the database
        $post = Post::find($id);
        $post->tags()->detach();
        Storage::delete($post->image);
        //return the view and pass the variable
        $post->delete();

        Session::flash('success', 'The post was successfully deleted');

        return redirect()->route('posts.index');

    }
}
