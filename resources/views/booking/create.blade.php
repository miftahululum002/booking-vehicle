@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<div>
    <form id="form-booking" action="{{route('dashboard.bookings.store')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="employee_id">Pegawai</label>
                <select class="form-select rounded-0" name="employee_id" id="employee_id" required>
                    <option value="" selected disabled>Pilih</option>
                    @if(!empty($employees))
                    @foreach($employees as $e => $employee)
                    <option value="{{$employee->id}}">{{$employee->code}} - {{$employee->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="driver_id">Driver</label>
                <select class="form-select rounded-0" name="driver_id" id="driver_id" required>
                    <option value="" selected disabled>Pilih</option>
                    @if(!empty($drivers))
                    @foreach($drivers as $e => $driver)
                    <option value="{{$driver->id}}">{{$driver->code}} - {{$driver->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="date">Tanggal</label>
                <input class="form-control rounded-0" type="date" name="date" id="date" placeholder="Tanggal" value="{{date('Y-m-d')}}" required />
            </div>
            <div class="col-md-6 mb-3">
                <label for="vehicle_id">Kendaraan</label>
                <select class="form-select rounded-0" name="vehicle_id" id="vehicle_id" required>
                    <option value="" selected disabled>Pilih</option>
                    @if(!empty($vehicles))
                    @foreach($vehicles as $e => $vehicle)
                    <option value="{{$vehicle->id}}">{{$vehicle->code}} - {{$vehicle->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label for="necessary">Tujuan</label>
                <input class="form-control rounded-0" type="text" name="necessary" id="necessary" placeholder="Tujuan" required />
            </div>
            <div class="col-md-6 mb-3">
                <label for="user_id_1">Approver 1</label>
                <select class="form-select rounded-0" name="user_id[]" id="user_id_1" required>
                    <option value="" selected disabled>Pilih</option>
                    @if(!empty($approvers))
                    @foreach($approvers as $e => $approver)
                    <option value="{{$approver->id}}">{{$approver->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="user_id_2">Approver 2</label>
                <select class="form-select rounded-0" name="user_id[]" id="user_id_2" required>
                    <option value="" selected disabled>Pilih</option>
                    @if(!empty($approvers))
                    @foreach($approvers as $e => $approver)
                    <option value="{{$approver->id}}">{{$approver->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-sm rounded-0">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    $('#user_id_1').change(function() {
        let isi = $(this).val();
        $.ajax({
            type: 'ajax',
            method: 'get',
            url: `{{route('dashboard.bookings.approver')}}`,
            dataType: 'json',
            data: {
                user_id: isi
            },
            beforeSend: () => {},
            success: (response) => {
                let data = response.data;
                $('#user_id_2').find('option').remove();
                $('#user_id_2').append(`<option value="" selected disabled>Pilih</option>`);
                data.forEach((item, index) => {
                    $('#user_id_2').append(`<option value="${item.id}">${item.name}</option>`);
                });
            },
            error: (xhr) => {
                console.log(xhr);
            }
        });
    })

    $('#form-booking').submit(function(e) {
        e.preventDefault();
        let warning = confirm('Apakah Anda yakin?');
        if (!warning) {
            return;
        }
        $.ajax({
            type: 'ajax',
            method: $(this).attr('method'),
            url: $(this).attr('action'),
            dataType: 'json',
            data: $(this).serializeArray(),
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
    })
</script>
@endpush