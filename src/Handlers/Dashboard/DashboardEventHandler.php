<?php namespace Sanatorium\Dashboards\Handlers\Dashboard;

use Illuminate\Events\Dispatcher;
use Sanatorium\Dashboards\Models\Dashboard;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class DashboardEventHandler extends BaseEventHandler implements DashboardEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.dashboards.dashboard.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.dashboards.dashboard.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.dashboards.dashboard.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.dashboards.dashboard.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.dashboards.dashboard.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Dashboard $dashboard)
	{
		$this->flushCache($dashboard);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Dashboard $dashboard, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Dashboard $dashboard)
	{
		$this->flushCache($dashboard);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Dashboard $dashboard)
	{
		$this->flushCache($dashboard);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Dashboards\Models\Dashboard  $dashboard
	 * @return void
	 */
	protected function flushCache(Dashboard $dashboard)
	{
		$this->app['cache']->forget('sanatorium.dashboards.dashboard.all');

		$this->app['cache']->forget('sanatorium.dashboards.dashboard.'.$dashboard->id);
	}

}
