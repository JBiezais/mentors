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
            Paldies, ka izvēlējies pieteikties Mentoram! Ceram, ka no Mentora iegūtās zināšanas un stāsti par viņa pieredzi Tev noderēs uzsākot studijas RSU!<br/>
            <br/>
            <strong>Tava kontaktinformācija ir nosūtīta Mentoram.</strong> Tuvākajā laikā viņš ar Tevi sazināsies!<br/>
            <br/>
            Ja tomēr vēlies sazināties pirmais, šeit būs Tava Mentora kontaktinformācija:<br/>
        </p>
        <p>Vārds Uzvārds: <strong>{{$data['mentor']['name']. ' '. $data['mentor']['lastName']}}</strong></p>
        <p>Telefona nummurs: <strong>{{$data['mentor']['phone']}}</strong></p>
        <p>E-pasts: <strong>{{$data['mentor']['email']}}</strong></p>
        <p>Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>Alisi Agnesi Rozentāli</strong> <a href="mailto:aliseagnese.rozentale@rsu.lv">aliseagnese.rozentale@rsu.lv</a> .</p>
        <p>
            Lai viss izdodas!<br/>
            <br/>
            RSU Mentoru programma
        </p>
    </div>
</body>
</html>
