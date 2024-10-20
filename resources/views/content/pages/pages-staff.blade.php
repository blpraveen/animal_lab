@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Users')
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<!-- Row Group CSS -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css')}}">
<!-- Form Validation -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<!-- Flat Picker -->
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<!-- Form Validation -->
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection
@section('page-script')
<script>
    (function () {
        const user_tenure_from = document.querySelector('#user_tenure_from');
        if (user_tenure_from) {
            user_tenure_from.flatpickr({
            monthSelectorType: 'static'
            });
        }
        const user_tenure_to = document.querySelector('#user_tenure_to');
        if (user_tenure_to) {
            user_tenure_to.flatpickr({
            monthSelectorType: 'static'
            });
        }
    })();
    (function () {
        const formAddNewRecord = document.getElementById('editUserForm');
        fve = FormValidation.formValidation(formAddNewRecord, {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'The name is required'
                        }
                    }
                },
                employee_code: {
                    validators: {
                        notEmpty: {
                            message: 'The name is required'
                        }
                    }
                },
                user_name: {
                    validators: {
                        notEmpty: {
                            message: 'The user name is required'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email is required'
                        },
                        emailAddress: {
                            message: 'The value is not a valid email address',
                        },
                    }
                },
                role_id: {
                    validators: {
                        notEmpty: {
                            message: 'The role is required'
                        }
                    }
                },
                designation: {
                    validators: {
                        notEmpty: {
                            message: 'The designation is required'
                        }
                    }
                },
                mobile_no: {
                    validators: {
                        notEmpty: {
                            message: 'The phone number is required'
                        }
                    }
                },
                department: {
                    validators: {
                        notEmpty: {
                            message: 'The department is required'
                        }
                    }
                },
                tenure_from: {
                    validators: {
                        notEmpty: {
                            message: 'The tenure from is required'
                        }
                    }
                }

            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.col-12'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                //defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        }).on('core.form.valid', function(e) {
           // e.preventDefault();
            var formData = new FormData();
            formData.append('name', document.getElementById('user_name').value);
            formData.append('user_name', document.getElementById('user_id').value);
            formData.append('email', document.getElementById('user_email').value);
            formData.append('role_id', document.getElementById('user_role').value);
            formData.append('designation', document.getElementById('user_designation').value);
            formData.append('mobile_no', document.getElementById('user_mobile').value);
            formData.append('department', document.getElementById('user_department').value);
            formData.append('extension_no', document.getElementById('user_extension').value);
            formData.append('tenure_from', document.getElementById('user_tenure_from').value);
            formData.append('tenure_to', document.getElementById('user_tenure_to').value);
            formData.append('employee_code', document.getElementById('user_employee').value);
            formData.append('password', document.getElementById('user_password').value);
            if($(formAddNewRecord).data('id') == 0){
                route = '{{ route('staffs.store') }}';
            } else {
                route = '/staffs/update/'+$(formAddNewRecord).data('id');
            }
            axios.post(route, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function(response) {
                $('#editUser').modal('hide');
                Swal.fire({
                    title: 'Good job!',
                    text: 'User Information has been saved',
                    icon: 'success',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
                $('.datatables-basic').DataTable().ajax.reload(update_action);
            }).catch((error) => {
                const errors = error.response.data.errors
                for (let key in errors) { 
                    $('#validation-errors').append('<div class="alert alert-danger" role="alert">'+errors[key][0]+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="right:20px;top:30px;">&times;</button></div');
                }
            });
        });
    })();
    var table = $('.datatables-basic').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('staffs.index') }}",
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    }
                ],
                buttons: [
                    {
                        text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
                        className: 'create-new btn btn-primary'
                    }
                ],
                initComplete:update_action
            });
            function update_action(){
                setTimeout(() => {
                    const createRecord = document.querySelector('.create-new');    
                    const editRecord = document.querySelector('.edit-user');
                    if (createRecord) {
                        createRecord.addEventListener('click', function (e) {
                            $('#editUser').modal('show');
                            $('#user_name').val('');
                            $('#user_id').val('');
                            $('#user_email').val('');
                            $('#user_role').val('');
                            $('#user_designation').val('');
                            $('#user_mobile').val('');
                            $('#user_department').val('');
                            $('#user_extension').val('');
                            $('#user_tenure_from').val('');
                            $('#user_employee').val('');
                            $('#user_tenure_to').val('');
                            let form_edit_record = document.getElementById('editUserForm');
                                //form_edit_record.action = '{{ route('staffs.store') }}';
                            $(form_edit_record).data('id',0);
                        });
                    }
                    // To open offCanvas, to add new record
                    if (editRecord) {
                        editRecord.addEventListener('click', function (e) {
                            axios
                            .get('/staffs/'+e.currentTarget.dataset.id)
                            .then(res=>{
                                // offCanvasElement.querySelector('.dt-name').value = res.data.role.name;
                                // offCanvasEle = new bootstrap.Offcanvas(offCanvasElement);
                                // offCanvasEle.show();
                                $('#user_name').val(res.data.user.name);
                                $('#user_id').val(res.data.user.user_name);
                                $('#user_email').val(res.data.user.email);
                                $('#user_role').val(res.data.user.role_id);
                                $('#user_designation').val(res.data.user.designation);
                                $('#user_mobile').val(res.data.user.mobile_no);
                                $('#user_department').val(res.data.user.department_id);
                                $('#user_employee').val(res.data.user.employee_code);
                                $('#user_extension').val(res.data.user.extension_no);
                                $('#user_tenure_from').val(res.data.user.tenure_from);
                                $('#user_tenure_to').val(res.data.user.tenure_to);
                                let form_edit_record = document.getElementById('editUserForm');
                                $(form_edit_record).data('id',res.data.user.id);
                                //form_edit_record.action = '/staffs/update/'+res.data.user.id
                                $('#editUser').modal('show');
                                
                            })
                            .catch(console.error);
                        
                        });
                    }
                    const deleteRecord = document.querySelector('.delete-user');
                        let url = "{{ route('staffs.destroy', 'id') }}";
                        if(deleteRecord){
                          deleteRecord.addEventListener('click', function (e) {
                            $('#confirm_delete').modal('show');
                            let id = $(this).data('id');
                            url = url.replace("id", id);
                            $('#delete_user_id').val(id);
                            $('#confirm_delete').find('form').attr('action',url)
                          });
                        }
                    }, 200);
            }
