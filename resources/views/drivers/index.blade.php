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
            <h4 class="m-0">{{ trans('main.drivers') }}</h4>
            <div>
                <a href="{{ route('drivers.driver.create') }}" class="btn btn-secondary" title="{{ trans('drivers.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($drivers) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('drivers.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped  zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('drivers.name_arabic') }}</th>
                            <th>{{ trans('drivers.name_english') }}</th>
                            <th>{{ trans('drivers.phone') }}</th>
                            <th>{{ trans('drivers.identity_number') }}</th>
                            <th>{{ trans('drivers.date_of_birth_gregorian') }}</th>
                            <th>{{ trans('drivers.account_id') }}</th>
                            <th>{{ trans('drivers.vehicle_id') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($drivers as $driver)
                        <tr>
                            <td class="align-middle">{{ $driver->name_arabic }}</td>
                            <td class="align-middle">{{ $driver->name_english }}</td>
                            <td class="align-middle">{{ $driver->phone }}</td>
                            <td class="align-middle">{{ $driver->identity_number }}</td>
                             <td class="align-middle">{{ $driver->date_of_birth_gregorian }}</td>
                            <td class="align-middle">{{ optional($driver->Account)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($driver->Vehicle)->owner_name }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('drivers.driver.destroy', $driver->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('drivers.driver.show', $driver->id ) }}" class="btn btn-info" title="{{ trans('drivers.show') }}">
                                            <i data-feather='eye'></i>
                                        </a>
                                        <a href="{{ route('drivers.driver.edit', $driver->id ) }}" class="btn btn-primary" title="{{ trans('drivers.edit') }}">
                                            <i data-feather='edit'></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('drivers.delete') }}" onclick="return confirm(&quot;{{ trans('drivers.confirm_delete') }}&quot;)">
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