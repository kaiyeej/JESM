<div class="modal fade" id="modalPassword" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="margin-top: 50px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel"><span class='fa fa-pen'></span> Update Password and Profile</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="hidden_id" name="input[product_id]">
                <div class="form-group row">
                    <input type="hidden" class="form-control input-item" name="input[user_id]" id="user_id" value="<?= $_SESSION["user_id"] ?>">
                    <div class="col-md-12">

                        <label><strong>New Password</strong></label>
                        <div>

                            <input type="password" class="form-control input-item" name="input[new_password1]" id="new_password1" placeholder="New Password" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><strong>Confirm Password</strong></label>
                        <div>
                            <input type="password" class="form-control input-item" name="input[new_password2]" id="new_password2" placeholder="Confirm Password" required>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" onclick="updatePasswordProfile()" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Submit</span>
                </button>
            </div>
        </div>
    </div>
</div>