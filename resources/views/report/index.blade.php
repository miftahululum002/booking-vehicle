@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<div class="mb-3">
    <form action="" id="data_filter">
        <div class="input-group rounded-0">
            <input type="date" id="start_date" name="start_date" value="{{date('Y-m-d')}}" class="form-control form-control-sm rounded-0" placeholder="Tanggal awal" required>
            <input type="date" id="end_date" name="end_date" value="{{date('Y-m-d')}}" class="form-control form-control-sm rounded-0" placeholder="Tanggal akhir" required>
            <button type="button" class="btn btn-primary btn-sm rounded-0" id="btn-filter">Filter</button>
            <button type="button" class="btn btn-info btn-sm rounded-0" id="btn-export">Expor</button>
        </div>
    </form>
</div>
<div class="mt-3">
    <div class="table-responsive small">
        {{ $dataTable->table() }}
    </div>
</div>
@endsection
@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
    $('#btn-filter').click(function() {
        reloadDatatable();
    })

    $('#btn-export').click(function() {
        let startDate = $('#start_date').val();
        let endDate = $('#end_date').val();
        location.href = `{{route('dashboard.reports.export')}}?start=${startDate}&end=${endDate}`;
    })
</script>
@endpush