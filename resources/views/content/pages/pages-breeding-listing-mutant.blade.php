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
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<!-- Flat Picker -->
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<!-- Form Validation -->
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
@endsection
@section('page-script')
<script>
     var fnd;
     (function () {
        setTimeout(() => {
        const newRecord = document.querySelector('.create-new'),
            offCanvasElement = document.querySelector('#add-new-record');

        // To open offCanvas, to add new record
        if (newRecord) {
            newRecord.addEventListener('click', function () {
            
            offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
            // Empty fields on offCanvas open
            (offCanvasElement.querySelector('.dt-date-of-ifm').value = '');
            // Open offCanvas with form
            offCanvasEl.show();
            });
        }
        }, 200);
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
        $('#date_of_ifm').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            orientation: isRtl ? 'auto right' : 'auto left'
        });
        $('.dt-dob').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            orientation: isRtl ? 'auto right' : 'auto left'
        });
        $('.dt-weaned-date').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            orientation: isRtl ? 'auto right' : 'auto left'
        });
        $(document).on('click','.add-new-born',function (){
            let cloneRecord = $('#add_delivered tbody tr:last').clone();
            cloneRecord.find("input").val("");
            cloneRecord.find("textarea").val("");
            cloneRecord.find('.delete-delivery').removeClass('d-none');
            $("#add_delivered tbody").append(cloneRecord);
            cloneRecord.find('.dt-dob').datepicker({
                todayHighlight: true,
                format: 'yyyy-mm-dd',
                orientation: isRtl ? 'auto right' : 'auto left'
            });
            window.fnd.destroy();
            update_form_delivery();
        });
        $(document).on('click','.delete-delivery',function(){
            $(this).closest('tr').remove();
            window.fnd.destroy();
            update_form_delivery();
        });
        $(document).on('keyup','.dt-homo-male',function(){
            let tr = $(this).closest('tr'); 
            let homo_female = isNaN(parseInt(tr.find('.dt-homo-female').val()))? 0 :parseInt(tr.find('.dt-homo-female').val());
            let homo_male = isNaN(parseInt($(this).val())) ? 0 :parseInt($(this).val());
            let hetro_male = isNaN(parseInt(tr.find('.dt-hetro-male').val()))? 0 :parseInt(tr.find('.dt-hetro-male').val());
            let hetro_female = isNaN(parseInt(tr.find('.dt-hetro-female').val()))? 0 :parseInt(tr.find('.dt-hetro-female').val());
            let wild_male = isNaN(parseInt(tr.find('.dt-wild-male').val()))? 0 :parseInt(tr.find('.dt-wild-male').val());
            let wild_female = isNaN(parseInt(tr.find('.dt-wild-female').val()))? 0 :parseInt(tr.find('.dt-wild-female').val());
            let total = homo_female+homo_male+hetro_male+hetro_female + wild_male + wild_female;
            let pups = parseInt(tr.find('.dt-pups').val());
            let dead_pups = pups - total;
            if(dead_pups < 0){
                alert('Invalid entry in males and female pups');
                $(this).val(0);
                total = homo_female+hetro_male+hetro_female + wild_male + wild_female;;
                dead_pups = pups - total;
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            } else {
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            }
        });
        $(document).on('keyup','.dt-homo-female',function(){
            let tr = $(this).closest('tr'); 
            let homo_male = isNaN(parseInt(tr.find('.dt-homo-male').val()))? 0 :parseInt(tr.find('.dt-homo-male').val());
            let homo_female = isNaN(parseInt($(this).val())) ? 0 :parseInt($(this).val());
            let hetro_male = isNaN(parseInt(tr.find('.dt-hetro-male').val()))? 0 :parseInt(tr.find('.dt-hetro-male').val());
            let hetro_female = isNaN(parseInt(tr.find('.dt-hetro-female').val()))? 0 :parseInt(tr.find('.dt-hetro-female').val());
            let wild_male = isNaN(parseInt(tr.find('.dt-wild-male').val()))? 0 :parseInt(tr.find('.dt-wild-male').val());
            let wild_female = isNaN(parseInt(tr.find('.dt-wild-female').val()))? 0 :parseInt(tr.find('.dt-wild-female').val());
            let total = homo_female+homo_male+hetro_male+hetro_female + wild_male + wild_female;
            let pups = parseInt(tr.find('.dt-pups').val());
            let dead_pups = pups - total;
            if(dead_pups < 0){
                alert('Invalid entry in males and female pups');
                $(this).val(0);
                total = homo_male + hetro_male + hetro_female + wild_male + wild_female;;
                dead_pups = pups - total;
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            } else {
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            }
        });
        $(document).on('keyup','.dt-hetro-male',function(){
            let tr = $(this).closest('tr'); 
            let homo_male = isNaN(parseInt(tr.find('.dt-homo-male').val()))? 0 :parseInt(tr.find('.dt-homo-male').val());
            let homo_female = isNaN(parseInt(tr.find('.dt-homo-female').val()))? 0 :parseInt(tr.find('.dt-homo-female').val());
            let hetro_male =  isNaN(parseInt($(this).val())) ? 0 :parseInt($(this).val());
            let hetro_female = isNaN(parseInt(tr.find('.dt-hetro-female').val()))? 0 :parseInt(tr.find('.dt-hetro-female').val());
            let wild_male = isNaN(parseInt(tr.find('.dt-wild-male').val()))? 0 :parseInt(tr.find('.dt-wild-male').val());
            let wild_female = isNaN(parseInt(tr.find('.dt-wild-female').val()))? 0 :parseInt(tr.find('.dt-wild-female').val());
            let total = homo_female+homo_male+hetro_male+hetro_female + wild_male + wild_female;
            let pups = parseInt(tr.find('.dt-pups').val());
            let dead_pups = pups - total;
            if(dead_pups < 0){
                alert('Invalid entry in males and female pups');
                $(this).val(0);
                total = homo_female+homo_male+hetro_female + wild_male + wild_female;;
                dead_pups = pups - total;
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            } else {
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            }
        });
        $(document).on('keyup','.dt-hetro-female',function(){
            let tr = $(this).closest('tr'); 
            let homo_male = isNaN(parseInt(tr.find('.dt-homo-male').val()))? 0 :parseInt(tr.find('.dt-homo-male').val());
            let homo_female = isNaN(parseInt(tr.find('.dt-homo-female').val()))? 0 :parseInt(tr.find('.dt-homo-female').val());
            let hetro_male =  isNaN(parseInt(tr.find('.dt-hetro-male').val()))? 0 :parseInt(tr.find('.dt-hetro-male').val());
            let hetro_female = isNaN(parseInt($(this).val())) ? 0 :parseInt($(this).val());
            let wild_male = isNaN(parseInt(tr.find('.dt-wild-male').val()))? 0 :parseInt(tr.find('.dt-wild-male').val());
            let wild_female = isNaN(parseInt(tr.find('.dt-wild-female').val()))? 0 :parseInt(tr.find('.dt-wild-female').val());
            let total = homo_female+homo_male+hetro_male+hetro_female + wild_male + wild_female;
            let pups = parseInt(tr.find('.dt-pups').val());
            let dead_pups = pups - total;
            if(dead_pups < 0){
                alert('Invalid entry in males and female pups');
                $(this).val(0);
                total = homo_female+homo_male+hetro_male + wild_male + wild_female;;
                dead_pups = pups - total;
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            } else {
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            }
        });
        $(document).on('keyup','.dt-wild-male',function(){
            let tr = $(this).closest('tr'); 
            let homo_male = isNaN(parseInt(tr.find('.dt-homo-male').val()))? 0 :parseInt(tr.find('.dt-homo-male').val());
            let homo_female = isNaN(parseInt(tr.find('.dt-homo-female').val()))? 0 :parseInt(tr.find('.dt-homo-female').val());
            let hetro_male = isNaN(parseInt(tr.find('.dt-hetro-male').val()))? 0 :parseInt(tr.find('.dt-hetro-male').val());
            let hetro_female = isNaN(parseInt(tr.find('.dt-hetro-female').val()))? 0 :parseInt(tr.find('.dt-hetro-female').val());
            let wild_male = isNaN(parseInt($(this).val())) ? 0 :parseInt($(this).val());
            let wild_female = isNaN(parseInt(tr.find('.dt-wild-female').val()))? 0 :parseInt(tr.find('.dt-wild-female').val());
            let total = homo_female+homo_male+hetro_male+hetro_female + wild_male + wild_female;
            let pups = parseInt(tr.find('.dt-pups').val());
            let dead_pups = pups - total;
            if(dead_pups < 0){
                alert('Invalid entry in males and female pups');
                $(this).val(0);
                total = homo_female + homo_male + hetro_male + hetro_female + wild_female;;
                dead_pups = pups - total;
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            } else {
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            }
        });
        $(document).on('keyup','.dt-wild-female',function(){
            let tr = $(this).closest('tr'); 
            let homo_male = isNaN(parseInt(tr.find('.dt-homo-male').val()))? 0 :parseInt(tr.find('.dt-homo-male').val());
            let homo_female = isNaN(parseInt(tr.find('.dt-homo-female').val()))? 0 :parseInt(tr.find('.dt-homo-female').val());
            let hetro_male = isNaN(parseInt(tr.find('.dt-hetro-male').val()))? 0 :parseInt(tr.find('.dt-hetro-male').val());
            let hetro_female = isNaN(parseInt(tr.find('.dt-hetro-female').val()))? 0 :parseInt(tr.find('.dt-hetro-female').val());
            let wild_male = isNaN(parseInt(tr.find('.dt-wild-male').val()))? 0 :parseInt(tr.find('.dt-wild-male').val());
            let wild_female = isNaN(parseInt($(this).val())) ? 0 :parseInt($(this).val());
            let total = homo_female+homo_male+hetro_male+hetro_female + wild_male + wild_female;
            let pups = parseInt(tr.find('.dt-pups').val());
            let dead_pups = pups - total;
            if(dead_pups < 0){
                alert('Invalid entry in males and female pups');
                $(this).val(0);
                total = homo_female + homo_male + hetro_male + hetro_female + wild_male;;
                dead_pups = pups - total;
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            } else {
                tr.find('.dt-pups-dead').val(dead_pups);
                tr.find('.dt-pups-weaned').val(total);
            }
        });
        $(document).on('click','#weaning_submit',function (){
            let id = $('#addWeaningForm').data('id');
            var formData = new FormData();
            let pups_homo_male=[];
            let pups_homo_female=[];
            let pups_hetro_male=[];
            let pups_hetro_female=[];
            let pups_wild_male=[];
            let pups_wild_female=[];
            let weaning_date=[];
            let delivery_id=[];
            let remarks=[];
            $('.dt-homo-male').each(function(index,item){
                pups_homo_male.push($(item).val());
            });
            $('.dt-homo-female').each(function(index,item){
                pups_homo_female.push($(item).val());
            });
            $('.dt-hetro-male').each(function(index,item){
                pups_hetro_male.push($(item).val());
            });
            $('.dt-hetro-female').each(function(index,item){
                pups_hetro_female.push($(item).val());
            });
            $('.dt-wild-male').each(function(index,item){
                pups_wild_male.push($(item).val());
            });
            $('.dt-wild-female').each(function(index,item){
                pups_wild_female.push($(item).val());
            });
            $('.dt-weaned-date').each(function(index,item){
                weaning_date.push($(item).val());
            });
            $('.dt-delivery-id').each(function(index,item){
                delivery_id.push($(item).val());
            });
            $('.dt-weaned-remarks').each(function(index,item){
                remarks.push($(item).val());
            });
            
            
            formData.append('pups_homo_male', JSON.stringify(pups_homo_male));
            formData.append('pups_homo_female', JSON.stringify(pups_homo_female));
            formData.append('pups_hetro_male', JSON.stringify(pups_hetro_male));
            formData.append('pups_hetro_female', JSON.stringify(pups_hetro_female));
            formData.append('pups_wild_male', JSON.stringify(pups_wild_male));
            formData.append('pups_wild_female', JSON.stringify(pups_wild_female));
            formData.append('weaning_date', JSON.stringify(weaning_date));
            formData.append('delivery_id', JSON.stringify(delivery_id));
            formData.append('remarks', JSON.stringify(remarks));
            route = '/weaning-update-mutant/'+id;
            axios.post(route, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function(response) {
                $('#addWeaning').modal('hide');
                Swal.fire({
                    title: 'Good job!',
                    text: 'Weaning Information has been saved',
                    icon: 'success',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }).catch((error) => {
                const errors = error.response.data.errors
                for (let key in errors) { 
                    $('#validation-errors').append('<div class="alert alert-danger" role="alert">'+errors[key][0]+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="right:20px;top:30px;">&times;</button></div');
                }
            });
        });
        const formAddNewRecord = document.getElementById('form-add-new-record');
        fve = FormValidation.formValidation(formAddNewRecord, {
            fields: {
                date_of_ifm: {
                    validators: {
                        notEmpty: {
                            message: 'The Date is required'
                        }
                    }
                },
                breeder_female: {
                    validators: {
                        notEmpty: {
                            message: 'The No of Female is required'
                        }
                    }
                },
                breeder_male: {
                    validators: {
                        notEmpty: {
                            message: 'The No of Male is required'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.col-sm-12'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
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
            var formData = new FormData(formAddNewRecord);
            route = '/breeding-store';
            axios.post(route, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function(response) {
                //document.querySelector('#add-new-record').hide();
                Swal.fire({
                    title: 'Good job!',
                    text: 'Breeding Information has been saved',
                    icon: 'success',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
                //$('.datatables-basic').DataTable().ajax.reload(update_action);
                //update_action();
                window.location.reload(true)
            }).catch((error) => {
                const errors = error.response.data.errors
                for (let key in errors) { 
                    $('#validation-errors').append('<div class="alert alert-danger" role="alert">'+errors[key][0]+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="right:20px;top:30px;">&times;</button></div');
                }
            });
        });
       
    })();

    const formAddNewBreeding = document.getElementById('form-add-new-breeding');
        fve = FormValidation.formValidation(formAddNewBreeding, {
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
        function update_form_delivery(){     
            const formAddNewDelivery = document.getElementById('addBreedingForm');
            window.fnd = FormValidation.formValidation(formAddNewDelivery, {
                fields: {
                    'dt-dob': {
                        selector: '.dt-dob',
                        validators: {
                            notEmpty: {
                                message: 'The Date of Birth is required'
                            }
                        }
                    },
                    'dt-cage-no': {
                        selector: 'input[name^="cage_no["]',
                        validators: {
                            notEmpty: {
                                message: 'The Cage no is required'
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
                    // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
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
                let dob=[];
                let cage_no=[];
                let delivered_females=[];
                let pups=[];
                let remarks=[];
                $('.dt-dob').each(function(index,item){
                    dob.push($(item).val());
                });
                $('.dt-cage-no').each(function(index,item){
                    cage_no.push($(item).val());
                });
                $('.dt-delivered').each(function(index,item){
                    delivered_females.push($(item).val());
                });
                $('.dt-pups').each(function(index,item){
                    pups.push($(item).val());
                });
                $('.dt-remarks').each(function(index,item){
                    remarks.push($(item).val());
                });
                formData.append('date_of_birth', JSON.stringify(dob));
                formData.append('cage_no', JSON.stringify(cage_no));
                formData.append('delivered_females', JSON.stringify(delivered_females));
                formData.append('pups', JSON.stringify(pups));
                formData.append('remarks', JSON.stringify(remarks));
                route = '/delivery-update/'+$(formAddNewDelivery).data('id');
                axios.post(route, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function(response) {
                    $('#addBreeding').modal('hide');
                    Swal.fire({
                        title: 'Good job!',
                        text: 'Delivery Information has been saved',
                        icon: 'success',
                        customClass: {
                        confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                }).catch((error) => {
                    const errors = error.response.data.errors
                    for (let key in errors) { 
                        $('#validation-errors').append('<div class="alert alert-danger" role="alert">'+errors[key][0]+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="right:20px;top:30px;">&times;</button></div');
                    }
                });
            });
        }
        var table = $('.datatables-basic').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pages-breeding') }}",
                    data: function(data) { data.room_id = $('#room_id').val();data.strain_id = $('#strain_id').val();data.colony_id = $('#colony_id').val(); } 
                },
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'date_of_ifm',
                        name: 'date_of_ifm'
                    },
                    {
                        data: 'breeder_male',
                        name: 'breeder_male'
                    },
                    {
                        data: 'breeder_female',
                        name: 'breeder_female'
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
            })
            function update_action(){
                setTimeout(() => {
                    const addDelivery = document.querySelector('.add-delivery');
                    if (addDelivery) {
                        $(document).on('click','.add-delivery',function(e) {
                            axios
                            .get('/breeding-delivery/'+e.currentTarget.dataset.id)
                            .then(res=>{
                                $('#add_date_of_ifm').val(res.data.breeding.date_of_ifm);
                                $('#add_breeder_male').val(res.data.breeding.breeder_male);
                                $('#add_breeder_female').val(res.data.breeding.breeder_female);
                                $('#addBreeding').modal('show');
                                $('#addBreedingForm').data('id',res.data.breeding.id);
                                if(res.data.deliveries){
                                    for (const item of res.data.deliveries) {
                                        let cloneRecord = $('#add_delivered tbody tr:last').clone();
                                        cloneRecord.find('.dt-dob').val(item.date_of_delivery);
                                        cloneRecord.find('.dt-dob').prop('disabled',true);
                                        cloneRecord.find('.dt-cage-no').val(item.cage_no);
                                        cloneRecord.find('.dt-cage-no').prop('disabled',true);
                                        cloneRecord.find('.dt-delivered').val(item.delivery_females);
                                        cloneRecord.find('.dt-delivered').prop('disabled',true);
                                        cloneRecord.find('.dt-pups').val(item.pups_born);
                                        cloneRecord.find('.dt-pups').prop('disabled',true);
                                        cloneRecord.find('.dt-remarks').val(item.remarks);
                                        cloneRecord.find('.dt-remarks').prop('disabled',true);
                                        cloneRecord.find('.delete-delivery').remove();
                                        $("#add_delivered tbody").prepend(cloneRecord);
                                    }
                                }
                                update_form_delivery();
                            })
                            .catch(console.error);
                        
                        });
                    }
                    const addWeaning = document.querySelector('.add-weaning');
                    if (addWeaning) {
                        $(document).on('click','.add-weaning',function(e) {
                            axios
                            .get('/breeding-weaning-mutant/'+e.currentTarget.dataset.id)
                            .then(res=>{
                                $('#weaning_date_of_ifm').val(res.data.breeding.date_of_ifm);
                                $('#weaning_breeder_male').val(res.data.breeding.breeder_male);
                                $('#weaning_breeder_female').val(res.data.breeding.breeder_female);
                                for (const item of res.data.deliveries) {
                                    let cloneRecord = $('#add_weaning tbody tr:last').clone();
                                    cloneRecord.find('.dt-dob').val(item.date_of_delivery);
                                    cloneRecord.find('.dt-dob').prop('disabled',true);
                                    cloneRecord.find('.dt-cage-no').val(item.cage_no);
                                    cloneRecord.find('.dt-cage-no').prop('disabled',true);
                                    cloneRecord.find('.dt-delivered').val(item.delivery_females);
                                    cloneRecord.find('.dt-delivered').prop('disabled',true);
                                    cloneRecord.find('.dt-pups').val(item.pups_born);
                                    cloneRecord.find('.dt-pups').prop('disabled',true);
                                    cloneRecord.find('.dt-delivery-id').val(item.id);
                                    cloneRecord.find('.dt-pups-dead').prop('disabled',true);
                                    if(item.weaning_mutant){
                                        let weaned_homo_female = isNaN(parseInt(item.weaning_mutant.weaned_homo_female)) ? 0 :parseInt(item.weaning_mutant.weaned_homo_female);
                                        let weaned_homo_male = isNaN(parseInt(item.weaning_mutant.weaned_homo_male)) ? 0 :parseInt(item.weaning_mutant.weaned_homo_male);
                                        let weaned_hetro_female = isNaN(parseInt(item.weaning_mutant.weaned_hetro_female)) ? 0 :parseInt(item.weaning_mutant.weaned_hetro_female);
                                        let weaned_hetro_male = isNaN(parseInt(item.weaning_mutant.weaned_hetro_male)) ? 0 :parseInt(item.weaning_mutant.weaned_hetro_male);
                                        let weaned_wild_female = isNaN(parseInt(item.weaning_mutant.weaned_wild_female)) ? 0 :parseInt(item.weaning_mutant.weaned_wild_female);
                                        let weaned_wild_male = isNaN(parseInt(item.weaning_mutant.weaned_wild_male)) ? 0 :parseInt(item.weaning_mutant.weaned_wild_male);
                                        let total_weaned = weaned_homo_female + weaned_homo_male + weaned_hetro_female + weaned_hetro_male + weaned_wild_female + weaned_wild_male;
                                        cloneRecord.find('.dt-homo-male').val(weaned_homo_male);
                                        cloneRecord.find('.dt-homo-male').prop('disabled',true);
                                        cloneRecord.find('.dt-homo-female').val(weaned_homo_female);
                                        cloneRecord.find('.dt-homo-female').prop('disabled',true);
                                        cloneRecord.find('.dt-hetro-male').val(weaned_hetro_male);
                                        cloneRecord.find('.dt-hetro-male').prop('disabled',true);
                                        cloneRecord.find('.dt-hetro-female').val(weaned_hetro_female);
                                        cloneRecord.find('.dt-hetro-female').prop('disabled',true);
                                        cloneRecord.find('.dt-wild-male').val(weaned_wild_male);
                                        cloneRecord.find('.dt-wild-male').prop('disabled',true);
                                        cloneRecord.find('.dt-wild-female').val(weaned_wild_female);
                                        cloneRecord.find('.dt-wild-female').prop('disabled',true);
                                        cloneRecord.find('.dt-weaned-date').val(item.weaning_mutant.date_of_weaned);
                                        cloneRecord.find('.dt-weaned-date').prop('disabled',true);
                                        cloneRecord.find('.dt-weaned-remarks').val(item.weaning_mutant.remarks);
                                        cloneRecord.find('.dt-weaned-remarks').prop('disabled',true);
                                        cloneRecord.find('.dt-pups-weaned').val(total_weaned);
                                        let total_dead = item.pups_born - total_weaned;
                                        cloneRecord.find('.dt-pups-dead').val(total_dead);
                                    }
                                    // cloneRecord.find('.dt-remarks').val(item.remarks);
                                    // cloneRecord.find('.dt-remarks').prop('disabled',true);
                                    cloneRecord.find('.delete-delivery').remove();
                                    $("#add_weaning tbody").prepend(cloneRecord);
                                }
                                if(res.data.deliveries){
                                    $('#add_weaning tbody tr:last').remove();
                                    $('.dt-weaned-date').datepicker({
                                        todayHighlight: true,
                                        format: 'yyyy-mm-dd',
                                        orientation: isRtl ? 'auto right' : 'auto left'
                                    });
                                }
                                $('#addWeaningForm').data('id',res.data.breeding.id);
                                $('#addWeaning').modal('show');
                                
                            })
                            .catch(console.error);
                        
                        });
                    }
                    const showSummary = document.querySelector('.show-summary');
                    if (showSummary) {
                        $(document).on('click','.show-summary',function(e) {
                            axios
                            .get('/breeding-summary-mutant/'+e.currentTarget.dataset.id)
                            .then(res=>{
                                t_dead = 0;
                                t_weaned = 0;
                                t_w_h_female = 0;
                                t_w_h_male = 0;
                                t_w_he_female = 0;
                                t_w_he_male = 0;
                                t_w_w_female = 0;
                                t_w_w_male = 0;
                                tot_w_male = 0;
                                tot_w_female = 0;
                                for (const item of res.data.deliveries) {
                                    t_w_male = 0;
                                    t_w_female = 0;
                                    let cloneRecord = $('#show_weaning tbody tr:last').clone();
                                    cloneRecord.find('.dt-dob-show').html(item.date_of_delivery);
                                    cloneRecord.find('.dt-cage-no').html(item.cage_no);
                                    cloneRecord.find('.dt-delivered').html(item.delivery_females);
                                    cloneRecord.find('.dt-pups').html(item.pups_born);
                                    if(item.weaning_mutant){
                                        let weaned_homo_female = isNaN(parseInt(item.weaning_mutant.weaned_homo_female)) ? 0 :parseInt(item.weaning_mutant.weaned_homo_female);
                                        t_w_h_female += weaned_homo_female; 
                                        t_w_female += weaned_homo_female; 
                                        let weaned_homo_male = isNaN(parseInt(item.weaning_mutant.weaned_homo_male)) ? 0 :parseInt(item.weaning_mutant.weaned_homo_male);
                                        t_w_h_male += weaned_homo_male; 
                                        t_w_male += weaned_homo_male; 
                                        let weaned_hetro_female = isNaN(parseInt(item.weaning_mutant.weaned_hetro_female)) ? 0 :parseInt(item.weaning_mutant.weaned_hetro_female);
                                        t_w_he_female += weaned_hetro_female; 
                                        t_w_female += weaned_hetro_female; 
                                        let weaned_hetro_male = isNaN(parseInt(item.weaning_mutant.weaned_hetro_male)) ? 0 :parseInt(item.weaning_mutant.weaned_hetro_male);
                                        t_w_he_male += weaned_hetro_male; 
                                        t_w_male += weaned_hetro_male; 
                                        let weaned_wild_female = isNaN(parseInt(item.weaning_mutant.weaned_wild_female)) ? 0 :parseInt(item.weaning_mutant.weaned_wild_female);
                                        t_w_w_female += weaned_wild_female; 
                                        t_w_female += weaned_wild_female; 
                                        let weaned_wild_male = isNaN(parseInt(item.weaning_mutant.weaned_wild_male)) ? 0 :parseInt(item.weaning_mutant.weaned_wild_male);
                                        t_w_w_male += weaned_wild_male;
                                        t_w_male += weaned_wild_male; 
                                        let total_weaned = weaned_homo_female + weaned_homo_male + weaned_hetro_female + weaned_hetro_male + weaned_wild_female + weaned_wild_male;
                                        cloneRecord.find('.dt-pups-male').html(t_w_male);
                                        cloneRecord.find('.dt-pups-female').html(t_w_female);
                                        cloneRecord.find('.dt-homo-male').html(weaned_homo_male);
                                        cloneRecord.find('.dt-homo-female').html(weaned_homo_female);
                                        cloneRecord.find('.dt-hetro-male').html(weaned_hetro_male);
                                        cloneRecord.find('.dt-hetro-female').html(weaned_hetro_female);
                                        cloneRecord.find('.dt-wild-male').html(weaned_wild_male);
                                        cloneRecord.find('.dt-wild-female').html(weaned_wild_female);
                                        cloneRecord.find('.dt-weaned-remarks').html(item.weaning_mutant.remarks);
                                        //cloneRecord.find('.dt-pups-weaned').html(total_weaned);
                                        t_weaned += total_weaned;
                                        tot_w_male += t_w_male
                                        tot_w_female += t_w_female
                                        let total_dead = item.pups_born - total_weaned;
                                        t_dead += total_dead;
                                        cloneRecord.find('.dt-pups-dead').html(total_dead);
                                    }
                                    // cloneRecord.find('.dt-remarks').val(item.remarks);
                                    // cloneRecord.find('.dt-remarks').prop('disabled',true);
                                    $("#show_weaning tbody").prepend(cloneRecord);
                                }
                                
                                if(res.data.deliveries){
                                    $('#show_weaning tbody tr:last').remove();
                                    $('#show_weaning tbody').append($('<tr>')
                                    .append($('<td>').append("Total"))
                                    .append($('<td>').append(res.data.no_cages))
                                    .append($('<td>').append(res.data.delivered_females))
                                    .append($('<td>').append(res.data.pups_born))
                                    .append($('<td>').append(t_dead))
                                    //.append($('<td>').append(t_weaned))
                                    .append($('<td>').append(tot_w_male))
                                    .append($('<td>').append(tot_w_female))
                                    .append($('<td>').append(t_w_h_male))
                                    .append($('<td>').append(t_w_h_female))
                                    .append($('<td>').append(t_w_he_male))
                                    .append($('<td>').append(t_w_he_female))
                                    .append($('<td>').append(t_w_w_male))
                                    .append($('<td>').append(t_w_w_female))
                                    .append($('<td>').append(''))
                                    .append($('<td>').append(''))
                                    .append($('<td>').append(''))
                                    .append($('<td>').append(''))
                                    .append($('<td>').append(''))
                                    );
                                }
                                $('#showSummary').modal('show');
                                
                            })
                            .catch(console.error);
                        
                        });
                    }
                }, 200);
            }
            
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
                    <option value="{{ $room->id }}" @if($room->id == $room_id) selected @endif>{{ $room->room_no }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-data px-3">
                    <label for="specie_id" class="form-label text-center w-100">Specie</label>
                    <select id="specie_id" name="specie_id" class="selectpicker dt-specie" style="width:300px;" data-style="btn-default">
                    <option value="">Select Specie</option>
                    @foreach($species as $specie)
                    <option value="{{ $specie->id }}" @if($specie->id == $specie_id) selected @endif>{{ $specie->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-data px-3">
                    <label for="strain_id" class="form-label text-center w-100">Strain</label>
                    <select id="strain_id" name="strain_id" class="selectpicker dt-strain" style="width:300px;" data-style="btn-default">
                    <option value="">Select Strain</option>
                    @foreach($strains as $strain)
                    <option value="{{ $strain->id }}" @if($strain->id == $strain_id) selected @endif>{{ $strain->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-data px-3">
                    <label for="colony_id" class="form-label text-center w-100">Colony</label>
                    <select id="colony_id" name="colony_id" class="selectpicker dt-colony" style="width:300px;" data-style="btn-default">
                    <option value="">Select Colony</option>
                    @foreach($colonies as $colony)
                    <option value="{{ $colony->id }}" @if($colony->id == $colony_id) selected @endif>{{ $colony->name }}</option>
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
    <div class="card-datatable table-responsive">
      <table class="datatables-basic table border-top">
        <thead>
          <tr>
            <th>id</th>
            <th>Date of IFM</th>
            <th>No of Males</th>
            <th>No of Females</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
</div>
<div class="modal fade" id="addBreeding" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user" style="width:1000px;margin: auto;--bs-modal-width:61rem">
      <div class="modal-content p-3 p-md-5" style="width:1000px;">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3>Breeder Information</h3>
          </div>
          <div id="validation-errors" style="position:relative">
          </div>
          <form id="addBreedingForm" class="row g-3" action="" method="POST">
            @csrf
            <div class="col-12 col-md-4 form-data">
                <label class="form-label" for="name">Date of IFM</label>
                <input type="text" id="add_date_of_ifm" name="date_of_ifm" class="form-control" placeholder="YYYY-MM-DD" disabled />
            </div>
            <div class="col-12 col-md-4 form-data">
                <label class="form-label" for="name">No of Males</label>
                <input type="text" id="add_breeder_male" name="breeder_male" class="form-control" placeholder="10" disabled/>
                </div>
            <div class="col-12 col-md-4 form-data">
                <label class="form-label" for="user_name">No of Females</label>
                <input type="text" id="add_breeder_female" name="breeder_female" class="form-control" placeholder="10"  disabled/>
            </div>
            <hr>
            <div class="col-12 col-md-6">
                <button class='add-new-born btn btn-primary btn-sm' type="button"><i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span></button>
            </div>
            <div class="col-12 col-md-6">
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-multilingual table border-top" id="add_delivered">
                  <thead>
                    <tr class="table-active">
                      <th>Date of Birth</th>
                      <th>Cage No.</th>
                      <th>Delivered Female(s)</th>
                      <th>Pups Born</th>
                      <th>Remarks</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                        <td class="form-data align-top">

                            <div class="input-group input-group-merge" style="width:160px">
                                <span id="basicFullname2" class="input-group-text"><i class="bx bx-calendar"></i></span>
                                <input type="text" class="form-control dt-dob" name="date_of_birth[]" placeholder="YYYY-MM-DD" aria-label="YYYY-MM-DD" aria-describedby="basicFullname2" />
                            </div>
                        </td>
                        <td class="form-data align-top">
                            <input type="text" name="cage_no[]" class="form-control dt-cage-no" placeholder="1" />
                        </td>
                        <td class="form-data align-top">
                            <input type="text"  name="delivered_females[]" class="form-control dt-delivered" placeholder="1" />
                        </td>
                        <td class="form-data align-top">
                            <input type="text"  name="pups[]" class="form-control dt-pups" placeholder="1" />
                        </td>
                        <td class="form-data align-top">
                            <textarea  name="remakrs[]" class="form-control dt-remarks"></textarea>
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="delete-delivery btn btn-primary btn-sm ml-2 d-none" style="margin-left:10px;"><i class="bx bx-trash me-sm-1"></i></a>
                        </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
          </form>
         
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="addWeaning" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user" style="width:1400px;margin: auto;--bs-modal-width:100rem">
      <div class="modal-content p-3 p-md-5" style="width:1400px;">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3>Add Weaning Information</h3>
          </div>
          <div id="validation-errors" style="position:relative">
          </div>
          <form id="addWeaningForm" class="row g-3" action="" method="POST">
            @csrf
            <div class="col-12 col-md-4">
                <label class="form-label" for="name">Date of IFM</label>
                <input type="text" id="weaning_date_of_ifm" name="date_of_ifm" class="form-control" placeholder="YYYY-MM-DD" disabled />
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="name">No of Males</label>
                <input type="text" id="weaning_breeder_male" name="breeder_male" class="form-control" placeholder="10" disabled/>
                </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="user_name">No of Females</label>
                <input type="text" id="weaning_breeder_female" name="breeder_female" class="form-control" placeholder="10"  disabled/>
            </div>
            <hr>
            <div class="card-datatable table-responsive">
                <table class="dt-multilingual table border-top" id="add_weaning">
                  <thead>
                    <tr class="table-active">
                      <th rowspan="2">Date of Birth</th>
                      <th rowspan="2">Cage No.</th>
                      <th rowspan="2">Delivered Female(s)</th>
                      <th rowspan="2">Pups Born (in Stock)</th>
                      <th rowspan="2">Pups Died Before Weaning</th>
                      <th rowspan="2">Pups Weaned</th>
                      <th colspan="2">Homozygous</th>
                      <th colspan="2">Heterozygous</th>
                      <th colspan="2">Wild</th>
                      <th rowspan="2">Date of Weaning</th>
                      <th rowspan="2">Remarks</th>
                    </tr>
                    <tr class="table-active">
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                        <td>
                            <input type="hidden" name="delivery_id[]" class="dt-delivery-id" value="" />
                            <div class="input-group input-group-merge" style="width:160px">
                                <span id="basicFullname2" class="input-group-text"><i class="bx bx-calendar"></i></span>
                                <input type="text" class="form-control dt-dob" name="date_of_birth[]" placeholder="YYYY-MM-DD" aria-label="YYYY-MM-DD" aria-describedby="basicFullname2" disabled />
                            </div>
                        </td>
                        <td>
                            <input type="text" name="cage_no[]" class="form-control dt-cage-no" placeholder="1" disabled/>
                        </td>
                        <td>
                            <input type="text"  name="delivered_females[]" class="form-control dt-delivered" placeholder="1" disabled/>
                        </td>
                        <td>
                            <input type="text"  name="pups[]" class="form-control dt-pups" placeholder="1" disabled/>
                        </td>
                        <td>
                            <input type="text"  name="pups_dead[]" class="form-control dt-pups-dead" placeholder="1" disabled/>
                        </td>
                        <td>
                            <input type="text"  name="pups_weaned[]" class="form-control dt-pups-weaned" placeholder="1" disabled/>
                        </td>
                        <td><input type="text"  name="pups_homo_male[]" class="form-control dt-homo-male" style="width:100px" placeholder="1" /></td>
                        <td><input type="text"  name="pups_homo_female[]" class="form-control dt-homo-female"  style="width:100px" placeholder="1" /></td>
                        <td><input type="text"  name="pups_hetro_male[]" class="form-control dt-hetro-male" style="width:100px"  placeholder="1" /></td>
                        <td><input type="text"  name="pups_hetro_female[]" class="form-control dt-hetro-female" style="width:100px"  placeholder="1" /></td>
                        <td><input type="text"  name="pups_wild_male[]" class="form-control dt-wild-male" style="width:100px"  placeholder="1" /></td>
                        <td><input type="text"  name="pups_wild_female[]" class="form-control dt-wild-female" style="width:100px"  placeholder="1" /></td>
                        {{-- <td>
                            <input type="text"  name="pups_male[]" class="form-control dt-pups-male" placeholder="1" />
                        </td>
                        <td>
                            <input type="text"  name="pups_female[]" class="form-control dt-pups-female" placeholder="1" />
                        </td> --}}
                        <td>
                            <input type="text"  name="weaned_date[]" class="form-control dt-weaned-date" style="width:140px" placeholder="YYYY-MM-DD" />
                        </td>
                        <td>
                            <textarea  name="remakrs[]" class="form-control dt-weaned-remarks" style="width:100px" ></textarea>
                        </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            
            <div class="col-12 text-center">
              <button type="button" class="btn btn-primary me-sm-3 me-1" id="weaning_submit">Submit</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
          </form>
         
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="showSummary" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user" style="width:1400px;margin: auto;--bs-modal-width:100rem">
      <div class="modal-content p-3 p-md-5" style="width:1400px;">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3>Issuable Stock Summary</h3>
          </div>
          <div id="validation-errors" style="position:relative">
          </div>
            <div class="card-datatable table-responsive text-nowrap">
                <table class="dt-multilingual table table-bordered" id="show_weaning">
                  <thead>
                    <tr class="table-info">
                      <th rowspan="3" style="width:150px">Date of Birth</th>
                      <th rowspan="3">Cage No.</th>
                      <th rowspan="3" style="white-space: break-spaces !important;">Delivered Female(s)</th>
                      <th rowspan="3" style="white-space: break-spaces !important;">Pups Born (in Stock)</th>
                      <th rowspan="3" style="white-space: break-spaces !important;">Pups Died Before Weaning</th>
                      <th rowspan="1" colspan="2">Pups Weaned</th>
                      <th rowspan="1" colspan="2">Homozygous</th>
                      <th rowspan="1" colspan="2">Heterozygous</th>
                      <th rowspan="1" colspan="2">Wild</th>
                      <th colspan="5">Issuable Stock</th>
                    </tr>
                    <tr class="table-info">
                        <th rowspan="2" class="align-middle">M</th>
                        <th rowspan="2" class="align-middle">F</th>
                        <th rowspan="2" class="align-middle">M</th>
                        <th rowspan="2" class="align-middle">F</th>
                        <th rowspan="2" class="align-middle">M</th>
                        <th rowspan="2" class="align-middle">F</th>
                        <th rowspan="2" class="align-middle">M</th>
                        <th rowspan="2" class="align-middle">F</th>
                        <th rowspan="2" class="align-middle">Pups</th>
                        <th colspan="2" class="align-middle">Homozygous</th>
                        <th colspan="2" class="align-middle">Heterozygous</th>
                    </tr>
                    <tr class="table-info">
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                        <td>
                            <span class="dt-dob-show"></span>
                        </td>
                        <td>
                            <span class="dt-cage-no"></span>
                        </td>
                        <td>
                            <span class="dt-delivered"></span>
                        </td>
                        <td>
                            <span class="dt-pups"></span>
                        </td>
                        <td>
                            <span class="dt-pups-dead"></span>
                        </td>
                        {{-- <td>
                            <span class="dt-pups-weaned"></span>
                        </td> --}}
                        <td>
                            <span class="dt-pups-male"></span>
                        </td>
                        <td>
                            <span class="dt-pups-female"></span>
                        </td>
                        <td>
                            <span class="dt-homo-male"></span>
                        </td>
                        <td>
                            <span class="dt-homo-female"></span>
                        </td>
                        <td>
                            <span class="dt-hetro-male"></span>
                        </td>
                        <td>
                            <span class="dt-hetro-female"></span>
                        </td>
                        <td>
                            <span class="dt-wild-male"></span>
                        </td>
                        <td>
                            <span class="dt-wild-female"></span>
                        </td>
                        <td> <span class="dt-pups-issuable"></span></td>
                        <td> <span class="dt-homo-male-issuable"></span></td>
                        <td> <span class="dt-homo-female-issuable"></span></td>
                        <td> <span class="dt-hetro-male-issuable"></span></td>
                        <td> <span class="dt-hetro-female-issuable"></span></td>
                        {{-- <td>
                            <span class="dt-weaned-remarks"></span>
                        </td> --}}
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="col-12 text-center">
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
              </div>
         
        </div>
      </div>
    </div>
</div>
<div class="offcanvas offcanvas-end" id="add-new-record">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="exampleModalLabel">New Breeding</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
    <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST" action="{{ route('breeding_store') }}">
        @csrf
        <input type="hidden" value="{{ $room_id }}" name="room_id" />
        <input type="hidden" value="{{ $specie_id }}" name="specie_id" />
        <input type="hidden" value="{{ $strain_id }}" name="strain_id" />
        <input type="hidden" value="{{ $colony_id }}" name="colony_id" />
        <div class="col-sm-12">
          <label class="form-label" for="basicFullname">Date of IFM</label>
          <div class="input-group input-group-merge">
            <span id="basicFullname2" class="input-group-text"><i class="bx bx-user"></i></span>
            <input type="text" id="date_of_ifm" class="form-control dt-date-of-ifm" name="date_of_ifm" placeholder="YYYY-MM-DD" aria-label="2024-10-10" aria-describedby="basicFullname2" />
          </div>
        </div>
        <div class="col-sm-12">
            <label class="form-label" for="basicFullname">No of Females</label>
            <div class="input-group input-group-merge">
              <span id="basicFullname3" class="input-group-text"><i class="bx bx-user"></i></span>
              <input type="text" id="breeder_female" class="form-control dt-breeder-female" name="breeder_female" placeholder="10" aria-label="10" aria-describedby="basicFullname3" />
            </div>
        </div>
        <div class="col-sm-12">
            <label class="form-label" for="basicFullname">No of Males</label>
            <div class="input-group input-group-merge">
              <span id="basicFullname4" class="input-group-text"><i class="bx bx-user"></i></span>
              <input type="text" id="breeder_male" class="form-control dt-breeder-male" name="breeder_male" placeholder="10" aria-label="10" aria-describedby="basicFullname4" />
            </div>
        </div>
        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>
  
    </div>
</div>
@endsection