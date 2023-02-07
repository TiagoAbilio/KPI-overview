@extends('layouts.app')

@section('content')

<body>
    @include('components.hearder')
    <div class="container">
        <div class="row">

            <h3>SLA overview <spam style="color:#E20074;">Sistemas internos - 2023</spam>
            </h3>

        </div>

        <div class="row" style="margin-top: 25px;">
            <div class="columns" style="text-align: center; width: 300px;">
                <div class="row">
                    <p><span class="tx-tickets">{{ $ticket[2] }}</span></p>
                    <span class="tx-tickets-rec">TICKETS RECEBIDOS</span>
                </div>
                <h5 style="text-align: center;margin-top: 10px; margin-bottom: 0px; font-size: 25px;">{{$slaResult}}%</h5>
                <h5 style="text-align: center;margin-top: 0px;">SLA Target</h5>
            </div>

            <div class="columns" style="width: 100px;">
                <p style="text-align: center;">
                    <span class="tx-tickets-rec" style="color:#e20074;">{{ $ticket[0] }}% <br> <span style="font-size:11px;">Change request</span></span>
                </p>
                <p style="text-align: center;">
                    <span class="tx-tickets-rec">{{ $ticket[1] }}% <br> <span style="font-size:11px;">Incidentes</span></span>
                </p>
            </div>

            <div class="columns">
                <nav class="chart_header" id="charts">
                    <ul>
                        <li>
                            <resolved :result="{{$resolved}}"></resolved>
                        </li>
                        <li>
                            <taskpro :result="{{$progress}}"></taskpro>
                        </li>
                        <li>
                            <notstarted :result="{{$notStarted}}"></notstarted>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="columns">
                <div class="row">

                    <p>
                        <span class="tx-tickets-rec">BACKLOG <span style="color:#e20074;">SEMANAL</span></span>
                    </p>
                    <div class="row" style="width:96%; height:200px; text-align: center; margin-left:10px;">
                        <backlog :databack="{{$backlogResult}}"></backlog>
                    </div>

                </div>
            </div>

        </div>
        <div class="container"></div>
        <!-- segunda linha dos gráficos -->
        <div class="row" style="flex-wrap: wrap;">
            <div class="row" style="width: 48%; min-width: 600px;">
                <p>
                    <span class="tx-tickets-rec">TOP FIVE <span style="color:#e20074;">CI'S</span></span>
                </p>

                <div class="row" style="text-align: center; margin-left:10px;">
                    <topci :topcidata="{{$topcis}}"></topci>
                </div>
            </div>
            <div class="row" style="width: 48%; min-width: 600px;">
                <p>
                    <span class="tx-tickets-rec">TOP <span style="color:#e20074;">SOLVERS</span></span>
                </p>
                <div class="row" style="text-align: center; margin-left:10px;">
                    <topsolver :topsolverdata="{{$topsalver}}"></topsolver>
                </div>

            </div>
        </div>

        <div class="row" style="width: 50%; min-width: 600px;">
            <p>
                <span class="tx-tickets-rec">SLA <span style="color:#e20074;">NEXT BREACH</span></span>
            </p>
            <div class="row" style="text-align: center; margin-left:10px;">
                <slatable :datatable="{{$toptables}}"></slatable>
            </div>
        </div>

        <footer class="secondary_header footer">
            <div class="copyright">2023 - T-Systems do Brasil Ltda.</div>
        </footer>
    </div>
</body>
@endsection