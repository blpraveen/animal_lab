@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Species')
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
    })();
    var table = $('.datatables-basic').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('species.index') }}",
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
                        data: 'code',
                        name: 'code'
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
                      
                    const editRecord = document.querySelector('.edit-specie');
                    // To open offCanvas, to add new record
                    if (editRecord) {
                        offCanvasElement = document.querySelector('#edit-record');
                        // To open offCanvas, to add new record
                        editRecord.addEventListener('click', function (e) {
                            axios
                            .get('/species/'+e.currentTarget.dataset.id)
                            .then(res=>{
                                offCanvasElement.querySelector('.dt-name').value = res.data.specie.name;
                                offCanvasElement.querySelector('.dt-code').value = res.data.specie.code;
                                offCanvasEle = new bootstrap.Offcanvas(offCanvasElement);
                                offCanvasEle.show();
                                let form_edit_record = document.getElementById('form-edit-record');
                                form_edit_record.action = '/species/'+res.data.specie.id
                            })
                            .catch(console.error);
                        });
                        
                    }
                    let url = "{{ route('species.destroy', 'id') }}";

                    $(document).on('click','.delete-specie',function () {
                        $('#confirm_delete').modal('show');
                        let id = $(this).data('id');
                        url = url.replace("id", id);
                        $('#delete_role_id').val(id);
                        $('#confirm_delete').find('form').attr('action',url)
                    });

                }, 200);
            }
</script>
@endsection
@section('content')
<h4>Species</h4>
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
            <th>Code</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
</div>
<div class="offcanvas offcanvas-end" id="add-new-record">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="exampleModalLabel">New Specie</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
    <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST" action="{{ route('species.store') }}">
        @csrf
        <div class="col-sm-12">
          <label class="form-label" for="basicFullname">Name</label>
          <div class="input-group input-group-merge">
            <span id="basicFullname2" class="input-group-text"><i class="bx bx-user"></i></span>
            <input type="text" id="add_name" class="form-control dt-name" name="name" placeholder="Mice" aria-label="John Doe" aria-describedby="basicFullname2" />
          </div>
        </div>
        <div class="col-sm-12">
            <label class="form-label" for="basicFullname">Code</label>
            <div class="input-group input-group-merge">
              <span id="basicFullname3" class="input-group-text"><i class="bx bx-barcode"></i></span>
              <input type="text" id="add_code" class="form-control dt-code" name="code" placeholder="200" aria-label="John Doe" aria-describedby="basicFullname3" />
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
      <h5 class="offcanvas-title" id="exampleModalLabel">Edit Specie</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
    <form class="edit-record pt-0 row g-2" id="form-edit-record"  method="POST" action="">
        @csrf
        @method('PUT')
        {{-- <input type="hidden" name="_method" value="put" /> --}}
        <div class="col-sm-12">
          <label class="form-label" for="basicFullname">Name</label>
          <div class="input-group input-group-merge">
            <span id="basicFullname2" class="input-group-text"><i class="bx bx-user"></i></span>
            <input type="text" id="edit_name" class="form-control dt-name" name="name" placeholder="Mice" aria-label="John Doe" aria-describedby="basicFullname2" />
          </div>
        </div>
        <div class="col-sm-12">
            <label class="form-label" for="basicFullname">Code</label>
            <div class="input-group input-group-merge">
              <span id="basicFullname3" class="input-group-text"><i class="bx bx-barcode"></i></span>
              <input type="text" id="edit_code" class="form-control dt-code" name="code" placeholder="200" aria-label="John Doe" aria-describedby="basicFullname3" />
            </div>
        </div>
        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>
  
    </div>
</div>
<div class="modal fade" id="confirm_delete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple">
      <div class="modal-content p-3 p-md-5">
        <form action="{{ route('species.destroy', 'id') }}" method="post">
          @csrf
          @method('DELETE')
          <input id="delete_role_id" name="id" hidden>
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-4">
              <h3 class="role-title">Are you sure you want to delete this specie?</h3>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" data-bs-dismiss="modal" class="btn btn-primary" id="delete">Yes, Delete Specie</button>
            <button type="button" data-bs-dismiss="modal" class="btn">Cancel</button>
          </div>
      </form>
      </div>
    </div>
</div>
@endsection