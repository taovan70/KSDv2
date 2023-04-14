@extends(backpack_view('blank'))

@section('content')
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<div class="jumbotron">
    <h1 class="mb-4">{{ __('models.file_manager') }}</h1>
    <div id="fm" style="height: 600px;"></div>
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
</div>
@endsection
