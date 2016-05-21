@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/dashboards::dashboards/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="dashboards-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.dashboards.dashboards.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $dashboard->exists ? $dashboard->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($dashboard->exists)
							<li>
								<a href="{{ route('admin.sanatorium.dashboards.dashboards.delete', $dashboard->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/dashboards::dashboards/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/dashboards::dashboards/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('name', ' has-error') }}">

									<label for="name" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/dashboards::dashboards/model.general.name_help') }}}"></i>
										{{{ trans('sanatorium/dashboards::dashboards/model.general.name') }}}
									</label>

									<input type="text" class="form-control" name="name" id="name" placeholder="{{{ trans('sanatorium/dashboards::dashboards/model.general.name') }}}" value="{{{ input()->old('name', $dashboard->name) }}}">

									<span class="help-block">{{{ Alert::onForm('name') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('slug', ' has-error') }}">

									<label for="slug" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/dashboards::dashboards/model.general.slug_help') }}}"></i>
										{{{ trans('sanatorium/dashboards::dashboards/model.general.slug') }}}
									</label>

									<input type="text" class="form-control" name="slug" id="slug" placeholder="{{{ trans('sanatorium/dashboards::dashboards/model.general.slug') }}}" value="{{{ input()->old('slug', $dashboard->slug) }}}">

									<span class="help-block">{{{ Alert::onForm('slug') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('roles', ' has-error') }}">

									<label for="roles" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/dashboards::dashboards/model.general.roles_help') }}}"></i>
										{{{ trans('sanatorium/dashboards::dashboards/model.general.roles') }}}
									</label>

									<textarea class="form-control" name="roles" id="roles" placeholder="{{{ trans('sanatorium/dashboards::dashboards/model.general.roles') }}}">{{{ input()->old('roles', $dashboard->roles) }}}</textarea>

									<span class="help-block">{{{ Alert::onForm('roles') }}}</span>

								</div>


							</div>

						</fieldset>

						{{-- @todo: make better interface (unlimited repeats, nicer appealing) --}}

						<fieldset>
							@for($i = 0; $i < 10; $i++)
							<div class="form-group">
								<div class="row">
									<label for="widgetsInput{{ $i }}" class="control-label col-sm-3 text-right">Widget</label>
									<div class="col-sm-9">
										<?php $selectedWidget = $dashboard->widgets()->where('order', $i)->first() ?>
										<select name="widgets[{{ $i }}]" id="widgetsInput{{ $i }}" class="form-control">
											@foreach( $widgets as $widget)
												<option></option>
												@if ( is_object($selectedWidget) )
													<option value="{{ $widget['service'] }}" {{ $selectedWidget->service == $widget['service'] ? 'selected' : '' }}>{{ $widget['name'] }}</option>
												@else
													<option value="{{ $widget['service'] }}">{{ $widget['name'] }}</option>
												@endif

											@endforeach
										</select>
									</div>
								</div>
							</div>
							@endfor
						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($dashboard)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
