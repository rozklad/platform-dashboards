# sanatorium/dashboards

Dashboards extension for Cartalyst Platform

## Documentation

### Register widget to dashboards

    class SampleServiceProvider {
    
        public function boot()
        {
            $this->registerDashboardWidget();
        }
    
        /**
         * Register the dashboard widget "Visitors and Pageviews"
         * @throws ReflectionException
         */
        protected function registerDashboardWidget()
        {
            $this->app['sanatorium.dashboards.widgets']->registerService(
                'visitors_and_pageviews',                           // slug
                'Path\To\Class\DashboardVisitors'   	            // class
            );
        }
        
    }

### Blade directive @dashboard

#### Dashboard by slug

    @dashboard('main')

## Changelog

Changelog not available.

## Support

Support not available.