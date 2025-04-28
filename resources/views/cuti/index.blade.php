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
            <th>Alasan Cuti</th>
            <th>Alamat Selama Cuti</th>
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
            <th>Alasan Cuti</th>
            <th>Alamat Selama Cuti</th>
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
        <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Formulir Permohonan Cuti</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
                <div class="modal-body">
                    <input type="hidden" id="uuid">
                    <div class="centered">
                        <iframe class="embed-responsive-item" id="suratPermohonanCuti" src="about:blank" style="width: 100%; height:1000px"></iframe>
                      </div>
                      <br>
                    <div class="row" id="approval">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-block btn-success" onclick="confirmSubmit(1)"><i class="fas fa-check"></i> Setujui Permohonan Cuti</button>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button type="button" class="btn btn-block btn-danger" onclick="confirmSubmit(0)"><i class="fas fa-times"></i> Tidak Setujui Permohonan Cuti</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-block btn-danger" onclick="hapusSubmit()"><i class="fas fa-trash"></i> Hapus Permohonan Cuti Ini</button>
                        </div>
                    </div>
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
    });


    function load_data() {
        // load_rekap();
        $('#filters').modal('hide');
        let tahun = $('#filter_tahun').val();
        // set filter badges
        $('#filters_tahun').html(tahun);
        // Hancurkan DataTable jika sudah ada
        if ($.fn.DataTable.isDataTable('#tabelCuti')) {
            $('#tabelCuti').DataTable().destroy();
        }

        // Inisialisasi ulang DataTable
        $('#tabelCuti').DataTable({
            "ordering": true,
            "order": [],
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "ajax": "{{ env('APP_URL') }}/cuti/load_detail?tahun=" + tahun,
            'createdRow': function( row, data, dataIndex ) {
                $(row).attr('id', data.uuid);
            },
            "columns": [
                {"data": "jabatan"},
                {"data": "name"},
                {"data": "tanggal_cuti"},
                {"data": "hari_diajukan"},
                {"data": "sisa_hari_sebelumnya"},
                {"data": "alasan"},
                {"data": "alamat_selama_cuti"},
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
            }).buttons().container().appendTo('#tabelCuti_wrapper .col-md-6:eq(0)');
    }

    $('#tabelCuti tbody').on('click', 'tr', function () {
        load_detail_pengajuan(this.id);
    });

    function load_detail_pengajuan(uuid) {
        //tampilkan surat permohonan cuti serverside
        url = '{{ route("cuti.permohonan") }}?uuid='+uuid;
        $('#suratPermohonanCuti').attr('src', url);

        $.ajax({
                url: "{{ route('cuti.load_detail_pengajuan') }}?uuid="+uuid,
                type: "GET",
                success: function (response){
                    $('#uuid').val(uuid);
                    $('#tanggal_cuti').val(response.tanggal_cuti);
                    $('#alamat_selama_cuti').val(response.alamat_selama_cuti);
                    $('#alasan').val(response.alasan);
                    if (response.disetujui == null) {
                        $('#approval').show();
                    } else {
                        $('#approval').hide();
                    }

                    // disabled all
                    $('#tanggal_cuti').prop('disabled', true);
                    $('#alamat_selama_cuti').prop('disabled', true);
                    $('#alasan').prop('disabled', true);
                    $('#almat_saat_cuti').prop('disabled', true);
                    $('#no_telp').prop('disabled', true);
                    $('#alamat_domisili').prop('disabled', true);
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

    function confirmSubmit(status) {
        let message = status === 1 ? "Apakah Anda yakin ingin menyetujui permohonan cuti?" : "Apakah Anda yakin ingin menolak permohonan cuti?";
        if (confirm(message)) {
            submitCuti(status);
        }
    }
    function submitCuti(disetujui) {
        if(validateForm('cuti')){
            let token   = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: "{{ route('cuti.persetujuan') }}",
                type: "POST",
                cache: false,
                data: {
                    uuid: $('#uuid').val(),
                    disetujui: disetujui,
                    _token: token
                },
                success: function (response){
                    load_data();
                    $('#permohonan_cuti').modal('hide');
                },
                error: function (response){
                    modal_error(response);
                }
                }).done(function() { 
            });
        } else {
            modal_error('Semua field wajib diisi!');
        }
    };

    function copyTextExportData(exportDataId) {
        var textInput = document.getElementById(exportDataId);
        textInput.select();
        textInput.setSelectionRange(0, 99999); // For mobile compatibility
        document.execCommand("copy");
        alert("Text berhasil dicopy ke clipboard: " + textInput.value);
    }

    function hapusSubmit() {
        let message = "Apakah Anda yakin ingin menghapus permohonan cuti ini? Jika anda menghapus permohonan cuti ini maka absen dengan label cuti di user juga akan terhapus.";
        if (confirm(message)) {
            let token   = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: "{{ route('cuti.destroy') }}",
                type: "POST",
                cache: false,
                data: {
                    uuid: $('#uuid').val(),
                    _method:'DELETE',
                    _token: token
                },
                success: function (response){
                    load_data();
                    $('#permohonan_cuti').modal('hide');
                },
                error: function (response){
                    modal_error(response);
                }
                }).done(function() { 
            });
        }
    }

</script>

@endsection
