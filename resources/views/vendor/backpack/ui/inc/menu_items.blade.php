{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('article') }}">
        <i class="nav-icon la la-copy"></i> {{ __('models.articles') }}
    </a>
</li>
<li class="nav-item ">
    <a class="nav-link text-capitalize" href="{{ backpack_url('category') }}">
        <i class="nav-icon la la-sitemap"></i>
        {{ __('models.categories') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('tag') }}">
        <i class="nav-icon la la-tags"></i>{{ __('models.tags') }}
    </a>
</li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('adv-page') }}"><i class="nav-icon la la-pager"></i>
        {{ __('models.adv-pages') }}</a></li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('adv-block') }}">
        <i class="nav-icon la la-anchor"></i> {{ __('models.adv-blocks') }}
    </a>
</li>

<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('author') }}">
        <i class="nav-icon la la-users"></i> {{ __('models.authors') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-capitalize" href="{{ backpack_url('user') }}">
        <i class="nav-icon la la-user"></i> {{ __('models.users') }}
    </a>
</li>

@if (Auth::guard('backpack')->user()->role->name == 'admin')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('log-user-event') }}"><i
                class="nav-icon la la-digital-tachograph"></i> {{ __('models.user_logging') }}</a></li>

    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i>
            <span>{{ __('models.settings') }}</span></a></li>
@endif
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('backup') }}'><i
            class='nav-icon la la-hdd-o'></i>{{ __('backpack::backup.backup') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('file_manager') }}"><i class="nav-icon la la-copy"></i>
        {{ __('models.file_manager') }}</a></li>

<li class="nav-item nav-dropdown text-capitalize">
    <a class="nav-link nav-dropdown-toggle" href="#">
        <i class="nav-icon la la-tv"></i>
        {{ __('models.outer_part') }}
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item nav-dropdown text-capitalize">
            <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon la la-share-alt"></i>
                {{ __('models.main_page') }}
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('popular-categories') }}">
                        <i class="nav-icon la la-chart-pie"></i>
                        {{ __('models.popular_categories') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('most-talked-article') }}">
                        <i class="nav-icon la la-comment-dots"></i>
                        {{ __('models.most_talked_articles') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('readers-recomend-article') }}">
                        <i class="nav-icon la la-user-tie"></i>
                        {{ __('models.readers_recomend_articles') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('big-card-article') }}">
                        <i class="nav-icon la la-user-tie"></i>
                        {{ __('models.big_card_article') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item nav-dropdown text-capitalize">
            <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon la la-file"></i>
                {{ __('models.articles') }}
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('did-you-know-in-articles') }}">
                        <i class="nav-icon la la-question"></i>
                        {{ __('models.did_you_know_in_articles') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item nav-dropdown text-capitalize">
            <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon la la-filter"></i>
                {{ __('models.categories') }}
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('everyone-talking-about') }}">
                        <i class="nav-icon la la-comments"></i>
                        {{ __('models.everyone_talking_about') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('q-a-category') }}">
                        <i class="nav-icon la la-retweet"></i>
                        {{ __('models.q-a-category') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item nav-dropdown text-capitalize">
            <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon la la-stream"></i>
                {{ __('models.sub_categories') }}
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-top-facts-block') }}">
                        <i class="nav-icon lab la-readme"></i>
                        {{ __('models.sub_cat_top_facts_block') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-alphavite-block') }}">
                        <i class="nav-icon la la-poll-h"></i>
                        {{ __('models.sub_cat_alphavite_block') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-calendar') }}">
                        <i class="nav-icon la la-calendar-check"></i>
                        {{ __('models.sub_cat_calendar') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-expert-advice') }}">
                        <i class="nav-icon la la-user-tie"></i>
                        {{ __('models.sub_cat_expert_advice') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-interesting-block') }}">
                        <i class="nav-icon la la-user-graduate"></i>
                        {{ __('models.sub_cat_interesting_block') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-game-one-block') }}">
                        <i class="nav-icon la la-dice-five"></i>
                        {{ __('models.sub_cat_game_one_block') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-know-more-about-each-block') }}">
                        <i class="nav-icon la la-route"></i>
                        {{ __('models.sub_cat_know_more_about_each_block') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-behind-the-scenes-block') }}">
                        <i class="nav-icon la la-mask"></i>
                        {{ __('models.sub_cat_behind_the_scenes_block') }}
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-game-two-block') }}">
                        <i class="nav-icon la la-dice-five"></i>
                        {{ __('models.sub_cat_game_two_block') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('sub-cat-encyclopedia-block') }}">
                        <i class="nav-icon la la-book"></i>
                        {{ __('models.sub_cat_encyclopedia_block') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item text-capitalize">
            <a class="nav-link" href="{{ backpack_url('info-block') }}">
                <i class="nav-icon la la-share-alt"></i>
                {{ __('models.info_blocks') }}
            </a>
        </li>
        <li class="nav-item text-capitalize">
            <a class="nav-link" href="{{ backpack_url('standalone-page') }}">
                <i class="nav-icon la la-file"></i>
                {{ __('models.standalone_pages') }}
            </a>
        </li>
        <li class="nav-item nav-dropdown text-capitalize">
            <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon la la-people-carry"></i>
                {{ __('models.experts_page') }}
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('popular-expert-articles') }}">
                        <i class="nav-icon la la-tape"></i>
                        {{ __('models.popular_experts_articles') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item nav-dropdown text-capitalize">
            <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon la la-glasses"></i>
                {{ 404 }}
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('popular-not-found-articles') }}">
                        <i class="nav-icon la la-hdd"></i>
                        {{ __('models.popular_articles') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('popular-not-found-categories') }}">
                        <i class="nav-icon la la-feather"></i>
                        {{ __('models.more_read_categories') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('popular-not-found-two-weeks-articles') }}">
                        <i class="nav-icon la la-sort-numeric-up-alt"></i>
                        {{ __('models.popular_two_weeks_articles') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item nav-dropdown text-capitalize">
            <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon la la-photo-video"></i>
                {{ __('models.stories') }}
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('stories') }}">
                        <i class="nav-icon la la-film"></i>
                        {{ __('models.stories') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize" href="{{ backpack_url('story-item') }}">
                        <i class="nav-icon la la-film"></i>
                        {{ __('models.story_item') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>

</li>
