@component('mail::message')
    {{__('general.greetings').' '. $user->first_name}}, <br><br>
<br>


{{__('email.password_reset_text')}}<br><br>

{{__('email.new_password'). ' : '. $user->temp_password}}<br>

{{__('email.password_change_instructions')}}<br>


{{__('email.thank_you')}}<br>
<div class="my-2">
    &copy; 2020 <strong>{{ config('app.name', 'Inovacion') }}</strong>
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
</footer>

@endcomponent
