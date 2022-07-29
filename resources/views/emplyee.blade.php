@extends('layouts.app')

@section('content')

<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Employee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  id="myForm" enctype="multipart/form-data">
                    <input type="hidden" id="hid" name="hid">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="rate">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter Employee First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="rate">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Employee Last Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="levy">Bank Name</label>
                            <select name="company" id="company" class="select2" required data-live-search="true" data-size="5">
                                <option value="">-- select bank --</option>
                                @foreach($Companys as $Company)
                                    <option value="{{ $Company->id }}">{{ $Company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="rate">Email</label>
                            <input type="text" class="form-control" id="employee_email" name="employee_email" placeholder="Enter Employee Email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="rate">Phone No</label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Enter Employee Phone No" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="rate">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" placeholder="Enter Employee Profile Image" required>
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
            <h1 class="m-0 text-dark">Employee</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item active">Bank Account</li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Employee</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="datatables">
                        <thead>
                            <tr>
                                <th style="width:20%">First Name</th>
                                <th style="width:20%">Email</th>
                                <th style="width:20%">Phone Number</th>
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
        //datatable show
        show_employee();

        //select 2 convert
        $('#company').select2({
            theme: 'bootstrap4'
        });
        // add new employee
        $(document).on("click",".addNew",function(){

            //open the model remove previous values
            empty_form();

            $("#modal").modal('show');
            $(".modal-title").html('Save Employee');
            $("#submit").html('Save Employee');
            $("#submit").click(function(){
                $("#submit").css("display","none");
                var hid =$("#hid").val();
                //save emplyee
                if(hid == ""){
                    var first_name =$("#first_name").val();
                    var last_name =$("#last_name").val();
                    var company =$("#company").val();
                    var employee_email =$("#employee_email").val();
                    var phone_no =$("#phone_no").val();

                    var formData = new FormData($('#myForm')[0]);

                    $.ajax({
                    'type': 'ajax',
                    'dataType': 'json',
                    'method': 'post',
                    'data' : formData,
                    'url' : 'employee',
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

        //employee edit
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
                'url': 'employee/'+id,
                'async': false,
                success: function(data){
                    $("#first_name").val(data.first_name);
                    $("#last_name").val(data.last_name);
                    $("#company").val(data.company);
                    $("#employee_email").val(data.email);
                    $("#phone_no").val(data.phone_no);
                }
            });
            //user button click submit data to controller
            $("#submit").click(function(){

                if($("#hid").val() != ""){
                var id =$("#hid").val();

                var first_name = $("#first_name").val();
                var last_name = $("#last_name").val();
                var company = $("#company").val();
                var employee_email = $("#employee_email").val();
                var phone_no = $("#phone_no").val();

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
                                'data' : {first_name:first_name,last_name:last_name,company:company,employee_email:employee_email,phone_no:phone_no},
                                'url': 'employee/'+id,
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

        //employee delete
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
                            'url': 'employee/'+id,
                            'async': false,
                            success: function(data){

                            if(data){
                                toastr.success('Employee Deleted');
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
    function show_employee(){

        $('#datatables').DataTable().clear();
        $('#datatables').DataTable().destroy();

        $("#datatables").DataTable({
            'processing': true,
            'serverSide': true,
            "bLengthChange": false,
            "autoWidth": false,
            'ajax': {
                        'method': 'get',
                        'url': 'employee/create'
            },
            'columns': [
                {data: 'first_name'},
                {data: 'email'},
                {data: 'phone_no'},
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
        $("#first_name").val("");
        $("#last_name").val("");
        $("#company").val("");
        $("#employee_email").val("");
        $("#phone_no").val("");
        $("#profile_image").val("");

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
