<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

<h1>Voos</h1> <br />
<a href="{{ route('voos.index') }}">Inicio</a>
<span> | </span>
<a href="{{ route('voos.show') }}">Todos os Voos</a>
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

        <strong>Grupos de Voos com Tarifas 4DA</strong><br />
        <p>

            @foreach ($result['daI'] as $index => $inbound)
                @foreach ($result['daO'] as $outbound)
                    ID {{ $index++ }} (Valor Total: R$ {{ $inbound['price'] + $outbound['price'] }}) | Idas: Voo
                    de ID {{ $outbound['id'] }} | Voltas: Voo de
                    ID {{ $inbound['id'] }} <br />
                @endforeach
            @endforeach
        </p>


        <strong>Grupos de Voos com Tarifas 1AF</strong><br />
        <p>

            @foreach ($result['afI'] as $ind => $inbounds)
                @foreach ($result['afO'] as $outbounds)
                    ID {{ $index++ }} (Valor Total: R$ {{ $inbounds['price'] + $outbounds['price'] }}) |
                    Idas: Voo de ID {{ $outbounds['id'] }} | Voltas: Voo de ID {{ $inbounds['id'] }} <br />
                @endforeach
            @endforeach
        </p>

    </div>
</div>
