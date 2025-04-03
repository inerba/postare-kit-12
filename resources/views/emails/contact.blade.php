<x-layouts.email title="Contatto">
    @slot('preheader')
        Nuovo contatto dal sito web
    @endslot

    <tr>
        <td class="wrapper">
            <h1>Nuovo contatto ricevuto</h1>
            <p>
                <strong>Nome:</strong>
                {{ $name }}
            </p>
            <p>
                <strong>Email:</strong>
                {{ $email }}
            </p>
            <p>
                <strong>Messaggio:</strong>
            </p>
            <p>
                {{ $body }}
            </p>
            @if (! empty($url))
                <p>
                    <strong>Pagina di provenienza:</strong>
                    <a href="{{ $url }}">{{ $url }}</a>
                </p>
            @endif

            {{--
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                <tbody>
                <tr>
                <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                <td>
                <a href="http://htmlemail.io" target="_blank">Bottone!</a>
                </td>
                </tr>
                </tbody>
                </table>
                </td>
                </tr>
                </tbody>
                </table>
            --}}
        </td>
    </tr>
    @slot('footer')
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            {{--
                <tr>
                <td class="content-block">
                <span class="apple-link">Company Inc, 7-11 Commercial Ct, Belfast BT1 2NB</span>
                <br />
                Don't like these emails?
                <a href="http://htmlemail.io/blog">Unsubscribe</a>
                .
                </td>
                </tr>
            --}}
            <tr>
                <td class="content-block powered-by">
                    Powered by
                    <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>
                </td>
            </tr>
        </table>
    @endslot
</x-layouts.email>
