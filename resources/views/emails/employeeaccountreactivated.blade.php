@component('mail::message')

@slot('slot')

    <section>
        <div class="col-md-12">
            <h3>Your {{ config('app.name') }} account has been reactivated!</h3>
            <p>
                Hello,
                <br>
                <br>
                Please access your account with your initial details
            </p>
        </div>
    </section>

Warm Regards,<br>
{{ config('app.name') }}
@endslot

@endcomponent
