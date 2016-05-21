<?php namespace Sanatorium\Dashboards\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Dashboards\Repositories\Dashboard\DashboardRepositoryInterface;

class DashboardsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Dashboards repository.
	 *
	 * @var \Sanatorium\Dashboards\Repositories\Dashboard\DashboardRepositoryInterface
	 */
	protected $dashboards;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Dashboards\Repositories\Dashboard\DashboardRepositoryInterface  $dashboards
	 * @return void
	 */
	public function __construct(DashboardRepositoryInterface $dashboards)
	{
		parent::__construct();

		$this->dashboards = $dashboards;
	}

	/**
	 * Display a listing of dashboard.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/dashboards::dashboards.index');
	}

	/**
	 * Datasource for the dashboard Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->dashboards->grid();

		$columns = [
			'id',
			'name',
			'slug',
			'roles',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.dashboards.dashboards.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new dashboard.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new dashboard.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating dashboard.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating dashboard.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified dashboard.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->dashboards->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/dashboards::dashboards/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.dashboards.dashboards.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->dashboards->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a dashboard identifier?
		if (isset($id))
		{
			if ( ! $dashboard = $this->dashboards->find($id))
			{
				$this->alerts->error(trans('sanatorium/dashboards::dashboards/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.dashboards.dashboards.all');
			}
		}
		else
		{
			$dashboard = $this->dashboards->createModel();
		}

		// Get widgets
		$widgets = app('sanatorium.dashboards.widgets')->getServicesWithInfo();

		// Show the page
		return view('sanatorium/dashboards::dashboards.form', compact('mode', 'dashboard', 'widgets'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the dashboard
		list($messages) = $this->dashboards->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/dashboards::dashboards/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.dashboards.dashboards.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
