
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MONA | Log in</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/fontawesome-free/css/all.min.css">

<link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
{{-- button SSO  --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css?v=3.2.0">
<script nonce="2a4605b5-be2e-493a-9c12-e0c2e67b0140">(function(w,d){!function(Z,_,ba,bb){Z.zarazData=Z.zarazData||{};Z.zarazData.executed=[];Z.zaraz={deferred:[],listeners:[]};Z.zaraz.q=[];Z.zaraz._f=function(bc){return function(){var bd=Array.prototype.slice.call(arguments);Z.zaraz.q.push({m:bc,a:bd})}};for(const be of["track","set","debug"])Z.zaraz[be]=Z.zaraz._f(be);Z.zaraz.init=()=>{var bf=_.getElementsByTagName(bb)[0],bg=_.createElement(bb),bh=_.getElementsByTagName("title")[0];bh&&(Z.zarazData.t=_.getElementsByTagName("title")[0].text);Z.zarazData.x=Math.random();Z.zarazData.w=Z.screen.width;Z.zarazData.h=Z.screen.height;Z.zarazData.j=Z.innerHeight;Z.zarazData.e=Z.innerWidth;Z.zarazData.l=Z.location.href;Z.zarazData.r=_.referrer;Z.zarazData.k=Z.screen.colorDepth;Z.zarazData.n=_.characterSet;Z.zarazData.o=(new Date).getTimezoneOffset();Z.zarazData.q=[];for(;Z.zaraz.q.length;){const bl=Z.zaraz.q.shift();Z.zarazData.q.push(bl)}bg.defer=!0;for(const bm of[localStorage,sessionStorage])Object.keys(bm||{}).filter((bo=>bo.startsWith("_zaraz_"))).forEach((bn=>{try{Z.zarazData["z_"+bn.slice(7)]=JSON.parse(bm.getItem(bn))}catch{Z.zarazData["z_"+bn.slice(7)]=bm.getItem(bn)}}));bg.referrerPolicy="origin";bg.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(Z.zarazData)));bf.parentNode.insertBefore(bg,bf)};["complete","interactive"].includes(_.readyState)?zaraz.init():Z.addEventListener("DOMContentLoaded",zaraz.init)}(w,d,0,"script");})(window,document);</script></head>

<style>
    .btn-glow {
        position: relative;
        color: #38bdf8;
        background-color: #1e293b;
        border: 2px solid #38bdf8;
        padding: 0.5rem 1rem;
        font-weight: bold;
        overflow: hidden;
        z-index: 0;
        }

        .btn-glow .glow-light {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #38bdf8;
        border-radius: 50%;
        box-shadow: 0 0 8px #38bdf8;
        }

        .glow-light1 {
        animation: orbit1 4s linear infinite;
        }

        .glow-light2 {
        animation: orbit2 4s linear infinite;
        }

        @keyframes orbit1 {
        0%   { top: -4px; left: -4px; }
        25%  { top: -4px; left: calc(100% - 4px); }
        50%  { top: calc(100% - 4px); left: calc(100% - 4px); }
        75%  { top: calc(100% - 4px); left: -4px; }
        100% { top: -4px; left: -4px; }
        }

        @keyframes orbit2 {
        0%   { top: calc(100% - 4px); left: calc(100% - 4px); }
        25%  { top: calc(100% - 4px); left: -4px; }
        50%  { top: -4px; left: -4px; }
        75%  { top: -4px; left: calc(100% - 4px); }
        100% { top: calc(100% - 4px); left: calc(100% - 4px); }
        }

        .btn-glow:hover {
        background-color: #38bdf8;
        color: #0f172a;
        }
        </style>

<body class="hold-transition login-page">
<div class="login-box">
<div class="login-logo">
<a href="#"><b>MONA</b></a>
</div>

<div class="card">
<div class="card-body login-card-body">
    <div class="d-flex align-items-center justify-content-center mb-3 text-light">
                        <div class="flex-grow-1 me-3" style="border-top: 2px solid #475569;"></div>
                        <span class="text-nowrap" style="color: black"><i class="fas fa-shield-alt"></i> SSO PPPA</span>
                        <div class="flex-grow-1 ms-3" style="border-top: 2px solid #475569;"></div>
                    </div>
                    <center>
                        <button type="button" onclick="loginWithMoke()" class="btn btn-glow btn-lg">
                            <i class="fas fa-lock"></i> Masuk dengan MOKE
                            <span class="glow-light glow-light1"></span>
                            <span class="glow-light glow-light2"></span>
                        </button>
                    </center>
                    <script>
                    function loginWithMoke() {
                        const target = "{{ env('APP_URL') }}";
                        const popup = window.open("{{ env('SSO_SERVER') }}/dashboard?target=" + encodeURIComponent(target), "ssoLogin", "width=600,height=600");

                        window.addEventListener('message', async function (e) {

                            const token = e.data.token;


                            // kirim token ke server untuk proses login
                            const response = await fetch("{{ route('sso.consume') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ token })
                            });

                            if (response.ok) {
                                window.location.href = '{{ route('home') }}';
                            }
                        });
                    }
                    </script>

<div style="display: show"> 
<p class="login-box-msg">Sign in to start your session</p>
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Email atau password yang anda masukan salah! Silahkan hubungi admin.
</div>
@endif
</div>


</div>

</div>
</div>


<script src="{{ asset('adminlte') }}/plugins/jquery/jquery.min.js"></script>

<script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('adminlte') }}/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>
</html>
