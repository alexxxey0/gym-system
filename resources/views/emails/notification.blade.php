<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $topic }}</title>

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
        {{ $text }}
    </p>
    <br>
    @if($from_admin)
        <p>Ar cieņu,<br>Administrators</p>
    @else
        <p>Ar cieņu,<br>{{ $coach_full_name }}</p>
    @endif
</body>
</html>