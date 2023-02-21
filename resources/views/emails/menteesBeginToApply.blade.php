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
        Ir atvērta pieteikšanās mentorējamajiem, kas nozīmē, ka drīzumā saņemsi e-pastu ar pirmkursnieka kontaktinformāciju. Mentorējamie varēs pieteikties līdz 30. Septembrim, tāpēc nebēdā, ja vēl neviens Tevi nav izvēlējies!<br/>
        <br/>
        Ja jau esi saņēmis sava Mentorējamā kontaktus, droši vari ar viņu sazināties un iespējams uzaicināt uz tikšanos klātienē.
        Ja Mentorējamais uzdod jautājumus par studiju procesu vai dzīvi universitātē uz kuriem nezini atbildes, nesatraucies!
        Visa nepieciešamā informācija ir pieejama kopīgajā Mentoru materiālu bāzē - <a href="{{$data['event']['link']}}" target="_blank">{{$data['event']['link']}}</a> Šeit sadaļā “{{$data['event']['description']}}”
        vari uzdot arī jebkuru jautājumu, uz kuru var atbildēt gan pārējie mentori, gan programmas koordinatori.
    </p>
    <p>Vari uzdot jautājumus arī sazinoties ar <strong>Alisi Agnesi Rozentāli</strong> (<a href="mailto:aliseagnese.rozentale@rsu.lv">aliseagnese.rozentale@rsu.lv</a>) vai rakstot uz <a href="mailto:sp@rsu.lv">sp@rsu.lv</a>.</p>
    <p>
        Lai veiksmīga saziņa ar Mentorējamajiem!<br/>
        <br/>
        RSU Mentoru programma
    </p>
</div>
</body>
</html>
