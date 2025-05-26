@component('mail::message')
# ¡Bienvenido a nuestra aplicación!

Gracias por registrarte. Nos alegra que formes parte de nuestra comunidad.

@component('mail::button', ['url' => "1"])
    Ir al sitio
@endcomponent

Este es un correo electrónico de prueba, diseñado con una plantilla personalizada.

¡Gracias por ser parte de nuestra comunidad!<br>
{{-- {{ config('app.name') }} --}}
@endcomponent
