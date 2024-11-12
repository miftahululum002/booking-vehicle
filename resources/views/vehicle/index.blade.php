@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<h2>{{$title}}</h2>
<div class="table-responsive small">
    {{ $dataTable->table() }}
</div>
@endsection
@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush