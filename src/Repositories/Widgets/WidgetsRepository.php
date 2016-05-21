<?php namespace Sanatorium\Dashboards\Repositories\Widgets;

/**
 * @example
 *  // Register the dashboard widget
 *  $this->app['sanatorium.dashboards.widgets']->registerService(
 *      'visitors_and_pageviews',                           // slug
 *      'Sanatorium\Analytics\Widgets\DashboardVisitors'    // class
 *  );
 */

use Exception;
use Log;

class WidgetsRepository implements WidgetsRepositoryInterface
{

    /**
     * Array of registered namespaces.
     *
     * @var array
     */
    protected $services = [];

    /**
     * {@inheritDoc}
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Returns something like
     *  0 => [
     *      "service" => "Sanatorium\Analytics\Widgets\DashboardVisitors"
     *      "name" => "Analytics: Visitors & pageviews"
     *  ],
     *
     * @return array
     */
    public function getServicesWithInfo()
    {
        $output = [];

        foreach ( $this->services as $service )
        {

            $output[] = [
                'service' => $service,
                'name'    => $service::NAME,
            ];

        }

        return $output;
    }

    /**
     * {@inheritDoc}
     */
    public function registerService($slug, $class)
    {
        $this->services[ $slug ] = $class;
    }

    /**
     * @param $slug
     * @return bool
     */
    public function getInstance($slug)
    {
        try
        {
            if ( !isset($this->services[ $slug ]) )
                throw new Exception(sprintf('Widget is not configured (%s)', $slug));

            $class_name = $this->services[ $slug ];

            if ( !class_exists($class_name) )
                throw new Exception(sprintf('Widget class does not exist (%s)', $class_name));

        } catch (Exception $e)
        {
            Log::error('sanatorium/dashboards: ' . $e->getMessage());

            return false;
        }

        return new $class_name;
    }

    public function getConfiguration($slug)
    {
        return $this->getInstance($slug)->configuration;
    }

}
