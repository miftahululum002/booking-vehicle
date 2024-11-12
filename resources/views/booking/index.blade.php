@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<div>
    <div class="mb-3">
        <a href="{{route('dashboard.bookings.create')}}" class="btn btn-primary btn-sm rounded-0">Tambah</a>
    </div>
    <div class="table-responsive small">
        {{ $dataTable->table() }}
    </div>
</div>
@endsection
@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush