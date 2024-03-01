@php
    $loadedAssets = json_decode($parentLoadedAssets ?? '[]', true);

    //mark parent crud assets as loaded.
    foreach($loadedAssets as $asset) {
        Assets::markAsLoaded($asset);
    }
@endphp
<div class="modal fade" id="inline-create-dialog" tabindex="0" data-backdrop="static" role="dialog" aria-labelledby="{{$entity}}-inline-create-dialog-label" aria-hidden="true">
    <div class="{{ $modalClass }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $entity }}-inline-create-dialog-label">
                    {!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form method="post"
                      id="{{$entity}}-inline-create-form"
                      action="#"
                      onsubmit="return false"
                      @if ($crud->hasUploadFields('create'))
                          enctype="multipart/form-data"
                        @endif
                >
                    {!! csrf_field() !!}
                    @include('vendor/backpack/crud/operations/form_content', [ 'fields' => $fields, 'action' => $action])

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelButton">{{trans('backpack::crud.cancel')}}</button>
                <button type="button" class="btn btn-primary" id="saveButton">{{trans('backpack::crud.save')}}</button>
            </div>
        </div>
    </div>
</div>

{{-- This is where modal fields assets are pushed.
We bind the modal content including what fields pushed to this stacks to the end of the body tag,
so we make sure all backpack assets are previously loaded, like jquery etc.
We can give the same stack name because backpack `crud_fields_scripts` is already rendered and
this is the only available when rendering the modal html. --}}

@stack('crud_fields_scripts')
<script>
  // Focus on first focusable field when modal is shown
  $('#inline-create-dialog').on('shown.bs.modal', function () {
    $(this).find('form').find('input, select, textarea, button').not('[readonly]').not('[disabled]').filter(':visible:first').focus();
  });
</script>

@stack('crud_fields_styles')

@stack('after_scripts')
<script>

  function translit(word) {
    if (!word) return ''
    let answer = '';
    let converter = {
      'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
      'е': 'e', 'ё': 'e', 'ж': 'zh', 'з': 'z', 'и': 'i',
      'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
      'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't',
      'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch',
      'ш': 'sh', 'щ': 'sch', 'ь': '', 'ы': 'y', 'ъ': '',
      'э': 'e', 'ю': 'yu', 'я': 'ya', '.': '-', ',': '-', '!': '', '?': '', ' ': '-', ':': '-',  ';': '-',

      'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D',
      'Е': 'E', 'Ё': 'E', 'Ж': 'Zh', 'З': 'Z', 'И': 'I',
      'Й': 'Y', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N',
      'О': 'O', 'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T',
      'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C', 'Ч': 'Ch',
      'Ш': 'Sh', 'Щ': 'Sch', 'Ь': '', 'Ы': 'Y', 'Ъ': '',
      'Э': 'E', 'Ю': 'Yu', 'Я': 'Ya'
    };

    for (let i = 0; i < word.length; ++i) {
      if (converter[word[i]] === undefined) {
        answer += word[i];
      } else {
        answer += converter[word[i]];
      }
    }

    return answer.replace(/\s+/g, '-').toLowerCase();
  }

  function hasWhiteSpace(s) {
    return (/\s/).test(s);
  }

  $('#inline-create-dialog').on('shown.bs.modal', function () {
   const $nameInput = $('input[name="name"]');
   const $slugInput = $('input[name="slug"]');
   const $saveButton = $('#saveButton');

    if ($nameInput.length > 0 && $slugInput.length > 0) {

      $nameInput.on("input", function() {
        $slugInput.val(translit($(this).val()));
      })

      $slugInput.on("input", function() {
          if (hasWhiteSpace($(this).val())) {
            if ($('#error_slug').length === 0) {
              $('<span id="error_slug" style="color: red;">Недопустимые символы</span>').insertAfter($(this));
            }
            $saveButton.prop('disabled', true);
          } else {
            $('#error_slug').remove();
            $saveButton.prop('disabled', false);
          }
      })
    }

  });

</script>

@stack('after_styles')
