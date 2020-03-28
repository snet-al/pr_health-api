@component('mail::message')

Përshëndetje {{ $user->first_name }},<br><br>
Të dhënat tuaja u regjistruan me sukses. Ju lutem klikoni linkun e mëposhtëm për të aktivizuar llogarinë tuaj.
<br>

@component('mail::button', ['url' => url(env('WEB_URL', 'http://test.test') . '/account/confirm/' . $user->confirmation_code)])
Konfirmoni llogarinë
@endcomponent

Faleminderit,<br><br>
<p>Ky mesazh i eshte derguar <a href="mailto:{{$user->email}}">{{$user->email}}</a> ne kerkesen tuaj.</p>
&copy; 2018 <strong>{{ config('app.name', 'Inovacion') }}</strong>

@endcomponent
