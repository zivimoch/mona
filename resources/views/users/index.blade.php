@extends('layouts.template')

@section('content')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">


<div class="card">
    <!-- /.card-header -->
    <div class="card-body" style="overflow-x: scroll">
      <table id="tabelUsers" class="table table-sm table-bordered table-hover"  style="cursor:pointer">
        <thead>
        <tr>
            <th>Jabatan</th>
            <th>Nama Petugas</th>
            <th>Email</th>
            <th>Koordinat Kantor</th>
            <th>Sisa Cuti</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <tr>
            <th>Jabatan</th>
            <th>Nama Petugas</th>
            <th>Email</th>
            <th>Koordinat Kantor</th>
            <th>Sisa Cuti</th>
        </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

   <!-- Modal Users-->
   <div class="modal fade" id="modalUsers" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Data Users</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
            <div class="modal-body">
                <input type="hidden" id="uuid">
                <div class="form-group">
                    <label for="">Nama Lengkap</label>
                    <input type="text" id="nama" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="">Jabatan</label>
                    <input type="text" id="jabatan" class="form-control" disabled>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Latitude Kantor</label>
                        <input type="text" class="form-control" id="kantor_latitude">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Longitude Kantor</label>
                        <input type="text" class="form-control" id="kantor_longitude">
                      </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Sisa Cuti</label>
                    <input type="text" id="sisa_cuti" class="form-control">
                </div>
            <button type="button" onclick="confirmSubmit()" class="btn btn-block btn-primary">Simpan</button>
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
        // Hancurkan DataTable jika sudah ada
        if ($.fn.DataTable.isDataTable('#tabelUsers')) {
            $('#tabelUsers').DataTable().destroy();
        }

        // Inisialisasi ulang DataTable
        $('#tabelUsers').DataTable({
            "ordering": true,
            "order": [[0, 'asc'],[1, 'asc']],
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "ajax": "{{ env('APP_URL') }}/users/load_data",
            'createdRow': function( row, data, dataIndex ) {
                $(row).attr('id', data.uuid);
            },
            "columns": [
                {"data": "jabatan"},
                {"data": "name"},
                {"data": "email"},
                {
                    "data": "koordinat", 
                    "render": function (data, type, row) {
                        return row.kantor_latitude+', '+row.kantor_longitude;
                    }
                },
                {"data": "sisa_cuti"},
            ],
            "pageLength": 300,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows','All']
            ],
            "dom": 'Blfrtip',
            "buttons": ["pageLength", "copy", "excel", "pdf"]
        }).buttons().container().appendTo('#tabelUsers_wrapper .col-md-6:eq(0)');
    }

    $('#tabelUsers tbody').on('click', 'tr', function () {
        load_detail_user(this.id);
    });

    function load_detail_user(uuid) {
        $.ajax({
            url: "{{ route('users.show') }}?uuid="+uuid,
            type: "GET",
            success: function (response){
                $('#uuid').val(uuid);
                $('#nama').val(response.name);
                $('#jabatan').val(response.jabatan);
                $('#kantor_latitude').val(response.kantor_latitude);
                $('#kantor_longitude').val(response.kantor_longitude);
                $('#sisa_cuti').val(response.sisa_cuti);

                $('#modalUsers').modal('show');

            },
            error: function (response){
                modal_error(response);
            }
            }).done(function() { 
        });
    }

    function confirmSubmit() {
        let message = "Apakah Anda yakin ingin menyimpan data ini?";
        if (confirm(message)) {
            simpanUser();
        }
    }

    function simpanUser() {
        let token   = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "{{ route('users.store') }}",
            type: "POST",
            cache: false,
            data: {
                uuid: $('#uuid').val(),
                kantor_latitude: $('#kantor_latitude').val(),
                kantor_longitude: $('#kantor_longitude').val(),
                sisa_cuti: $('#sisa_cuti').val(),
                _token: token
            },
            success: function (response){
                load_data();
                $('#modalUsers').modal('hide');
            },
            error: function (response){
                modal_error(response);
            }
            }).done(function() { 
        });
    };

</script>

@endsection
