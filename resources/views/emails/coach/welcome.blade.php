<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esiet sveicināti FitLife!</title>

    <style>
        p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <p>[{{ date('Y-m-d H:i:s'); }}]</p>
    <br>
    <h1>Sveiki, treneris/-e {{ $name }} {{ $surname }}!</h1>
    <p>Jūsu reģistrācija ir veiksmīga. Jūs varat autentificēties sistēmā ar jūsu autentifikācijas datiem:</p>
    <p><span style="font-weight: bold; font-size: 24px;">Personas kods:</span> {{ $personal_id }}</p>
    <p><span style="font-weight: bold; font-size: 24px;">Parole:</span> {{ $temporary_password }}</p>
    <p>Lūdzu, izmantojiet šo paroli, lai autentificēties, un pēc tam mainiet to uz pastāvīgu paroli.</p>
</body>
</html>
