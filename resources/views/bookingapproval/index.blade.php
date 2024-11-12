@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<div>
    <div class="table-responsive small">
        {{ $dataTable->table() }}
    </div>
</div>
@endsection
@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
    function approve(bookingId, approveId) {
        console.log(bookingId);
        let warning = confirm('Apakah Anda yakin untuk melakukan persetujuan?');
        if (!warning) {
            return;
        }
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: `{{route('dashboard.approvals.approve')}}`,
            dataType: 'json',
            data: {
                _token: token,
                booking_id: bookingId,
                approve_id: approveId
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