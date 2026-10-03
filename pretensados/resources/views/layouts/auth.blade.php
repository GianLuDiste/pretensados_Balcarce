<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ingresar') · Pretensados Balcarce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #28324D;        /* fondo de la pieza gráfica */
            --navy-deep: #1E2740;   /* panel del formulario */
            --navy-field: #323D5E;  /* campos */
            --navy-line: #4A5578;
            --lime: #A0C728;        /* verde de los íconos y la palabra "medida" */
            --lime-hover: #B3DA35;
            --white: #ffffff;
            --soft: rgba(255,255,255,.78);
            --error-bg: rgba(255,120,120,.12);
            --error-ink: #FFB4B4;
            --font: "Montserrat", "Segoe UI", system-ui, -apple-system, sans-serif;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body { margin: 0; font-family: var(--font); color: var(--white); background: var(--navy); line-height: 1.5; }

        .shell { min-height: 100%; display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr); }

        /* ---- Lado de marca ---- */
        .brand { background: var(--navy); padding: clamp(1.5rem, 4vw, 3.5rem); display: flex; flex-direction: column; justify-content: space-between; gap: 2.5rem; }
        .logo { width: 210px; max-width: 60%; height: auto; display: block; }
        .brand-body { max-width: 30rem; }
        .brand h1 { font-size: clamp(2rem, 4.2vw, 3.3rem); line-height: 1.12; font-weight: 800; margin: 0 0 1.4rem; letter-spacing: -.01em; }
        .brand h1 .acc { color: var(--lime); }
        .rule { height: 2px; width: 100%; background: var(--lime); opacity: .85; margin: 0 0 1.4rem; border: 0; }
        .brand p.lead { margin: 0 0 2rem; color: var(--soft); font-weight: 500; max-width: 26rem; }
        .servicios { list-style: none; margin: 0; padding: 0; display: grid; gap: 1rem; }
        .servicios li { display: flex; align-items: center; gap: .9rem; font-weight: 700; line-height: 1.25; }
        .servicios svg { flex: 0 0 auto; width: 46px; height: 46px; }
        .brand small { color: rgba(255,255,255,.5); font-weight: 500; }

        /* ---- Lado del formulario ---- */
        .panel { background: var(--navy-deep); display: flex; align-items: center; justify-content: center; padding: clamp(1.5rem, 4vw, 3rem); }
        .card { width: 100%; max-width: 24rem; }
        .card h2 { font-size: 1.6rem; font-weight: 700; margin: 0 0 .35rem; }
        .card p.sub { margin: 0 0 1.6rem; color: var(--soft); font-size: .95rem; }
        .field { margin-bottom: 1.1rem; }
        label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: .35rem; color: var(--soft); }
        input[type=text], input[type=password], input[type=email] {
            width: 100%; padding: .8rem .9rem; font: inherit; color: var(--white);
            background: var(--navy-field); border: 1px solid var(--navy-line); border-radius: 6px;
        }
        input::placeholder { color: rgba(255,255,255,.4); }
        input:focus-visible, button:focus-visible, a:focus-visible, .check input:focus-visible { outline: 2px solid var(--lime); outline-offset: 2px; }
        input:focus { border-color: var(--lime); }
        .pw { position: relative; }
        .pw input { padding-right: 5.2rem; }
        .pw button { position: absolute; right: .35rem; top: 50%; transform: translateY(-50%); background: none; border: 0; color: var(--lime); font: inherit; font-size: .8rem; font-weight: 700; cursor: pointer; padding: .4rem .55rem; border-radius: 4px; }
        .row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin: .2rem 0 1.5rem; font-size: .9rem; }
        .check { display: flex; align-items: center; gap: .5rem; font-weight: 500; color: var(--soft); margin: 0; cursor: pointer; }
        .check input { width: 1.05rem; height: 1.05rem; accent-color: var(--lime); margin: 0; }
        a { color: var(--lime); font-weight: 600; text-underline-offset: 3px; }
        .btn { width: 100%; padding: .85rem 1rem; font: inherit; font-weight: 700; font-size: 1rem; color: var(--navy); background: var(--lime); border: 0; border-radius: 6px; cursor: pointer; }
        .btn:hover { background: var(--lime-hover); }
        .alert { padding: .7rem .85rem; border-radius: 6px; margin-bottom: 1.2rem; font-size: .9rem; }
        .alert.err { background: var(--error-bg); color: var(--error-ink); border: 1px solid rgba(255,120,120,.35); }
        .alert.ok  { background: rgba(160,199,40,.14); color: #D5EC8F; border: 1px solid rgba(160,199,40,.4); }
        .back { display: inline-block; margin-top: 1.4rem; font-size: .9rem; }

        @media (max-width: 880px) {
            .shell { grid-template-columns: 1fr; }
            .brand { gap: 1.2rem; padding-bottom: 1.5rem; }
            .brand p.lead, .servicios, .brand small { display: none; }
            .brand h1 { font-size: 1.8rem; margin-bottom: 1rem; }
            .rule { margin-bottom: 0; }
            .panel { align-items: flex-start; }
        }
    </style>
</head>
<body>
<div class="shell">
    <section class="brand">
        @include('auth._logo')

        <div class="brand-body">
            <h1>Soluciones de hormigón a <span class="acc">medida</span></h1>
            <hr class="rule">
            <p class="lead">En Pretensados Balcarce te ofrecemos calidad, experiencia y compromiso en cada proyecto.</p>
            <ul class="servicios">
                <li>
                    <svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="24" fill="#A0C728"/>
                        <rect x="14" y="10" width="20" height="4" rx="1" fill="#28324D"/>
                        <rect x="19" y="14" width="10" height="20" fill="#28324D"/><rect x="22" y="16" width="4" height="16" fill="#A0C728"/>
                        <rect x="14" y="34" width="20" height="4" rx="1" fill="#28324D"/></svg>
                    Fabricación<br>de columnas
                </li>
                <li>
                    <svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="24" fill="#A0C728"/>
                        <polygon points="9,19 15,11 40,11 36,19" fill="#28324D"/>
                        <rect x="9" y="19" width="27" height="15" rx="1" fill="#28324D"/>
                        <circle cx="16" cy="26.5" r="2.4" fill="#A0C728"/><circle cx="22.5" cy="26.5" r="2.4" fill="#A0C728"/><circle cx="29" cy="26.5" r="2.4" fill="#A0C728"/>
                        <polygon points="36,19 40,11 40,26 36,34" fill="#28324D" opacity=".75"/></svg>
                    Premoldeados<br>de hormigón
                </li>
                <li>
                    <svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="24" fill="#A0C728"/>
                        <rect x="8" y="26" width="11" height="9" rx="1.5" fill="#28324D"/>
                        <rect x="8" y="33" width="32" height="3" fill="#28324D"/>
                        <ellipse cx="29" cy="25" rx="11" ry="6.5" transform="rotate(-14 29 25)" fill="#28324D"/>
                        <circle cx="14" cy="37" r="3.6" fill="#28324D" stroke="#A0C728" stroke-width="1.5"/><circle cx="33" cy="37" r="3.6" fill="#28324D" stroke="#A0C728" stroke-width="1.5"/></svg>
                    Hormigón<br>elaborado
                </li>
            </ul>
        </div>

        <small>Sistema de gestión · Cotizaciones</small>
    </section>

    <section class="panel">
        <div class="card">
            @yield('form')
        </div>
    </section>
</div>
@stack('scripts')
</body>
</html>
