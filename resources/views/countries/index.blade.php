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
            <h4 class="m-0">Countries</h4>
            <div>
                <a href="{{ route('countries.country.create') }}" class="btn btn-secondary" title="{{ trans('countries.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($countries) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('countries.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped  zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('countries.name_arabic') }}</th>
                            <th>{{ trans('countries.name_english') }}</th>
                            <th>{{ trans('countries.alpha2_code') }}</th>
                            <th>{{ trans('countries.alpha3_code') }}</th>
                            <th>{{ trans('countries.phone_code') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($countries as $country)
                        <tr>
                            <td class="align-middle">{{ $country->name_arabic }}</td>
                            <td class="align-middle">{{ $country->name_english }}</td>
                            <td class="align-middle">{{ $country->alpha2_code }}</td>
                            <td class="align-middle">{{ $country->alpha3_code }}</td>
                            <td class="align-middle">{{ $country->phone_code }}</td>

                            <td class="text-end">

                                <form method="POST" action="{{ route('countries.country.destroy', $country->id) }}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('countries.country.show', $country->id ) }}" class="btn btn-info" title="{{ trans('countries.show') }}">
                                            <i data-feather='eye'></i>
                                        </a>
                                        <a href="{{ route('countries.country.edit', $country->id ) }}" class="btn btn-primary" title="{{ trans('countries.edit') }}">
                                            <i data-feather='edit'></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('countries.delete') }}" onclick="return confirm(&quot;{{ trans('countries.confirm_delete') }}&quot;)">
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