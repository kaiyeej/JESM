<form method='POST' id='frm_submit' class="formulation">
    <div class="modal fade" id="modalEntry" aria-labelledby="myModalLabel">
        <div class="modal-dialog" style="margin-top: 50px;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel"><span class='fa fa-pen'></span> Add Entry</h4>
                </div>
                <div class="modal-body" style="padding: 15px;">
                    <input type="hidden" id="hidden_id" name="input[formulation_id]">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><strong>Product</strong></label>
                                <div>
                                    <select class="form-control form-control-sm input-item select2" name="input[product_id]" id="product_id" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><strong>Remarks</strong></label>
                                <div>
                                    <textarea class="form-control form-control-sm input-item" name="input[remarks]" id="remarks" autocomplete="off" placeholder="Remarks" maxlength="255"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Submit</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>


<div class="modal fade bd-example-modal-lg" id="modalEntry2" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="margin-top: 50px;" role="document">
        <div class="modal-content">
            <div class="modal-header" style="display:block;">
                <div class="row" style="font-size: small;">
                    <div class="col-sm-12">
                        <div><b>Product:</b> <span id="product_label" class="label-item"></span></div>
                        <div><b>Remarks:</b> <span id="remarks_label" class="label-item"></span></div>
                    </div>
                </div>
            </div>
            <div class="modal-body" style="padding: 15px;">
                <div class="row">
                    <div class="col-sm-12" id="col-item">
                        <form method='POST' id='frm_submit_2'>
                            <input type="hidden" id="hidden_id_2" name="input[formulation_id]">

                            <div class="form-group row">
                                <div class="col">
                                    <label><strong>Product</strong></label>
                                    <div>
                                        <select class="form-control form-control-sm select2" name="input[product_id]" id="product_id_2" required></select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label><strong>Qty</strong></label>
                                    <div>
                                        <input type="number" class="form-control form-control-sm input-item" autocomplete="off" name="input[qty]" id="qty" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary btn-sm"  style="float:right;font-size: small;" id="btn_submit_2"><i class="mdi mdi-plus"></i> Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                        <hr>
                    <div class="col-sm-12" id="col-list">
                        <div class="btn-group">
                            <button id="menu-delete-selected-items" class="btn btn-danger btn-sm" href="#" style="font-size: small;"><i class='mdi mdi-trash-can-outline'></i>Delete Selected</button>
                        
                            <button id="menu-finish-transaction" style="display:none;" class="btn btn-success btn-sm" href="#" style="font-size: small;"><i class='mdi mdi-check-outline'></i>Finish Transaction</button>
                        </div>
                        <div class="table-responsive" style="margin-top: 22px">
                            <table class="table table-bordered" id="dt_entries_2" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th><input type='checkbox' onchange="checkAll(this, 'dt_id_2')"></th>
                                        <th>Description</th>
                                        <th>Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button id="menu-edit-transaction" class="btn btn-info btn-sm" href="#" style="font-size: small;"><i class='mdi mdi-lead-pencil'></i> Edit Transaction</button>
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: small;">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>