<h1>Detalhes do Voos {{ $details['id'] }}</h1> <br/>
<a href="{{ route('voos.index') }}">Inicio</a>
<a href="{{ route('voos.show') }}">Ver Todos</a>
<hr>

ID: {{ $details['id'] }}<br/>
Empresa: {{ $details['cia'] }}<br/>
Tarifa: {{ $details['fare'] }}<br/>
Nº Voo: {{ $details['flightNumber'] }}<br/>
Data Saida: {{ $details['departureDate'] }}<br/>
Data Chegada: {{ $details['arrivalDate'] }}<br/>
Origem: {{ $details['origin'] }}<br/>
Destino: {{ $details['destination'] }}<br/>
Preço: {{ $details['price'] }}<br/>
Ida: {{ $details['outbound'] }}<br/>
Volta: {{ $details['inbound'] }}<br/>