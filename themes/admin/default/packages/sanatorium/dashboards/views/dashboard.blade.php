@foreach( $widgets as $widget )

    <?php $service = $widget->service; ?>

    @if ( $service::HAS_WRAPPER )
    <div class="panel panel-default widget dashboard-widget">

        <div class="panel-body">
            @endif

            {!! (new $service)->run() !!}

            @if ( $service::HAS_WRAPPER )
        </div>

    </div>
    @endif

@endforeach
