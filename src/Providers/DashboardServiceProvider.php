<?php namespace Sanatorium\Dashboards\Providers;

use Cartalyst\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Dashboards\Models\Dashboard']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.dashboards.dashboard.handler.event');

		// Register the manager
		$this->bindIf('sanatorium.dashboards.widgets', 'Sanatorium\Dashboards\Repositories\Widgets\WidgetsRepository');

        // Register the blade @dashboard directive
        $this->registerBladeDashboardDirective();

	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.dashboards.dashboard', 'Sanatorium\Dashboards\Repositories\Dashboard\DashboardRepository');

		// Register the data handler
		$this->bindIf('sanatorium.dashboards.dashboard.handler.data', 'Sanatorium\Dashboards\Handlers\Dashboard\DashboardDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.dashboards.dashboard.handler.event', 'Sanatorium\Dashboards\Handlers\Dashboard\DashboardEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.dashboards.dashboard.validator', 'Sanatorium\Dashboards\Validator\Dashboard\DashboardValidator');
	}

    /**
     * Register the Blade @dashboard directive.
     *
     * @return void
     */
    protected function registerBladeDashboardDirective()
    {
        $this->app['blade.compiler']->directive('dashboard', function ($value) {
            return "<?php echo Widget::make('sanatorium/dashboards::dashboard.show', array$value); ?>";
        });
    }

}
