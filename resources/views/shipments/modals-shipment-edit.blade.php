<div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0"  method="POST" action="{{ route('shipments.shipment.getAddVehcileToShipment') }}" id="create_shipment_details_form">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                             {{ trans('shipments.edit') }}
                                        </h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                    <input  name="shipment_delivery_detail_id" type="hidden" id="shipment_delivery_detail_id" >
                                    <input  name="supervisor_user_id" type="hidden"  value=" {{ Auth::user()->id }} " >
                                    <input  name="shipment_id" type="hidden" id="shipment_id" >
                                    {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="form-label" for="vehicle_id">{{ trans('shipment_delivery_details.vehicle_id') }}</label>
                                            <select class="form-select form-control{? ' is-invalid' : '' }}" id="vehicle_id" name="vehicle_id" required="true" placeholder="">
                                                  <option >  {{ trans('shipment_delivery_details.vehicle_id__placeholder') }} </option> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-fullname">{{ trans('shipments.carrier_price') }}</label>
                                            <input class="form-control" name="carrier_price" type="number" id="carrier_price"  placeholder="{{ trans('shipments.carrier_price__placeholder') }}"  value="@if (!empty($shipment->carrier_price)){{$shipment->carrier_price }}@endif" >
                                        </div>
                                    </div>
                                   
                                   
                                    <div class="modal-body flex-grow-1" >
                                        <button type="submit" class="btn btn-primary mr-1 data-submit">{{ trans('shipments.update') }}</button>
                                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('main.reset') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>