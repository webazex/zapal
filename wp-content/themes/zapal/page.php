<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0b0b">
    <title>ZAPAL — ведётся разработка</title>

    <style>
        :root {
            --bg: #0a0a0a;
            --bg-soft: #111111;
            --text: #f5f2ed;
            --muted: #9f9a93;
            --line: rgba(255, 255, 255, .11);
            --accent: #e53a24;
            --accent-soft: rgba(229, 58, 36, .16);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            background:
                    radial-gradient(circle at 78% 48%, rgba(229, 58, 36, .18), transparent 0 27%),
                    radial-gradient(circle at 78% 48%, rgba(229, 58, 36, .05), transparent 0 45%),
                    var(--bg);
            color: var(--text);
            font-family: Inter, Arial, Helvetica, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .12;
            background-image:
                    linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, transparent, #000 18%, #000 82%, transparent);
        }

        .page {
            position: relative;
            min-height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr auto;
            padding: 34px 42px 28px;
            isolation: isolate;
        }

        .page::after {
            content: "ZAPAL";
            position: fixed;
            z-index: -1;
            right: -2.2vw;
            top: 50%;
            transform: translateY(-50%) rotate(90deg);
            transform-origin: center;
            font-size: clamp(110px, 17vw, 290px);
            line-height: .78;
            font-weight: 900;
            letter-spacing: -.08em;
            color: rgba(255,255,255,.018);
            white-space: nowrap;
            user-select: none;
            pointer-events: none;
        }

        .header,
        .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            position: relative;
            z-index: 2;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--text);
            text-decoration: none;
            letter-spacing: .18em;
            font-size: 14px;
            font-weight: 800;
        }

        .brand__logo {
            display: block;
            width: 38px;
            height: 38px;
            object-fit: contain;
            flex: 0 0 auto;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--muted);
            font-size: 12px;
            letter-spacing: .11em;
            text-transform: uppercase;
        }

        .status__dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 0 rgba(229, 58, 36, .45);
            animation: pulse 2s infinite;
        }

        .main {
            width: min(1220px, 100%);
            margin: auto;
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(260px, .65fr);
            align-items: center;
            gap: clamp(44px, 7vw, 110px);
            padding: 80px 0 70px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 26px;
            color: var(--accent);
            font-size: 12px;
            font-weight: 750;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 46px;
            height: 1px;
            background: currentColor;
        }

        h1 {
            margin: 0;
            max-width: 880px;
            font-size: clamp(54px, 8vw, 128px);
            line-height: .89;
            letter-spacing: -.065em;
            font-weight: 850;
        }

        h1 span {
            display: block;
            color: transparent;
            -webkit-text-stroke: 1px rgba(245, 242, 237, .62);
        }

        .lead {
            max-width: 630px;
            margin: 30px 0 0;
            color: var(--muted);
            font-size: clamp(16px, 1.5vw, 20px);
            line-height: 1.65;
        }

        .progress {
            width: min(520px, 100%);
            margin-top: 42px;
        }

        .progress__meta {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 11px;
            color: rgba(245, 242, 237, .58);
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .progress__track {
            position: relative;
            height: 1px;
            overflow: hidden;
            background: var(--line);
        }

        .progress__track::after {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 32%;
            background: linear-gradient(90deg, transparent, var(--accent), #ff6b48);
            box-shadow: 0 0 20px rgba(229, 58, 36, .45);
            animation: loading 2.8s ease-in-out infinite;
        }

        .visual {
            position: relative;
            min-height: 430px;
            display: grid;
            place-items: center;
        }

        .visual__halo {
            position: absolute;
            width: min(35vw, 420px);
            aspect-ratio: 1;
            border: 1px solid rgba(229, 58, 36, .18);
            border-radius: 50%;
        }

        .visual__halo::before,
        .visual__halo::after {
            content: "";
            position: absolute;
            inset: 14%;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 50%;
        }

        .visual__halo::after {
            inset: 31%;
            border-color: rgba(229, 58, 36, .2);
        }

        .bottle {
            position: relative;
            width: 124px;
            height: 300px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 28px 28px 18px 18px;
            background:
                    linear-gradient(90deg, rgba(255,255,255,.025), transparent 28% 68%, rgba(255,255,255,.035)),
                    linear-gradient(180deg, rgba(229,58,36,.12), rgba(229,58,36,.025));
            box-shadow:
                    inset 0 0 46px rgba(229,58,36,.07),
                    0 50px 80px rgba(0,0,0,.42);
            transform: rotate(5deg);
            animation: float 5s ease-in-out infinite;
        }

        .bottle::before {
            content: "";
            position: absolute;
            left: 50%;
            top: -55px;
            width: 62px;
            height: 65px;
            transform: translateX(-50%);
            border: 1px solid rgba(255,255,255,.18);
            border-bottom: 0;
            border-radius: 10px 10px 4px 4px;
            background: #111;
        }

        .bottle__logo {
            position: absolute;
            left: 50%;
            top: 44%;
            width: 72px;
            height: 72px;
            transform: translate(-50%, -50%);
            object-fit: contain;
            filter: drop-shadow(0 0 18px rgba(229, 58, 36, .18));
        }

        .visual__caption {
            position: absolute;
            right: 0;
            bottom: 14px;
            width: 190px;
            padding-top: 13px;
            border-top: 1px solid var(--line);
            color: rgba(245, 242, 237, .42);
            font-size: 10px;
            line-height: 1.55;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .footer {
            padding-top: 22px;
            border-top: 1px solid var(--line);
        }

        .footer__copy {
            color: rgba(245, 242, 237, .34);
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .latul {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            color: rgba(245, 242, 237, .58);
            text-decoration: none;
            font-size: 11px;
            font-weight: 750;
            letter-spacing: .13em;
            text-transform: uppercase;
            transition: color .2s ease;
        }

        .latul:hover {
            color: var(--text);
        }

        .latul img {
            display: block;
            width: 25px;
            height: 25px;
            object-fit: contain;
            filter: grayscale(1) brightness(2);
            opacity: .82;
            transition: opacity .2s ease, transform .2s ease;
        }

        .latul:hover img {
            opacity: 1;
            transform: translateY(-1px);
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(229, 58, 36, .42); }
            50% { box-shadow: 0 0 0 8px rgba(229, 58, 36, 0); }
        }

        @keyframes loading {
            0% { transform: translateX(-120%); }
            55%, 100% { transform: translateX(420%); }
        }

        @keyframes float {
            0%, 100% { transform: rotate(5deg) translateY(0); }
            50% { transform: rotate(3deg) translateY(-13px); }
        }

        @media (max-width: 820px) {
            .page {
                padding: 24px 20px 22px;
            }

            .status {
                display: none;
            }

            .main {
                grid-template-columns: 1fr;
                gap: 54px;
                padding: 70px 0 60px;
            }

            h1 {
                font-size: clamp(52px, 15vw, 88px);
            }

            .visual {
                min-height: 350px;
            }

            .visual__halo {
                width: min(78vw, 360px);
            }

            .bottle {
                width: 104px;
                height: 250px;
            }

            .bottle::before {
                width: 54px;
                height: 57px;
                top: -49px;
            }

            .visual__caption {
                display: none;
            }

            .footer {
                align-items: flex-end;
            }

            .footer__copy {
                max-width: 180px;
                line-height: 1.55;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
</head>
<body>
<div class="page">
    <header class="header">
        <a class="brand" href="/" aria-label="ZAPAL">
            <img class="brand__logo" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyBpZD0iX9Co0LDRgF8xIiBkYXRhLW5hbWU9ItCo0LDRgCAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAgMjAwIj4KICA8ZGVmcz4KICAgIDxzdHlsZT4KICAgICAgLmNscy0xIHsKICAgICAgICBzdHJva2Utd2lkdGg6IC42cHg7CiAgICAgIH0KCiAgICAgIC5jbHMtMSwgLmNscy0yLCAuY2xzLTMsIC5jbHMtNCwgLmNscy01LCAuY2xzLTYgewogICAgICAgIGZpbGw6IG5vbmU7CiAgICAgIH0KCiAgICAgIC5jbHMtMSwgLmNscy0zLCAuY2xzLTQsIC5jbHMtNSwgLmNscy02IHsKICAgICAgICBzdHJva2UtbWl0ZXJsaW1pdDogMTA7CiAgICAgIH0KCiAgICAgIC5jbHMtMSwgLmNscy0zLCAuY2xzLTYgewogICAgICAgIHN0cm9rZTogIzFkMWQxYjsKICAgICAgfQoKICAgICAgLmNscy03IHsKICAgICAgICBmaWxsOiAjZmZmOwogICAgICB9CgogICAgICAuY2xzLTMgewogICAgICAgIHN0cm9rZS13aWR0aDogLjg1cHg7CiAgICAgIH0KCiAgICAgIC5jbHMtNCB7CiAgICAgICAgc3Ryb2tlLXdpZHRoOiAxMC4xcHg7CiAgICAgIH0KCiAgICAgIC5jbHMtNCwgLmNscy01IHsKICAgICAgICBzdHJva2U6ICNlMjA2MTM7CiAgICAgIH0KCiAgICAgIC5jbHMtNSB7CiAgICAgICAgc3Ryb2tlLXdpZHRoOiAuNjdweDsKICAgICAgfQoKICAgICAgLmNscy04IHsKICAgICAgICBmaWxsOiAjMWQxZDFiOwogICAgICB9CgogICAgICAuY2xzLTYgewogICAgICAgIHN0cm9rZS13aWR0aDogLjY5cHg7CiAgICAgIH0KICAgIDwvc3R5bGU+CiAgPC9kZWZzPgogIDxnPgogICAgPHBhdGggY2xhc3M9ImNscy00IiBkPSJNMTg1LDk1LjM4YzIuMjUsMi4zLDIuMjUsNi4wNCwwLDguMzRsLTgxLjQ1LDgzLjM4Yy0yLjI1LDIuMy01LjksMi4zLTguMTQsMEwxMy45NiwxMDMuNzJjLTIuMjUtMi4zLTIuMjUtNi4wNCwwLTguMzRMOTUuNDEsMTJjMi4yNS0yLjMsNS45LTIuMyw4LjE0LDBsODEuNDUsODMuMzhoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTE4NC44MSw5NS41YzIuMjUsMi4zLDIuMjUsNi4wNCwwLDguMzRsLTgxLjQ1LDgzLjM4Yy0yLjI1LDIuMy01LjksMi4zLTguMTUsMEwxMy43NiwxMDMuODRjLTIuMjUtMi4zLTIuMjUtNi4wNCwwLTguMzRMOTUuMjEsMTIuMTNjMi4yNS0yLjMsNS45LTIuMyw4LjE1LDBsODEuNDUsODMuMzhoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTE4MS41Nyw5NS42NWMyLjE2LDIuMjIsMi4xNiw1LjgyLDAsOC4wNGwtNzguMzcsODAuNDNjLTIuMTYsMi4yMi01LjY3LDIuMjItNy44NCwwTDE3LDEwMy42OWMtMi41NC0yLjc1LTIuOTctNS4yMywwLTguMDRMOTUuMzcsMTUuMjFjMi4xNi0yLjIyLDUuNjctMi4yMiw3Ljg0LDBsNzguMzcsODAuNDNoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTE4MS41Nyw5NS42NWMyLjE2LDIuMjIsMi4xNiw1LjgyLDAsOC4wNGwtNzguMzcsODAuNDNjLTIuMTYsMi4yMi01LjY3LDIuMjItNy44NCwwTDE3LDEwMy42OWMtMi41NC0yLjc1LTIuOTctNS4yMywwLTguMDRMOTUuMzcsMTUuMjFjMi4xNi0yLjIyLDUuNjctMi4yMiw3Ljg0LDBsNzguMzcsODAuNDNoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtMyIgZD0iTTE4MS41Nyw5NS42NWMyLjE2LDIuMjIsMi4xNiw1LjgyLDAsOC4wNGwtNzguMzcsODAuNDNjLTIuMTYsMi4yMi01LjY3LDIuMjItNy44NCwwTDE3LDEwMy42OWMtMi41NC0yLjc1LTIuOTctNS4yMywwLTguMDRMOTUuMzcsMTUuMjFjMi4xNi0yLjIyLDUuNjctMi4yMiw3Ljg0LDBsNzguMzcsODAuNDNoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtNyIgZD0iTTE4MS41Nyw5NS42NWMyLjE2LDIuMjIsMi4xNiw1LjgyLDAsOC4wNGwtNzguMzcsODAuNDNjLTIuMTYsMi4yMi01LjY3LDIuMjItNy44NCwwTDE3LDEwMy42OWMtMi41NC0yLjc1LTIuOTctNS4yMywwLTguMDRMOTUuMzcsMTUuMjFjMi4xNi0yLjIyLDUuNjctMi4yMiw3Ljg0LDBsNzguMzcsODAuNDNoMFoiLz4KICAgIDxnPgogICAgICA8Zz4KICAgICAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik01OS4xLDc2LjY2aDEzLjk5djMuMmwtOC45OCw5LjM2aDkuMzF2My40NGgtMTUuMTh2LTMuMzJsOC44OS05LjI2aC04LjAydi0zLjQyWiIvPgogICAgICAgIDxwYXRoIGNsYXNzPSJjbHMtOCIgZD0iTTg2LjYyLDkwLjAxaC01LjYxbC0uNzgsMi42NGgtNS4wNWw2LjAxLTE2aDUuMzlsNi4wMSwxNmgtNS4xOGwtLjgtMi42NFpNODUuNTksODYuNTZsLTEuNzctNS43NS0xLjc1LDUuNzVoMy41MVoiLz4KICAgICAgPC9nPgogICAgICA8Zz4KICAgICAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik05NS4yOCw3Ni42Nmg4LjIyYzEuNzksMCwzLjEzLjQzLDQuMDIsMS4yOC44OS44NSwxLjM0LDIuMDYsMS4zNCwzLjYzcy0uNDksMi44OC0xLjQ2LDMuNzljLS45Ny45MS0yLjQ1LDEuMzYtNC40NSwxLjM2aC0yLjcxdjUuOTRoLTQuOTZ2LTE2Wk0xMDAuMjUsODMuNDhoMS4yMWMuOTUsMCwxLjYyLS4xNywyLjAxLS41LjM5LS4zMy41OC0uNzUuNTgtMS4yN3MtLjE3LS45My0uNS0xLjI4Yy0uMzQtLjM1LS45Ni0uNTItMS44OS0uNTJoLTEuNDF2My41N1oiLz4KICAgICAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik0xMjAuNTIsOTAuMDFoLTUuNjFsLS43OCwyLjY0aC01LjA1bDYuMDEtMTZoNS4zOWw2LjAxLDE2aC01LjE4bC0uOC0yLjY0Wk0xMTkuNDksODYuNTZsLTEuNzctNS43NS0xLjc1LDUuNzVoMy41MVoiLz4KICAgICAgPC9nPgogICAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik0xMjkuNTIsNzYuMzRoNC45NHYxMi4wNmg3LjcydjMuOTRoLTEyLjY2di0xNloiLz4KICAgICAgPGc+CiAgICAgICAgPHBhdGggY2xhc3M9ImNscy01IiBkPSJNNTkuMSw3Ni42NmgxMy45OXYzLjJsLTguOTgsOS4zNmg5LjMxdjMuNDRoLTE1LjE4di0zLjMybDguODktOS4yNmgtOC4wMnYtMy40MloiLz4KICAgICAgICA8cGF0aCBjbGFzcz0iY2xzLTUiIGQ9Ik04Ni42Miw5MC4wMWgtNS42MWwtLjc4LDIuNjRoLTUuMDVsNi4wMS0xNmg1LjM5bDYuMDEsMTZoLTUuMThsLS44LTIuNjRaTTg1LjU5LDg2LjU2bC0xLjc3LTUuNzUtMS43NSw1Ljc1aDMuNTFaIi8+CiAgICAgIDwvZz4KICAgICAgPGc+CiAgICAgICAgPHBhdGggY2xhc3M9ImNscy01IiBkPSJNOTUuMjgsNzYuNjZoOC4yMmMxLjc5LDAsMy4xMy40Myw0LjAyLDEuMjguODkuODUsMS4zNCwyLjA2LDEuMzQsMy42M3MtLjQ5LDIuODgtMS40NiwzLjc5Yy0uOTcuOTEtMi40NSwxLjM2LTQuNDUsMS4zNmgtMi43MXY1Ljk0aC00Ljk2di0xNlpNMTAwLjI1LDgzLjQ4aDEuMjFjLjk1LDAsMS42Mi0uMTcsMi4wMS0uNS4zOS0uMzMuNTgtLjc1LjU4LTEuMjdzLS4xNy0uOTMtLjUtMS4yOGMtLjM0LS4zNS0uOTYtLjUyLTEuODktLjUyaC0xLjQxdjMuNTdaIi8+CiAgICAgICAgPHBhdGggY2xhc3M9ImNscy01IiBkPSJNMTIwLjUyLDkwLjAxaC01LjYxbC0uNzgsMi42NGgtNS4wNWw2LjAxLTE2aDUuMzlsNi4wMSwxNmgtNS4xOGwtLjgtMi42NFpNMTE5LjQ5LDg2LjU2bC0xLjc3LTUuNzUtMS43NSw1Ljc1aDMuNTFaIi8+CiAgICAgIDwvZz4KICAgICAgPHBhdGggY2xhc3M9ImNscy01IiBkPSJNMTI5LjUyLDc2LjM0aDQuOTR2MTIuMDZoNy43MnYzLjk0aC0xMi42NnYtMTZaIi8+CiAgICAgIDxwYXRoIGQ9Ik0xNDUuMTUsNzQuNGMwLC45Mi0uMzMsMS43MS0uOTgsMi4zNnMtMS40NC45OC0yLjM2Ljk4LTEuNzEtLjMzLTIuMzYtLjk4LS45OC0xLjQ0LS45OC0yLjM2LjMzLTEuNy45OC0yLjM2LDEuNDQtLjk4LDIuMzYtLjk4LDEuNzEuMzMsMi4zNi45OC45OCwxLjQ0Ljk4LDIuMzZaTTE0NC43LDc0LjRjMC0uOC0uMjgtMS40OS0uODUtMi4wNnMtMS4yNC0uODUtMi4wNC0uODUtMS40OC4yOC0yLjA0Ljg1LS44NSwxLjI1LS44NSwyLjA2LjI4LDEuNDkuODUsMi4wNiwxLjI0Ljg1LDIuMDQuODUsMS40OC0uMjgsMi4wNC0uODUuODUtMS4yNS44NS0yLjA2Wk0xNDMuNzMsNzYuMDloLS44N2wtMS4wOS0xLjM3aC0uNDd2MS4zN2gtLjY0di0zLjUzaDEuMDhjLjI0LDAsLjQzLDAsLjU3LjAzcy4yOC4wNy40My4xNWMuMTUuMDkuMjcuMTkuMzQuMzJzLjEuMjguMS40N2MwLC4yNi0uMDcuNDctLjIxLjYzcy0uMzMuMjktLjU2LjM5bDEuMzMsMS41NFpNMTQyLjQ2LDczLjU4YzAtLjEtLjAyLS4xOC0uMDUtLjI1cy0uMDgtLjEzLS4xNi0uMThjLS4wNi0uMDQtLjE0LS4wNy0uMjItLjA4cy0uMTgtLjAyLS4zMS0uMDJoLS40NHYxLjE5aC4zOGMuMTIsMCwuMjQtLjAxLjM1LS4wNHMuMi0uMDcuMjYtLjEzLjExLS4xMy4xNC0uMi4wNC0uMTguMDQtLjNaIi8+CiAgICA8L2c+CiAgICA8cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xODQuODEsOTUuNWMyLjI1LDIuMywyLjI1LDYuMDQsMCw4LjM0bC04MS40NSw4My4zOGMtMi4yNSwyLjMtNS45LDIuMy04LjE1LDBMMTMuNzYsMTAzLjg0Yy0yLjI1LTIuMy0yLjI1LTYuMDQsMC04LjM0TDk1LjIxLDEyLjEzYzIuMjUtMi4zLDUuOS0yLjMsOC4xNSwwbDgxLjQ1LDgzLjM4aDBaIi8+CiAgPC9nPgogIDxnPgogICAgPHBhdGggY2xhc3M9ImNscy04IiBkPSJNNjQuMDYsMTE2LjFjMCwuNTMtLjA5LDEuMDUtLjI4LDEuNTUtLjE4LjUtLjQ2Ljk1LS44NCwxLjM0LS4zNy4zOS0uODUuNzEtMS40Mi45NC0uNTcuMjMtMS4yNS4zNS0yLjAzLjM1aC0yLjAydjQuNzdoLTMuNzZ2LTEzLjEyaDUuNTFjLjg2LDAsMS42LjEsMi4yMS4zMXMxLjEyLjUsMS41Ljg3Yy4zOS4zNy42Ny44Ljg1LDEuMzEuMTguNTEuMjcsMS4wNi4yNywxLjY3Wk01Ny40OSwxMTQuNDh2My4zM2gxLjI0Yy40MywwLC44LS4xMywxLjA5LS4zOC4zLS4yNS40NS0uNjcuNDUtMS4yNSwwLS42Mi0uMTYtMS4wNi0uNDctMS4zMS0uMzEtLjI1LS43LS4zOC0xLjE3LS4zOGgtMS4xNFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtOCIgZD0iTTc0LjA4LDExOS4xOWMuMjguMS41Mi4yMy43Mi4zOS4yLjE2LjM3LjM0LjUuNTQuMTMuMi4yNC40Mi4zMy42NS4wOS4yMy4xNy40Ni4yNS43bDEuMDMsMy41N2gtMy43MWwtLjk3LTMuMzFjLS4xOC0uNjUtLjQyLTEuMDgtLjcyLTEuMzEtLjMtLjIzLS43Mi0uMzQtMS4yNC0uMzRoLS44NnY0Ljk2aC0zLjc1di0xMy4xMmg1Ljk5Yy44NCwwLDEuNTUuMTEsMi4xMy4zMi41OC4yMSwxLjA2LjUsMS40Mi44NnMuNjIuNzguNzksMS4yNWMuMTYuNDcuMjUuOTUuMjUsMS40NCwwLC4zNC0uMDQuNjgtLjEzLDEuMDItLjA5LjM0LS4yMi42NS0uNC45NC0uMTguMjktLjQuNTYtLjY4LjgtLjI3LjI0LS41OS40NC0uOTYuNTl2LjA0Wk03Mi40NywxMTYuMDJjMC0uNDgtLjE0LS44Ni0uNDMtMS4xMi0uMjktLjI3LS42Ny0uNC0xLjE1LS40aC0xLjQ2djMuMTJoMS40NmMuMiwwLC40LS4wMy41OS0uMDlzLjM2LS4xNi41LS4yOWMuMTUtLjEzLjI2LS4yOS4zNS0uNS4wOS0uMi4xMy0uNDQuMTMtLjcyWiIvPgogICAgPHBhdGggY2xhc3M9ImNscy04IiBkPSJNNzguODIsMTI1LjA1di0xMy4xMmg5Ljg1djIuNTVoLTYuMDd2Mi40OWg1LjA4djIuNTFoLTUuMDh2Mi45N2g2LjMxdjIuNmgtMTAuMVoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtOCIgZD0iTTEwMi42OCwxMTcuMzVsLTEuMiwzLjAyLTEuMzksMy4zNWgtMi45M2wtMS4zOS0zLjUyLTEuMDgtMi44N2gtLjA4djcuNzJoLTMuNjF2LTEzLjEyaDQuNzNsMS43Nyw0LjcsMS4wOCwzLjAyaC4xN2wxLjIyLTMuMjEsMS44My00LjUxaDQuNnYxMy4xMmgtMy42NXYtNy43aC0uMDhaIi8+CiAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik0xMDkuMDksMTI1LjA1di0xMy4xMmgzLjczdjEzLjEyaC0zLjczWiIvPgogICAgPHBhdGggY2xhc3M9ImNscy04IiBkPSJNMTI3LjEzLDEyMC4xM2MwLC44MS0uMTEsMS41My0uMzMsMi4xNy0uMjIuNjMtLjU3LDEuMTctMS4wNSwxLjYxLS40OC40NC0xLjA4Ljc3LTEuODIsMS0uNzQuMjMtMS42MS4zNC0yLjYyLjM0LTEuMDYsMC0xLjk3LS4xMi0yLjcyLS4zNS0uNzUtLjI0LTEuMzUtLjU3LTEuODItMS4wMS0uNDYtLjQ0LS44LS45Ny0xLjAyLTEuNjEtLjIyLS42My0uMzItMS4zNS0uMzItMi4xNXYtOC4xOWgzLjY5djguMzhjMCwuODMuMTksMS40Mi41NywxLjc4LjM4LjM2LjkzLjU0LDEuNjUuNTQuMywwLC41OS0uMDQuODUtLjEyLjI2LS4wOC40OS0uMjIuNjgtLjQuMi0uMTguMzUtLjQyLjQ2LS43MXMuMTYtLjY1LjE2LTEuMDlsLS4wMi04LjM4aDMuNjdsLS4wMiw4LjE5WiIvPgogICAgPHBhdGggY2xhc3M9ImNscy04IiBkPSJNMTQxLjMyLDExNy4zNWwtMS4yLDMuMDItMS4zOSwzLjM1aC0yLjkzbC0xLjM5LTMuNTItMS4wOC0yLjg3aC0uMDh2Ny43MmgtMy42MXYtMTMuMTJoNC43M2wxLjc3LDQuNywxLjA4LDMuMDJoLjE3bDEuMjItMy4yMSwxLjgzLTQuNTFoNC42djEzLjEyaC0zLjY1di03LjdoLS4wOFoiLz4KICA8L2c+CiAgPGxpbmUgY2xhc3M9ImNscy02IiB4MT0iMzkuNCIgeTE9IjEwMS43IiB4Mj0iMTU5LjM3IiB5Mj0iMTAxLjciLz4KPC9zdmc+" alt="ZAPAL">
            <span>ZAPAL</span>
        </a>

        <div class="status" aria-label="Статус проекта">
            <span class="status__dot" aria-hidden="true"></span>
            В разработке
        </div>
    </header>

    <main class="main">
        <section>
            <div class="eyebrow">Скоро запуск</div>

            <h1>
                Ведётся
                <span>разработка.</span>
            </h1>

            <p class="lead">
                Пожалуйста, подождите. Мы готовим новую версию сайта ZAPAL
                и приводим всё в порядок перед запуском.
            </p>

            <div class="progress" aria-hidden="true">
                <div class="progress__meta">
                    <span>Подготовка сайта</span>
                    <span>In progress</span>
                </div>
                <div class="progress__track"></div>
            </div>
        </section>

        <aside class="visual" aria-hidden="true">
            <div class="visual__halo"></div>
            <div class="bottle">
                <img class="bottle__logo" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyBpZD0iX9Co0LDRgF8xIiBkYXRhLW5hbWU9ItCo0LDRgCAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAgMjAwIj4KICA8ZGVmcz4KICAgIDxzdHlsZT4KICAgICAgLmNscy0xIHsKICAgICAgICBzdHJva2Utd2lkdGg6IC42cHg7CiAgICAgIH0KCiAgICAgIC5jbHMtMSwgLmNscy0yLCAuY2xzLTMsIC5jbHMtNCwgLmNscy01LCAuY2xzLTYgewogICAgICAgIGZpbGw6IG5vbmU7CiAgICAgIH0KCiAgICAgIC5jbHMtMSwgLmNscy0zLCAuY2xzLTQsIC5jbHMtNSwgLmNscy02IHsKICAgICAgICBzdHJva2UtbWl0ZXJsaW1pdDogMTA7CiAgICAgIH0KCiAgICAgIC5jbHMtMSwgLmNscy0zLCAuY2xzLTYgewogICAgICAgIHN0cm9rZTogIzFkMWQxYjsKICAgICAgfQoKICAgICAgLmNscy03IHsKICAgICAgICBmaWxsOiAjZmZmOwogICAgICB9CgogICAgICAuY2xzLTMgewogICAgICAgIHN0cm9rZS13aWR0aDogLjg1cHg7CiAgICAgIH0KCiAgICAgIC5jbHMtNCB7CiAgICAgICAgc3Ryb2tlLXdpZHRoOiAxMC4xcHg7CiAgICAgIH0KCiAgICAgIC5jbHMtNCwgLmNscy01IHsKICAgICAgICBzdHJva2U6ICNlMjA2MTM7CiAgICAgIH0KCiAgICAgIC5jbHMtNSB7CiAgICAgICAgc3Ryb2tlLXdpZHRoOiAuNjdweDsKICAgICAgfQoKICAgICAgLmNscy04IHsKICAgICAgICBmaWxsOiAjMWQxZDFiOwogICAgICB9CgogICAgICAuY2xzLTYgewogICAgICAgIHN0cm9rZS13aWR0aDogLjY5cHg7CiAgICAgIH0KICAgIDwvc3R5bGU+CiAgPC9kZWZzPgogIDxnPgogICAgPHBhdGggY2xhc3M9ImNscy00IiBkPSJNMTg1LDk1LjM4YzIuMjUsMi4zLDIuMjUsNi4wNCwwLDguMzRsLTgxLjQ1LDgzLjM4Yy0yLjI1LDIuMy01LjksMi4zLTguMTQsMEwxMy45NiwxMDMuNzJjLTIuMjUtMi4zLTIuMjUtNi4wNCwwLTguMzRMOTUuNDEsMTJjMi4yNS0yLjMsNS45LTIuMyw4LjE0LDBsODEuNDUsODMuMzhoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTE4NC44MSw5NS41YzIuMjUsMi4zLDIuMjUsNi4wNCwwLDguMzRsLTgxLjQ1LDgzLjM4Yy0yLjI1LDIuMy01LjksMi4zLTguMTUsMEwxMy43NiwxMDMuODRjLTIuMjUtMi4zLTIuMjUtNi4wNCwwLTguMzRMOTUuMjEsMTIuMTNjMi4yNS0yLjMsNS45LTIuMyw4LjE1LDBsODEuNDUsODMuMzhoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTE4MS41Nyw5NS42NWMyLjE2LDIuMjIsMi4xNiw1LjgyLDAsOC4wNGwtNzguMzcsODAuNDNjLTIuMTYsMi4yMi01LjY3LDIuMjItNy44NCwwTDE3LDEwMy42OWMtMi41NC0yLjc1LTIuOTctNS4yMywwLTguMDRMOTUuMzcsMTUuMjFjMi4xNi0yLjIyLDUuNjctMi4yMiw3Ljg0LDBsNzguMzcsODAuNDNoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTE4MS41Nyw5NS42NWMyLjE2LDIuMjIsMi4xNiw1LjgyLDAsOC4wNGwtNzguMzcsODAuNDNjLTIuMTYsMi4yMi01LjY3LDIuMjItNy44NCwwTDE3LDEwMy42OWMtMi41NC0yLjc1LTIuOTctNS4yMywwLTguMDRMOTUuMzcsMTUuMjFjMi4xNi0yLjIyLDUuNjctMi4yMiw3Ljg0LDBsNzguMzcsODAuNDNoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtMyIgZD0iTTE4MS41Nyw5NS42NWMyLjE2LDIuMjIsMi4xNiw1LjgyLDAsOC4wNGwtNzguMzcsODAuNDNjLTIuMTYsMi4yMi01LjY3LDIuMjItNy44NCwwTDE3LDEwMy42OWMtMi41NC0yLjc1LTIuOTctNS4yMywwLTguMDRMOTUuMzcsMTUuMjFjMi4xNi0yLjIyLDUuNjctMi4yMiw3Ljg0LDBsNzguMzcsODAuNDNoMFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtNyIgZD0iTTE4MS41Nyw5NS42NWMyLjE2LDIuMjIsMi4xNiw1LjgyLDAsOC4wNGwtNzguMzcsODAuNDNjLTIuMTYsMi4yMi01LjY3LDIuMjItNy44NCwwTDE3LDEwMy42OWMtMi41NC0yLjc1LTIuOTctNS4yMywwLTguMDRMOTUuMzcsMTUuMjFjMi4xNi0yLjIyLDUuNjctMi4yMiw3Ljg0LDBsNzguMzcsODAuNDNoMFoiLz4KICAgIDxnPgogICAgICA8Zz4KICAgICAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik01OS4xLDc2LjY2aDEzLjk5djMuMmwtOC45OCw5LjM2aDkuMzF2My40NGgtMTUuMTh2LTMuMzJsOC44OS05LjI2aC04LjAydi0zLjQyWiIvPgogICAgICAgIDxwYXRoIGNsYXNzPSJjbHMtOCIgZD0iTTg2LjYyLDkwLjAxaC01LjYxbC0uNzgsMi42NGgtNS4wNWw2LjAxLTE2aDUuMzlsNi4wMSwxNmgtNS4xOGwtLjgtMi42NFpNODUuNTksODYuNTZsLTEuNzctNS43NS0xLjc1LDUuNzVoMy41MVoiLz4KICAgICAgPC9nPgogICAgICA8Zz4KICAgICAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik05NS4yOCw3Ni42Nmg4LjIyYzEuNzksMCwzLjEzLjQzLDQuMDIsMS4yOC44OS44NSwxLjM0LDIuMDYsMS4zNCwzLjYzcy0uNDksMi44OC0xLjQ2LDMuNzljLS45Ny45MS0yLjQ1LDEuMzYtNC40NSwxLjM2aC0yLjcxdjUuOTRoLTQuOTZ2LTE2Wk0xMDAuMjUsODMuNDhoMS4yMWMuOTUsMCwxLjYyLS4xNywyLjAxLS41LjM5LS4zMy41OC0uNzUuNTgtMS4yN3MtLjE3LS45My0uNS0xLjI4Yy0uMzQtLjM1LS45Ni0uNTItMS44OS0uNTJoLTEuNDF2My41N1oiLz4KICAgICAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik0xMjAuNTIsOTAuMDFoLTUuNjFsLS43OCwyLjY0aC01LjA1bDYuMDEtMTZoNS4zOWw2LjAxLDE2aC01LjE4bC0uOC0yLjY0Wk0xMTkuNDksODYuNTZsLTEuNzctNS43NS0xLjc1LDUuNzVoMy41MVoiLz4KICAgICAgPC9nPgogICAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik0xMjkuNTIsNzYuMzRoNC45NHYxMi4wNmg3LjcydjMuOTRoLTEyLjY2di0xNloiLz4KICAgICAgPGc+CiAgICAgICAgPHBhdGggY2xhc3M9ImNscy01IiBkPSJNNTkuMSw3Ni42NmgxMy45OXYzLjJsLTguOTgsOS4zNmg5LjMxdjMuNDRoLTE1LjE4di0zLjMybDguODktOS4yNmgtOC4wMnYtMy40MloiLz4KICAgICAgICA8cGF0aCBjbGFzcz0iY2xzLTUiIGQ9Ik04Ni42Miw5MC4wMWgtNS42MWwtLjc4LDIuNjRoLTUuMDVsNi4wMS0xNmg1LjM5bDYuMDEsMTZoLTUuMThsLS44LTIuNjRaTTg1LjU5LDg2LjU2bC0xLjc3LTUuNzUtMS43NSw1Ljc1aDMuNTFaIi8+CiAgICAgIDwvZz4KICAgICAgPGc+CiAgICAgICAgPHBhdGggY2xhc3M9ImNscy01IiBkPSJNOTUuMjgsNzYuNjZoOC4yMmMxLjc5LDAsMy4xMy40Myw0LjAyLDEuMjguODkuODUsMS4zNCwyLjA2LDEuMzQsMy42M3MtLjQ5LDIuODgtMS40NiwzLjc5Yy0uOTcuOTEtMi40NSwxLjM2LTQuNDUsMS4zNmgtMi43MXY1Ljk0aC00Ljk2di0xNlpNMTAwLjI1LDgzLjQ4aDEuMjFjLjk1LDAsMS42Mi0uMTcsMi4wMS0uNS4zOS0uMzMuNTgtLjc1LjU4LTEuMjdzLS4xNy0uOTMtLjUtMS4yOGMtLjM0LS4zNS0uOTYtLjUyLTEuODktLjUyaC0xLjQxdjMuNTdaIi8+CiAgICAgICAgPHBhdGggY2xhc3M9ImNscy01IiBkPSJNMTIwLjUyLDkwLjAxaC01LjYxbC0uNzgsMi42NGgtNS4wNWw2LjAxLTE2aDUuMzlsNi4wMSwxNmgtNS4xOGwtLjgtMi42NFpNMTE5LjQ5LDg2LjU2bC0xLjc3LTUuNzUtMS43NSw1Ljc1aDMuNTFaIi8+CiAgICAgIDwvZz4KICAgICAgPHBhdGggY2xhc3M9ImNscy01IiBkPSJNMTI5LjUyLDc2LjM0aDQuOTR2MTIuMDZoNy43MnYzLjk0aC0xMi42NnYtMTZaIi8+CiAgICAgIDxwYXRoIGQ9Ik0xNDUuMTUsNzQuNGMwLC45Mi0uMzMsMS43MS0uOTgsMi4zNnMtMS40NC45OC0yLjM2Ljk4LTEuNzEtLjMzLTIuMzYtLjk4LS45OC0xLjQ0LS45OC0yLjM2LjMzLTEuNy45OC0yLjM2LDEuNDQtLjk4LDIuMzYtLjk4LDEuNzEuMzMsMi4zNi45OC45OCwxLjQ0Ljk4LDIuMzZaTTE0NC43LDc0LjRjMC0uOC0uMjgtMS40OS0uODUtMi4wNnMtMS4yNC0uODUtMi4wNC0uODUtMS40OC4yOC0yLjA0Ljg1LS44NSwxLjI1LS44NSwyLjA2LjI4LDEuNDkuODUsMi4wNiwxLjI0Ljg1LDIuMDQuODUsMS40OC0uMjgsMi4wNC0uODUuODUtMS4yNS44NS0yLjA2Wk0xNDMuNzMsNzYuMDloLS44N2wtMS4wOS0xLjM3aC0uNDd2MS4zN2gtLjY0di0zLjUzaDEuMDhjLjI0LDAsLjQzLDAsLjU3LjAzcy4yOC4wNy40My4xNWMuMTUuMDkuMjcuMTkuMzQuMzJzLjEuMjguMS40N2MwLC4yNi0uMDcuNDctLjIxLjYzcy0uMzMuMjktLjU2LjM5bDEuMzMsMS41NFpNMTQyLjQ2LDczLjU4YzAtLjEtLjAyLS4xOC0uMDUtLjI1cy0uMDgtLjEzLS4xNi0uMThjLS4wNi0uMDQtLjE0LS4wNy0uMjItLjA4cy0uMTgtLjAyLS4zMS0uMDJoLS40NHYxLjE5aC4zOGMuMTIsMCwuMjQtLjAxLjM1LS4wNHMuMi0uMDcuMjYtLjEzLjExLS4xMy4xNC0uMi4wNC0uMTguMDQtLjNaIi8+CiAgICA8L2c+CiAgICA8cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xODQuODEsOTUuNWMyLjI1LDIuMywyLjI1LDYuMDQsMCw4LjM0bC04MS40NSw4My4zOGMtMi4yNSwyLjMtNS45LDIuMy04LjE1LDBMMTMuNzYsMTAzLjg0Yy0yLjI1LTIuMy0yLjI1LTYuMDQsMC04LjM0TDk1LjIxLDEyLjEzYzIuMjUtMi4zLDUuOS0yLjMsOC4xNSwwbDgxLjQ1LDgzLjM4aDBaIi8+CiAgPC9nPgogIDxnPgogICAgPHBhdGggY2xhc3M9ImNscy04IiBkPSJNNjQuMDYsMTE2LjFjMCwuNTMtLjA5LDEuMDUtLjI4LDEuNTUtLjE4LjUtLjQ2Ljk1LS44NCwxLjM0LS4zNy4zOS0uODUuNzEtMS40Mi45NC0uNTcuMjMtMS4yNS4zNS0yLjAzLjM1aC0yLjAydjQuNzdoLTMuNzZ2LTEzLjEyaDUuNTFjLjg2LDAsMS42LjEsMi4yMS4zMXMxLjEyLjUsMS41Ljg3Yy4zOS4zNy42Ny44Ljg1LDEuMzEuMTguNTEuMjcsMS4wNi4yNywxLjY3Wk01Ny40OSwxMTQuNDh2My4zM2gxLjI0Yy40MywwLC44LS4xMywxLjA5LS4zOC4zLS4yNS40NS0uNjcuNDUtMS4yNSwwLS42Mi0uMTYtMS4wNi0uNDctMS4zMS0uMzEtLjI1LS43LS4zOC0xLjE3LS4zOGgtMS4xNFoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtOCIgZD0iTTc0LjA4LDExOS4xOWMuMjguMS41Mi4yMy43Mi4zOS4yLjE2LjM3LjM0LjUuNTQuMTMuMi4yNC40Mi4zMy42NS4wOS4yMy4xNy40Ni4yNS43bDEuMDMsMy41N2gtMy43MWwtLjk3LTMuMzFjLS4xOC0uNjUtLjQyLTEuMDgtLjcyLTEuMzEtLjMtLjIzLS43Mi0uMzQtMS4yNC0uMzRoLS44NnY0Ljk2aC0zLjc1di0xMy4xMmg1Ljk5Yy44NCwwLDEuNTUuMTEsMi4xMy4zMi41OC4yMSwxLjA2LjUsMS40Mi44NnMuNjIuNzguNzksMS4yNWMuMTYuNDcuMjUuOTUuMjUsMS40NCwwLC4zNC0uMDQuNjgtLjEzLDEuMDItLjA5LjM0LS4yMi42NS0uNC45NC0uMTguMjktLjQuNTYtLjY4LjgtLjI3LjI0LS41OS40NC0uOTYuNTl2LjA0Wk03Mi40NywxMTYuMDJjMC0uNDgtLjE0LS44Ni0uNDMtMS4xMi0uMjktLjI3LS42Ny0uNC0xLjE1LS40aC0xLjQ2djMuMTJoMS40NmMuMiwwLC40LS4wMy41OS0uMDlzLjM2LS4xNi41LS4yOWMuMTUtLjEzLjI2LS4yOS4zNS0uNS4wOS0uMi4xMy0uNDQuMTMtLjcyWiIvPgogICAgPHBhdGggY2xhc3M9ImNscy04IiBkPSJNNzguODIsMTI1LjA1di0xMy4xMmg5Ljg1djIuNTVoLTYuMDd2Mi40OWg1LjA4djIuNTFoLTUuMDh2Mi45N2g2LjMxdjIuNmgtMTAuMVoiLz4KICAgIDxwYXRoIGNsYXNzPSJjbHMtOCIgZD0iTTEwMi42OCwxMTcuMzVsLTEuMiwzLjAyLTEuMzksMy4zNWgtMi45M2wtMS4zOS0zLjUyLTEuMDgtMi44N2gtLjA4djcuNzJoLTMuNjF2LTEzLjEyaDQuNzNsMS43Nyw0LjcsMS4wOCwzLjAyaC4xN2wxLjIyLTMuMjEsMS44My00LjUxaDQuNnYxMy4xMmgtMy42NXYtNy43aC0uMDhaIi8+CiAgICA8cGF0aCBjbGFzcz0iY2xzLTgiIGQ9Ik0xMDkuMDksMTI1LjA1di0xMy4xMmgzLjczdjEzLjEyaC0zLjczWiIvPgogICAgPHBhdGggY2xhc3M9ImNscy04IiBkPSJNMTI3LjEzLDEyMC4xM2MwLC44MS0uMTEsMS41My0uMzMsMi4xNy0uMjIuNjMtLjU3LDEuMTctMS4wNSwxLjYxLS40OC40NC0xLjA4Ljc3LTEuODIsMS0uNzQuMjMtMS42MS4zNC0yLjYyLjM0LTEuMDYsMC0xLjk3LS4xMi0yLjcyLS4zNS0uNzUtLjI0LTEuMzUtLjU3LTEuODItMS4wMS0uNDYtLjQ0LS44LS45Ny0xLjAyLTEuNjEtLjIyLS42My0uMzItMS4zNS0uMzItMi4xNXYtOC4xOWgzLjY5djguMzhjMCwuODMuMTksMS40Mi41NywxLjc4LjM4LjM2LjkzLjU0LDEuNjUuNTQuMywwLC41OS0uMDQuODUtLjEyLjI2LS4wOC40OS0uMjIuNjgtLjQuMi0uMTguMzUtLjQyLjQ2LS43MXMuMTYtLjY1LjE2LTEuMDlsLS4wMi04LjM4aDMuNjdsLS4wMiw4LjE5WiIvPgogICAgPHBhdGggY2xhc3M9ImNscy04IiBkPSJNMTQxLjMyLDExNy4zNWwtMS4yLDMuMDItMS4zOSwzLjM1aC0yLjkzbC0xLjM5LTMuNTItMS4wOC0yLjg3aC0uMDh2Ny43MmgtMy42MXYtMTMuMTJoNC43M2wxLjc3LDQuNywxLjA4LDMuMDJoLjE3bDEuMjItMy4yMSwxLjgzLTQuNTFoNC42djEzLjEyaC0zLjY1di03LjdoLS4wOFoiLz4KICA8L2c+CiAgPGxpbmUgY2xhc3M9ImNscy02IiB4MT0iMzkuNCIgeTE9IjEwMS43IiB4Mj0iMTU5LjM3IiB5Mj0iMTAxLjciLz4KPC9zdmc+" alt="">
            </div>
            <div class="visual__caption">
                Professional solutions<br>
                for HoReCa
            </div>
        </aside>
    </main>

    <footer class="footer">
        <div class="footer__copy">
            © 2026 ZAPAL
        </div>

        <a
            class="latul"
            href="https://latul.website/cooperation/"
            target="_blank"
            rel="noopener noreferrer"
            title="Разработка сайта — LATUL"
        >
            <img
                src="https://latul.website/assets/brand/favicon/favicon.svg"
                alt=""
                onerror="this.style.display='none'"
            >
            <span>LATUL · разработка</span>
        </a>
    </footer>
</div>
</body>
</html>
