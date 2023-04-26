@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create?page='.$crud->getRequest()->query("page")) }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>
@endif
