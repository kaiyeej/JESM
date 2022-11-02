<div class="modal fade" id="modalProfile" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="margin-top: 50px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel"><span class='fa fa-pen'></span> Update Profile</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="hidden_id" name="input[product_id]">
                <div class="form-group row">
                    <div class="col">
                        <label><strong>Confirm Password</strong></label>
                        <div>
                            <input type="hidden" class="form-control input-item" name="input[user_id]" id="user_id" value="<?= $_SESSION["user_id"] ?>">
                            <input type="password" class="form-control input-item" name="input[confirm_password]" id="confirm_password" placeholder="Confirm Password" required>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" onclick="updateProfile()" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Submit</span>
                </button>
            </div>
        </div>
    </div>
</div>