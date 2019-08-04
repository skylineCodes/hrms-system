@component('mail::message')

@slot('slot')

    <section>
        <div class="col-md-12">
            <h3>Your {{ config('app.name') }} account has been deactivated!</h3>
            <p>
                Hello,
                <br>
                <br>
                You would no longer have access to your account until further notice.
            </p>
            <span>Please contact {{ config('app.name') }} admin if this was a mistake</span>
        </div>
    </section>

Thanks for understanding,<br>
{{ config('app.name') }}
@endslot

@endcomponent
