<!doctype html>
<html lang="en">
<head>
    <title>Permohonan Cuti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" href="" >
    <style>
        /* Styles for signature plugin v1.2.0. */
.kbw-signature {
display: inline-block;
border: 1px solid #a0a0a0;
-ms-touch-action: none;
}
.kbw-signature-disabled {
opacity: 0.35;
}
        body {
            background: #eee;
        }
        .container {
            background: #fff;
        }
        .wrapper {
            position: relative;
            width: 100%;
            max-width: 330px;
            height: 200px;
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: solid 1px #ddd;
            margin: 10px 0px;
        }

        .checkboxgroup{
            display:inline-block;
            text-align:center;
        }
        .checkboxgroup label {
            display:block;
        }
        
        .kbw-signature { width: 100%; height: 200px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
            border-style: solid;
        }
    </style>    
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap CSS CDN -->
<link
rel="stylesheet"
href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
/>
</head>
<body style="background-color:#eee">
<div class="container" style="margin-top:20px; margin-bottom:20px">


    <form action="{{ route('cuti.permohonan.store') }}" method="post" id="form-submit">
        <input type="text" name="uuid" value="{{ $data->uuid }}" hidden/>
<br>    
<br>    
        <div class="col-md-12" style="font-size: 18px;">

            <input type="text" class="form-control" value="" hidden/>
            <textarea name="alamat" class="form-control" id="alamat" hidden></textarea>
            <input type="number" name="no_telp" class="form-control" id="no_telp" value="" hidden/>
            <h5 style="font-weight: bold; text-align:center">SURAT PERMOHONAN CUTI</h5>
            <br>
            Kepada yth. <br>
            <b>Kepala UPT. PPPA Provinsi DKI Jakarta</b> <br>
            Di Jakarta <br>
            <br>
            Prihal : Permohonan Cuti <br>
            <br>
            Dengan hormat, <br>
            Saya yang bertanda tangan dibawah ini : <br>   
            <table>
                <tr>
                    <td>Nama </td>
                    <td> : </td>
                    <td>{{ $data->name }}</td>
                </tr>
                {{-- <tr>
                    <td>Tempat / Tanggal Lahir </td>
                    <td> : </td>
                    <td>x</td>
                </tr> --}}
                <tr>
                    <td>Alamat </td>
                    <td> : </td>
                    <td>{{ $data->alamat_domisili }}</td>
                </tr>
                <tr>
                    <td>No. Tlp </td>
                    <td> : </td>
                    <td>{{ $data->no_telp }}</td>
                </tr>
                <tr>
                    <td>Jabatan </td>
                    <td> : </td>
                    <td>{{ $data->jabatan }}</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <br>
                        Dengan ini, Saya mengajukan permohon cuti selama {{ $data->jumlah_hari }} hari pada hari : 
                        @php
                            $dates = collect(explode(',', $data->tanggal_cuti))
                                ->map(fn($date) => \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y'))
                                ->toArray();

                            echo implode('; ', array_slice($dates, 0, -1)) . (count($dates) > 1 ? ' dan ' : '') . end($dates);
                        @endphp
                        , dengan alasan {{ $data->alasan }}.
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td>Alamat selama cuti </td>
                    <td> : </td>
                    <td>{{ $data->alamat_selama_cuti }}</td>
                </tr>
            </table>
            <br>
            Demikian permohonan cuti ini Saya buat, atas perhatiannya diucapkan terima kasih.
            <br>
            <br>
        <div class="row">
            <div class="col-md-6 align-self-center mt-5">
                    <label class="" for="">Tanda Tangan Atasan :</label>
                    <br/>
                @if ($data->tandatangan1)
                <center>
                    <img src="{{ asset('img/tandatangan/ttd_cuti') }}/{{ $data->tandatangan1 }}" style="width: 100%; max-height:200px; min-height:200px;   border:#000 1px solid"/>
                    <br>
                    <br>
                    <span style="text-decoration: underline; font-weight: bold;">
                        {{ $data->nama_penandatangan1 }}
                    </span>
                </center>
                @else
                    <div id="sig1" >
                    <button id="clear1" class="btn btn-danger btn-sm" style="position: absolute">Hapus</button>
                    </div>
                    <textarea id="signature641" name="tandatangan1" style="display: none"></textarea>
                    <br/>
                <tr>
                    <td>
                        <input type="text" name="nama_penandatangan1" class="form-control" id="nama_penandatangan1" style="border: none; border-bottom: 2px solid black;" placeholder="Nama Lengkap"/>
                    </td>
                </tr>
                @endif
            </div>
            <div class="col-md-6 align-self-center mt-5">
                    <label class="" for="">Tanda Tangan Pemohon :</label>
                    <br/>
                    @if ($data->tandatangan2)
                        <center>
                            <img src="{{ asset('img/tandatangan/ttd_cuti') }}/{{ $data->tandatangan2 }}" style="width: 100%; max-height:200px; min-height:200px;   border:#000 1px solid"/>
                            <br>
                            <br>
                            <span style="text-decoration: underline; font-weight: bold;">
                                {{ $data->nama_penandatangan2 }}
                            </span>
                        </center>
                    @else
                        <div id="sig2" >
                        <button id="clear2" class="btn btn-danger btn-sm" style="position: absolute">Hapus</button>
                        </div>
                        <textarea id="signature642" name="tandatangan2" style="display: none"></textarea>
                        <br/>
                        <tr>
                            <td>
                                <input type="text" name="nama_penandatangan2" class="form-control" id="nama_penandatangan2" style="border: none; border-bottom: 2px solid black;" placeholder="Nama Lengkap"/>
                            </td>
                        </tr>
                    @endif
            </div>
            <div class="col-md-6 align-self-center mt-5">
                    <label class="" for="">Tanda Tangan Kasubbag. Tata Usaha :</label>
                    <br/>
                    @if ($data->tandatangan3)
                        <center>
                            <img src="{{ asset('img/tandatangan/ttd_cuti') }}/{{ $data->tandatangan3 }}" style="width: 100%; max-height:200px; min-height:200px;   border:#000 1px solid"/>
                            <br>
                            <br>
                            <span style="text-decoration: underline; font-weight: bold;">
                                {{ $data->nama_penandatangan3 }}
                            </span>
                        </center>
                    @else
                        <div id="sig3" >
                        <button id="clear3" class="btn btn-danger btn-sm" style="position: absolute">Hapus</button>
                        </div>
                        <textarea id="signature643" name="tandatangan3" style="display: none"></textarea>
                        <br/>
                        <tr>
                            <td>
                                <input type="text" name="nama_penandatangan3" class="form-control" id="nama_penandatangan3" style="border: none; border-bottom: 2px solid black;" placeholder="Nama Lengkap"/>
                            </td>
                        </tr>
                    @endif
            </div>
            <div class="col-md-6 align-self-center mt-5">
                    <label class="" for="">Tanda Tangan Kepala PPPA :</label>
                    <br/>
                    @if ($data->tandatangan4)
                        <center>
                            <img src="{{ asset('img/tandatangan/ttd_cuti') }}/{{ $data->tandatangan4 }}" style="width: 100%; max-height:200px; min-height:200px;   border:#000 1px solid"/>
                            <br>
                            <br>
                            <span style="text-decoration: underline; font-weight: bold;">
                                {{ $data->nama_penandatangan4 }}
                            </span>
                        </center>
                    @else
                        <div id="sig4" >
                        <button id="clear4" class="btn btn-danger btn-sm" style="position: absolute">Hapus</button>
                        </div>
                        <textarea id="signature644" name="tandatangan4" style="display: none"></textarea>
                        <br/>
                        <tr>
                            <td>
                                <input type="text" name="nama_penandatangan4" class="form-control" id="nama_penandatangan4" style="border: none; border-bottom: 2px solid black;" placeholder="Nama Lengkap"/>
                            </td>
                        </tr>
                    @endif
            </div>
        </div>
    </div>
    <br/>
    @if ($data->tandatangan1 == NULL || $data->tandatangan2 == NULL)
        <div class="col-md-12" style="padding-bottom:50px;">
            <button id="submit" class="btn btn-success" style="display:block; width:100%; ">Simpan dan Kirim</button>
        </div>
    @endif
        </div>
        @csrf
    </form>
</div>
{{-- tanda tangan / signature pad --}}

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ asset('source/js/jquery.signature.js') }}"></script>
{{-- <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css"> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js" ></script>

<script src="{{ asset('/source/js/validation.js') }}"></script>

<script type="text/javascript">
    var sig1 = $('#sig1').signature({syncField: '#signature641', syncFormat: 'PNG'});
    $('#clear1').click(function(e) {
        e.preventDefault();
        sig1.signature('clear');
        $("#signature641").val('');
    });

    var sig2 = $('#sig2').signature({syncField: '#signature642', syncFormat: 'PNG'});
    $('#clear2').click(function(e) {
        e.preventDefault();
        sig2.signature('clear');
        $("#signature642").val('');
    });

    var sig3 = $('#sig3').signature({syncField: '#signature643', syncFormat: 'PNG'});
    $('#clear3').click(function(e) {
        e.preventDefault();
        sig3.signature('clear');
        $("#signature643").val('');
    });

    var sig4 = $('#sig4').signature({syncField: '#signature644', syncFormat: 'PNG'});
    $('#clear4').click(function(e) {
        e.preventDefault();
        sig4.signature('clear');
        $("#signature644").val('');
    });

    $('#submit').click(function() {
        if(validateForm('agenda') == false){
            return false;
            $('#message').html('Mohon cek ulang data yang wajib diinput.');
            $("#success-message").hide();
            $("#error-message").show();
        }
    })
</script>
</body>