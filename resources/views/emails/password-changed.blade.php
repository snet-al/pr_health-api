@component('mail::message')

Përshëndetje {{$user->first_name}}, <br><br>
Fjalëkalimi i llogarisë tuaj u ndryshua me sukses.
<br><br>

Gjithë të mirat,<br>
<div class="my-2">
    &copy; 2018 <strong>{{ config('app.name', 'Inovacion') }}</strong>
</div>

<footer class="flex flex-col px-4 w-full md:w-2/5">
    <div class="flex justify-between">
        <a href="#" class="">
            <img src="{{ asset('/assets/icons/google-play-badge.png') }}" width="150" alt="">
        </a>
        <a href="#" class="md:mx-2">
            <img src="{{ asset('/assets/icons/App_Store_Badge.png') }}" width="150"  alt="">
        </a>
    </div>
    <p>Ky mesazh i eshte derguar <a href="mailto:{{$user->email}}">{{$user->email}}</a> ne kerkesen tuaj.</p>
</footer>

@endcomponent
