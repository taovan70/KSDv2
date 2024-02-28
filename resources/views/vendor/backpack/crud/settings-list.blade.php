@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.list') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
    $locale = app()->getLocale();
    $settingsNameMap = [
        "newBehaviour" => ["ru" => "Новое поведение", "en" => "new behaviour"],
        "initial" => ["ru" => "По умолчанию", "en" => "initial"],
        "yes" => ["ru" => "Да", "en" => "Yes"],
        "no" => ["ru" => "Нет", "en" => "No"],
        "viewSettings" => ["ru"=> "Настройки отображения", "en" => "View Settings"],
        "commonSettings" => ["ru"=> "Общие настройки", "en" => "Common Settings"]
    ];
@endphp

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none"
             bp-section="page-header">
        <h1 class="text-capitalize mb-0"
            bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
    </section>
@endsection

@section('content')
    <div class="row" bp-section="crud-operation-list">
        <div class="{{ $crud->getListContentClass() }}">

            <div class="row mb-2 align-items-center">
                <div class="col-sm-9">
                    @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
                        <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">
                            @include('crud::inc.button_stack', ['stack' => 'top'])
                        </div>
                    @endif
                </div>
            </div>
            <div class="{{ backpack_theme_config('classes.tableWrapper') }}">
                <h4 class="table-settings-name">{{$settingsNameMap['commonSettings'][$locale]}}</h4>
                <table
                        id="crudTable1"
                        class="{{ backpack_theme_config('classes.table') ?? 'table table-striped table-hover nowrap rounded card-table table-vcenter card d-table shadow-xs border-xs' }}"
                        data-responsive-table="{{ (int) $crud->getOperationSetting('responsiveTable') }}"
                        data-has-details-row="{{ (int) $crud->getOperationSetting('detailsRow') }}"
                        data-has-bulk-actions="{{ (int) $crud->getOperationSetting('bulkActions') }}"
                        data-has-line-buttons-as-dropdown="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdown') }}"
                        cellspacing="0">
                    <thead>
                    <tr>
                        {{-- Table columns --}}
                        @foreach ($crud->columns() as $column)
                            <th
                                    data-orderable="false"
                                    data-priority="{{ $column['priority'] }}"
                                    data-column-name="{{ $column['name'] }}"
                            >
                                {!! $column['label'] !!}
                            </th>
                        @endforeach

                        @if ( $crud->buttons()->where('stack', 'line')->count() )
                            <th data-orderable="false"
                                data-priority="{{ $crud->getActionsColumnPriority() }}"
                                data-visible-in-export="false"
                                data-action-column="true"
                            >{{ trans('backpack::crud.actions') }}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($settingCommon as $setting)
                        @php
                            if ($setting["value"] === '1') {
                                $settingValue = $settingsNameMap['yes'][$locale];
                            } else {
                                if ($setting["value"] === '0') {
                                    $settingValue = $settingsNameMap['no'][$locale];
                                } else {
                                    $settingValue = $setting["value"];
                                }
                            }
                        @endphp
                        <tr class="odd">
                            <td class="dtr-control">
                                <span>{{json_decode($setting["name"])->$locale}}</span>
                            </td>
                            <td><span>{{$settingValue}}</span>
                            </td>
                            <td><span>{{json_decode($setting["description"])->$locale}}</span>
                            </td>
                            <td>
                                <a href="/admin/setting/{{$setting["id"]}}/edit" class="btn btn-sm btn-link">
                                    <span><i class="la la-edit"></i> Редактировать</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        {{-- Table columns --}}
                        @foreach ($crud->columns() as $column)
                            <th>
                                {{-- Bulk checkbox --}}
                                @if($loop->first && $crud->getOperationSetting('bulkActions'))
                                    {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                                @endif
                                {!! $column['label'] !!}
                            </th>
                        @endforeach

                        @if ( $crud->buttons()->where('stack', 'line')->count() )
                            <th>{{ trans('backpack::crud.actions') }}</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>

            <div class="{{ backpack_theme_config('classes.tableWrapper') }}">
                <h4 class="table-settings-name">{{$settingsNameMap['viewSettings'][$locale]}}</h4>
                <table
                        id="crudTable1"
                        class="{{ backpack_theme_config('classes.table') ?? 'table table-striped table-hover nowrap rounded card-table table-vcenter card d-table shadow-xs border-xs' }}"
                        data-responsive-table="{{ (int) $crud->getOperationSetting('responsiveTable') }}"
                        data-has-details-row="{{ (int) $crud->getOperationSetting('detailsRow') }}"
                        data-has-bulk-actions="{{ (int) $crud->getOperationSetting('bulkActions') }}"
                        data-has-line-buttons-as-dropdown="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdown') }}"
                        cellspacing="0">
                    <thead>
                    <tr>
                        {{-- Table columns --}}
                        @foreach ($crud->columns() as $column)
                            <th
                                    data-orderable="false"
                                    data-priority="{{ $column['priority'] }}"
                                    data-column-name="{{ $column['name'] }}"
                            >
                                {!! $column['label'] !!}
                            </th>
                        @endforeach

                        @if ( $crud->buttons()->where('stack', 'line')->count() )
                            <th data-orderable="false"
                                data-priority="{{ $crud->getActionsColumnPriority() }}"
                                data-visible-in-export="false"
                                data-action-column="true"
                            >{{ trans('backpack::crud.actions') }}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($settingView as $setting)
                        @php
                            if ($setting["value"] === '1') {
                                $settingValue = $settingsNameMap['yes'][$locale];
                            } else {
                                if ($setting["value"] === '0') {
                                    $settingValue = $settingsNameMap['no'][$locale];
                                } else {
                                    if($settingsNameMap[$setting["value"]]) {
                                        $settingValue = $settingsNameMap[$setting["value"]][$locale];
                                    } else {
                                        $settingValue = $setting["value"];
                                    }
                                }
                            }
                        @endphp
                        <tr class="odd">
                            <td class="dtr-control">
                                <span>{{json_decode($setting["name"])->$locale}}</span>
                            </td>
                            <td><span>{{$settingValue}}</span>
                            </td>
                            <td><span>{{json_decode($setting["description"])->$locale}}</span>
                            </td>
                            <td>
                                <a href="/admin/setting/{{$setting["id"]}}/edit" class="btn btn-sm btn-link">
                                    <span><i class="la la-edit"></i> Редактировать</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        {{-- Table columns --}}
                        @foreach ($crud->columns() as $column)
                            <th>
                                {{-- Bulk checkbox --}}
                                @if($loop->first && $crud->getOperationSetting('bulkActions'))
                                    {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                                @endif
                                {!! $column['label'] !!}
                            </th>
                        @endforeach

                        @if ( $crud->buttons()->where('stack', 'line')->count() )
                            <th>{{ trans('backpack::crud.actions') }}</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <style>
        .table-settings-name {
            margin-top: 40px;
        }
    </style>

@endsection

@section('after_styles')
    {{-- DATA TABLES --}}
    @basset('https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css')
    @basset('https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css')
    @basset('https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css')

    {{-- CRUD LIST CONTENT - crud_list_styles stack --}}
    @stack('crud_list_styles')
@endsection

@section('after_scripts')
    @include('crud::inc.datatables_logic')

    {{-- CRUD LIST CONTENT - crud_list_scripts stack --}}
    @stack('crud_list_scripts')
@endsection
