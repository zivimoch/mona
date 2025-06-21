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
          Bulan : <span id="filters_bulan"></span> 
        </span> 
        <span class="badge badge-primary badge-lg jam_agenda">
            Tahun : <span id="filters_tahun"></span> 
        </span>
    </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="overflow-x: scroll">
      <table id="tabelRekap" class="table table-sm table-bordered table-hover"  style="cursor:pointer">
        <thead>
        <tr>
        <th>Jabatan</th>
          <th>Nama Petugas</th>
          <th>Rules</th>
          <th>Tanggal Masuk</th>
          <th>Jam Masuk</th>
          <th>Jarak Masuk</th>
          {{-- <th>Catatan Masuk</th> --}}
          <th>Tanggal Pulang</th>
          <th>Jam Pulang</th>
          <th>Jarak Pulang</th>
          <th>Terlambat (menit)</th>
          <th>Perbaikan</th>
          {{-- <th>Catatan Pulang</th> --}}
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <tr>
            <th>Jabatan</th>
          <th>Nama Petugas</th>
          <th>Rules</th>
          <th>Tanggal Masuk</th>
          <th>Jam Masuk</th>
          <th>Jarak Masuk</th>
          {{-- <th>Catatan Masuk</th> --}}
          <th>Tanggal Pulang</th>
          <th>Jam Pulang</th>
          <th>Jarak Pulang</th>
          <th>Terlambat (menit)</th>
          <th>Perbaikan</th>
              {{-- <th>Catatan Pulang</th> --}}
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
                        <label for="">Bulan</label>
                        <select id="filter_bulan" class="form-control">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
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

    <!-- Modal Detail-->
    <div class="modal fade" id="perbaikan" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Permohonan Perbaikan Absen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
                <div class="modal-body">
                    <div class="card-body">
                        Petugas melalukan permohonan perbaikan absen pada tanggal <span id="created_at_perbaikan"></span> <br><br>
                        Tipe Perbaikan : <span id="tipe_perbaikan"></span> <br>
                        Tipe Absen : <span id="tipe_absen"></span> <br>
                        <span id="jam_sebelumnya"></span> <br>
                        <span id="jarak_sebelumnya"></span><br>
                        Alasan : <span id="alasan"></span> <br>
                        Status : <span id="disetujui"></span><br>
                        Keterangan Sekretariat : <span id="keterangan_pic_html"></span><br>
                        <br>
                        <div id="formpersetujuan">
                            <div class="form-group">
                                <label>Persetujuan : </label>
                                <br>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioPrimary1" name="disetujui" checked value="1">
                                    <label for="radioPrimary1">
                                        Setujui
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioPrimary2" name="disetujui" value="0">
                                    <label for="radioPrimary2">
                                        Tidak Setujui
                                    </label>
                                </div>
                            </div>
                            <div class="form-group"> 
                                <label>Keterangan Sekretariat : </label>
                                <input id="keterangan_pic" type="text" class="form-control">
                            </div>
                            <input type="hidden" id="uuid">
                            <input type="hidden" id="uuid_absen">
                            <input type="hidden" id="tipe_absen">
                            <button id="simpan_perbaikan" type="button" class="btn btn-block btn-primary">Submit</button>
                        </div>
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
<script>
    $(function () {
      load_data();
      load_rekap();
    });

    function load_data() {
        load_rekap();
        $('#filters').modal('hide');
        let tahun = $('#filter_tahun').val();
        let bulan = $('#filter_bulan').val();
        // set filter badges
        $('#filters_bulan').html(bulan);
        $('#filters_tahun').html(tahun);
        // Hancurkan DataTable jika sudah ada
        if ($.fn.DataTable.isDataTable('#tabelRekap')) {
            $('#tabelRekap').DataTable().destroy();
        }

        // Inisialisasi ulang DataTable
        $('#tabelRekap').DataTable({
            "ordering": true,
            "order": [],
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            'createdRow': function( row, data, dataIndex ) {
                $(row).attr('id', data.uuid);
            },
            "ajax": "{{ env('APP_URL') }}/rekap/load_detail_user?perbaikan=1&tahun="+tahun+"&bulan="+bulan,
            "columns": [
                {"data": "jabatan"},
                {"data": "name"},
                {"data": "rules"},
                {"data": "tanggal_masuk"},
                {"data": "jam_masuk"},
                {"data": "jarak_masuk"},
                // {"data": "catatan_masuk"},
                {"data": "tanggal_pulang"},
                {"data": "jam_pulang"},
                {"data": "jarak_pulang"},
                // {"data": "catatan_pulang"},
                {"data": "menit_terlambat"},
                {
                    "data": "perbaikans",
                    "render": function (data, type, row, meta) {
                        if (!data) return '';
                        
                        var items = data.split(',');
                        if (items.length > 0) {
                            var html = '';
                            var no = 1;
                            items.forEach(function(item) {
                                var parts = item.split(':');

                                if (parts[2] == 1) {
                                    html += '<a href="#" onclick="load_perbaikan(`'+parts[0]+'`)" class="badge badge-success badge-lg jam_agenda">'+no+'. Perbaikan Absen '+parts[1]+' Disetujui</a>'
                                } else if (parts[2] == 0) {
                                    html += '<a href="#" onclick="load_perbaikan(`'+parts[0]+'`)" class="badge badge-danger badge-lg jam_agenda">'+no+'. Perbaikan Absen '+parts[1]+' Tidak Disetujui</a><br>'
                                } else {
                                    html += '<a href="#" onclick="load_perbaikan(`'+parts[0]+'`)" class="badge badge-primary badge-lg jam_agenda">'+no+'. Perbaikan Absen '+parts[1]+' Belum Disetujui</a><br>'
                                }
                                no++;
                            });
                            return html;
                        }

                    }
                }
            ],
            "pageLength": 25,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows','All']
            ],
            "dom": 'Blfrtip',
            "buttons": ["pageLength", "copy", "excel", "pdf"]
        }).buttons().container().appendTo('#tabelRekap_wrapper .col-md-6:eq(0)');
    }

    function load_rekap() {
        $('#filters').modal('hide');
        Swal.fire({
            title: "Proses...",
            position: "center",
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick:false
        });
        bulan = $('#filter_bulan').val();
        tahun = $('#filter_tahun').val();
        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL') }}/rekap/load_rekap?uuid={{ request()->user_id }}&bulan=" + bulan + "&tahun=" + tahun,
            success: function (response) {
                $('#total_hari').html(response.total_hari);
                $('#total_menit_telat').html(response.total_menit_telat+" Menit ("+ Math.floor(response.total_menit_telat/480)+" Hari)");
                $('#diluar_radius').html(response.diluar_radius+" Kali");
                $('#tidak_absen_pulang').html(response.tidak_absen_pulang+" Kali");
                Swal.close();
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Terjadi kesalahan. Silakan hubungi admin.",
                    });
                }
            });
        }

        function load_perbaikan(uuid) {

            $.ajax({
            type: "GET",
            url: "{{ env('APP_URL') }}/absen/load_perbaikan?uuid=" + uuid,
            success: function (response) {
                $('#uuid').val(response.uuid);
                $('#uuid_absen').val(response.uuid_absen);
                $('#tipe_absen').val(response.tipe_absen);
                $('#keterangan_pic').val(response.keterangan_pic);
                if (response.disetujui == 1) {
                    $('#disetujui').html('<span class="badge badge-success">Disetujui</span>');
                } else if (response.disetujui == 0) {
                    $('#disetujui').html('<span class="badge badge-danger">Tidak Disetujui</span>');
                } else {
                    $('#disetujui').html('<span class="badge badge-primary">Belum Disetujui</span>');
                    
                }
                const createdAt = new Date(response.created_at);
                const options = {
                    day: 'numeric',
                    month: 'short', // 'short' will use abbreviated month names like "Jun"
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                    hour12: false,
                };
                const formattedDate = createdAt.toLocaleString('id-ID', options);
                $('#created_at_perbaikan').html(formattedDate);
                $('#tipe_perbaikan').html(response.tipe_perbaikan);
                if (response.jam_sebelumnya != null) {
                    $('#jam_sebelumnya').html("Jam Sebelumnya : "+response.jam_sebelumnya);
                } else {
                    $('#jam_sebelumnya').html('Jam Sebelumnya : Tidak Dirubah');
                }
                if (response.jarak_sebelumnya != null) {
                    $('#jarak_sebelumnya').html("Jarak Sebelumnya : "+response.jarak_sebelumnya);
                } else {
                    $('#jarak_sebelumnya').html('Jarak Sebelumnya : Tidak Dirubah');
                }
                $('#alasan').html(response.alasan);
                $('#tipe_absen').html(response.tipe_absen);
                $('#keterangan_pic_html').html(response.keterangan_pic);
                $('#perbaikan').modal('show');

                if (response.disetujui == null) {
                    $('#formpersetujuan').show();
                } else {
                    $('#formpersetujuan').hide();
                }

                Swal.close();
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Terjadi kesalahan. Silakan hubungi admin.",
                    });
                }
            });
        }

        $('#simpan_perbaikan').click(function() {
                Swal.fire({
                    title: "Proses...",
                    position: "center",
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick:false
                });
                
                let token   = $("meta[name='csrf-token']").attr("content");

                $.ajax({
                    url: "{{ route('absen.store_perbaikan') }}",
                    type: "POST",
                    cache: false,
                    data: {
                        uuid: $("#uuid").val(),
                        uuid_absen: $("#uuid_absen").val(),
                        tipe_absen: $("#tipe_absen").val(),
                        disetujui: $('input[name="disetujui"]:checked').val(),
                        keterangan_pic: $("#keterangan_pic").val(),
                        _token: token
                    },
                    success: function (response){
                        $('#perbaikan').modal('hide');
                        load_data();
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil di simpan",
                            showConfirmButton: false,
                            timer: 1500,
                            position: "center"
                        });
                        Swal.close();
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Terjadi kesalahan. Silakan hubungi admin.",
                        });
                    }
                });
            });
</script>

@endsection
