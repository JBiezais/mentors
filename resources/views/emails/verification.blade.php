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
            flex-direction: column;
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
        .button{
            padding: 0.5rem 1.5rem;
            border-radius: 10px;
            background-color: rgb(209 213 219);
            font-weight: 600;
            color: white;
        }
        @media (max-width: 500px) {
            .message-box{
                width: 90%;
                padding: 0.5rem 1rem;
            }
        }
    </style>
</head>
<body class="background">
<h1 style="text-align: center; font-size: 1.5rem; margin: 1rem 0"><a href="{{route('home')}}">RSU Mentoru Programa</a></h1>
    <div class="message-box">
        <p>Sveiks/-a {{$data['name']}} {{$data['lastName']}},</p>
        <p>
            Paldies Tev, ka izvēlējies pieteikties Mentoru programmai un kļūt par Mentoru kādam pirmkursniekam jaunajā mācību gadā! Lai pabeigtu pieteikšanos programmai, lūdzu, <strong>apstiprini savu e-pastu</strong> spiežot uz zemāk redzamā lodziņa.
        </p>
        <div style="width: 100%; text-align: center">
            <a class="button" href="{{ route('verify.mentor', $data['key']) }}">Verificēt e-pastu</a>
        </div>
        <p>
            Pēc e-pasta apstiprināšanas Tavu pieteikumu izskatīs programmas koordinatori, lai pārliecinātos par tā atbilstību. <strong>Tiklīdz Tavs pieteikums tiks apstiprināts saņemsi ziņu par turpmāko programmas norisi.</strong>
        </p>
        <p>Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>{{$contacts['name']}}</strong> <a href="mailto:{{$contacts['email']}}">({{$contacts['email']}})</a> .</p>
        <p>
            Lai viss izdodas!<br/>
            <br/>
            RSU Mentoru programma
        </p>
    </div>
</body>
</html>
