@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.content-page')
    <div id="vue"> vue </div>
    <div id="react">React</div>
  @endwhile
@endsection
