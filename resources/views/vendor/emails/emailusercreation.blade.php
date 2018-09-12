@component('mail::message')
@isset($data)
Hi {{ $data->name }} <br/>
{{ $data->message }}, <br/>
Your login credentials as below
email Id: {{ $data->name }} <br/>
password: {{ $data->password }} <br/>

Please login to using these credentials and Change password.

@component('mail::button', ['url' =>$data->url, 'color' => 'blue'])
Click here to Login 
@endcomponent

{{-- Subcopy --}}
@isset($data->url)
@component('mail::subcopy')
    "If you’re having trouble clicking the 'Click here to Login' button, copy and paste the URL below". <br/>
    {{ $data->url }}
@endcomponent
@endisset

@endisset

@endcomponent
