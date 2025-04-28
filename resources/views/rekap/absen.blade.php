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
            <th>Jumlah Diluar Radius</th>
            <th>Total Hari Kerja</th>
            <th>Total Menit Telat</th>
            <th>Total Hari Telat</th>
            <th>Tidak Absen Pulang (Hari)</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <tr>
            <th>Jabatan</th>
            <th>Nama Petugas</th>
            <th>Jumlah Diluar Radius</th>
            <th>Total Hari Kerja</th>
            <th>Total Menit Telat</th>
            <th>Total Hari Telat</th>
            <th>Tidak Absen Pulang (Hari)</th>
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
            "order": [[0, 'asc'],[1, 'asc']],
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "ajax": "{{ env('APP_URL') }}/rekap/load_rekap?tahun="+tahun+"&bulan="+bulan,
            'createdRow': function( row, data, dataIndex ) {
                $(row).attr('id', data.uuid);
            },
            "columns": [
                {"data": "jabatan"},
                {"data": "name"},
                {"data": "diluar_radius"},
                {"data": "total_hari"},
                {"data": "total_menit_telat"},
                {"data": "total_hari_telat"},
                {"data": "tidak_absen_pulang"},
            ],
            "pageLength": 300,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows','All']
            ],
            "dom": 'Blfrtip',
            "buttons": ["pageLength", "copy", "excel", "pdf"]
        }).buttons().container().appendTo('#tabelRekap_wrapper .col-md-6:eq(0)');
    }

    $('#tabelRekap tbody').on('click', 'tr', function () {
        redirectUrl = "{{ route('rekap.detail_user') }}?user_id="+this.id;
        window.location.href = redirectUrl;
    });

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
            url: "{{ env('APP_URL') }}/rekap/load_rekap?bulan="+bulan+"&tahun="+tahun,
            success: function (response) {
                $('#total_hari').html(response.total_hari+" / 20");
                $('#total_menit_telat').html(response.total_menit_telat+" Menit ("+ Math.floor(response.total_menit_telat/450)+" Hari)");
                $('#diluar_radius').html(response.diluar_radius+" Kali");
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

</script>

@endsection
