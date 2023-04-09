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
<li class="nav-item nav-dropdown text-capitalize">
    <a class="nav-link nav-dropdown-toggle" href="#">
        <i class="nav-icon la la-sitemap"></i>
        {{ __('models.structure') }}
    </a>
    <ul class="nav-dropdown-items">
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
        <li class="nav-item">
            <a class="nav-link text-capitalize" href="{{ backpack_url('sub-section') }}">
                {{ __('models.sub_sections') }}
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('author') }}">
        <i class="nav-icon la la-users"></i> {{ __('models.authors') }}
    </a>
</li>