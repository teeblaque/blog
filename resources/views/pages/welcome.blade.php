@extends('main')

@section('title', '| HomePage')

@section('content')
          <div class="row">
               <div class="col-md-12">
                <div class="jumbotron">
                  <h1>Welcome {{ $word }} blog!</h1>
                  <p class="lead">Thanks for visiting my site. This is my test website with laravel. Please read my post</p>
                  <p><a class="btn btn-primary btn-lg" href="#" role="button">Popular post</a></p>
              </div>
            </div>
          </div>   <!--end of .row header-->

          <div class="row">
            <div class="col-md-8" >

                  @foreach($posts as $post)

                 <div class="post">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ substr($post->body, 0, 250)}}{{ strlen($post->body) > 250 ? "..." : " " }}</p>
                    <a href="{{ url('blog/'.$post->slug) }}" class="btn btn-primary">Read More</a>
                 </div>
                 <hr>

                  @endforeach

            </div>
            <div class="col-md-3 col-md-offset-1" >
              <h2>Recent Post</h2>
            </div>
          </div>

 @endsection
