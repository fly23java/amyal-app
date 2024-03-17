@extends('layouts.app')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">Price Details</h4>
            <div>
                <a href="{{ route('price_details.price_detail.create') }}" class="btn btn-secondary" title="{{ trans('price_details.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($priceDetails) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('price_details.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>{{ trans('price_details.price_id') }}</th>
                            <th>{{ trans('price_details.vehicle_type_id') }}</th>
                            <th>{{ trans('price_details.goods_id') }}</th>
                            <th>{{ trans('price_details.loading_city_id') }}</th>
                            <th>{{ trans('price_details.dispersal_city_id') }}</th>
                            <th>{{ trans('price_details.price') }}</th>
                            <th>{{ trans('price_details.accepted_user_id') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($priceDetails as $priceDetail)
                        <tr>
                            <td class="align-middle">{{ optional($priceDetail->Price)->price_title }}</td>
                            <td class="align-middle">{{ optional($priceDetail->VehicleType)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($priceDetail->Good)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($priceDetail->City)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($priceDetail->City)->name_arabic }}</td>
                            <td class="align-middle">{{ $priceDetail->price }}</td>
                            <td class="align-middle">{{ optional($priceDetail->User)->name }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('price_details.price_detail.destroy', $priceDetail->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('price_details.price_detail.show', $priceDetail->id ) }}" class="btn btn-info" title="{{ trans('price_details.show') }}">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('price_details.price_detail.edit', $priceDetail->id ) }}" class="btn btn-primary" title="{{ trans('price_details.edit') }}">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('price_details.delete') }}" onclick="return confirm(&quot;{{ trans('price_details.confirm_delete') }}&quot;)">
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

            {!! $priceDetails->links('pagination') !!}
        </div>
        
        @endif
    
    </div>
@endsection