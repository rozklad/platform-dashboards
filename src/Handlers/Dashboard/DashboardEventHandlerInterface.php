<?php namespace Sanatorium\Dashboards\Handlers\Dashboard;

use Sanatorium\Dashboards\Models\Dashboard;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface DashboardEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a dashboard is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a dashboard is created.
	 *
	 * @param  \Sanatorium\Dashboards\Models\Dashboard  $dashboard
	 * @return mixed
	 */
	public function created(Dashboard $dashboard);

	/**
	 * When a dashboard is being updated.
	 *
	 * @param  \Sanatorium\Dashboards\Models\Dashboard  $dashboard
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Dashboard $dashboard, array $data);

	/**
	 * When a dashboard is updated.
	 *
	 * @param  \Sanatorium\Dashboards\Models\Dashboard  $dashboard
	 * @return mixed
	 */
	public function updated(Dashboard $dashboard);

	/**
	 * When a dashboard is deleted.
	 *
	 * @param  \Sanatorium\Dashboards\Models\Dashboard  $dashboard
	 * @return mixed
	 */
	public function deleted(Dashboard $dashboard);

}
