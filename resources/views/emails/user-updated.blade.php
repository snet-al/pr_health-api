@component('mail::message')

Përshëndetje {{$user->first_name}}, <br><br>
Të dhënat tuaja u ndryshuan me sukses. Një nga të dhënat që u ndryshua është edhe email. <br>
Ju lutem klikoni linkun e mëposhtëm për të konfirmuar ndryshimin.
<br>

@component('mail::button', ['url' => url(env('WEB_URL', 'http://test.test') . '/account/confirm/' . $user->confirmation_code)])
Konfirmoni ndryshimin
@endcomponent

Faleminderit,<br><br>
<p>Ky mesazh i eshte derguar <a href="mailto:{{$user->email}}">{{$user->email}}</a> ne kerkesen tuaj.</p>
&copy; 2018 <strong>{{ config('app.name', 'Inovacion') }}</strong>

@endcomponent
