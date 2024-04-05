<div class="modal modal-slide-in statuses-update fade" id="modals-statuses-update">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0"  method="POST" action="{{ route('shipments.shipment.statusUpdate') }}" id="create_shipment_details_form">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                             {{ trans('statuses.edit') }}
                                        </h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                               
                                    <input  name="shipment_status_id" type="hidden" id="shipment_status_id" >
                                    {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="form-label" for="status_id">{{ trans('statuses.model_plural') }}</label>
                                            <select class="form-select form-control" id="status_id" name="status_id" required="true" placeholder="">
                                                  
                                            </select>
                                        </div>
                                    </div>
                                   
                                   
                                   
                                    <div class="modal-body flex-grow-1" >
                                        <button type="submit" class="btn btn-primary mr-1 data-submit">{{ trans('shipments.update') }}</button>
                                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('main.reset') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>