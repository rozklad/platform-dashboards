<?php namespace Sanatorium\Dashboards\Repositories\Dashboard;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class DashboardRepository implements DashboardRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Dashboards\Handlers\Dashboard\DashboardDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent dashboards model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.dashboards.dashboard.handler.data'];

		$this->setValidator($app['sanatorium.dashboards.dashboard.validator']);

		$this->setModel(get_class($app['Sanatorium\Dashboards\Models\Dashboard']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.dashboards.dashboard.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.dashboards.dashboard.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new dashboard
		$dashboard = $this->createModel();

		// Fire the 'sanatorium.dashboards.dashboard.creating' event
		if ($this->fireEvent('sanatorium.dashboards.dashboard.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the dashboard
			$dashboard->fill($data)->save();

			// Fire the 'sanatorium.dashboards.dashboard.created' event
			$this->fireEvent('sanatorium.dashboards.dashboard.created', [ $dashboard ]);
		}

		return [ $messages, $dashboard ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the dashboard object
		$dashboard = $this->find($id);

		// Fire the 'sanatorium.dashboards.dashboard.updating' event
		if ($this->fireEvent('sanatorium.dashboards.dashboard.updating', [ $dashboard, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($dashboard, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			$widgets = array_pull($data, 'widgets');
			
			if ( isset($data['widgets']) )
				unset($data['widgets']);
			
			// Update the dashboard
			$dashboard->fill($data)->save();

			foreach ( $widgets as $key => $widget ) {

				// If no widget is chosen
				if ( empty($widget) )
					continue;

                // Delete widgets on the same position
                $dashboard->widgets()->where('order', $key)->where('service', '!=', $widget)->delete();

                \Sanatorium\Dashboards\Models\Widget::firstOrCreate([
                    'service'       => $widget,
                    'order'         => $key,
                    'dashboard_id'  => $dashboard->id,
                    'configuration' => json_encode([])      // @todo: pass configuration
                ]);
			}

			// Fire the 'sanatorium.dashboards.dashboard.updated' event
			$this->fireEvent('sanatorium.dashboards.dashboard.updated', [ $dashboard ]);
		}

		return [ $messages, $dashboard ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the dashboard exists
		if ($dashboard = $this->find($id))
		{
			// Fire the 'sanatorium.dashboards.dashboard.deleted' event
			$this->fireEvent('sanatorium.dashboards.dashboard.deleted', [ $dashboard ]);

			// Delete the dashboard entry
			$dashboard->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
