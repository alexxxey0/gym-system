<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atjaunota nodarbība: {{ $training_name }}</title>

    <style>
        p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <p>[{{ date('Y-m-d H:i:s'); }}]</p>
    <br>
    <p>
        Uzmanību! Paziņojam, ka iepriekš atcelta {{ $training_name }} nodarbība {{ $training_date }} tika atjaunota un notiks ierastajā laikā.
    </p>
</body>
</html>