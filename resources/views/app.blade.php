@extends('layouts.app')

@section('content')
<div id="app" class="container">
  @if (Auth::user()->role == 1)
  <Admin />
  @else
  @if (Auth::user()->role == 2)
  <Leader />
  @else
  <Handyman />
  @endif
  @endif
</div>
@endsection