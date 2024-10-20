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
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
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
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
@endsection
@section('page-script')
<script>
     (function () {
        $(document).on('change','#room_id',function (){
            let room_id = $(this).val();
            if(room_id){
                $.ajax({
                    url: '{{ route("get.species") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', 
                        room_id : room_id
                    },
                    success: function(response) {
                        $('#specie_id').empty(); // Clear existing options
                        $('#specie_id').selectpicker("destroy");
                        $('#specie_id').append(`<option value="">Select Specie</option>`);
                        $.each(response, function(key, value) {
                            $('#specie_id').append(`<option value="${value.id}">${value.name}</option>`);
                        });    
                        $('#specie_id').selectpicker('render'); 
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
        $(document).on('change','#specie_id',function (){
            let specie_id = $(this).val();
            if(specie_id){
                $.ajax({
                    url: '{{ route("get.strains") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', 
                        specie_id : specie_id
                    },
                    success: function(response) {
                        $('#strain_id').empty(); // Clear existing options
                        $('#strain_id').selectpicker("destroy");
                        $('#strain_id').append(`<option value="">Select Strain</option>`);
                        $.each(response, function(key, value) {
                            $('#strain_id').append(`<option value="${value.id}">${value.name}</option>`);
                        });    
                        $('#strain_id').selectpicker('render'); 
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    })();
    const formAddNewRecord = document.getElementById('form-add-new-breeding');
        fve = FormValidation.formValidation(formAddNewRecord, {
            fields: {
                room_id: {
                    validators: {
                        notEmpty: {
                            message: 'The Room is required'
                        }
                    }
                },
                specie_id: {
                    validators: {
                        notEmpty: {
                            message: 'The Specie is required'
                        }
                    }
                },
                strain_id: {
                    validators: {
                        notEmpty: {
                            message: 'The Strain is required'
                        }
                    }
                },
                colony_id: {
                    validators: {
                        notEmpty: {
                            message: 'The Colony is required'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.form-data'
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
</script>
@endsection
@section('content')
<h4>Breeding</h4>
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
    <div class="card-header">
        <form class="add-new-record pt-0 row g-2" id="form-add-new-breeding" method="POST" action="{{ route('breeding.store') }}">
            @csrf
            <div class="d-flex justify-content-center">
                <div class="form-data">
                    <label for="room_id" class="form-label text-center w-100">Room</label>
                    <select id="room_id" name="room_id" class="selectpicker dt-room" style="width:300px;" data-style="btn-default">
                    <option value="">Select Room</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->room_no }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-data px-3">
                    <label for="specie_id" class="form-label text-center w-100">Specie</label>
                    <select id="specie_id" name="specie_id" class="selectpicker dt-specie" style="width:300px;" data-style="btn-default">
                    <option value="">Select Specie</option>
                    </select>
                </div>
                <div class="form-data px-3">
                    <label for="strain_id" class="form-label text-center w-100">Strain</label>
                    <select id="strain_id" name="strain_id" class="selectpicker dt-strain" style="width:300px;" data-style="btn-default">
                    <option value="">Select Strain</option>
                    </select>
                </div>
                <div class="form-data px-3">
                    <label for="colony_id" class="form-label text-center w-100">Colony</label>
                    <select id="colony_id" name="colony_id" class="selectpicker dt-colony" style="width:300px;" data-style="btn-default">
                    <option value="">Select Colony</option>
                    @foreach($colonies as $colony)
                    <option value="{{ $colony->id }}">{{ $colony->name }}</option>
                    @endforeach 
                    </select>
                </div>
                <div class="form-data px-3">
                    <label for="button" class="form-label text-center w-100">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection