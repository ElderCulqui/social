@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 mb-3 bg-light shadow-sm">
                <status-form></status-form>
            </div>
            <status-list></status-list>
        </div>
    </div>

</div>
@endsection