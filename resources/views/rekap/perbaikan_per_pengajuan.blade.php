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
      <div class="mb-2">
        <div class="icheck-primary d-inline mr-3">
            <input type="checkbox" id="checkAllPerbaikan">
            <label for="checkAllPerbaikan">Check all</label>
        </div>
        <button type="button" id="btnSetujuiSemua" class="btn btn-sm btn-success">
            <i class="fas fa-check"></i> Setujui semua
        </button>
        <span class="badge badge-secondary ml-2"><span id="selectedCount">0</span> dipilih</span>
      </div>
      <table id="tabelRekap" class="table table-sm table-bordered table-hover"  style="cursor:pointer">
        <thead>
        <tr>
          <th>Pilih</th>
          <th>Tanggal Pengajuan</th>
          <th>Tanggal Masuk</th>
          <th>Nama Petugas</th>
          <th>Tipe Absen</th>
          <th>Tipe Perbaikan</th>
          <th>Jam Sebelumnya</th>
          <th>Jarak Sebelumnya</th>
          <th>Alasan</th>
          <th>Link Surat Tugas</th>
          <th>Keterangan Sekretariat</th>
          <th>Status</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <tr>
          <th>Pilih</th>
          <th>Tanggal Pengajuan</th>
          <th>Tanggal Masuk</th>
          <th>Nama Petugas</th>
          <th>Tipe Absen</th>
          <th>Tipe Perbaikan</th>
          <th>Jam Sebelumnya</th>
          <th>Jarak Sebelumnya</th>
          <th>Alasan</th>
          <th>Link Surat Tugas</th>
          <th>Keterangan Sekretariat</th>
          <th>Status</th>
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
                        Tipe Absen : <span id="tipe_absen_label"></span> <br>
                        <span id="jam_sebelumnya"></span> <br>
                        <span id="jarak_sebelumnya"></span><br>
                        Alasan : <span id="alasan"></span> <br>
                        Link Surat Tugas : <span id="link_surat_tugas"></span> <br>
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
                            <input type="hidden" id="tipe_absen_input">
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
    let selectedUuids = [];

    $(function () {
      load_data();
    });

    function formatTanggal(data, withTime) {
        const date = new Date(data);
        const options = {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        };

        if (withTime) {
            options.hour = 'numeric';
            options.minute = 'numeric';
            options.second = 'numeric';
            options.hour12 = false;
        }

        return date.toLocaleString('id-ID', options);
    }

    function badgeStatus(disetujui) {
        if (disetujui == 1) {
            return '<span class="badge badge-success">Disetujui</span>';
        }

        if (disetujui == 0) {
            return '<span class="badge badge-danger">Tidak Disetujui</span>';
        }

        return '<span class="badge badge-primary">Belum Disetujui</span>';
    }

    function setPersetujuanValue(disetujui) {
        const value = disetujui == 0 ? '0' : '1';
        $('input[name="disetujui"][value="'+value+'"]').prop('checked', true);
    }

    function addSelectedUuid(uuid) {
        if (selectedUuids.indexOf(uuid) === -1) {
            selectedUuids.push(uuid);
        }
    }

    function removeSelectedUuid(uuid) {
        selectedUuids = selectedUuids.filter(function (item) {
            return item !== uuid;
        });
    }

    function updateSelectedCount() {
        $('#selectedCount').html(selectedUuids.length);
    }

    function refreshTableKeepPage() {
        if ($.fn.DataTable.isDataTable('#tabelRekap')) {
            $('#tabelRekap').DataTable().ajax.reload(null, false);
        } else {
            load_data();
        }
    }

    function load_data() {
        $('#filters').modal('hide');
        selectedUuids = [];
        $('#checkAllPerbaikan').prop('checked', false);
        updateSelectedCount();
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
        const table = $('#tabelRekap').DataTable({
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
            "ajax": "{{ route('rekap.load_perbaikan_per_pengajuan') }}?tahun="+tahun+"&bulan="+bulan,
            "drawCallback": function () {
                $('.perbaikan-checkbox').each(function () {
                    $(this).prop('checked', selectedUuids.indexOf($(this).val()) !== -1);
                });

                const totalCheckbox = $('.perbaikan-checkbox:not(:disabled)').length;
                const totalChecked = $('.perbaikan-checkbox:not(:disabled):checked').length;
                $('#checkAllPerbaikan').prop('checked', totalCheckbox > 0 && totalCheckbox == totalChecked);
                updateSelectedCount();
            },
            "columns": [
                {
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        if (row.disetujui !== null) {
                            return '<input type="checkbox" class="perbaikan-checkbox" value="'+row.uuid+'" disabled>';
                        }

                        const checked = selectedUuids.indexOf(row.uuid) !== -1 ? 'checked' : '';
                        return '<input type="checkbox" class="perbaikan-checkbox" value="'+row.uuid+'" '+checked+'>';
                    }
                },
                {
                    "data": "created_at"
                },
                {"data": "tanggal_masuk",
                    "render": function (data) {
                        if (!data) return '-';
                        return formatTanggal(data, false);
                    }

                },
                {"data": "nama"},
                {"data": "tipe_absen"},
                {"data": "tipe_perbaikan"},
                {
                    "data": "jam_sebelumnya",
                    "render": function (data) {
                        return data ? data : '-';
                    }
                },
                {
                    "data": "jarak_sebelumnya",
                    "render": function (data) {
                        return data ? data : '-';
                    }
                },
                {"data": "alasan"},
                {
                    "data": "link_surat_tugas",
                    "render": function (data) {
                        if (!data) return '-';
                        return '<a href="'+data+'" target="_blank">'+data+'</a>';
                    }
                },
                {
                    "data": "keterangan_pic",
                    "render": function (data) {
                        return data ? data : '-';
                    }
                },
                {
                    "data": "disetujui",
                    "render": function (data) {
                        return badgeStatus(data);
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
        });
        table.buttons().container().appendTo('#tabelRekap_wrapper .col-md-6:eq(0)');

        $('#tabelRekap tbody').off('click', 'tr').on('click', 'tr', function (event) {
            if ($(event.target).closest('a,button,input,label').length) {
                return;
            }

            const uuid = $(this).attr('id');
            if (uuid) {
                load_perbaikan(uuid);
            }
        });

        $('#tabelRekap tbody').off('change', '.perbaikan-checkbox').on('change', '.perbaikan-checkbox', function () {
            if ($(this).is(':checked')) {
                addSelectedUuid($(this).val());
            } else {
                removeSelectedUuid($(this).val());
            }

            const totalCheckbox = $('.perbaikan-checkbox:not(:disabled)').length;
            const totalChecked = $('.perbaikan-checkbox:not(:disabled):checked').length;
            $('#checkAllPerbaikan').prop('checked', totalCheckbox > 0 && totalCheckbox == totalChecked);
            updateSelectedCount();
        });
    }

    $('#checkAllPerbaikan').change(function () {
        const checked = $(this).is(':checked');

        $('.perbaikan-checkbox:not(:disabled)').each(function () {
            $(this).prop('checked', checked);
            if (checked) {
                addSelectedUuid($(this).val());
            } else {
                removeSelectedUuid($(this).val());
            }
        });

        updateSelectedCount();
    });

    $('#btnSetujuiSemua').click(function () {
        if (selectedUuids.length == 0) {
            Swal.fire({
                icon: "warning",
                title: "Belum ada data dipilih",
                text: "Pilih minimal satu pengajuan perbaikan absen."
            });
            return;
        }

        Swal.fire({
            title: "Setujui semua pengajuan terpilih?",
            text: selectedUuids.length + " pengajuan akan disetujui.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Setujui",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            Swal.fire({
                title: "Proses...",
                position: "center",
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick:false
            });

            let token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: "{{ route('absen.bulk_setujui_perbaikan') }}",
                type: "POST",
                cache: false,
                data: {
                    uuids: selectedUuids,
                    _token: token
                },
                success: function (response) {
                    selectedUuids = [];
                    $('#checkAllPerbaikan').prop('checked', false);
                    refreshTableKeepPage();
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                        position: "center"
                    });
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
    });

    function load_perbaikan(uuid) {
        Swal.fire({
            title: "Proses...",
            position: "center",
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick:false
        });

        $.ajax({
            type: "GET",
            url: "{{ route('absen.load_perbaikan') }}?uuid=" + uuid,
            success: function (response) {
                $('#uuid').val(response.uuid);
                $('#uuid_absen').val(response.uuid_absen);
                $('#tipe_absen_input').val(response.tipe_absen);
                $('#keterangan_pic').val(response.keterangan_pic);
                $('#disetujui').html(badgeStatus(response.disetujui));
                setPersetujuanValue(response.disetujui);
                $('#created_at_perbaikan').html(formatTanggal(response.created_at, true));
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
                if (response.link_surat_tugas) {
                    $('#link_surat_tugas').html('<a href="'+response.link_surat_tugas+'" target="_blank">'+response.link_surat_tugas+'</a>');
                } else {
                    $('#link_surat_tugas').html('-');
                }
                $('#tipe_absen_label').html(response.tipe_absen);
                $('#keterangan_pic_html').html(response.keterangan_pic ? response.keterangan_pic : '-');
                $('#perbaikan').modal('show');
                $('#formpersetujuan').show();

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
                        tipe_absen: $("#tipe_absen_input").val(),
                        disetujui: $('input[name="disetujui"]:checked').val(),
                        keterangan_pic: $("#keterangan_pic").val(),
                        _token: token
                    },
                    success: function (response){
                        $('#perbaikan').modal('hide');
                        refreshTableKeepPage();
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
