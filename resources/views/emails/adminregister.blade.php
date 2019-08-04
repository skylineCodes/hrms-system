@component('mail::message')

@slot('slot')

    <section>
        <div class="col-md-12">
            <p>
                Hello,

                Your email has been registered successfully to {{ config('app.name') }}.

                Please access your portal with the following details:
                <br>
                <br>
                Email: <strong>{{ $admin->email }}</strong>,
                <br>
                Code: <strong>{{ $admin->employee_code }}</strong>
                <br>
                Role: <strong>{{ $admin->getRoleNames()->first() }}</strong>
            </p>
        </div>
    </section>

@endslot

@component('mail::button', ['url' => config('app.url') . '/api/auth/login'])
Login
@endcomponent

Warm Regards,<br>
{{ config('app.name') }}
@endcomponent
