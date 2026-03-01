<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Pengajuan Proposal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{
            --ink:#0f172a;
            --muted:#475569;
            --brand:#0ea5e9;
            --brand-dark:#0f4c81;
            --accent:#f59e0b;
            --paper:#ffffff;
            --shadow:0 20px 40px rgba(15,23,42,.12);
        }
        body{
            font-family:"Source Sans 3", sans-serif;
            color:var(--ink);
            background:
                radial-gradient(1000px 500px at 80% -20%, rgba(14,165,233,.15), transparent 60%),
                radial-gradient(800px 400px at 10% 0%, rgba(245,158,11,.18), transparent 55%),
                #f8fafc;
            min-height:100vh;
        }
        .public-shell{
            max-width:1100px;
            margin:0 auto;
            padding:56px 20px 80px;
        }
        .public-hero{
            background:linear-gradient(135deg, #0f172a 0%, #0f4c81 60%, #0ea5e9 100%);
            color:#fff;
            border-radius:22px;
            padding:28px 28px 30px;
            box-shadow:var(--shadow);
            position:relative;
            overflow:hidden;
        }
        .public-hero:after{
            content:"";
            position:absolute;
            right:-60px;
            top:-60px;
            width:220px;
            height:220px;
            background:rgba(255,255,255,.12);
            border-radius:50%;
            filter:blur(4px);
            pointer-events:none;
        }
        .hero-title{
            font-family:"Playfair Display", serif;
            letter-spacing:.2px;
            margin:0 0 6px;
            font-size:30px;
        }
        .hero-sub{
            margin:0;
            color:rgba(255,255,255,.85);
            font-size:15px;
        }
        .card-elevated{
            background:var(--paper);
            border:none;
            border-radius:18px;
            box-shadow:var(--shadow);
        }
        .form-label{
            font-weight:600;
            color:var(--muted);
        }
        .btn-brand{
            background:var(--brand);
            border-color:var(--brand);
            color:#fff;
            font-weight:600;
            letter-spacing:.2px;
        }
        .btn-brand:hover{
            background:#0284c7;
            border-color:#0284c7;
            color:#fff;
        }
        .badge-soft{
            background:rgba(14,165,233,.1);
            color:var(--brand-dark);
            border:1px solid rgba(14,165,233,.2);
            font-weight:600;
        }
        .public-hero .badge-soft{
            background:rgba(255,255,255,.18);
            color:#fff;
            border:1px solid rgba(255,255,255,.35);
        }
        .public-hero .btn-outline-light{
            border-color:rgba(255,255,255,.7);
            color:#fff;
        }
        .public-hero .btn-outline-light:hover{
            background:rgba(255,255,255,.12);
            color:#fff;
        }
        .divider{
            height:1px;
            background:linear-gradient(90deg, transparent, rgba(15,23,42,.15), transparent);
            margin:18px 0;
        }
        .input-hint{
            font-size:12px;
            color:#94a3b8;
        }
        @media (max-width: 768px){
            .public-shell{ padding:32px 14px 64px; }
            .hero-title{ font-size:24px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="public-shell">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
