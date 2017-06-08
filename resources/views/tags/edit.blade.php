@extends('main')

@section('title', "| Edit Tag")

@section('content')

	{{ Form::model($tag, ['route' => ['tags.update', $tag->id], 'method' => "PUT"]) }}


		{{ Form::label('name', 'Title:') }}
		{{ Form::text('name', null, ['class' => 'form-control']) }}

		{{ Form::submit('Update', ['class' => 'btn btn-success btn-h1-primary'])}}


	{{ Form::close() }}

@endsection