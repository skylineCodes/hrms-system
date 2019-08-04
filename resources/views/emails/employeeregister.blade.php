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
                Email: <strong>{{ $employee->email }}</strong>,
                <br>
                Code: <strong>{{ $employee->employee_code }}</strong>
                <br>
                Role: <strong>{{ $employee->getRoleNames()->first() }}</strong>
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
