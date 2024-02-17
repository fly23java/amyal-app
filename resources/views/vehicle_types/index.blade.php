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
            <h4 class="m-0">{{ trans('main.vehicle_types') }}</h4>
            <div>
                <a href="{{ route('vehicle_types.vehicle_type.create') }}" class="btn btn-secondary" title="{{ trans('vehicle_types.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($vehicleTypes) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('vehicle_types.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped  zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('vehicle_types.name_arabic') }}</th>
                            <th>{{ trans('vehicle_types.name_english') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($vehicleTypes as $vehicleType)
                        <tr>
                            <td class="align-middle">{{ $vehicleType->name_arabic }}</td>
                            <td class="align-middle">{{ $vehicleType->name_english }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('vehicle_types.vehicle_type.destroy', $vehicleType->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('vehicle_types.vehicle_type.show', $vehicleType->id ) }}" class="btn btn-info" title="{{ trans('vehicle_types.show') }}">
                                            <i data-feather='eye'></i>
                                        </a>
                                        <a href="{{ route('vehicle_types.vehicle_type.edit', $vehicleType->id ) }}" class="btn btn-primary" title="{{ trans('vehicle_types.edit') }}">
                                            <i data-feather='edit'></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('vehicle_types.delete') }}" onclick="return confirm(&quot;{{ trans('vehicle_types.confirm_delete') }}&quot;)">
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