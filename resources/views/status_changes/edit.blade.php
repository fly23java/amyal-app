@extends('layouts.app')

@section('content')

    <div class="card text-bg-theme">
  
         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ !empty($title) ? $title : 'Status Change' }}</h4>
            <div>
                <a href="{{ route('status_changes.status_change.index') }}" class="btn btn-primary" title="{{ trans('status_changes.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('status_changes.status_change.create') }}" class="btn btn-secondary" title="{{ trans('status_changes.create') }}">
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

            <form method="POST" class="needs-validation" novalidate action="{{ route('status_changes.status_change.update', $statusChange->id) }}" id="edit_status_change_form" name="edit_status_change_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('status_changes.form', [
                                        'statusChange' => $statusChange,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('status_changes.update') }}">
                </div>
            </form>

        </div>
    </div>

@endsection