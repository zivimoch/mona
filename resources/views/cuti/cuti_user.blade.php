@extends('layouts.template')

@section('content')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">


<div class="card">
    <div class="card-header">
      <div class="container-fluid"  data-toggle="modal" data-target="#filters">
        <span class="badge badge-primary badge-lg jam_agenda">
            <i class="fas fa-filter"></i> Filters
        </span>
        <span class="badge badge-primary badge-lg jam_agenda">
            Tahun : <span id="filters_tahun"></span> 
        </span>
    </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="overflow-x: scroll">
      <table id="tabelCuti" class="table table-sm table-bordered table-hover"  style="cursor:pointer">
        <thead>
        <tr>
            <th>Jabatan</th>
            <th>Nama Petugas</th>
            <th>Tanggal Cuti</th>
            <th>Jumlah Hari Yang Diajukan</th>
            <th>Sisa Cuti Sebelumnya</th>
            <th>Tanggal Diajukan</th>
            <th>Persetujuan</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <tr>
            <th>Jabatan</th>
            <th>Nama Petugas</th>
            <th>Tanggal Cuti</th>
            <th>Jumlah Hari Yang Diajukan</th>
            <th>Sisa Cuti Sebelumnya</th>
            <th>Tanggal Diajukan</th>
            <th>Persetujuan</th>
        </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

      <!-- Modal Filter-->
      <div class="modal fade" id="filters" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Filters</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tahun</label>
                        <select id="filter_tahun" class="form-control">
                            @php
                                $currentYear = date('Y');
                                $startYear = 2025;
                                $endYear = date('Y');
                            @endphp
                            @for ($year = $startYear; $year <= $endYear; $year++)
                                @php
                                    $selected = ($year == $currentYear) ? 'selected' : '';
                                @endphp
                                <option value="{{ $year }}" {{ $selected }}>{{ $year }}</option>
                            @endfor
                        </select>
                </div>
                <button type="button" class="btn btn-block btn-primary" onclick="load_data()" >Tampilkan</button>

            </div>
        </div>
        </div>
    </div>

    <!-- Modal Tambah Pengajuan CUti-->
    <div class="modal fade" id="permohonan_cuti" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Formulir Permohonan Cuti</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
                <div class="modal-body">
                    <input type="hidden" id="uuid">
                    <div class="form-group">
                        <label for="">No Telp</label>
                        <input type="text" id="no_telp" class="form-control required-field-cuti" value="{{ Auth::user()->no_telp }}">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat Domisili Saat Ini</label>
                        <textarea id="alamat_domisili" class="form-control required-field-cuti" rows="1">{{ Auth::user()->alamat }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal Cuti</label>
                        <input type="text" id="tanggal_cuti" class="form-control required-field-cuti" >
                        *sisa <span id="jumlah_cuti"></span> hari
                    </div>
                    <div class="form-group">
                        <label for="">Alamat Selama Cuti</label>
                        <textarea id="alamat_selama_cuti" class="form-control required-field-cuti" rows="1"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Alasan Cuti</label>
                        <textarea id="alasan" class="form-control required-field-cuti" rows="1"></textarea>
                    </div>
                    <div id="url" class="callout callout-success" style="display: none">
                        <h5>Permohonan cuti berhasil, mohon mengikuti petunjuk di bawah ini : </h5>
                        <p>
                            <ol>
                                <li>Salin link di bawah ini :</li>
                                <div class="input-group">
                                    <input type="text" id="url_permohonan_cuti" class="form-control" readonly value="" style="color:blue;">
                                    <span class="input-group-append">
                                      <button type="button" class="btn btn-primary" onclick="copyTextExportData('url_permohonan_cuti')">Salin</button>
                                    </span>
                                </div>
                                <li>Berikan link pada atasan anda untuk ditanda tangani.</li>
                                <li>Setelah semua selesai menandatangani, konfirmasi pada PIC cuti di sekretariat.</li>
                                <li>PIC cuti di sekretariat menyetujui cuti.</li>
                            </ol>
                        </p>
                    </div>
                <button type="button" id="submitCuti" class="btn btn-block btn-primary">Submit</button>
                {{-- <button type="button" id="hapusCuti" class="btn btn-block btn-danger"><i class="fa fa-trash"></i> Hapus Permohonan Cuti Ini</button> --}}

            </div>
        </div>
        </div>
    </div>

<!-- DataTables  & Plugins -->
<script src="{{ asset('adminlte') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/jszip/jszip.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/pdfmake/pdfmake.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/pdfmake/vfs_fonts.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function () {
        load_data();

        var totalDay = {{ $sisa_cuti }}; // Get max selectable days
        var selectedDates = []; // Store selected dates
        $('#jumlah_cuti').html(totalDay);
        $('#tanggal_cuti').datepicker({
            format: "yyyy-mm-dd",
            multidate: true, // Enable multiple date selection
            todayHighlight: true,
            autoclose: false
        }).on('changeDate', function (e) {
            selectedDates = e.dates.map(date => date.toISOString().split('T')[0]); // Format dates
            if (selectedDates.length > totalDay) {
                alert("You can only select up to " + totalDay + " dates.");
                selectedDates.pop(); 
                $(this).datepicker('setDates', selectedDates); 
            }
            $('#jumlah_cuti').html(totalDay-selectedDates.length);

            $(this).val(selectedDates.join(', '));
        });
    });


    function load_data() {
        // load_rekap();
        $('#filters').modal('hide');
        let tahun = $('#filter_tahun').val();
        let bulan = $('#filter_bulan').val();
        // set filter badges
        $('#filters_bulan').html(bulan);
        $('#filters_tahun').html(tahun);
        // Hancurkan DataTable jika sudah ada
        if ($.fn.DataTable.isDataTable('#tabelCuti')) {
            $('#tabelCuti').DataTable().destroy();
        }

        // Inisialisasi ulang DataTable
        $('#tabelCuti').DataTable({
            "ordering": true,
            "order": [[0, 'asc'],[1, 'asc']],
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "ajax": "{{ env('APP_URL') }}/cuti/load_detail?tahun=" + tahun + "&user_id={{ request()->user_id }}",
            'createdRow': function( row, data, dataIndex ) {
                $(row).attr('id', data.uuid);
            },
            "columns": [
                {"data": "jabatan"},
                {"data": "name"},
                {"data": "tanggal_cuti"},
                {"data": "hari_diajukan"},
                {"data": "sisa_hari_sebelumnya"},
                {"data": "created_at"},
                {
                    "data": "disetujui", 
                    "render": function (data, type, row) {
                        if (data == 1) {
                            return '<span class="badge badge-success badge-lg jam_agenda">Disetujui</span>'
                        } else if (data == 0) {
                            return '<span class="badge badge-danger badge-lg jam_agenda">Tidak Disetujui</span>'
                        } else {
                            return '<span class="badge badge-primary badge-lg jam_agenda">Belum Disetujui</span>'
                        }
                    }
                },
            ],
            "pageLength": 300,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows','All']
            ],
            "dom": 'Blfrtip',
            "buttons": ["pageLength", "copy", "csv", "excel", "pdf"]
                .concat(
                '{{ $akses }}' == 1 ? [{
                    className: "btn-info",
                    text: 'Tambah',
                    action: function () {
                        $('#tanggal_cuti').prop('disabled', false);
                        $('#alamat_selama_cuti').prop('disabled', false);
                        $('#alasan').prop('disabled', false);
                        $('#no_telp').prop('disabled', false);
                        $('#alamat_domisili').prop('disabled', false);
                        $('#uuid').val('');
                        $('#tanggal_cuti').val('');
                        $('#alamat_selama_cuti').val('');
                        $('#alasan').val('');
                        $("#submitCuti").show();
                        $("#hapusCuti").hide();
                        $('#url').hide();

                        $('#permohonan_cuti').modal('show');
                    }
                }] : []
                )
            }).buttons().container().appendTo('#tabelCuti_wrapper .col-md-6:eq(0)');
    }

    $('#tabelCuti tbody').on('click', 'tr', function () {
        load_detail_pengajuan(this.id);
    });

    function load_detail_pengajuan(uuid) {
        $.ajax({
                url: "{{ route('cuti.load_detail_pengajuan') }}?uuid="+uuid,
                type: "GET",
                success: function (response){
                    $('#uuid').val(uuid);
                    $('#tanggal_cuti').val(response.tanggal_cuti);
                    $('#alamat_selama_cuti').val(response.alamat_selama_cuti);
                    $('#alasan').val(response.alasan);

                    // disabled all
                    $('#tanggal_cuti').prop('disabled', true);
                    $('#alamat_selama_cuti').prop('disabled', true);
                    $('#alasan').prop('disabled', true);
                    $('#almat_saat_cuti').prop('disabled', true);
                    $('#no_telp').prop('disabled', true);
                    $('#alamat_domisili').prop('disabled', true);
                    $("#submitCuti").hide();
                    $("#hapusCuti").show();
                    $('#url_permohonan_cuti').val(`{{ route("cuti.permohonan") }}?uuid=${response.uuid}`);
                    $("#url").show();

                    $('#permohonan_cuti').modal('show');

                },
                error: function (response){
                    modal_error(response);
                }
                }).done(function() { 
            });
    }

    // function load_rekap() {
    //     $('#filters').modal('hide');
    //     Swal.fire({
    //         title: "Proses...",
    //         position: "center",
    //         didOpen: () => {
    //             Swal.showLoading();
    //         },
    //         allowOutsideClick:false
    //     });
    //     bulan = $('#filter_bulan').val();
    //     tahun = $('#filter_tahun').val();
    //     $.ajax({
    //         type: "GET",
    //         url: "{{ env('APP_URL') }}/rekap/load_rekap?bulan="+bulan+"&tahun="+tahun,
    //         success: function (response) {
    //             $('#total_hari').html(response.total_hari+" / 20");
    //             $('#total_menit_telat').html(response.total_menit_telat+" Menit ("+ Math.floor(response.total_menit_telat/450)+" Hari)");
    //             $('#diluar_radius').html(response.diluar_radius+" Kali");
    //             Swal.close();
    //         },
    //         error: function (xhr, status, error) {
    //             Swal.fire({
    //                 icon: "error",
    //                 title: "Oops...",
    //                 text: "Terjadi kesalahan. Silakan hubungi admin.",
    //                 });
    //             }
    //         });
    //     }

    $('#submitCuti').click(function() {
        if(validateForm('cuti')){
            let token   = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: "{{ route('cuti.store') }}",
                type: "POST",
                cache: false,
                data: {
                    uuid: $('#uuid').val(),
                    no_telp: $('#no_telp').val(),
                    alamat_domisili: $('#alamat_domisili').val(),
                    tanggal_cuti: $('#tanggal_cuti').val(),
                    alamat_selama_cuti: $('#alamat_selama_cuti').val(),
                    alasan: $('#alasan').val(),
                    _token: token
                },
                success: function (response){
                    // $('#permohonan_cuti').modal('hide');
                    load_data();

                    $('#uuid').val('');
                    $('#tanggal_cuti').val('');
                    $('#alamat_selama_cuti').val('');
                    $('#alasan').val('');    
                    load_detail_pengajuan(response.uuid);
                    $("#submitCuti").hide();
                    $("#url").show();
                },
                error: function (response){
                    modal_error(response);
                }
                }).done(function() { 
            });
        } else {
            modal_error('Semua field wajib diisi!');
        }
    });

    function copyTextExportData(exportDataId) {
        var textInput = document.getElementById(exportDataId);
        textInput.select();
        textInput.setSelectionRange(0, 99999); // For mobile compatibility
        document.execCommand("copy");
        alert("Text berhasil dicopy ke clipboard: " + textInput.value);
    }

</script>

@endsection
