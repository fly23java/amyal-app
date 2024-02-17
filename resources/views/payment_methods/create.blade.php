@extends('layouts.dashbord-layout')

@section('content')

    <div class="card text-bg-theme">

         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('payment_methods.create') }}</h4>
            <div>
                <a href="{{ route('payment_methods.payment_method.index') }}" class="btn btn-primary" title="{{ trans('payment_methods.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        

        <div class="card-body">
        
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" class="needs-validation" novalidate action="{{ route('payment_methods.payment_method.store') }}" accept-charset="UTF-8" id="create_payment_method_form" name="create_payment_method_form" >
            {{ csrf_field() }}
            @include ('payment_methods.form', [
                                        'paymentMethod' => null,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('payment_methods.add') }}">
                </div>

            </form>

        </div>
    </div>

@endsection


