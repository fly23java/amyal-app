@extends('layouts.dashbord-layout')

@section('content')
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
             

                        <!-- Statistics Card -->
                        <div class="col-xl-12 col-md-6 col-12">
                            <div class="card card-statistics">
                                <div class="card-header">
                                    <h4 class="card-title">
                                         
                                        {{ trans('statistics.statistics') }}
                                    </h4>
                                    <div class="d-flex align-items-center">
                                        <p class="card-text font-small-2 mr-25 mb-0">
                                        {{ trans('statistics.updated_1_month_age') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="card-body statistics-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <div class="media">
                                                <div class="avatar bg-light-primary mr-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="trending-up" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder mb-0">{{ $statusActiveWithShipments }}</h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                    {{ trans('statistics.sales') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <div class="media">
                                                <div class="avatar bg-light-info mr-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="user" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder mb-0">{{ $accountCount }}</h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                    {{ trans('statistics.customers') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                            <div class="media">
                                                <div class="avatar bg-light-danger mr-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="box" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder mb-0">{{ $vehicleCount }}</h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                    {{ trans('statistics.vehciles') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12">
                                            <div class="media">
                                                <div class="avatar bg-light-success mr-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder mb-0">{{ $statusFinishedWithShipments }}</h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                    {{ trans('statistics.revenue') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>

                   

                    <div class="row match-height">
                        <!-- Company Table Card -->
                        <div class="col-lg-12 col-12">
                            <div class="card card-company-table">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Company</th>
                                                    @foreach ($status as $status1) 
                                                    <th>{{ $status1->name_arabic }}</th>
                                                    @endforeach
                                                   
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($Accounts as $key => $Account) 
                                                <tr>
                                           
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            
                                                            <div>
                                                                <div class="font-weight-bolder">
                                                                    {{ $Account }}
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </td>
                                                 

                                                 
                                                    @foreach ($status as $status1) 
                                                    <td class="text-nowrap">
                                                        <div class="d-flex flex-column">
                                                            <span class="font-weight-bolder mb-25">
                                                            @php
                                                                $shipmentCount = App\Models\Shipment::where([
                                                                    ['account_id', '=', $key],
                                                                    ['status_id', '=', $status1->id],
                                                                ])->count();
                                                                echo  $shipmentCount;
                                                            @endphp

                                                            </span>
                                                            
                                                        </div>
                                                    </td>
                                                    @endforeach
                                                </tr>     
                                                @endforeach
                                          
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Company Table Card -->

                        

                    </div>
                </section>
                <!-- Dashboard Ecommerce ends -->

            </div>
@endsection
