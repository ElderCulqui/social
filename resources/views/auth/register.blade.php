@extends('layouts/app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
        
            @include('partials.validation-errors')

            <div class="card border-0 px-4 py-2">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Username:</label>
                            <input class="form-control border-0" type="text" name="name" placeholder="Tu nombre de usuario...">
                        </div>
                        <div class="form-group">
                            <label for="">Nombre:</label>
                            <input class="form-control border-0" type="text" name="first_name" placeholder="Tu primer nombre...">
                        </div>
                        <div class="form-group">
                            <label for="">Apellido:</label>
                            <input class="form-control border-0" type="text" name="last_name" placeholder="Tu primer apelldio...">
                        </div>
                        <div class="form-group">
                            <label for="">Email:</label>
                            <input class="form-control border-0" type="email" name="email" placeholder="Tu email...">
                        </div>
                        <div class="form-group">
                            <label for="">Contraseña:</label>
                            <input class="form-control border-0" type="password" name="password" placeholder="Tu contraseña...">
                        </div>
                        <div class="form-group">
                            <label for="">Repite la Contraseña:</label>
                            <input class="form-control border-0" type="password" name="password_confirmation" placeholder="Tu confirmacion de contraseña...">
                        </div>
        
                        <button class="btn btn-primary btn-block" id="register-btn" dusk="register-btn">Login</button>
    
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
@endsection