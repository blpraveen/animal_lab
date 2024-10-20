@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles')
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
@endsection
@section('page-script')
<script>
    let fv, offCanvasEl,fve, offCanvasEle;
    document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formAddNewRecord = document.getElementById('form-add-new-record');

    setTimeout(() => {
      const newRecord = document.querySelector('.create-new'),
        offCanvasElement = document.querySelector('#add-new-record');

      // To open offCanvas, to add new record
      if (newRecord) {
        newRecord.addEventListener('click', function () {
          offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
          // Empty fields on offCanvas open
          (offCanvasElement.querySelector('.dt-name').value = '');
          // Open offCanvas with form
          offCanvasEl.show();
        });
      }
    }, 200);

    // Form validation for Add new record
    fv = FormValidation.formValidation(formAddNewRecord, {
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'The name is required'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-12'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    });
  })();
  (function () {
    const formAddNewRecord = document.getElementById('form-edit-record');

    

    // Form validation for Add new record
    fve = FormValidation.formValidation(formAddNewRecord, {
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'The name is required'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-12'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    });
  })();
});
   var table = $('.datatables-basic').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('roles.index') }}",
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
                initComplete:function( settings, json){
                    setTimeout(() => {
                    const newRecord = document.querySelector('.edit-role'),
                        offCanvasElement = document.querySelector('#edit-record');

                    // To open offCanvas, to add new record
                    if (newRecord) {
                        newRecord.addEventListener('click', function (e) {
                            axios
                            .get('/roles/'+e.currentTarget.dataset.id)
                            .then(res=>{
                                offCanvasElement.querySelector('.dt-name').value = res.data.role.name;
                                offCanvasEle = new bootstrap.Offcanvas(offCanvasElement);
                                offCanvasEle.show();
                                let form_edit_record = document.getElementById('form-edit-record');
                                form_edit_record.action = '/roles/update/'+res.data.role.id
                                if(res.data.rolePermissions){
                                    res.data.rolePermissions.forEach((item)=> {
                                        var permission_list = document.querySelectorAll('.permission-check'); // returns NodeList
                                        var permission_array = [...permission_list]; // converts NodeList to Array
                                        permission_list.forEach(row => {
                                            if(row.value == item.name){
                                                row.checked = true;
                                            }
                                        });
                                    });
                                }
                            })
                            .catch(console.error);
                           
                        // Empty fields on offCanvas open
                        // Open offCanvas with form
                        
                        });
                        const deleteRecord = document.querySelector('.delete-role');
                        let url = "{{ route('roles.destroy', 'id') }}";
                        if(deleteRecord){
                          deleteRecord.addEventListener('click', function (e) {
                            $('#confirm_delete').modal('show');
                            let id = $(this).data('id');
                            url = url.replace("id", id);
                            $('#delete_role_id').val(id);
                            $('#confirm_delete').find('form').attr('action',url)
                          });
                        }
                    }
                    }, 200);
                }
            });
    
  
</script>
@endsection

@section('content')
<h4>Roles</h4>
@if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
@endif
<div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatables-basic table border-top">
        <thead>
          <tr>
            <th>id</th>
            <th>Name</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
</div>
  <!-- Add Role Modal -->
<div class="modal fade" id="confirm_delete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-simple">
    <div class="modal-content p-3 p-md-5">
      <form action="{{ route('roles.destroy', 'id') }}" method="post">
        @csrf
        @method('DELETE')
        <input id="delete_role_id" name="id" hidden>
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="role-title">Are you sure you want to delete this role?</h3>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" data-bs-dismiss="modal" class="btn btn-primary" id="delete">Yes, Delete Role</button>
          <button type="button" data-bs-dismiss="modal" class="btn">Cancel</button>
        </div>
    </form>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->

<div class="offcanvas offcanvas-end" id="add-new-record">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="exampleModalLabel">New Role</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
    <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST" action="{{ route('roles.store') }}">
        @csrf
        <div class="col-sm-12">
          <label class="form-label" for="basicFullname">Name</label>
          <div class="input-group input-group-merge">
            <span id="basicFullname2" class="input-group-text"><i class="bx bx-user"></i></span>
            <input type="text" id="basicFullname" class="form-control dt-name" name="name" placeholder="John Doe" aria-label="John Doe" aria-describedby="basicFullname2" />
          </div>
        </div>
        
        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>
  
    </div>
  </div>
  <div class="offcanvas offcanvas-end" id="edit-record">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="exampleModalLabel">Edit Role</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
    <form class="edit-record pt-0 row g-2" id="form-edit-record"  method="POST" action="">
        @csrf
        {{-- <input type="hidden" name="_method" value="put" /> --}}
        <div class="col-sm-12">
          <label class="form-label" for="basicFullname">Name</label>
          <div class="input-group input-group-merge">
            <span id="basicFullname2" class="input-group-text"><i class="bx bx-user"></i></span>
            <input type="text" id="basicFullname" class="form-control dt-name" name="name" placeholder="John Doe" aria-label="John Doe" aria-describedby="basicFullname2" />
          </div>
        </div>
        <div class="col-sm-12">
        @foreach($permissions as $key=>$permission)
        <div class="form-check mt-3">
            <input class="permission-check form-check-input" name="permission[]" type="checkbox" value="{{ $permission->name }}" id="defaultCheck{{ $key }}" />
            <label class="form-check-label" for="defaultCheck1">
                {{ $permission->name }}
            </label>
        </div>
        @endforeach
        </div>
        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>
  
    </div>
  </div>
@endsection
