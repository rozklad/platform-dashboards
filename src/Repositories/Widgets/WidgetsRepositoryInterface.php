<?php namespace Sanatorium\Dashboards\Repositories\Widgets;

interface WidgetsRepositoryInterface {

    public function getServices();

    public function registerService($slug, $class);

    public function getInstance($slug);

}
