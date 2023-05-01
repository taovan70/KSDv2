@extends(backpack_view('blank'))

@section('before_styles')
    @vite('resources/js/make-article.js')
    @inertiaHead
@endsection

@section('content')
    @inertia
@endsection


