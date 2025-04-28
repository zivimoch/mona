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
        <div class="container-fluid" data-toggle="modal" data-target="#filters">
            <span class="badge badge-primary badge-lg jam_agenda">
                <i class="fas fa-filter"></i> Filters
            </span>
            <span class="badge badge-primary badge-lg jam_agenda">
                Bulan : <span id="filters_bulan"></span> 
            </span>
            <span class="badge badge-primary badge-lg jam_agenda">
                Tahun : <span id="filters_tahun"></span> 
            </span>
            <span class="badge badge-primary badge-lg jam_agenda">
                Tanggal tidak masuk : <span id="filters_tanggal_tidak_masuk"></span> 
            </span>
        </div>
      </section>
  
      <input type="hidden" id="my_latitude">
      <input type="hidden" id="my_longitude">

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                <a href="#" onclick="input_absen('masuk', '{{ now()->toDateString() }}', '{{ now()->toDateString() }}', '') ">
                    <div style="position: relative;">
                        <span style="position: absolute; left: 10px; transform: translateY(-50%); background-color: #f8f9fa; padding: 0 10px; z-index:1000"><i class="fas fa-plus" style="margin-right:5px"></i> Tambah absen di hari yang sama</span>
                        <div style="position: absolute; left: 0; right: 0; height: 1px; background-color: #000;"></div>
                    </div>
                </a>
                <br>
                <div id="list_absen"></div>
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
                    <div class="widget-user-image d-flex justify-content-center align-items-center mb-2" onclick="capture(event)">
                        <button type="file" class="rounded-circle overflow-hidden bg-success d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; border: 5px solid #9b9999; padding: 0;">
                            <img id="foto" src="{{ asset('img') }}/camera.png" alt="Photo" style="width: 150px; max-height: 200px; object-fit: cover;">
                        </button>
                            <input id="foto_file" onchange="get_latlong(event)" type="file" accept="image/*" capture="user" hidden></input>
                    </div>
                    <div class="form-group">
                        <div class="metadata_absen" id="maps"></div>
                        <a href="#" id="reload_map" onclick="reload_map()"><i class="fas fa-sync-alt"></i> Refresh map</a>
                        <br>
                        <span class="metadata_absen" id="distance"></span>
                        <span id="waktu"></span>
                        <span class="metadata_absen" id="distance_warning"></span>
                        <div class="form-group"> 
                            <select name="shift" id="shift" class="form-control mt-3">
                                @foreach ($shift_rules as $item)
                                    @php
                                        $selected = '';
                            
                                        // Select opsi 1 jika bukan hari Jumat
                                        // ramadhan 7 dan 8, reguler 1 dan 2
                                        if ($item->kode == 1 && date('N') !== '5') { 
                                            $selected = 'selected';
                                        }
                            
                                        // Select opsi 2 jika hari Jumat
                                        if ($item->kode == 2 && date('N') == '5') { 
                                            $selected = 'selected';
                                        }
                            
                                        // Select salah satu shift URC jika jabatan URC dan waktu terdekat 30 menit
                                        if (Auth::user()->jabatan == "Unit Reaksi Cepat") {
                                            if ($item->kode == 3 && \Carbon\Carbon::now()->format('H:i') < '16:30') {
                                                $selected = 'selected';
                                            }
                            
                                            if ($item->kode == 4 && \Carbon\Carbon::now()->format('H:i') < '24:00') { // 00:00 is always false
                                                $selected = 'selected';
                                            }
                            
                                            if ($item->kode == 5 && \Carbon\Carbon::now()->format('H:i') < '08:00') {
                                                $selected = 'selected';
                                            }
                                        }
                            
                                        // Select RPS jika koordinat == koordinat RPS
                                        if ($item->kode == 6 && Auth::user()->kantor_latitude == '-6.183773887087405') {
                                            $selected = 'selected';
                                        }
                                    @endphp
                                    <option value="{{ $item->kode }}" {{ $selected }}>{{ $item->judul }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                    <input type="hidden" id="uuid">
                    <input type="hidden" id="tipe">
                    <input type="hidden" id="jarak">
                   
                    {{-- <input type="" id="my_latitude">
                    <input type="" id="my_longitude"> --}}
                    {{-- <button type="button" class="btn btn-block btn-primary" onclick="modal_error('Untuk kebutuhan uji coba tombol Simpan sementara tidak dapat digunakan')">Simpan</button> --}}
                    <button id="simpan" type="button" class="btn btn-block btn-primary" disabled>Simpan</button>
                </div>
        </div>
        </div>
    </div>

    <!-- Modal Detail-->
    <div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Detail Absen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
                <div class="modal-body">
                    <div class="widget-user-image d-flex justify-content-center align-items-center mb-2">
                        <button type="file" class="rounded-circle overflow-hidden bg-success d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; border: 5px solid #9b9999; padding: 0;">
                            <img id="foto_detail" src="{{ asset('img/absen/default.png') }}" alt="Photo" style="width: 150px; max-height: 200px; object-fit: cover;">
                        </button>
                    </div>
                    <div class="form-group">
                        <div id="maps_detail"></div>
                        <br>
                        <span id="detail_maps"></span>
                        <span id="detail_absen"></span>
                        <br>
                        <span id="permohonan_perbaikan"></span>
                        <div class="col-12" id="accordion-two" style="padding:0px !important">
                            <div class="card card-primary">
                              <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <div class="card-header p-0">
                                    <div class="btn btn-block btn-outline-primary rounded-0 mb-0">
                                        <span>Buat Permohonan Perbaikan Absen</span>
                                      </div>
                                </div>
                              </a>
                              <div id="collapseTwo" class="collapse" data-parent="#accordion-two">
                                <div class="card-body">
                                    <div class="form-group"> 
                                        <label>Tipe Perbaikan : </label>
                                        <div class="icheck-primary d-inline d-flex"><input type="checkbox" id="perbaikan_jam" name="tipe_perbaikan[]" value="jam"><label for="perbaikan_jam">Jam Masuk</label></div>
                                        <div class="icheck-primary d-inline d-flex"><input type="checkbox" id="perbaikan_jarak" name="tipe_perbaikan[]" value="jarak"><label for="perbaikan_jarak">Jarak Masuk</label></div>
                                    </div>
                                    <div class="form-group"> 
                                        <label>Alasan : </label>
                                        <input id="catatan" type="text" class="form-control" placeholder="Tulis alasan sejelas-jelasnya">
                                    </div>
                                    <input type="hidden" id="uuid_absen">
                                    <input type="hidden" id="tipe_absen">
                                    <span style="color: red">*Setelah Submit, silahkan informasikan PIC Absen di Sekretariat untuk menyetujui Permohonan Perbaikan Absen.</span>
                                    <button id="simpan_perbaikan" type="button" class="btn btn-block btn-primary">Submit</button>
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
        </div>
        </div>
    </div>

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
                <div class="form-group">
                    <label for="">Tanggal tidak masuk</label>
                    <select id="filter_tanggal_tidak_masuk" class="form-control">
                        <option value="sembunyikan" selected>sembunyikan</option>
                        <option value="tampilkan">tampilkan</option>
                    </select>
                </div>
                <button type="button" class="btn btn-block btn-primary" onclick="load_tanggal()" >Tampilkan</button>

            </div>
        </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/geolocator/dist/geolocator.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#my_latitude').val('');
            $('#my_longitude').val('');
            geolocator.locate({
                enableHighAccuracy: false,
                timeout: 5000,
                maximumAge: 60000, // Accept cached location up to 1 minute old
                fallbackToIP: true
            }, function(err, location) {
                if (err) {
                    modal_error(err);
                } else {
                    $('#my_latitude').val(location.coords.latitude);
                    $('#my_longitude').val(location.coords.longitude);
                }
            });
            // geolocator.locate({
            //     enableHighAccuracy: true,
            //     timeout: 5000,
            //     maximumAge: 60000, // Accept cached location up to 1 minute old
            //     fallbackToIP: true
            // }, function(err, location) {
            //     if (err) {
            //         alert(err);
            //     } else {
            //         $('#my_latitude').val(location.coords.latitude);
            //         $('#my_longitude').val(location.coords.longitude);
            //         // alert(location.coords.latitude+' | '+location.coords.longitude);
            //     }
            // });
            // if (navigator.geolocation) {
            //     navigator.geolocation.getCurrentPosition(
            //         (position) => {
            //             const { latitude, longitude } = position.coords;
            //             alert(`Latitude: ${latitude}, Longitude: ${longitude}`);
            //         },
            //         (error) => {
            //             alert(`Error: ${error.message}`);
            //         }
            //     );
            // } else {
            //     alert("Geolocation is not supported by this browser.");
            // }

            // navigator.geolocation.getCurrentPosition(
            // function(position) {
            //     const my_latitude = position.coords.latitude;
            //     const my_longitude = position.coords.longitude;
            //     $('#nyoba').val(my_latitude + '|' + my_longitude);
            // },
            // function(error) {
            //     $('#nyoba').val('Error occurred: ', error);
            // },
            // {
            //     enableHighAccuracy: false, // Faster but less accurate
            //     timeout: 5000, // Wait up to 5 seconds
            //     maximumAge: 0 // Avoid using stale cached data
            // });

            load_tanggal();
            load_list_agenda('{{ now()->toDateString() }}');
            var currentDate = "{{ \Carbon\Carbon::now()->format('d M Y') }}"; // Get current date from Blade
            function updateTime() {
                var time = new Date().toLocaleTimeString(); // Get current time in local format
                $("#waktu").html("<b>Waktu :</b> " + currentDate + ", " + time + "<br>");
            }
            setInterval(updateTime, 1000); // Update every second
        });

        function reload_map() {
            $('#my_latitude').val('');
            $('#my_longitude').val('');
            $("#reload_map").html('mohon tunggu, sedang memproses lokasi...');

            geolocator.locate({
                enableHighAccuracy: false,
                timeout: 5000,
                maximumAge: 60000, // Accept cached location up to 1 minute old
                fallbackToIP: true
            }, function(err, location) {
                if (err) {
                    modal_error(err);
                } else {
                    $('#my_latitude').val(location.coords.latitude);
                    $('#my_longitude').val(location.coords.longitude);
                }

                // pakai geolocator
                kantor_latitude = '{{ Auth::user()->kantor_latitude }}';
                kantor_longitude = '{{ Auth::user()->kantor_longitude }}';
                my_latitude = $('#my_latitude').val();
                my_longitude = $('#my_longitude').val();
                // alert(my_latitude);
                // if (my_latitude=='' || my_latitude=='') {
                //     let randomOffset = Math.floor(Math.random() * 9) + 1; // Generates a random number between 1 and 9
                //     let my_latitude = parseFloat('{{ Auth::user()->kantor_latitude }}');
                //     let my_longitude = parseFloat('{{ Auth::user()->kantor_longitude }}');
                // }
                
                // alert(my_latitude+'|'+my_longitude+'| KANTOR : {{ Auth::user()->kantor_latitude }} | {{ Auth::user()->kantor_longitude }}');
                
                $("#waktu").show();
                // $("#maps_melacak").html('');
                distance = hitung_jarak(kantor_latitude, kantor_longitude, my_latitude, my_longitude);
                if (distance <= 100) {
                    distance_warning = '<span class="badge badge-success jam_agenda"><i class="fas fa-check-circle"></i> Lokasi absen dalam radius</span><br>';
                } else {
                    distance_warning = '<span class="badge badge-danger jam_agenda"><i class="fas fa-times-circle"></i> Lokasi absen diluar radius (>100 m)</span><br>';
                }
                $("#distance").html('<b>Jarak :</b> '+distance.toFixed(2)+' meter<br>');
                $("#distance_warning").html(distance_warning);
                $("#jarak").val(distance.toFixed(2));
                $("#reload_map").html('<i class="fas fa-sync-alt"></i> Refresh map');
                load_map(location.coords.latitude, location.coords.longitude);
                $('#simpan').prop('disabled', false);
            });
        }

        // Download/Install Button
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('beforeinstallprompt triggered');
            e.preventDefault(); // Prevent the default mini-infobar
            deferredPrompt = e; // Save the event
            $('#installBtn').show(); // Show the button for Android/Chrome
        });
        window.addEventListener('appinstalled', () => {
            console.log('App installed');
            $('#installBtn').hide(); // Hide button when app is installed
        });
        $(document).ready(() => {
            if (window.matchMedia('(display-mode: standalone)').matches) {
                console.log('App is already installed (iOS/standalone)');
                $('#installBtn').hide(); // Hide the button if iOS PWA is installed
            } else {
                // For iOS, suggest the user add to home screen
                if (navigator.userAgent.includes('iPhone') || navigator.userAgent.includes('iPad')) {
                    $('#installBtn').show(); // Show the button on iOS
                }
            }
        });
        $('#installBtn').on('click', () => {
            if (deferredPrompt) {
                deferredPrompt.prompt(); // Show the install prompt
                deferredPrompt.userChoice.then((choiceResult) => {
                    console.log('User choice:', choiceResult.outcome);
                    deferredPrompt = null; // Clear the saved prompt
                });
            } else {
                // For iOS, suggest user manually add the app to home screen
                modal_error('Untuk install MONA gunakan opsi "Add to Home Screen" pada browser anda');
            }
        });


        function load_tanggal() {
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
            tanggal_tidak_masuk = $('#filter_tanggal_tidak_masuk').val();
            // set filter badges
            $('#filters_bulan').html(bulan);
            $('#filters_tahun').html(tahun);
            $('#filters_tanggal_tidak_masuk').html(tanggal_tidak_masuk);
            $.ajax({
                type: "GET",
                url: "{{ env('APP_URL') }}/absen/load_tanggal?bulan="+bulan+"&tahun="+tahun+"&tanggal_tidak_masuk="+tanggal_tidak_masuk,
                success: function (response) {
                    let hari_ini = @json(now()->toDateString());;
                    disabled_button_masuk = disabled_button_pulang = '';
                    $('#list_absen').html('');
                    
                    response.forEach(e => {
                    if (e.tanggal != hari_ini) {
                        collapsed = 'collapsed-card';
                        hari_ini_bedge = '';
                    } else {
                        collapsed = '';
                        hari_ini_bedge = '<span class="badge badge-danger badge-lg jam_agenda" style="position: absolute; top: -5px; right: 10px;"><i class="fas fa-calendar-week"></i> Hari Ini</span>'
                    }
                    // masuk
                    if (e.tanggal_masuk != null && e.kategori == 'masuk') {
                        if (e.foto_masuk == null ) {
                            foto_masuk = 'default.png';
                        } else {
                            foto_masuk = e.foto_masuk;
                        }
                        card_masuk = 'success';
                        button_masuk = `<div class="widget-user-image" onclick="event.stopPropagation(); load_detail('${e.uuid}','masuk')"  style="margin-right: 10px; position: relative; border: 3px solid #ffffff; border-radius: 50%;">
                                    <img class="img-circle elevation-2" src="{{ asset('img') }}/absen/${foto_masuk}" style="max-width:58px; border-radius: 50%;">
                                    <i class="fas fa-info" style="position: absolute; bottom: 2px; right: -10px; color:#fff; background-color: #007BFF; border-radius: 50%; width: 27px; height: 25px; display: flex; align-items: center; justify-content: center;"></i>
                                </div>`;
                        jam_masuk = e.jam_masuk;
                    } else if (e.kategori == 'cuti') {
                        card_masuk = 'warning';
                        button_masuk = `<div class="widget-user-image" onclick="input_absen('masuk', '${e.tanggal}', '${e.tanggal_masuk}', '${e.uuid}') "style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                    <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs disabled"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                </div>`;
                        jam_masuk = "CUTI";
                    } else {
                        card_masuk = 'secondary';
                        if (e.tanggal != hari_ini) {
                            disabled_button_masuk = 'disabled';
                        } else {
                            disabled_button_masuk = '';
                        }
                        button_masuk = `<div class="widget-user-image" onclick="input_absen('masuk', '${e.tanggal}', '${e.tanggal_masuk}', '${e.uuid}')" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                    <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs ${disabled_button_masuk}"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                </div>`;
                        jam_masuk = e.jam_masuk;
                    }
                    // menit telat
                    menit_telat = '';
                    if (e.menit_telat > 0) {
                        menit_telat = `<span class="badge badge-danger badge-lg jam_agenda"><i class="far fa-clock"></i> Telat : ${e.menit_telat}m</span>`;
                    }
                    // pulang
                    if (e.tanggal_pulang != null && e.kategori == 'masuk') {
                        if (e.foto_pulang == null ) {
                            foto_pulang = 'default.png';
                        } else {
                            foto_pulang = e.foto_pulang;
                        }
                        card_pulang = 'success';
                        button_pulang = `<div class="widget-user-image" onclick="event.stopPropagation(); load_detail('${e.uuid}','pulang')"  style="margin-right: 10px; position: relative; border: 3px solid #ffffff; border-radius: 50%;">
                                    <img class="img-circle elevation-2" src="{{ asset('img') }}/absen/${foto_pulang}" style="max-width:58px; border-radius: 50%;">
                                    <i class="fas fa-info" style="position: absolute; bottom: 2px; right: -10px; color:#fff; background-color: #007BFF; border-radius: 50%; width: 27px; height: 25px; display: flex; align-items: center; justify-content: center;"></i>
                                </div>`;
                    } else if (e.kategori == 'cuti') {
                        card_pulang = 'warning';
                        button_pulang = `<div class="widget-user-image" onclick="input_absen('pulang', '${e.tanggal}', '${e.tanggal_masuk}', '${e.uuid}')" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                    <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs disabled"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                </div>`;
                    } else {
                        card_pulang = 'secondary';
                        if (e.tanggal != hari_ini && e.tanggal_masuk == null) {
                            disabled_button_pulang = 'disabled';
                        } else {
                            disabled_button_pulang = '';
                        }
                        button_pulang = `<div class="widget-user-image" onclick="input_absen('pulang', '${e.tanggal}', '${e.tanggal_masuk}', '${e.uuid}')" style="margin-right: 10px; position: relative; border: 3px solid #9b9999; border-radius: 50%;">
                                    <button type="button" class="btn bg-gradient-success p-2 rounded-circle btn-xs ${disabled_button_pulang}"><i class="fas fa-plus" style="margin:0px 5px; font-size:35px; color:#ffffff"></i></button>
                                </div>`;
                    }

                    var html = `
                        <div class="card ${collapsed}">
                            <div class="card-header bg-${card_masuk}" data-card-widget="collapse" onclick="load_list_agenda('${e.tanggal}')">
                                ${hari_ini_bedge}
                                <table>
                                    <tr>
                                        <td>
                                            ${button_masuk}
                                        </td>
                                        <td>
                                            <span class="widget-user-username tanggal_agenda"><i class="fas fa-sign-in-alt" style="transform: scaleX(-1);"></i> ${e.tanggal_human}</span>
                                            <br>
                                            <span class="badge badge-warning badge-lg jam_agenda">
                                                <i class="far fa-clock"></i> ${jam_masuk}
                                            </span>
                                            ${menit_telat}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <h6 id="agendaAndaHeading_${e.tanggal}"></h6>
                                <ul class="todo-list" data-widget="todo-list" id="list_agenda_${e.tanggal}"></ul>
                                <div style="text-align: center;">
                                    <h5 id="loading_${e.tanggal}">Loading...</h5>
                                    <span style="font-size:20px; color:#b8b6b6; font-weight:bold; display:none" id="non_agenda_${e.tanggal}">
                                        <i class="fas fa-tasks"></i> Tidak ada agenda
                                    </span>
                                </div>
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

            $('#simpan').click(function() {
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
                    url: "{{ route('absen.store') }}",
                    type: "POST",
                    cache: false,
                    data: {
                        uuid: $("#uuid").val(),
                        tipe: $("#tipe").val(),
                        shift: $("#shift").val(),
                        my_latitude: $("#my_latitude").val(),
                        my_longitude: $("#my_longitude").val(),
                        jarak: $("#jarak").val(),
                        _token: token
                    },
                    success: function (response){
                        console.log(response);
                        $('#list_absen').empty();
                        load_tanggal();
                        $('#absen').modal('hide');
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

            function load_modal(uuid) {
                $('#uuid').val(''); // clear uuid first
                var foto = $('#foto');
                foto.attr('src',"{{ asset('img') }}/camera.png");
                $('#foto_file').val(''); // reset
                // $('#my_latitude').val('');
                // $('#my_longitude').val('');
                $('.metadata_absen').hide();
                $('#simpan').prop('disabled', true);
                $('#uuid').val(uuid);
                $('#absen').modal('show');
            }

            function input_absen(tipe, tanggal_input, tanggal_masuk, uuid) {
                $.ajax({
                type: "GET",
                url: "{{ env('APP_URL') }}/absen/load_detail?uuid="+uuid,
                success: function (response) {
                    const current_time = new Date().toLocaleTimeString('en-GB', { hour12: false });
                    if (tipe == 'pulang' && response.jam_pulang_fleksi > current_time) {
                        // aturan fleksi
                        modal_error('Absen pulang anda saat ini baru dapat dilakukan pukul <span style="color:red">'+response.jam_pulang_fleksi+'</span> dikarenakan masuk pukul '+response.jam_masuk+'.'); 
                    } else {
                        event.stopPropagation();
                        let hari_ini = @json(now()->toDateString());
                        if (tipe == 'masuk' && tanggal_input != hari_ini) {
                            modal_error('Absen masuk tidak dapat dilakukan karena sudah lewat hari.'); 
                        } else if (tipe == 'pulang' && tanggal_masuk == 'null') {
                            modal_error('Absen pulang tidak dapat dilakukan karena belum absen masuk.');
                        } else {
                            load_modal(uuid);
                        }

                        if (tipe == 'masuk') {
                            $('#modal_title').html('<i class="fas fa-sign-in-alt" style="transform: scaleX(-1);"></i> Absen Masuk');
                            $('#tipe').val('masuk');
                            $('#shift').show();
                        } else {
                            $('#modal_title').html('<i class="fas fa-sign-out-alt""></i> Absen Pulang');
                            $('#tipe').val('pulang');
                            $('#shift').hide();
                        }
                    }
                }
                });
            }

            function load_detail(uuid,tipe) {
                event.preventDefault();
                $.ajax({
                type: "GET",
                url: "{{ env('APP_URL') }}/absen/load_detail?uuid="+uuid+"&tipe="+tipe,
                success: function (response) {
                        $('#detail').modal('show');

                        $('#uuid_absen').val(response["uuid"]);
                        $('#tipe_absen').val(tipe);
                        $('#permohonan_perbaikan').html('');

                        $('#detail_absen').html('');
                        let tanggal = response["tanggal_" + tipe];
                        let jam = response["jam_" + tipe];
                        let jarak = response["jarak_" + tipe];
                        let rules = response["rules"] || '';
                        const latitude = response[tipe + "_latitude"] ;
                        const longitude = response[tipe + "_longitude"];
                        const latlong = `${latitude},${longitude}`;
                        const zoom = 18;
                        const mapSrc = `https://maps.google.com/maps?q=${encodeURIComponent(latlong)}&z=${zoom}&output=embed`;
                        $("#detail_maps iframe").remove();
                        const iframe = `<iframe width="100%" height="200" src="${mapSrc}"></iframe>`;
                        $("#detail_maps").append(iframe);
                        if (jarak > 100) {
                            distance_warning = '<span class="badge badge-danger jam_agenda"><i class="fas fa-times-circle"></i> Lokasi absen diluar radius (>100 m)</span><br>';
                        } else {
                            distance_warning = '<span class="badge badge-success jam_agenda"><i class="fas fa-check-circle"></i> Lokasi absen dalam radius</span><br>';
                        }

                        $('#detail_absen').append("Tanggal " + tipe + " : " + tanggal + "<br>");
                        $('#detail_absen').append("Jam " + tipe + " : " + jam + "<br>");
                        $('#detail_absen').append("Jarak " + tipe + " : " + jarak + "<br>");
                        $('#detail_absen').append("Rules : " + rules + "<br>");
                        $('#detail_absen').append(distance_warning);
                        if (tipe=='masuk') {
                            $('#detail_absen').append("Telat : " + response.menit_telat + " menit<br>");
                        }

                        // bersihkan inputan perbaikan
                        $("input[name='tipe_perbaikan[]']").prop('checked', false); 
                        $('#catatan').val('');

                        // Loop perbaikan
                        response.perbaikan.forEach(function(perbaikan) {
                        let statusBadge, statusText;

                        if (perbaikan.disetujui === 1) {
                            statusBadge = 'badge-success';
                            statusText = 'Disetujui';
                        } else if (perbaikan.disetujui === 0) {
                            statusBadge = 'badge-danger';
                            statusText = 'Tidak Disetujui';
                        } else {
                            statusBadge = 'badge-primary';
                            statusText = 'Belum Disetujui';
                        }

                        let tipePerbaikanText = Array.isArray(perbaikan.tipe_perbaikan) 
                            ? perbaikan.tipe_perbaikan.join(', ') 
                            : perbaikan.tipe_perbaikan;

                        if (perbaikan.jam_sebelumnya){
                            jam_sebelumnya = "Jam Sebelumnya : "+perbaikan.jam_sebelumnya+"<br>";
                        } else {
                            jam_sebelumnya = '';
                        }

                        if (perbaikan.jarak_sebelumnya){
                            jarak_sebelumnya = "Jarak Sebelumnya : "+perbaikan.jarak_sebelumnya+"<br>";
                        } else {
                            jarak_sebelumnya = '';
                        }

                        const createdAt = new Date(perbaikan.created_at);
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

                        let perbaikanHtml = `
                            <div class="col-12" style="padding:0px !important">
                            <div class="card card-primary">
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse_${perbaikan.uuid}">
                                <div class="card-header p-0">
                                    <div class="btn btn-block btn-outline-primary rounded-0 mb-0 d-flex justify-content-between align-items-center p-2">
                                    <span>Permohonan Perbaikan Absen</span>
                                    <span class="badge ${statusBadge} d-flex align-items-center">${statusText}</span>
                                    </div>
                                </div>
                                </a>
                                <div id="collapse_${perbaikan.uuid}" class="collapse" data-parent="#permohonan_perbaikan">
                                <div class="card-body">
                                    Anda melakukan permohonan perbaikan absen pada tanggal ${formattedDate}.<br><br>
                                    Tipe Perbaikan: ${tipePerbaikanText}<br>
                                    ${jam_sebelumnya}
                                    ${jarak_sebelumnya}
                                    Alasan: ${perbaikan.alasan}<br>
                                    Status: ${statusText}<br>
                                    Keterangan Sekretariat: ${perbaikan.keterangan_pic}<br>
                                </div>
                                </div>
                            </div>
                            </div>
                        `;

                        $('#permohonan_perbaikan').append(perbaikanHtml);
                        });

                        
                        $('#detail_absen').append("Keterangan " + tipe + " : " + keterangan + "<br>");
                    }
                });
            }

            function get_latlong(event) {
                event.preventDefault(); // Prevent any default action, like form submission
                event.stopPropagation(); // Stop any bubbling or form-related behavior

                const file = event.target.files[0];
                var foto = $('#foto');
                if (file) {
                    // $("#maps_melacak").html('Melacak lokasi... (kecepatan tergantung device)');
                    $('.metadata_absen').show();
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Update image with the captured photo
                        foto.attr('src', e.target.result);
                        const image = new Image();
                        image.onload = function() {
                            reload_map();

                            // pakai pure jquery
                        //     navigator.geolocation.getCurrentPosition(
                        //         function(position) {
                        //             // Set up location and distance logic
                        //             kantor_latitude = '{{ Auth::user()->kantor_latitude }}';
                        //             kantor_longitude = '{{ Auth::user()->kantor_longitude }}';
                        //             $("#waktu").show();
                        //             const my_latitude = position.coords.latitude;
                        //             const my_longitude = position.coords.longitude;
                        //             if (my_latitude && my_longitude) {
                        //                 $("#maps_melacak").html('');
                        //                 distance = hitung_jarak(kantor_latitude, kantor_longitude, my_latitude, my_longitude);
                        //                 if (distance > 100) {
                        //                     distance_warning = '<span class="badge badge-danger jam_agenda"><i class="fas fa-times-circle"></i> Lokasi absen diluar radius (>100 m)</span><br>';
                        //                 } else {
                        //                     distance_warning = '<span class="badge badge-success jam_agenda"><i class="fas fa-check-circle"></i> Lokasi absen dalam radius</span><br>';
                        //                 }
                        //                 $("#distance").html('<b>Jarak :</b> '+distance.toFixed(2)+' meter<br>');
                        //                 $("#distance_warning").html(distance_warning);
                        //                 $('#my_latitude').val(my_latitude);
                        //                 $('#my_longitude').val(my_longitude);
                        //                 $("#jarak").val(distance.toFixed(2));
                        //                 load_map(my_latitude, my_longitude);
                        //                 $('#simpan').prop('disabled', false);
                        //             } else {
                        //                 $('#simpan').prop('disabled', true);
                        //                 distance_warning = '<span class="badge badge-danger jam_agenda"><i class="fas fa-times-circle"></i> Lokasi tidak ditemukan, nyalakan GPS</span><br>';
                        //                 $("#distance_warning").html(distance_warning);
                        //             }
                        //     },
                        //     function(error) {
                        //         // sementara saja
                        //         kantor_latitude = '{{ Auth::user()->kantor_latitude }}';
                        //         kantor_longitude = '{{ Auth::user()->kantor_longitude }}';

                        //         let randomOffset = Math.floor(Math.random() * 9) + 1; // Generates a random number between 1 and 9
                        //         let my_latitude = parseFloat('{{ Auth::user()->kantor_latitude }}') - (randomOffset * 0.00001);
                        //         let my_longitude = parseFloat('{{ Auth::user()->kantor_longitude }}') - (randomOffset * 0.00001);

                        //         $("#waktu").show();
                        //         $("#maps_melacak").html('');
                        //         distance = hitung_jarak(kantor_latitude, kantor_longitude, my_latitude, my_longitude);
                        //         if (distance > 100) {
                        //             distance_warning = '<span class="badge badge-danger jam_agenda"><i class="fas fa-times-circle"></i> Lokasi absen diluar radius (>100 m)</span><br>';
                        //         } else {
                        //             distance_warning = '<span class="badge badge-success jam_agenda"><i class="fas fa-check-circle"></i> Lokasi absen dalam radius</span><br>';
                        //         }
                        //         $("#distance").html('<b>Jarak :</b> '+distance.toFixed(2)+' meter<br>');
                        //         $("#distance_warning").html(distance_warning);
                        //         $('#my_latitude').val(my_latitude);
                        //         $('#my_longitude').val(my_longitude);
                        //         $("#jarak").val(distance.toFixed(2));
                        //         load_map(my_latitude, my_longitude);
                        //         $('#simpan').prop('disabled', false);
                        //         // modal_error('Lokasi tidak ditemukan, silakhan ulangi ambil foto');
                        //     },
                        //     {
                        //         enableHighAccuracy: false, // Faster but less accurate
                        //         timeout: 3000, // Wait up to 5 seconds
                        //         maximumAge: 0 // Avoid using stale cached data
                        //     }
                        // );
                        };
                        image.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }



            function load_map(latitude, longitude) {
                if (latitude && longitude) {
                    const latlong = `${latitude},${longitude}`;
                    const zoom = 18;
                    const mapSrc = `https://maps.google.com/maps?q=${latlong}&z=${zoom}&output=embed`;

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

            function capture(event) {
                // event.preventDefault(); // Prevent any default action, like form submission
                event.stopPropagation(); // Prevent event from bubbling up and causing unwanted behavior
                document.getElementById('foto_file').click(); // Trigger file input click
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

            function load_list_agenda(tanggal_masuk) {
                $('#loading_'+tanggal_masuk).show();
                $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL') }}/absen/load_agenda?tanggal_masuk="+tanggal_masuk,
                    success: function (response) {
                        $('#loading_'+tanggal_masuk).hide();
                        data = response.data;
                        $('#list_agenda_'+tanggal_masuk).html('');
                        if (data.length == 0) {
                            $('#non_agenda_'+tanggal_masuk).show();
                        } else {
                            i = 1;
                            data.forEach(e => {
                                $('#non_agenda_'+tanggal_masuk).hide();

                                if (e.jam_selesai == null) {
                                done = '';
                                checked = '';
                                disabled = '';
                                } else {
                                done = 'done';
                                checked = 'checked';
                                disabled = 'disabled';
                                }

                                nama_klien = '';
                                if (e.nama != null) {
                                nama_klien = '<br>Klien : <a href="#" onclick="modal_klien(`'+e.uuid_klien+'`)">'+e.nama+'</a>';
                                }
                                $('#list_agenda_'+tanggal_masuk).append('<li class="'+done+'"><div  class="icheck-primary d-inline ml-2"><input type="checkbox" value="" id="todoCheck'+i+'" '+checked+' '+disabled+' onclick="modal_error(`Fitur belum tersedia. Untuk mengupdate agenda sementara ini masih hanya dapat dilakukan di MOKA.`)"><label for="todoCheck'+i+'"></label></div><span class="text">'+e.judul_kegiatan+'</span><span class="badge badge-warning badge-lg" style="font-size:13px"><i class="far fa-clock"></i> '+e.jam_mulai+'</span>'+nama_klien+'</li>');
                                $('#agendaAndaHeading_'+tanggal_masuk).html('<b>Terdapat '+i+' agenda</b>')
                                i++;
                            });
                        }
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
                        uuid_absen: $("#uuid_absen").val(),
                        tipe_absen: $("#tipe_absen").val(),
                        tipe_perbaikan: $("input[name='tipe_perbaikan[]']:checked").map(function() {
                                    return this.value;
                                    }).get(),
                        catatan: $("#catatan").val(),
                        _token: token
                    },
                    success: function (response){
                        $('#detail').modal('hide');
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
