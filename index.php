<?php
include 'core/config.php';
// print_r($_SESSION);

if (!isset($_SESSION['user']['id'])) {
  header("location:./login/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JESM</title>

  <!-- <link rel="stylesheet" href="assets/css/pages/form-element-select.css"> -->
  <link rel="stylesheet" href="assets/css/main/app.css">
  <link rel="stylesheet" href="assets/css/main/app-dark.css">
  <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
  <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">

  <link rel="stylesheet" href="vendors/sweetalert/sweetalert.css">

  <link rel="stylesheet" href="assets/css/shared/iconly.css">
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <link rel="stylesheet" href="assets/css/pages/fontawesome.css">
  <link rel="stylesheet" href="assets/css/pages/datatables.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <link rel="stylesheet" href="vendors/select2/select2.min.css">

  <style>
    body.theme-dark .form-check-input:checked {
      background-color: #435ebe !important;
      border-color: #435ebe !important;
    }

    body.theme-dark .btn-primary {
      background-color: #435ebe !important;
      border-color: #435ebe !important;
    }

    .btn-primary {
      background-color: #435ebe !important;
      border-color: #435ebe !important;
    }

    .btn-check:focus+.btn-primary,
    .btn-primary:focus,
    .btn-primary:hover {
      background-color: #efa753 !important;
      ;
      border-color: #efa753 !important;
      ;
    }

    .btn-primary:focus {
      box-shadow: 0 0 0 0.25rem rgb(255 197 130) !important;
    }

    body.theme-dark .dropdown-menu {
      background-color: #161b22 !important;
    }

    /* .dropdown-menu {
      background-color: #bdbdbd !important;

    } */

    .form-check-input:focus {
      border-color: #efa753;

    }

    .page-item.active .page-link {
      background-color: #435ebe !important;
      border-color: #435ebe !important;
    }

    body.theme-dark ol,
    body.theme-dark ul {
      padding-left: 0rem !important;
    }

    body.theme-dark .select2-container--default .select2-search--dropdown .select2-search__field {
      border: 1px solid #35354f !important;
    }

    body.theme-dark .select2-container--default .select2-selection--single {
      background-color: #1b1b29 !important;
      border: 1px solid #35354f !important;

    }

    body.theme-dark .select2-dropdown {
      background-color: #40404d !important;
    }

    body.theme-dark .select2-container--default .select2-results__option[aria-selected=true] {
      background-color: #939393 !important;
      color: #fff !important;
    }
  </style>
</head>

<body>

  <div id="app">
    <div id="sidebar" class="active">
      <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
          <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
              <a href="./"><img src="assets/images/logo.png" style="height: 67px;" alt="Logo" srcset=""></a>
            </div>
            <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                  <g transform="translate(-210 -1)">
                    <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                    <circle cx="220.5" cy="11.5" r="4"></circle>
                    <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                  </g>
                </g>
              </svg>
              <div class="form-check form-switch fs-6">
                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                <label class="form-check-label"></label>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path>
              </svg>
              <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="fa fa-power-off"></span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="#" onclick="logout()">Logout</a>
                </div>
              </div>
            </div>
            <div class="sidebar-toggler  x">
              <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
          </div>
        </div>

        <!-- SIDEBAR START -->

        <?php require 'components/sidebar.php'; ?>

        <!-- SIDEBAR END -->

      </div>
    </div>
    <div id="main">
      <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
          <i class="bi bi-justify fs-3"></i>
        </a>
      </header>

      <!-- PAGE START -->
      <?php require 'routes/routes.php'; ?>
      <!-- PAGE END -->

      <!-- START FOOTER -->
      <?php require 'components/footer.php'; ?>
      <!-- END FOOTER -->
    </div>
  </div>
  <!-- <script src="assets/js/extensions/form-element-select.js"></script> -->
  <script src="assets/js/app.js"></script>
  <script src="assets/js/extensions/datatables.js"></script>
  <script src="vendors/sweetalert/sweetalert.js"></script>
  <script src="vendors/select2/select2.min.js"></script>



  <script type='text/javascript'>
    <?php
    echo "var route_settings = " . $route_settings . ";\n";
    ?>
  </script>
  <script type="text/javascript">
    var modal_detail_status = 0;
    $(document).ready(function() {

      // FilePond.registerPlugin(
      //   FilePondPluginImagePreview,
      //   FilePondPluginImageExifOrientation,
      //   FilePondPluginFileValidateSize,
      //   FilePondPluginImageEdit,
      //   FilePondPluginFileValidateType
      // );
      // FilePond.create(
      //   document.querySelector('#imagesFilepond'), {
      //     name: 'filepond',
      //     maxFiles: 5,
      //     allowBrowse: true,
      //     acceptedFileTypes: ['image/*'],
      //     // server


      //   }
      // );
      $(".select2").select2();

      $(".select2").css({
        "width": "100%"
      });


    });

    function printCanvas() {
      var printContents = document.getElementById('print_canvas').innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
      window.close();
      location.reload();

    }



    function schema() {
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=schema",
        data: [],
        success: function(data) {
          var json = JSON.parse(data);
          console.log(json.data);
        }
      });
    }

    function success_add() {
      swal("Success!", "Successfully added entry!", "success");
    }

    function success_update() {
      swal("Success!", "Successfully updated entry!", "success");
    }

    function success_delete() {
      swal("Success!", "Successfully deleted entry!", "success");
    }

    function entry_already_exists() {
      swal("Cannot proceed!", "Entry already exists!", "warning");
    }

    function amount_is_greater() {
      swal("Cannot proceed!", "Amount is greater than balance!", "warning");
    }

    function failed_query(data) {
      swal("Failed to execute query!", data, "warning");
      //alert('Something is wrong. Failed to execute query. Please try again.');
    }

    function checkAll(ele, ref) {
      var checkboxes = document.getElementsByClassName(ref);
      if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = true;
          }
        }
      } else {
        for (var i = 0; i < checkboxes.length; i++) {
          //console.log(i)
          if (checkboxes[i].type == 'checkbox') {
            checkboxes[i].checked = false;
          }
        }
      }
    }



    function logout() {
      var url = "controllers/sql.php?c=LoginUser&q=logout";
      $.ajax({
        url: url,
        success: function(data) {

          location.reload();

        }
      });
    }

    function uploadProductImage(id) {
      // alert(id);
      $("#product_id_hidden").val(id);
      $("#modalUpload").modal('show');

    }
    $("#frm_upload_img_product").submit(function(e) {
      e.preventDefault();

      //var formData = new FormData(this);
      $("#btn_submit").prop('disabled', true);
      $("#btn_submit").html("<span class='fa fa-spinner fa-spin'></span> Submitting ...");
      // console.log(formData);

      var url = "controllers/sql.php?c=" + route_settings.class_name + "&q=uploadImage";
      $.ajax({
        url: url,
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {

          var json = JSON.parse(data);
          if (json.data == 1) {
            $("#modalUpload").modal('hide');
            success_update();
            setTimeout(function() {
              location.reload();
            }, 2000);

          }



        }
      });

    });

    function addModal() {
      $(".select2").select2({
        dropdownParent: $("#modalEntry")
      });
      modal_detail_status = 0;
      $("#hidden_id").val(0);
      document.getElementById("frm_submit").reset();

      var element = document.getElementById('reference_number');
      if (typeof(element) != 'undefined' && element != null) {
        generateReference(route_settings.class_name);
      }

      $("#modalLabel").html("<span class='fa fa-pen'></span> Add Entry");
      $("#modalEntry").modal('show');
    }

    $("#frm_submit").submit(function(e) {
      e.preventDefault();

      $("#btn_submit").prop('disabled', true);
      $("#btn_submit").html("<span class='fa fa-spinner fa-spin'></span> Submitting ...");

      var hidden_id = $("#hidden_id").val();
      var q = hidden_id > 0 ? "edit" : "add";
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=" + q,
        data: $("#frm_submit").serialize(),
        success: function(data) {
          getEntries();
          var json = JSON.parse(data);
          if (route_settings.has_detail == 1) {
            if (json.data > 0) {
              $("#modalEntry").modal('hide')
              hidden_id > 0 ? success_update() : success_add();
              hidden_id > 0 ? $("#modalEntry2").modal('hide') : '';
              hidden_id > 0 ? getEntryDetails2(hidden_id) : getEntryDetails2(json.data);
            } else if (json.data == -2) {
              entry_already_exists();
            } else {
              failed_query(json);
            }
          } else {
            if (json.data == 1) {
              hidden_id > 0 ? success_update() : success_add();
              $("#modalEntry").modal('hide');
            } else if (json.data == 2) {
              entry_already_exists();
            } else {
              failed_query(json);
            }
          }
          $("#btn_submit").prop('disabled', false);
          $("#btn_submit").html("<span class='fa fa-check-circle'></span> Submit");
        }
      });
    });

    function getEntryDetails(id, is_det = 0) {
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=view",
        data: {
          input: {
            id: id
          }
        },
        success: function(data) {
          var jsonParse = JSON.parse(data);
          const json = jsonParse.data;

          $("#hidden_id").val(id);

          $('.input-item').map(function() {
            //console.log(this.id);
            const id_name = this.id;
            this.value = json[id_name];
          });

          $(".select2").select2().trigger('change');

          $("#modalLabel").html("<span class='fa fa-pen'></span> Update Entry");
          $("#modalEntry").modal('show');
        }
      });

      if (is_det == 1) {
        modal_detail_status == 1 ? setTimeout(() => {
          $("#modalEntry2").modal('hide')
        }, 500) : '';
      } else {
        modal_detail_status = 0;
      }
    }

    function deleteEntry() {

      var count_checked = $("input[class='dt_id']:checked").length;

      if (count_checked > 0) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover these entries!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              var checkedValues = $("input[class='dt_id']:checked").map(function() {
                return this.value;
              }).get();

              $.ajax({
                type: "POST",
                url: "controllers/sql.php?c=" + route_settings.class_name + "&q=remove",
                data: {
                  input: {
                    ids: checkedValues
                  }
                },
                success: function(data) {
                  getEntries();
                  var json = JSON.parse(data);
                  console.log(json);
                  if (json.data == 1) {
                    success_delete();
                  } else {
                    failed_query(json);
                  }
                }
              });

              $("#btn_delete").prop('disabled', true);

            } else {
              swal("Cancelled", "Entries are safe :)", "error");
            }
          });
      } else {
        swal("Cannot proceed!", "Please select entries to delete!", "warning");
      }
    }

    // MODULE WITH DETAILS LIKE SALES

    function getEntryDetails2(id) {

      $("#hidden_id_2").val(id);
      modal_detail_status = 1;
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=view",
        data: {
          input: {
            id: id
          }
        },
        success: function(data) {

          var jsonParse = JSON.parse(data);
          const json = jsonParse.data;

          $('.label-item').map(function() {
            const id_name = this.id;
            const new_id = id_name.replace('_label', '');
            this.innerHTML = json[new_id];
          });

          var transaction_edit = document.getElementById("menu-edit-transaction");
          var transaction_delete_items = document.getElementById("menu-delete-selected-items");
          var transaction_finish = document.getElementById("menu-finish-transaction");
          var col_list = document.getElementById("col-list");
          var col_item = document.getElementById("col-item");

          if (json.status == 'F') {
            transaction_edit.classList.add('disabled');
            (typeof(transaction_delete_items) != 'undefined' && transaction_delete_items != null) ? transaction_delete_items.classList.add('disabled'): '';
            transaction_finish.classList.add('disabled');

            transaction_edit.setAttribute("onclick", "");
            (typeof(transaction_delete_items) != 'undefined' && transaction_delete_items != null) ? transaction_delete_items.setAttribute("onclick", ""): '';
            transaction_finish.setAttribute("onclick", "");

            (typeof(col_item) != 'undefined' && col_item != null) ? col_item.style.display = "none": '';
            (typeof(col_list) != 'undefined' && col_list != null) ? col_list.classList.remove('col-8'): '';
            (typeof(col_list) != 'undefined' && col_list != null) ? col_list.classList.add('col-12'): '';
          } else {
            transaction_edit.classList.remove('disabled');
            (typeof(transaction_delete_items) != 'undefined' && transaction_delete_items != null) ? transaction_delete_items.classList.remove('disabled'): '';
            transaction_finish.classList.remove('disabled');

            transaction_edit.setAttribute("onclick", "getEntryDetails(" + id + ",1)");
            (typeof(transaction_delete_items) != 'undefined' && transaction_delete_items != null) ? transaction_delete_items.setAttribute("onclick", "deleteEntry2()"): '';
            transaction_finish.setAttribute("onclick", "finishTransaction()");

            (typeof(col_item) != 'undefined' && col_item != null) ? col_item.style.display = "block": '';
            (typeof(col_list) != 'undefined' && col_list != null) ? col_list.classList.remove('col-12'): '';
            (typeof(col_list) != 'undefined' && col_list != null) ? col_list.classList.add('col-8'): '';
          }

          getEntries2();
          $("#modalEntry2").modal('show');
          // $(".select2").select2().trigger('change');
          $(".select2").select2({
            dropdownParent: $("#modalEntry2")
          });
        }
      });
    }

    $("#frm_submit_2").submit(function(e) {
      e.preventDefault();

      $("#btn_submit_2").prop('disabled', true);
      $("#btn_submit_2").html("<span class='fa fa-spinner fa-spin'></span> Submitting ...");

      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + route_settings.class_name + "&q=add_detail",
        data: $("#frm_submit_2").serialize(),
        success: function(data) {
          getEntries2();
          var json = JSON.parse(data);
          if (json.data == 1) {
            success_add();
            var id = $("#hidden_id_2").val();
            document.getElementById("frm_submit_2").reset();
            $('.select2').select2().trigger('change');
            $("#hidden_id_2").val(id);
          } else if (json.data == 2) {
            entry_already_exists();
          } else if (json.data == 3) {
            amount_is_greater();
          } else {
            failed_query(json);
            $("#modalEntry2").modal('hide');
          }
          $("#btn_submit_2").prop('disabled', false);
          $("#btn_submit_2").html("<span class='fa fa-check-circle'></span> Submit");
        }
      });
    });

    function deleteEntry2() {

      var count_checked = $("input[class='dt_id_2']:checked").length;

      if (count_checked > 0) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover these entries!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              var checkedValues = $("input[class='dt_id_2']:checked").map(function() {
                return this.value;
              }).get();

              $.ajax({
                type: "POST",
                url: "controllers/sql.php?c=" + route_settings.class_name + "&q=remove_detail",
                data: {
                  input: {
                    ids: checkedValues
                  }
                },
                success: function(data) {
                  getEntries2();
                  var json = JSON.parse(data);
                  console.log(json);
                  if (json.data == 1) {
                    success_delete();
                  } else {
                    failed_query(json);
                  }
                }
              });

              $("#btn_delete").prop('disabled', true);

            } else {
              swal("Cancelled", "Entries are safe :)", "error");
            }
          });
      } else {
        swal("Cannot proceed!", "Please select entries to delete!", "warning");
      }
    }

    function finishTransaction() {
      var id = $("#hidden_id_2").val();

      var count_checked = $("input[class='dt_id_2']").length;
      if (count_checked > 0) {
        swal({
            title: "Are you sure?",
            text: "This entries will be finished!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-info",
            confirmButtonText: "Yes, finish it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              $.ajax({
                type: "POST",
                url: "controllers/sql.php?c=" + route_settings.class_name + "&q=finish",
                data: {
                  input: {
                    id: id
                  }
                },
                success: function(data) {
                  getEntries();
                  var json = JSON.parse(data);
                  if (json.data == 1) {
                    success_add();
                    $("#modalEntry2").modal('hide');
                  } else {
                    failed_query(json);
                  }
                }
              });
            } else {
              swal("Cancelled", "Entries are safe :)", "error");
            }
          });
      } else {
        swal("Cannot proceed!", "No entries found!", "warning");
      }
    }
    // END MODULE

    function getSelectOption(class_name, primary_id, label, param = '', attributes = [], pre_value = '', pre_label = 'Please Select', sub_option = '',add_opt ="") {

      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + class_name + "&q=show",
        data: {
          input: {
            param: param
          }
        },
        success: function(data) {
          var json = JSON.parse(data);
          
          if(add_opt != ""){
            $("#" + primary_id).append("<option value='" + pre_value + "'> &mdash; " + pre_label + " &mdash; </option><option value='-1'>" + add_opt + "</option>");
          }else{
            $("#" + primary_id).html("<option value='" + pre_value + "'> &mdash; " + pre_label + " &mdash; </option>");
          }


          for (list_index = 0; list_index < json.data.length; list_index++) {
            const list = json.data[list_index];
            var data_attributes = {};
            if (sub_option == 1) {
              data_attributes['value'] = list[primary_id.slice(0, -2)];
            } else {
              data_attributes['value'] = list[primary_id];
            }
            for (var attr_index in attributes) {
              const attr = attributes[attr_index];
              data_attributes[attr] = list[attr];
            }
            $('#' + primary_id).append($("<option></option>").attr(data_attributes).text(list[label]));
          }
        }
      });
    }

    function generateReference(class_name) {
      $.ajax({
        type: "POST",
        url: "controllers/sql.php?c=" + class_name + "&q=generate",
        data: [],
        success: function(data) {
          var json = JSON.parse(data);
          $("#reference_number").val(json.data);
        }
      });
    }
  </script>
</body>

</html>