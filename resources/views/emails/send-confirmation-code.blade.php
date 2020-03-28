<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kodi i regjistrimit tek platforma {{ config('app.name', 'Inovacion') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <style>
        .container {
            margin-top: 2rem;
            margin-left: 0;
        }
        footer {
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>
            Përshëndetje {{ $user->first_name }},
            <br><br><br>
            Ju lutem, përdorni kodin e mëposhtëm për të aktivizuar llogarinë tuaj.
        </p>

        <ul class="list-group">
          <li class="list-group-item">Kodi aktivizimit: {{ $user->confirmation_code }}</li>
        </ul>

        <footer>
            <p>Ky mesazh i eshte derguar <a href="mailto:{{$user->email}}">{{$user->email}}</a> ne kerkesen tuaj.</p>
            &copy; 2018 <strong>{{ config('app.name', 'Inovacion') }}</strong>
        </footer>
    </div>

</body>
</html>