</script>
@endsection
@section('content')
<h4>Users</h4>
@if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
@endif

@if ($errors->any())
     @foreach ($errors->all() as $error)
     <div class="alert alert-danger">
        <ul>
            <li>{!! $error !!}</li>
        </ul>
    </div>
     @endforeach
 @endif
<div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatables-basic table border-top">
        <thead>
          <tr>
            <th>id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
</div>
<div class="modal fade" id="confirm_delete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple">
      <div class="modal-content p-3 p-md-5">
        <form action="{{ route('staffs.destroy', 'id') }}" method="post">
          @csrf
          @method('DELETE')
          <input id="delete_user_id" name="id" hidden>
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-4">
              <h3 class="role-title">Are you sure you want to delete this user?</h3>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" data-bs-dismiss="modal" class="btn btn-primary" id="delete">Yes, Delete User</button>
            <button type="button" data-bs-dismiss="modal" class="btn">Cancel</button>
          </div>
      </form>
      </div>
    </div>
  </div>
<!-- Add Role Modal -->
<!-- Edit User Modal -->
<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3>User Information</h3>
            <p>Updating user details will receive a privacy audit.</p>
          </div>
          <div id="validation-errors" style="position:relative">
          </div>
          <form id="editUserForm" class="row g-3" action="" method="POST">
            @csrf
            <div class="col-12 col-md-6">
            <label class="form-label" for="name">Name</label>
            <input type="text" id="user_name" name="name" class="form-control" placeholder="John" />
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="name">Employee Code</label>
                <input type="text" id="user_employee" name="employee_code" class="form-control" placeholder="1BT433Y66" />
                </div>
            <div class="col-12 col-md-6">
            <label class="form-label" for="user_name">Username</label>
            <input type="text" id="user_id" name="user_name" class="form-control" placeholder="john.doe.007" />
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="user_password" name="password" class="form-control" placeholder="********" />
                </div>
            <div class="col-12 col-md-6">
            <label class="form-label" for="email">Email</label>
            <input type="text" id="user_email" name="email" class="form-control" placeholder="example@domain.com" />
            </div>
            <div class="col-12 col-md-6">
            <label class="form-label" for="user_role">Role</label>
            <select id="user_role" name="role_id" class="form-select" aria-label="Default select example">
                <option selected value="">Select Role</option>
                @foreach($roles as $role) 
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label" for="designation">Designation</label>
              <input type="text" id="user_designation" name="designation" class="form-control modal-edit-tax-id" placeholder="Asst. Manager" />
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label" for="mobile_no">Mobile No</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text">+91</span>
                <input type="text" id="user_mobile" name="mobile_no" class="form-control phone-number-mask" placeholder="202 555 0111" />
              </div>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="department">Department</label>
                <select id="user_department" name="department" class="form-select" aria-label="Default select example">
                    <option selected value="">Select Department</option>
                    @foreach($departments as $department) 
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="extension_no">Extension No.</label>
                <input type="text" id="user_extension" name="extension_no" class="form-control modal-edit-tax-id" placeholder="123 456 7890" />
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="tenure_from">Tenure From</label>
                <input type="text" name="tenure_from" id="user_tenure_from" class="form-control" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="tenure_to">Tenure TO</label>
                <input type="text" name="tenure_to" id="user_tenure_to" class="form-control" placeholder="YYYY-MM-DD" />
            </div>
            {{-- <div class="col-12 col-md-6">
                <label class="form-label" for="remarks">Remarks</label>
                <textarea class="form-control" id="user_remarks" name="remarks" rows="3"></textarea>
            </div> --}}
            
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
          </form>
         
        </div>
      </div>
    </div>
  </div>
  <!--/ Edit User Modal -->
  
  <!--/ Add Role Modal -->
  
@endsection