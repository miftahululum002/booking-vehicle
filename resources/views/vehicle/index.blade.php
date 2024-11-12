@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<div class="table-responsive small">
    {{ $dataTable->table() }}
</div>
@endsection
@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush