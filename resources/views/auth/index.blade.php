@extends('layouts.auth')
@push('styles')
@endpush
@section('content')
<form action="{{route('authenticate')}}" method="post">
    @csrf
    <h1>{{$app_name}}</h1>
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
    <div class="form-floating">
        <input type="email" class="form-control rounded-0" id="email" name="email" placeholder="Email" required>
        <label for="email">Email</label>
    </div>
    <div class="form-floating">
        <input type="password" class="form-control rounded-0" id="password" name="password" placeholder="Password" required>
        <label for="password">Password</label>
    </div>
    <!-- <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Remember me
                </label>
            </div> -->
    <button type="submit" class="btn btn-primary w-100 py-2 rounded-0">Login</button>
    <!-- <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p> -->
</form>
@endsection
@push('scripts')
@endpush