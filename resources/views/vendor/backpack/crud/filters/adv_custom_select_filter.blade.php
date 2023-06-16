<li filter-name="{{$filter->name}}" class="adv-custom-filter-block @if(count($filter->values) > 6) large-amount-pages @endif">
    @foreach ($filter->values as $page)
    <div  page-block="{{$page->slug}}"
         class="pages-link pages-link-{{$page->slug}} @if(request()->query('page') === $page->slug) active-page-link @endif">
        <div class="page-name-block">
            <a href="#"
               parameter="{{$filter->name}}"
               val="{{$page->slug}}"
               type="page"
               class="btn btn-primary page-btn @if(request()->query('page') === $page->slug) active-page @endif"
               data-style="zoom-in">
                <span class="ladda-label">{{$page->name}}</span>
            </a>
        </div>

        <div class="page-options-block">
            <div class="color_type_line">
                <a href="#"
                   parameter="{{$filter->name}}"
                   type="device_type"
                   val="pc"
                   class="btn btn-primary types-button @if(request()->query('device_type') === 'pc') active-types-button @endif"
                   data-style="zoom-in">
                    <span class="ladda-label">{{__('models.pc')}}</span>
                </a>
                <a href="#"
                   parameter="{{$filter->name}}"
                   type="device_type"
                   val="mobile"
                   class="btn btn-primary types-button @if(request()->query('device_type') === 'mobile') active-types-button @endif"
                   data-style="zoom-in">
                    <span class="ladda-label">{{__('models.mob')}}</span>
                </a>
            </div>
            <div class="color_type_line">
                <a href="#"
                   parameter="{{$filter->name}}"
                   type="color_type"
                   val="day"
                   class="btn btn-primary types-button @if(request()->query('color_type') === 'day') active-types-button @endif"
                   data-style="zoom-in">
                    <span class="ladda-label">{{__('models.day')}}</span>
                </a>
                <a href="#"
                   parameter="{{$filter->name}}"
                   type="color_type"
                   val="night"
                   class="btn btn-primary types-button @if(request()->query('color_type') === 'night') active-types-button @endif"
                   data-style="zoom-in">
                    <span class="ladda-label">{{__('models.night')}}</span>
                </a>
            </div>
        </div>
    </div>
    @endforeach

    <div class="create-page-block create-page-block-desktop">
        <a class="btn btn-secondary mb-12-px btn-hide">{{ __('table.adv_block_fields.add_page') }}</a>
        <a href="{{ backpack_url('adv-page/create')}}" class="btn btn-secondary mb-12-px">{{ __('table.adv_block_fields.add_page') }}</a>
        <a href="{{ backpack_url('adv-block/create')}}" class="btn btn-secondary">{{ __('table.adv_block_fields.add_block') }}</a>
    </div>
</li>

@push('crud_list_scripts')
<script>
  jQuery(document).ready(function ($) {
    let ajax_table = $("#crudTable").DataTable();

    $("li[filter-name={{$filter->name}}] .pages-link a").click(function (e) {
      e.preventDefault();
      let parameter = $(this).attr('parameter');
      let type = $(this).attr('type');
      let val = $(this).attr('val');
      const allowParams = ["page", "device_type", "color_type"]


      let new_url = ajax_table.ajax.url()


      if (!URI(new_url).hasQuery(parameter)) {
        new_url = URI(new_url).addQuery(parameter, true);
      }

      if (!URI(new_url).hasQuery(type)) {
        new_url = URI(new_url).addQuery(type, val);
      } else {
        new_url = URI(new_url).setQuery(type, val);
      }

      new_url = normalizeAmpersand(new_url.toString());

      // replace the datatables ajax url with new_url and reload it
      ajax_table.ajax.url(new_url).load();

      // add filter to URL
      crud.updateUrl(new_url);

      const queryParams = URI.parseQuery(new_url)
      $("li[filter-name={{$filter->name}}] a").removeClass("active-types-button")
      for (const paramName in queryParams) {
        if(allowParams.includes(paramName)) {
          $('a[type=' + paramName + '][val=' + queryParams[paramName] + ']').addClass("active-types-button")
        }
      }

      if (type === 'page') {
        let pageBlock = $(this).parent().parent().attr('page-block');
        $("li[filter-name={{$filter->name}}] a").removeClass("active-page")
        $(this).addClass('active-page')
        if (pageBlock === val) {
          $(".pages-link").removeClass("active-page-link")
          $(this).parent().parent().addClass("active-page-link")
        }
      }
    });

  });
</script>
@endpush


@push('after_scripts')
<style>

    #bp-filters-navbar ul {
        width: 100%;
        display: block;
    }

    .types-button {
        min-width: 60px;
        flex-basis: 100%;
    }

    .pages-link {
        flex-basis: 100%;
    }

    .pages-link .types-button {
        background-color: #c5bdf5;
        border-color: #c5bdf5;
        pointer-events: none;
    }

    .active-page-link .types-button, .page-btn {
        background-color: #9b8bf5;
        border-color: #9b8bf5;
        pointer-events: initial;
    }

    .active-page-link .active-types-button, .page-btn.active-page {
        background-color: #7c69ef;
        border-color: #7c69ef;
        pointer-events: initial;
    }

    li.adv-custom-filter-block {
        display: flex;
        gap: 2%;
        margin-bottom: 20px;
    }

    .adv-custom-filter-block  + .nav-item {
        display: none;
    }

    .color_type_line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        gap: 10px;
    }

    .page-name-block {
        text-align: center;
        margin-bottom: 12px;
    }

    .navbar-filters a.d-lg-block {
        display: none!important;
    }

    .navbar-filters {
        padding: 0;
    }

    .create-page-block {
        display: flex;
        flex-direction: column;
        flex-basis: 100%;
    }

    .btn-hide {
        opacity: 0;
    }

    .mb-12-px {
        margin-bottom: 12px;
    }

    .create-page-block .btn-secondary {
        cursor: pointer;
    }

    li.adv-custom-filter-block.large-amount-pages {
        flex-wrap: wrap;
    }

    .large-amount-pages .pages-link {
        flex-basis: 20%;
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .large-amount-pages .btn-hide {
        display: none;
    }

    .la.la-eye::before {
        font-size: 30px;
    }

    .la.la-edit::before {
        font-size: 30px;
    }

    #crudTable .btn.btn-sm.btn-link {
        font-size: 0;
    }

    @media (max-width: 1600px) {
        li.adv-custom-filter-block {
            flex-wrap: wrap;
        }

        .pages-link {
            flex-basis: 32%;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .btn-hide {
            display: none;
        }
    }

    @media (max-width: 992px) {
        .create-page-block-desktop {
            display: none;
        }
    }

    @media (max-width: 680px) {
        .pages-link {
            flex-basis: 100%;
        }

    }

</style>
@endpush
