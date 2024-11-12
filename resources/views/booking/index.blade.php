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
<script>
    function setDone(bookingId) {
        let warning = confirm('Apakah Anda yakin?');
        if (!warning) {
            return;
        }
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: `{{route('dashboard.bookings.set-done')}}`,
            dataType: 'json',
            data: {
                _token: token,
                booking_id: bookingId,
            },
            beforeSend: () => {},
            success: (response) => {
                alert(response.message);
                location.reload();
            },
            error: (xhr) => {
                let response = xhr.responseJSON;
                alert(response.message);
            }
        });
    }
</script>
@endpush