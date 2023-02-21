<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="icon" href="img/logo.svg">
    <title>test mail</title>
    <style>
        body{
            font-size: 1rem; /* 16px */
            line-height: 1.5rem; /* 24px */
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
        }
        a{
            text-decoration: none;
            color: black;
        }
        .background{
            background-color: rgb(209 213 219);
            display: flex;
        }
        .message-box{
            background-color: rgb(243 244 246);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            border-radius: 10px;
            height: auto;
            width: 60%;
            margin: auto;
            padding: 1rem 3rem;
        }
        @media (max-width: 500px) {
            .message-box{
                width: 90%;
            }
        }
    </style>
</head>
<body class="background">
<div class="message-box">
    <p>Sveiks {{$data['name']. ' '. $data['lastName']}},</p>
    <p>
        Tuvojas brīdis, kad jaunie studējošie sāks aktīvi pieteikties saviem Mentoriem, tāpēc esam nolēmuši rīkot Mentoru apmācības, lai nodotu Tev visas nepieciešamākās zināšanas, kas noderēs saziņai ar Mentorējamo!

        Apmācības notiks {{\Carbon\Carbon::parse($data['event']['date'])->format('d M Y, H:i')}}, {{$data['event']['location']}}. Lūdzu, norādi vai plāno ierasties uz apmācībām aizpildot anketu <a href="{{$data['event']['link']}}">{{$data['event']['link']}}</a>.
    </p>
    <p>Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>Alisi Agnesi Rozentāli</strong> <a href="mailto:aliseagnese.rozentale@rsu.lv">aliseagnese.rozentale@rsu.lv</a> .</p>
    <p>
        Gaidīsim Tavu atbildi!<br/>
        <br/>
        RSU Mentoru programma
    </p>
</div>
</body>
</html>
