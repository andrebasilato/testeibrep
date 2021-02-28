<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

<h1>Voos</h1> <br />
<a href="{{ route('voos.index') }}">Inicio</a>
<span> | </span>
<a href="{{ route('voos.show') }}">Todos os Voos</a>
<span> | </span>
<a href="{{ route('voos.inbound') }}">Somente Volta</a>
<span> | </span>
<a href="{{ route('voos.outbound') }}">Somente Ida</a>
<span> | </span>
<a href="{{ route('voos.fare') }}">Tarifas</a>
<span> | </span>
<strong><a href="{{ route('voos.groups') }}">GRUPOS</a></strong>
<hr>

<div class="container">
    <div class="row">
        <div class="col">
            <strong>Voos de Ida</strong><br/>
            <span>Valor Total: <strong>{{ $roundtrips['priceO'] }}</strong></span><br/>
            @foreach ($roundtrips['outbounds'] as $flight)
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
                </p>
            @endforeach
        </div>

        <div class="col">
            <strong>Voos de Volta</strong><br/>
            <span>Valor Total: <strong>{{ $roundtrips['priceI'] }}</strong></span><br/>
            @foreach ($roundtrips['inbounds'] as $flights)
            <p>
                ID: {{ $flights['id'] }}<br />
                Empresa: {{ $flights['cia'] }}<br />
                Tarifa: {{ $flights['fare'] }}<br />
                Nº Voo: {{ $flights['flightNumber'] }}<br />
                Data Saida: {{ $flights['departureDate'] }}<br />
                Data Chegada: {{ $flights['arrivalDate'] }}<br />
                Origem: {{ $flights['origin'] }}<br />
                Destino: {{ $flights['destination'] }}<br />
                Preço: {{ $flights['price'] }}<br />
                Ida: {{ $flights['outbound'] }}<br />
                Volta: {{ $flights['inbound'] }}<br />
            </p>
        @endforeach
        </div>
    </div>
</div>
