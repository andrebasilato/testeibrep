<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

<h1>Voos</h1> <br />
<a href="{{ route('voos.index') }}">Inicio</a>
<span> | </span>
<a href="{{ route('voos.outbound') }}">Somente Ida</a>
<span> | </span>
<a href="{{ route('voos.inbound') }}">Somente Volta</a>
<span> | </span>
<a href="{{ route('voos.roundtrip') }}">Ida e Volta</a>
<span> | </span>
<a href="{{ route('voos.fare') }}">Tarifas</a>
<span> | </span>
<strong><a href="{{ route('voos.groups') }}">GRUPOS</a></strong>
<hr>

<div class="container">
    <div class="row">
        @foreach ($flights as $flight)

            <p>
                ID: {{ $flight['id'] }}<br />
                Empresa: {{ $flight['cia'] }}<br />
                Tarifa: {{ $flight['fare'] }}<br />
                Nº Voo: {{ $flight['flightNumber'] }}<br />
                Data Saida: {{ $flight['departureDate'] }}<br />
                Data Chegada: {{ $flight['arrivalDate'] }}<br />
                Origem: {{ $flight['origin'] }}<br />
                Destino: {{ $flight['destination'] }}<br />
                Preço: {{ $flight['price'] }}<br />
                Ida: {{ $flight['outbound'] }}<br />
                Volta: {{ $flight['inbound'] }}<br />
                [ <a href="{{ route('voos.details', $flight['id']) }}">Detalhes</a> ]
            </p>

        @endforeach
    </div>
</div>
