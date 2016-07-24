@foreach( $widgets as $widget )

    <div class="panel panel-default widget dashboard-widget">

        <div class="panel-body">
            {!! (new $widget->service)->run() !!}
        </div>

    </div>

@endforeach
