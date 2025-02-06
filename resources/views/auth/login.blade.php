
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MONA | Log in</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/fontawesome-free/css/all.min.css">

<link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

<link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css?v=3.2.0">
<script nonce="2a4605b5-be2e-493a-9c12-e0c2e67b0140">(function(w,d){!function(Z,_,ba,bb){Z.zarazData=Z.zarazData||{};Z.zarazData.executed=[];Z.zaraz={deferred:[],listeners:[]};Z.zaraz.q=[];Z.zaraz._f=function(bc){return function(){var bd=Array.prototype.slice.call(arguments);Z.zaraz.q.push({m:bc,a:bd})}};for(const be of["track","set","debug"])Z.zaraz[be]=Z.zaraz._f(be);Z.zaraz.init=()=>{var bf=_.getElementsByTagName(bb)[0],bg=_.createElement(bb),bh=_.getElementsByTagName("title")[0];bh&&(Z.zarazData.t=_.getElementsByTagName("title")[0].text);Z.zarazData.x=Math.random();Z.zarazData.w=Z.screen.width;Z.zarazData.h=Z.screen.height;Z.zarazData.j=Z.innerHeight;Z.zarazData.e=Z.innerWidth;Z.zarazData.l=Z.location.href;Z.zarazData.r=_.referrer;Z.zarazData.k=Z.screen.colorDepth;Z.zarazData.n=_.characterSet;Z.zarazData.o=(new Date).getTimezoneOffset();Z.zarazData.q=[];for(;Z.zaraz.q.length;){const bl=Z.zaraz.q.shift();Z.zarazData.q.push(bl)}bg.defer=!0;for(const bm of[localStorage,sessionStorage])Object.keys(bm||{}).filter((bo=>bo.startsWith("_zaraz_"))).forEach((bn=>{try{Z.zarazData["z_"+bn.slice(7)]=JSON.parse(bm.getItem(bn))}catch{Z.zarazData["z_"+bn.slice(7)]=bm.getItem(bn)}}));bg.referrerPolicy="origin";bg.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(Z.zarazData)));bf.parentNode.insertBefore(bg,bf)};["complete","interactive"].includes(_.readyState)?zaraz.init():Z.addEventListener("DOMContentLoaded",zaraz.init)}(w,d,0,"script");})(window,document);</script></head>
<body class="hold-transition login-page">
<div class="login-box">
<div class="login-logo">
<a href="#"><b>MONA</b></a>
</div>

<div class="card">
<div class="card-body login-card-body">
<p class="login-box-msg">Sign in to start your session</p>
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Email atau password yang anda masukan salah! Silahkan hubungi admin.
</div>
@endif
<form method="POST" action="{{ route('login') }}">
@csrf
<div class="input-group mb-3">
<input type="email" name="email" class="form-control" placeholder="Email" autofocus value="">
<div class="input-group-append">
<div class="input-group-text">
<span class="fas fa-envelope"></span>
</div>
</div>
</div>
<div class="input-group mb-3">
<input type="password" name="password" class="form-control" placeholder="Password" value="">
<div class="input-group-append">
<div class="input-group-text">
<span class="fas fa-lock"></span>
</div>
</div>
</div>
<div class="row">
<div class="col-4">
<button type="submit" class="btn btn-primary btn-block">Sign In</button>
</div>

</div>
</form>
</div>

</div>
</div>


<script src="{{ asset('adminlte') }}/plugins/jquery/jquery.min.js"></script>

<script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('adminlte') }}/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>
</html>
