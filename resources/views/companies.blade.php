@extends('layouts.app')

@section('content')

<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Comapny</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="myForm" enctype="multipart/form-data">
                    <input type="hidden" id="hid" name="hid">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="rate">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Employee First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="rate">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Employee Last Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="rate">Telephone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Enter Employee Email">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="rate">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" placeholder="Enter Employee Email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="rate">Cover Image</label>
                            <input type="file" class="form-control" id="cover_images" name="cover_images" placeholder="Enter Employee Phone No" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="rate">Website</label>
                            <input type="text" class="form-control" id="website" name="website" placeholder="Enter Employee Profile Image" required>
                        </div>
                    </div>
                </form>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success submit" id="submit">Save changes</button>
          </div>
      </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h1 class="m-0 text-dark">Company</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item active">Company</li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Company</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="datatables">
                        <thead>
                            <tr>
                                <th style="width:20%">Name</th>
                                <th style="width:20%">Telephone</th>
                                <th style="width:20%">Email</th>
                                <th style="width:20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        //csrf token error
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        show_company();

        $(document).on("click",".addNew",function(){
            $("#modal").modal('show');
            $(".modal-title").html('Save Company');
            $("#submit").html('Save Company');
            $("#submit").click(function(){
                $("#submit").css("display","none");
                var hid =$("#hid").val();
                //save emplyee
                if(hid == ""){
                    var name =$("#name").val();
                    var email =$("#email").val();
                    var telephone =$("#telephone").val();
                    var website =$("#website").val();

                    var formData = new FormData($('#myForm')[0]);

                    $.ajax({
                        'type': 'ajax',
                        'dataType': 'json',
                        'method': 'post',
                        'data' : formData,
                        'url' : 'company',
                        'processData': false,
                        'contentType': false,
                        success:function(data){
                            if(data.validation_error){
                            validation_error(data.validation_error);//if has validation error call this function
                            }

                            if(data.db_error){
                            db_error(data.db_error);
                            }

                            if(data.db_success){
                                toastr.success(data.db_success);
                            setTimeout(function(){
                                $("#modal").modal('hide');
                                location.reload();
                            }, 2000);
                            }

                        },
                        error: function(jqXHR, exception) {
                            db_error(jqXHR.responseText);
                        }
                    });
                };
            });
        });
        $(document).on("click", ".edit", function(){

            var id = $(this).attr('data');

            empty_form();
            $("#hid").val(id);
            $("#modal").modal('show');
            $(".modal-title").html('Edit Employee');
            $("#submit").html('Update Employee');

            $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'get',
                'url': 'company/'+id,
                'async': false,
                success: function(data){
                    $("#name").val(data.name);
                    $("#email").val(data.email);
                    $("#telephone").val(data.telephone);
                    $("#website").val(data.website);
                }
            });

            $("#submit").click(function(){

                if($("#hid").val() != ""){
                var id =$("#hid").val();

                var name = $("#name").val();
                var email = $("#email").val();
                var telephone = $("#telephone").val();
                var website = $("#website").val();

                Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Update it!'
                        }).then((result) => {
                        if (result.isConfirmed) {

                            $.ajax({
                                'type': 'ajax',
                                'dataType': 'json',
                                'method': 'put',
                                'data' : {name:name,email:email,telephone:telephone,website:website},
                                'url': 'company/'+id,
                                'async': false,
                                success:function(data){
                                if(data.validation_error){
                                    validation_error(data.validation_error);//if has validation error call this function
                                    }

                                    if(data.db_error){
                                    db_error(data.db_error);
                                    }

                                    if(data.db_success){
                                    toastr.success(data.db_success);
                                    setTimeout(function(){
                                        $("#modal").modal('hide');
                                        location.reload();
                                    }, 2000);
                                    }
                                },
                            });
                        }
                    });
                }
            });
        });

//company delete
        $(document).on("click", ".delete", function(){
            var id = $(this).attr('data');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            'type': 'ajax',
                            'dataType': 'json',
                            'method': 'delete',
                            'url': 'company/'+id,
                            'async': false,
                            success: function(data){

                            if(data){
                                toastr.success('Comany Deleted');
                                setTimeout(function(){
                                location.reload();
                                }, 2000);

                            }

                            }
                        });

                    }

            });

        });

    });

    //Data Table show
    function show_company(){

        $('#datatables').DataTable().clear();
        $('#datatables').DataTable().destroy();

        $("#datatables").DataTable({
            'processing': true,
            'serverSide': true,
            "bLengthChange": false,
            "autoWidth": false,
            'ajax': {
                        'method': 'get',
                        'url': 'company/create'
            },
            'columns': [
                {data: 'name'},
                {data: 'email'},
                {data: 'telephone'},
                {
                    data: null,
                    render: function(d){
                        var html = "";
                        html+="<td><button class='btn btn-warning btn-sm edit' data='"+d.id+"' title='Edit'><i class='fas fa-edit' ></i></button>";
                        html+="&nbsp;<button class='btn btn-danger btn-sm delete' data='"+d.id+"'title='Delete'><i class='fas fa-trash'></i></button>";
                        return html;

                    }

                }
            ]
        });
    }
    function empty_form(){
        $("#bp_no").val("");
        $("#supplier_email").val("");
        $("#supplier_telephone").val("");
        $("#account_no").val("");
        $("#account_name").val("");
        $("#holder_nic").val("");

    }

    function validation_error(error){
        for(var i=0;i< error.length;i++){
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error[i],
            });
        }
    }

    function db_error(error){
        Swal.fire({
            icon: 'error',
            title: 'Database Error',
            text: error,
        });
    }

    function db_success(message){
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
        });
    }
</script>
@endsection
