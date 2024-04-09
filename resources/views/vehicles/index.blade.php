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
            <h4 class="m-0">{{ trans('main.vehicles') }}</h4>
            <div>
                <a href="{{ route('vehicles.vehicle.create') }}" class="btn btn-secondary" title="{{ trans('vehicles.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($vehicles) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('vehicles.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped  zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('vehicles.owner_name') }}</th>
                            <th>{{ trans('vehicles.sequence_number') }}</th>
                            <th>{{ trans('vehicles.plate') }}</th>
                           
                            <th>{{ trans('vehicles.vehicle_type_id') }}</th>
                            <!-- Start deriver -->
                            <th>{{ trans('drivers.driver_name_arabic') }}</th>
                            <th>{{ trans('drivers.phone') }}</th>
                            <th>{{ trans('drivers.identity_number') }}</th>
                            <!-- End deriver -->
                            <th>{{ trans('vehicles.account_id') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($vehicles as $vehicle)
                        <tr>
                            <td class="align-middle">{{ $vehicle->owner_name }}</td>
                            <td class="align-middle">{{ $vehicle->sequence_number }}</td>
                            <td class="align-middle">
                                    {{ $vehicle->right_letter }}
                                    {{ $vehicle->middle_letter }}
                                    {{ $vehicle->left_letter }}
                                    {{ $vehicle->plate }}
                            </td>
                            
                          
                            <td class="align-middle">{{$vehicle->Type->name_arabic }}</td>
                             <!-- Start deriver -->
                            <td class="align-middle">{{ optional($vehicle->driver)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($vehicle->driver)->phone }}</td>
                            <td class="align-middle">{{ optional($vehicle->driver)->identity_number }}</td>
                            <!-- End deriver -->
                            <td class="align-middle">{{ optional($vehicle->Account)->name_arabic }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('vehicles.vehicle.destroy', $vehicle->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('vehicles.vehicle.show', $vehicle->id ) }}" class="btn btn-info" title="{{ trans('vehicles.show') }}">
                                            <i data-feather='eye'></i>
                                        </a>
                                        <a href="{{ route('vehicles.vehicle.edit', $vehicle->id ) }}" class="btn btn-primary" title="{{ trans('vehicles.edit') }}">
                                            <i data-feather='edit'></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('vehicles.delete') }}" onclick="return confirm(&quot;{{ trans('vehicles.confirm_delete') }}&quot;)">
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