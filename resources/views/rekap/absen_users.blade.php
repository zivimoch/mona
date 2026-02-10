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
        <div class="row">
        <div class="col-md-3">
            <div class="alert alert-info alert-dismissible">
                <h5 id="rata_menit_hadir">x Menit</h5>
                Rata-Rata Menit Hadir per Hari
              </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-info alert-dismissible">
                <h5 id="total_hari">x Hari</h5>
                Jumlah Hari Kerja
              </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-info alert-dismissible">
                <h5 id="total_menit_telat">x Menit (x Hari)</h5>
                Jumlah Menit Terlambat
              </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-info alert-dismissible">
                <h5 id="diluar_radius">x Kali</h5>
                Diluar Radius
              </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-info alert-dismissible">
                <h5 id="tidak_absen_pulang">x Kali</h5>
                Tidak Absen Pulang
              </div>
        </div>
    </div>
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
          {{-- <th>Catatan Pulang</th> --}}
          <th>Terlambat (menit)</th>
          <th>Perbaikan</th>
          <th>Alasan Perbaikan</th>
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
              {{-- <th>Catatan Pulang</th> --}}
              <th>Terlambat (menit)</th>
              <th>Perbaikan</th>
            <th>Alasan Perbaikan</th>
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
                                <option value="{{ $i }}" {{ $i == (request('bulan') ?? date('n')) ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Tahun</label>
                        <select id="filter_tahun" class="form-control">
                            @for ($year = 2025; $year <= date('Y'); $year++)
                                <option value="{{ $year }}" {{ $year == (request('tahun') ?? date('Y')) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
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

        <!-- Modal Detail Absen-->
    <div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Detail Absen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
                <div class="modal-body">
                    <input type="hidden" id="detail_uuid">
                        <div class="form-group">
                            <label>Jabatan : </label>
                            <input id="detail_jabatan" type="text" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap : </label>
                            <input id="detail_name" type="text" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Rules : </label>
                            <select name="kode_shift_rules" id="kode_shift_rules" class="form-control">
                                @foreach ($rules as $rule)
                                    <option value="{{ $rule->kode }}">{{ $rule->judul }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Masuk : </label>
                            <input id="detail_tanggal_masuk" type="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Jam Masuk : </label>
                            <input id="detail_jam_masuk" type="time" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Jarak Masuk : </label>
                            <input id="detail_jarak_masuk" type="number" class="form-control">
                        </div>
                        <div class="form-group">
                            <input id="detail_catatan_masuk" type="hidden" class="form-control" value="Diperbaiki oleh sekretariat karena lewat waktu perbaikan.">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Pulang : </label>
                            <input id="detail_tanggal_pulang" type="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Jam Pulang : </label>
                            <input id="detail_jam_pulang" type="time" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Jarak Pulang : </label>
                            <input id="detail_jarak_pulang" type="number" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Terlambat (menit) : </label>
                            <input id="detail_menit_terlambat" type="number" class="form-control">
                        </div>
                        <div class="form-group">
                            <input id="detail_catatan_pulang" type="hidden" class="form-control" value="Diperbaiki oleh sekretariat karena lewat waktu perbaikan.">
                        </div>
                        <div class="form-group">
                            <button id="simpan_absen" type="button" class="btn btn-block btn-primary">Submit</button>
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
            "ajax": "{{ env('APP_URL') }}/rekap/load_detail_user?user_id={{ request()->user_id }}&tahun="+tahun+"&bulan="+bulan,
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
                },
                {"data": "alasan"}
            ],
            "pageLength": 25,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows','All']
            ],
            "dom": 'Blfrtip',
            "buttons": ["pageLength", "copy", "excel", "pdf"]
        }).buttons().container().appendTo('#tabelRekap_wrapper .col-md-6:eq(0)');

        @if (Auth::user()->jabatan == 'Sekretariat')
            $('#tabelRekap tbody').on('click', 'tr', function (evt) {
                if (![10].includes($(evt.target).closest('td').index())) {
                    var table = $('#tabelRekap').DataTable();
                    var rowData = table.row(this).data();   
                    showModalDetail(rowData);
                }
            });
        @endif
    }

    function showModalDetail(data) {
        console.log(data);
        
        $('#detail_uuid').val(data.uuid);
        $('#detail_jabatan').val(data.jabatan);
        $('#detail_name').val(data.name);
        $('#detail_kode_shift_rules').val(data.kode_shift_rules).trigger('change');
        $('#detail_tanggal_masuk').val(data.tanggal_masuk);
        $('#detail_jam_masuk').val(data.jam_masuk);
        $('#detail_jarak_masuk').val(data.jarak_masuk);
        $('#detail_tanggal_pulang').val(data.tanggal_pulang);
        $('#detail_jam_pulang').val(data.jam_pulang);
        $('#detail_jarak_pulang').val(data.jarak_pulang);
        $('#detail_menit_terlambat').val(data.menit_terlambat);
        
        $('#modal_detail').modal('show');
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
                $('#rata_menit_hadir').html(response.rata_menit_hadir+ " Menit ("+ Math.floor(response.rata_menit_hadir/60)+" Jam)");
                $('#total_hari').html(response.total_hari + " Hari");
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

                if (response.disetujui == null && '{{ auth()->user()->jabatan }} '== 'Sekretariat') {
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

        $('#simpan_absen').click(function() {
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
                    url: "{{ route('absen.update_absen') }}",
                    type: "POST",
                    cache: false,
                    data: {
                        uuid: $("#detail_uuid").val(),
                        kode_shift_rules: $("#kode_shift_rules").val(),
                        tanggal_masuk: $("#detail_tanggal_masuk").val(),
                        jam_masuk: $("#detail_jam_masuk").val(),
                        jarak_masuk: $("#detail_jarak_masuk").val(),
                        catatan_masuk: $("#detail_catatan_masuk").val(),
                        tanggal_pulang: $("#detail_tanggal_pulang").val(),
                        jam_pulang: $("#detail_jam_pulang").val(),
                        jarak_pulang: $("#detail_jarak_pulang").val(),
                        menit_terlambat: $("#detail_menit_terlambat").val(),
                        catatan_pulang: $("#detail_catatan_pulang").val(),
                        _token: token
                    },
                    success: function (response){
                        $('#modal_detail').modal('hide');
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
