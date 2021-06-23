@extends('layouts.app')

@section('js-data')
    <script> window.Laravel.js_data = <?php echo json_encode($data) ?> </script>
@endsection

@section('content')
    <main-products></main-products>
@endsection