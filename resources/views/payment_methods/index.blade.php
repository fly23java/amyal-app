@extends('layouts.dashbord-layout')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('main.payment_methods') }}</h4>
            <div>
                <a href="{{ route('payment_methods.payment_method.create') }}" class="btn btn-secondary" title="{{ trans('payment_methods.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($paymentMethods) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('payment_methods.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>{{ trans('payment_methods.name_arabic') }}</th>
                            <th>{{ trans('payment_methods.name_english') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($paymentMethods as $paymentMethod)
                        <tr>
                            <td class="align-middle">{{ $paymentMethod->name_arabic }}</td>
                            <td class="align-middle">{{ $paymentMethod->name_english }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('payment_methods.payment_method.destroy', $paymentMethod->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('payment_methods.payment_method.show', $paymentMethod->id ) }}" class="btn btn-info" title="{{ trans('payment_methods.show') }}">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('payment_methods.payment_method.edit', $paymentMethod->id ) }}" class="btn btn-primary" title="{{ trans('payment_methods.edit') }}">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('payment_methods.delete') }}" onclick="return confirm(&quot;{{ trans('payment_methods.confirm_delete') }}&quot;)">
                                            <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

           
        </div>
        
        @endif
    
    </div>
@endsection