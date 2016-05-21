<?php namespace Sanatorium\Dashboards\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class DashboardsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/dashboards::index');
	}

}
