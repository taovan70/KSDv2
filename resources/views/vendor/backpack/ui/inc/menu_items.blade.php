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
    </ul>
</li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('adv-page') }}"><i class="nav-icon la la-pager"></i> {{ __('models.adv-pages') }}</a></li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('adv-block')}}">
        <i class="nav-icon la la-anchor"></i> {{ __('models.adv-blocks') }}
    </a>
</li>

<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('author') }}">
        <i class="nav-icon la la-users"></i> {{ __('models.authors') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('article') }}">
        <i class="nav-icon la la-copy"></i> {{ __('models.articles') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('user') }}">
        <i class="nav-icon la la-user"></i> {{ __('models.users') }}
    </a>
</li>

@if(Auth::guard('backpack')->user()->role->name == 'admin')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('log-user-event') }}"><i class="nav-icon la la-digital-tachograph"></i> {{ __('models.user_logging') }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i> <span>{{ __('models.settings') }}</span></a></li>
@endif
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('backup') }}'><i class='nav-icon la la-hdd-o'></i>{{ __('backpack::backup.backup') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('file_manager') }}"><i class="nav-icon la la-copy"></i> {{ __('models.file_manager') }}</a></li>
