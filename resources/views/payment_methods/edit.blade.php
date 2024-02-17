@extends('layouts.dashbord-layout')

@section('content')

    <div class="card text-bg-theme">
  
         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ !empty($title) ? $title : 'Payment Method' }}</h4>
            <div>
                <a href="{{ route('payment_methods.payment_method.index') }}" class="btn btn-primary" title="{{ trans('payment_methods.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('payment_methods.payment_method.create') }}" class="btn btn-secondary" title="{{ trans('payment_methods.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
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

            <form method="POST" class="needs-validation" novalidate action="{{ route('payment_methods.payment_method.update', $paymentMethod->id) }}" id="edit_payment_method_form" name="edit_payment_method_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('payment_methods.form', [
                                        'paymentMethod' => $paymentMethod,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('payment_methods.update') }}">
                </div>
            </form>

        </div>
    </div>

@endsection