@foreach( $widgets as $widget )

    @if ( ($widget->service)::HAS_WRAPPER )
    <div class="panel panel-default widget dashboard-widget">

        <div class="panel-body">
            @endif

            {!! (new $widget->service)->run() !!}

            @if ( ($widget->service)::HAS_WRAPPER )
        </div>

    </div>
    @endif

@endforeach
