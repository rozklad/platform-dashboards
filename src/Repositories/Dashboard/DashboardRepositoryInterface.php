<?php namespace Sanatorium\Dashboards\Repositories\Dashboard;

interface DashboardRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Dashboards\Models\Dashboard
	 */
	public function grid();

	/**
	 * Returns all the dashboards entries.
	 *
	 * @return \Sanatorium\Dashboards\Models\Dashboard
	 */
	public function findAll();

	/**
	 * Returns a dashboards entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Dashboards\Models\Dashboard
	 */
	public function find($id);

	/**
	 * Determines if the given dashboards is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given dashboards is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given dashboards.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a dashboards entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Dashboards\Models\Dashboard
	 */
	public function create(array $data);

	/**
	 * Updates the dashboards entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Dashboards\Models\Dashboard
	 */
	public function update($id, array $data);

	/**
	 * Deletes the dashboards entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
