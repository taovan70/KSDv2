{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('tag') }}">
        <i class="nav-icon la la-tags"></i>{{ __('models.tags') }}
    </a>
</li>
<li class="nav-item text-capitalize">
    <a class="nav-link" href="#">
        <i class="nav-icon la la-sitemap"></i>
        {{ __('models.categories_structure') }}
    </a>
    <ul class="treeview-menu">
        <li class="nav-item">
            <a class="nav-link text-capitalize" href="{{ backpack_url('category') }}">
                {{ __('models.categories') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-capitalize" href="{{ backpack_url('subject') }}">
                {{ __('models.subjects') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-capitalize" href="{{ backpack_url('section') }}">
                {{ __('models.sections') }}
            </a>
        </li>
    </ul>
</li>
