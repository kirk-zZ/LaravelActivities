@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <a class="btn button btn-info" href="/posts">Back</a> <br><br>

            <div class="card">
                <div class="card-body">
                    Title : {{ $post->title }} <br>
                    Description : {{ $post->description }} <br>
                    Created At : {{ $post->created_at }} <br>
                    Image: 
                    @if ($post->img)
                    <img src="{{ asset('/storage/public/images/'.$post->img) }}"> <br>
                    @else
                        No image available
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection