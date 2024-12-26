@extends('layouts.template')

@section('content')
    <style>
        .tanggal_agenda {
            font-size: 20px;
            font-weight: bold;
        }
        .jam_agenda {
            font-size: 15px;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header" style="margin-bottom:5px">
        {{-- <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>HOME</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Layout</a></li>
                <li class="breadcrumb-item active">Fixed Layout</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid --> --}}
        <div class="container-fluid">
            <span class="badge badge-primary badge-lg jam_agenda" onclick="alert('modal filters')">
                <i class="fas fa-filter"></i> Filters
            </span>
            <span class="badge badge-primary badge-lg jam_agenda" onclick="alert('modal filters')">
                Bulan : Januari
            </span>
            <span class="badge badge-primary badge-lg jam_agenda" onclick="alert('modal filters')">
                Tahun : 2024
            </span>
            <span class="badge badge-primary badge-lg jam_agenda" onclick="alert('modal filters')">
                Tanggal tidak masuk : Sembunyikan 
            </span>
        </div>
      </section>
  
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                <a href="#" onclick="alert('Silahkan absen pulang terlebih dahulu')">
                    <div style="position: relative;">
                        <span style="position: absolute; left: 10px; transform: translateY(-50%); background-color: #f8f9fa; padding: 0 10px; z-index:1000"><i class="fas fa-plus" style="margin-right:5px"></i> Tambah absen di hari yang sama</span>
                        <div style="position: absolute; left: 0; right: 0; height: 1px; background-color: #000;"></div>
                    </div>
                </a>
                <br>
                <div id="list_absen"></div>
                  <!-- Default box -->
                  <div class="card">
                    <div class="card-header bg-success" data-card-widget="collapse">
                        <table>
                            <tr>
                                <td>
                                    <div class="widget-user-image" onclick="input_absen()" style="margin-right: 10px; position: relative; border: 3px solid #ffffff; border-radius: 50%;">
                                        <img class="img-circle elevation-2" src="{{ asset('adminlte') }}/dist/img/user7-128x128.jpg" style="max-width:58px; border-radius: 50%;">
                                        <i class="fas fa-check-circle" style="position: absolute; bottom: 5px; right: -7px; font-size: 20px;"></i>
                                    </div>
                                </td>
                                <td>
                                    <span class="widget-user-username tanggal_agenda">24 Dec 2024</span>
                                    <br>
                                    <span class="badge badge-warning badge-lg jam_agenda">
                                        <i class="far fa-clock"></i> 07:30:00
                                    </span>
                                </td>
                            </tr>
                        </table>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <ul class="list-unstyled" style="margin :-10px 0px">
                            <li class="d-flex align-items-start border-bottom py-2">
                                <div class="mr-2">
                                    <div class="icheck-primary">
                                        <input type="checkbox" value="" id="todoCheck1">
                                        <label for="todoCheck1"></label>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge badge-warning badge-lg" style="font-size: 13px;">
                                        <i class="far fa-clock"></i> 07:30:00
                                    </span><br>
                                    <b>Pemeriksaan Psikologis Ke-2</b>
                                </div>
                            </li>
                            <li class="d-flex align-items-start border-bottom py-2">
                                <div class="mr-2">
                                    <div class="icheck-primary">
                                        <input type="checkbox" value="" id="todoCheck2">
                                        <label for="todoCheck2"></label>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge badge-warning badge-lg" style="font-size: 13px;">
                                        <i class="far fa-clock"></i> 07:30:00
                                    </span><br>
                                    <b>Pemeriksaan Psikologis Ke-2</b>
                                </div>
                            </li>
                            <li class="d-flex align-items-start border-bottom py-2">
                                <div class="mr-2">
                                    <div class="icheck-primary">
                                        <input type="checkbox" value="" id="todoCheck2">
                                        <label for="todoCheck2"></label>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge badge-warning badge-lg" style="font-size: 13px;">
                                        <i class="far fa-clock"></i> 07:30:00
                                    </span><br>
                                    <b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates nisi porro molestias doloribus delectus voluptatem</b>
                                </div>
                            </li>
                            <li class="d-flex align-items-start border-bottom py-2">
                                <div class="mr-2">
                                    <div class="icheck-primary">
                                        <input type="checkbox" value="" id="todoCheck2">
                                        <label for="todoCheck2"></label>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge badge-warning badge-lg" style="font-size: 13px;">
                                        <i class="far fa-clock"></i> 07:30:00
                                    </span><br>
                                    <b>Pemeriksaan Psikologis Ke-2</b>
                                </div>
                            </li>
                        </ul>
                        
                        
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-secondary">
                        <table>
                            <tr>
                                <td>
                                    <div class="widget-user-image" onclick="input_absen()" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                        <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                    </div>
                                </td>
                                <td>
                                    <span class="widget-user-username tanggal_agenda">-</span>
                                    <br>
                                    <span class="badge badge-warning badge-lg jam_agenda">
                                        <i class="far fa-clock"></i> -
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                  </div>


                  <!-- Default box -->
                <div class="card collapsed-card">
                    <div class="card-header bg-secondary" data-card-widget="collapse">
                    <table>
                        <tr>
                        <td>
                            <div class="widget-user-image" onclick="input_absen()" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs disabled"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                            </div>
                        </td>
                        <td>
                            <span class="widget-user-username tanggal_agenda">24 Dec 2024</span>
                            <br>
                            <span class="badge badge-warning badge-lg jam_agenda">
                            <i class="far fa-clock"></i> -
                            </span>
                        </td>
                        </tr>
                    </table>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    <ul class="list-unstyled" style="margin :-10px 0px">
                        <li class="d-flex align-items-start border-bottom py-2">
                        <div class="mr-2">
                            <div class="icheck-primary">
                            <input type="checkbox" value="" id="todoCheck1">
                            <label for="todoCheck1"></label>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <span class="badge badge-warning badge-lg" style="font-size: 13px;">
                            <i class="far fa-clock"></i> 07:30:00
                            </span><br>
                            <b>Pemeriksaan Psikologis Ke-2</b>
                        </div>
                        </li>
                        <!-- Other list items here -->
                    </ul>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-secondary">
                    <table>
                        <tr>
                        <td>
                            <div class="widget-user-image" onclick="input_absen()" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs disabled" ><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                            </div>
                        </td>
                        <td>
                            <span class="widget-user-username tanggal_agenda">-</span>
                            <br>
                            <span class="badge badge-warning badge-lg jam_agenda">
                            <i class="far fa-clock"></i> -
                            </span>
                        </td>
                        </tr>
                    </table>
                    </div>
                </div>

            <!-- Default box -->
            <div class="card collapsed-card">
            <div class="card-header bg-warning" data-card-widget="collapse">
            <table>
                <tr>
                <td>
                    <div class="widget-user-image" onclick="input_absen()" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                        <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs disabled"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                    </div>
                </td>
                <td>
                    <span class="widget-user-username tanggal_agenda">24 Dec 2024</span>
                    <br>
                    <span class="badge badge-danger badge-lg jam_agenda">
                        Cuti
                    </span>
                </td>
                </tr>
            </table>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <ul class="list-unstyled" style="margin :-10px 0px">
                <li class="d-flex align-items-start border-bottom py-2">
                <div class="mr-2">
                    <div class="icheck-primary">
                    <input type="checkbox" value="" id="todoCheck1">
                    <label for="todoCheck1"></label>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <span class="badge badge-warning badge-lg" style="font-size: 13px;">
                    <i class="far fa-clock"></i> 07:30:00
                    </span><br>
                    <b>Pemeriksaan Psikologis Ke-2</b>
                </div>
                </li>
                <!-- Other list items here -->
            </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer bg-secondary">
            <table>
                <tr>
                <td>
                    <div class="widget-user-image" onclick="input_absen()" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                        <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs disabled"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                    </div>
                </td>
                <td>
                    <span class="widget-user-username tanggal_agenda">-</span>
                    <br>
                    <span class="badge badge-warning badge-lg jam_agenda">
                    <i class="far fa-clock"></i> -
                    </span>
                </td>
                </tr>
            </table>
            </div>
        </div>
  
                </div>
              </div>
    </section>
    <!-- /.content -->
    <!-- Modal Absen-->
    <div class="modal fade" id="absen" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="modal_title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
                <div class="modal-body">
                    <div class="widget-user-image d-flex justify-content-center align-items-center mb-2" onclick="capture()">
                        <button type="file" class="rounded-circle overflow-hidden bg-success d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; border: 5px solid #9b9999; padding: 0;">
                            <img id="foto" src="{{ asset('img') }}/camera.png" alt="Photo" style="width: 150px; max-height: 200px; object-fit: cover;">
                        </button>
                        <input id="foto_file" onchange="get_latlong()" type="file" accept="image/*" capture="environtment" hidden></input>
                    </div>
                    <div class="form-group" id="metadata_absen" style="display: none">
                        <div id="maps"></div>
                        <span id="distance"></span>
                        <span id="waktu" style="display: none"></span>
                        <span id="distance_warning"></span>
                        <input id="catatan" type="text" class="form-control mt-2" placeholder="Keterangan..." style="display: none">
                    </div>
                    <button id="simpan" type="submit" class="btn btn-block btn-primary" disabled>Simpan</button>
                </div>
        </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>

    <script>
        $(document).ready(function () {
            load_tanggal();
            var currentDate = "{{ \Carbon\Carbon::now()->format('d M Y') }}"; // Get current date from Blade
            function updateTime() {
                var time = new Date().toLocaleTimeString(); // Get current time in local format
                $("#waktu").html("<b>Waktu :</b> " + currentDate + ", " + time + "<br>");
            }
            setInterval(updateTime, 1000); // Update every second
        });


        function load_tanggal() {
                // $('#overlayListAgenda').show();
                // filter = "month="+month+"&year="+year;
                filter = null;
                $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL') }}/absen/load_tanggal?"+filter,
                    success: function (response) {
                        let hari_ini = @json(now()->toDateString());;
                        disabled_button_masuk = disabled_button_pulang = '';

                        response.forEach(e => {
                        if (e.tanggal != hari_ini) {
                            collapsed = 'collapsed-card';
                        } else {
                            collapsed = '';
                        }
                        // masuk
                        if (e.tanggal_masuk != null) {
                            card_masuk = 'success';
                            button_masuk = `<div class="widget-user-image" onclick="alert('Data absen tidak dapat dirubah')" style="margin-right: 10px; position: relative; border: 3px solid #ffffff; border-radius: 50%;">
                                        <img class="img-circle elevation-2" src="{{ asset('img') }}/absen/${e.foto_masuk}" style="max-width:58px; border-radius: 50%;">
                                        <i class="fas fa-check-circle" style="position: absolute; bottom: 5px; right: -7px; font-size: 20px;"></i>
                                    </div>`;

                        } else if (e.cuti == 1) {
                            card_masuk = 'warning';
                            button_masuk = `<div class="widget-user-image" onclick="input_absen('masuk', '${e.tanggal}', '${e.tanggal_masuk}') "style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                        <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs disabled"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                    </div>`;
                        } else {
                            card_masuk = 'secondary';
                            if (e.tanggal != hari_ini) {
                                disabled_button_masuk = 'disabled';
                            } 
                            button_masuk = `<div class="widget-user-image" onclick="input_absen('masuk', '${e.tanggal}', '${e.tanggal_masuk}')" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                        <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs ${disabled_button_masuk}"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                    </div>`;
                        }
                        // pulang
                        if (e.tanggal_pulang != null) {
                            card_pulang = 'success';
                            button_pulang = `<div class="widget-user-image" onclick="alert('Data absen tidak dapat dirubah')" style="margin-right: 10px; position: relative; border: 3px solid #ffffff; border-radius: 50%;">
                                        <img class="img-circle elevation-2" src="{{ asset('img') }}/absen/${e.foto_pulang}" style="max-width:58px; border-radius: 50%;">
                                        <i class="fas fa-check-circle" style="position: absolute; bottom: 5px; right: -7px; font-size: 20px;"></i>
                                    </div>`;
                        } else if (e.cuti == 1) {
                            card_pulang = 'warning';
                            button_pulang = `<div class="widget-user-image" onclick="input_absen('pulang', '${e.tanggal}', '${e.tanggal_masuk}')" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                        <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs disabled"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                    </div>`;
                        } else {
                            card_pulang = 'secondary';
                            if (e.tanggal != hari_ini && e.tanggal_masuk == null) {
                                disabled_button_pulang = 'disabled';
                            } else {
                                disabled_button_pulang = '';
                            }
                            button_pulang = `<div class="widget-user-image" onclick="input_absen('pulang', '${e.tanggal}', '${e.tanggal_masuk}')" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                        <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs ${disabled_button_pulang}"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                    </div>`;
                        }
                        
                        var html = `
                            <div class="card ${collapsed}">
                                <div class="card-header bg-${card_masuk}" data-card-widget="collapse">
                                    <table>
                                        <tr>
                                            <td>
                                                ${button_masuk}
                                            </td>
                                            <td>
                                                <span class="widget-user-username tanggal_agenda"><i class="fas fa-sign-in-alt" style="transform: scaleX(-1);"></i> ${e.tanggal_human}</span>
                                                <br>
                                                <span class="badge badge-warning badge-lg jam_agenda">
                                                    <i class="far fa-clock"></i> ${e.jam_masuk}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <ul class="list-unstyled" style="margin :-10px 0px">
                                        <li class="d-flex align-items-start border-bottom py-2">
                                            <div class="mr-2">
                                                <div class="icheck-primary">
                                                    <input type="checkbox" value="" id="todoCheck1">
                                                    <label for="todoCheck1"></label>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="badge badge-warning badge-lg" style="font-size: 13px;">
                                                    <i class="far fa-clock"></i> ${e.jam_detail}
                                                </span><br>
                                                <b>${e.kegiatan}</b>
                                            </div>
                                        </li>
                                        <!-- Other list items can be dynamically added here -->
                                    </ul>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer bg-${card_pulang}">
                                    <table>
                                        <tr>
                                            <td>
                                                ${button_pulang}
                                            </td>
                                            <td>
                                                <span class="widget-user-username tanggal_agenda"><i class="fas fa-sign-out-alt""></i> ${e.tanggal_pulang_human}</span>
                                                <br>
                                                <span class="badge badge-warning badge-lg jam_agenda">
                                                    <i class="far fa-clock"></i> ${e.jam_pulang}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        `;

                        $('#list_absen').append(html);
                        });
                    }
                });
            }

            function load_modal() {
                var foto = $('#foto');
                foto.attr('src',"{{ asset('img') }}/camera.png");
                $('#foto_file').val(''); // reset
                $('#metadata_absen').hide();
                $('#simpan').prop('disabled', true);
                $('#absen').modal('show');
            }

            function input_absen(tipe, tanggal_input, tanggal_masuk) {
                event.stopPropagation();
                let hari_ini = @json(now()->toDateString());;
                if (tipe == 'masuk' && tanggal_input != hari_ini) {
                    alert('Gagal! Absen masuk tidak dapat dilakukan karena sudah lewat hari.'); 
                } else if (tipe == 'pulang' && tanggal_masuk == 'null') {
                    alert('Gagal! Absen pulang tidak dapat dilakukan karena belum absen masuk.');
                } else {
                    load_modal();
                }

                if (tipe == 'masuk') {
                    $('#modal_title').html('<i class="fas fa-sign-in-alt" style="transform: scaleX(-1);"></i> Absen Masuk');
                } else {
                    $('#modal_title').html('<i class="fas fa-sign-out-alt""></i> Absen Pulang');
                }
            }

            function get_latlong() {
                const file = event.target.files[0];
                var foto = $('#foto');
                if (file) {
                    $('#metadata_absen').show();
                    $('#simpan').prop('disabled', false);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // ubah image menjadi foto
                        foto.attr('src', e.target.result); 

                        const image = new Image();
                        image.onload = function() {
                            EXIF.getData(image, function() {
                                // tempat harusnya kerja
                                kantor_latitude = '{{ Auth::user()->kantor_latitude }}'; 
                                kantor_longitude = '{{ Auth::user()->kantor_longitude }}'; 
                                // lokasi sesuai gps
                                // my_latitude = '-6.190253199201834'; // nanti dihapus
                                // my_longitude = '106.90609272642648'; // nanti dihapus
                                var my_latitude = EXIF.getTag(image, "GPSLatitude");
                                var my_longitude = EXIF.getTag(image, "GPSLongitude");
                                var latitudeRef = EXIF.getTag(image, "GPSLatitudeRef"); // 'N' or 'S'
                                var longitudeRef = EXIF.getTag(image, "GPSLongitudeRef"); // 'E' or 'W'

                                // Convert latitude and longitude from DMS to decimal
                                my_latitude = convertToDecimal(my_latitude);
                                my_longitude = convertToDecimal(my_longitude);

                                // Adjust latitude and longitude based on hemisphere
                                if (latitudeRef === 'S') {
                                    my_latitude = -my_latitude; // Southern Hemisphere
                                }

                                if (longitudeRef === 'W') {
                                    my_longitude = -my_longitude; // Western Hemisphere
                                }

                                distance = hitung_jarak(kantor_latitude, kantor_longitude, my_latitude, my_longitude);
                                if (distance > 100) {
                                    distance_warning = '<span class="badge badge-danger jam_agenda"><i class="fas fa-times-circle"></i> Lokasi absen diluar radius (>100 m)</span><br>';
                                    $('#catatan').show();

                                } else {
                                    distance_warning = '<span class="badge badge-success jam_agenda"><i class="fas fa-check-circle"></i> Lokasi absen dalam radius</span><br>';
                                    $('#catatan').hide();
                                }
                                $("#distance").html('<b>Jarak :</b> '+distance.toFixed(2)+' meter<br>');
                                $("#waktu").show();
                                $("#distance_warning").html(distance_warning);

                                if (my_latitude && my_longitude) {
                                    load_map(my_latitude, my_longitude);
                                } else {
                                    distance_warning = '<span class="badge badge-danger jam_agenda"><i class="fas fa-times-circle"></i> Lokasi tidak ditemukan, nyalakan GPS</span><br>';
                                    $("#distance_warning").html(distance_warning);
                                    $('#catatan').show();
                                }
                            });
                        };
                        image.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            };

            function load_map(latitude, longitude) {

                if (latitude && longitude) {
                    const latlong = `${latitude},${longitude}`;
                    const zoom = 18;
                    const mapSrc = `https://maps.google.com/maps?q=${latlong}&z=${zoom}&output=embed`;

                    // Remove any existing iframe to prevent duplicates
                    $("#maps iframe").remove();

                    // Create and append the iframe
                    const iframe = `<iframe width="100%" height="200" src="${mapSrc}"></iframe>`;
                    $("#maps").append(iframe);
                } else {
                    // Remove iframe if latitude or longitude is empty
                    $("#maps iframe").remove();
                    console.log("Latitude or Longitude is missing.");
                }
            }

            function capture() {
                document.querySelector('input[type="file"]').click();
            }

            function hitung_jarak(lat1, lon1, lat2, lon2) {
                const R = 6371000; // Radius of the Earth in meters
                const toRadians = angle => (angle * Math.PI) / 180;

                const dLat = toRadians(lat2 - lat1);
                const dLon = toRadians(lon2 - lon1);

                const a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);

                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return R * c; // Distance in meters
            }

            function convertToDecimal(gpsArray) {
                var degrees = gpsArray[0];
                var minutes = gpsArray[1];
                var seconds = gpsArray[2];
                return degrees + (minutes / 60) + (seconds / 3600);
            }
    </script>
@endsection
