@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<div>
    <form action="" method="post">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="employee_id">Pegawai</label>
                <select class="form-select rounded-0" name="employee_id" id="employee_id">
                    <option value="">Pilih</option>
                    @if(!empty($employees))
                    @foreach($employees as $e => $employee)
                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
@endpush