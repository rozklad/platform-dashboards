<?php namespace Sanatorium\Dashboards\Widgets;

class Dashboard {

	public function show($slug = null)
	{
	    // Slug of the dashboard was not specified
	    if ( $slug == null )
	        return null;

		$repository = app('sanatorium.dashboards.dashboard');

        return $repository->where('slug', $slug)->first();
	}

}
