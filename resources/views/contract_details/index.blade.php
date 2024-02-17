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
            <h4 class="m-0">{{ trans('main.contract_details') }}</h4>
            <div>
                <a href="{{ route('contract_details.contract_detail.create') }}" class="btn btn-secondary" title="{{ trans('contract_details.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($contractDetails) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('contract_details.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>{{ trans('contract_details.contract_id') }}</th>
                            <th>{{ trans('contract_details.vehicle_type_id') }}</th>
                            <th>{{ trans('contract_details.goods_id') }}</th>
                            <th>{{ trans('contract_details.loading_city_id') }}</th>
                            <th>{{ trans('contract_details.dispersal_city_id') }}</th>
                            <th>{{ trans('contract_details.price') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($contractDetails as $contractDetail)
                        <tr>
                            <td class="align-middle">{{ optional($contractDetail->Contract)->description }}</td>
                            <td class="align-middle">{{ optional($contractDetail->VehicleType)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($contractDetail->Good)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($contractDetail->City)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($contractDetail->City)->name_arabic }}</td>
                            <td class="align-middle">{{ $contractDetail->price }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('contract_details.contract_detail.destroy', $contractDetail->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('contract_details.contract_detail.show', $contractDetail->id ) }}" class="btn btn-info" title="{{ trans('contract_details.show') }}">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('contract_details.contract_detail.edit', $contractDetail->id ) }}" class="btn btn-primary" title="{{ trans('contract_details.edit') }}">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('contract_details.delete') }}" onclick="return confirm(&quot;{{ trans('contract_details.confirm_delete') }}&quot;)">
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