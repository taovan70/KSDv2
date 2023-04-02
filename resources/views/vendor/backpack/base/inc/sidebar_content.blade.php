{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
                class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item">
        <a class="nav-link text-capitalize" href="{{ backpack_url('tag') }}">
                <i class="nav-icon la la-question"></i>{{ __('models.tags') }}
        </a>
</li>
<li class="nav-item">
        <a class="nav-link text-capitalize" href="{{ backpack_url('category') }}">
                <i class="nav-icon la la-question"></i>{{ __('models.categories') }}
        </a>
</li>