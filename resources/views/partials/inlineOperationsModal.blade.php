@push('after_scripts')
<script>
  function initializeFieldsWithJavascript(container) {
    let selector;
    if (container instanceof jQuery) {
      selector = container;
    } else {
      selector = $(container);
    }
    // NOT_FORGET add .not('input') here
    selector.find("[data-init-function]").not("[data-initialized=true]").each(function() {
      let element = $(this);
      let functionName = element.data('init-function');

      if (typeof window[functionName] === "function") {
        window[functionName](element);

        // mark the element as initialized, so that its function is never called again
        element.attr('data-initialized', 'true');
      }
    });
  }
  /**
   * Takes all inputs in a repeatable element and makes them an object.
   */
  function repeatableElementToObj(element) {
    var obj = {};

    element.find('input, select, textarea').each(function () {
      if ($(this).data('repeatable-input-name')) {
        obj[$(this).data('repeatable-input-name')] = $(this).val();
      }
    });

    return obj;
  }

  /**
   * The method that initializes the javascript on this field type.
   */
  function bpFieldInitRepeatableElement(element) {

    var field_name = element.attr('name');

    var container_holder = $('[data-repeatable-holder="'+field_name+'"]');

    var init_rows = Number(container_holder.attr('data-init-rows'));
    var min_rows = Number(container_holder.attr('data-min-rows'));
    var max_rows = Number(container_holder.attr('data-max-rows')) || Infinity;

    // make a copy of the group of inputs in their default state
    // this way we have a clean element we can clone when the user
    // wants to add a new group of inputs
    var container = $('[data-repeatable-identifier="'+field_name+'"]').last();

    // make sure the inputs get the data-repeatable-input-name
    // so we can know that they are inside repeatable
    container.find('input, select, textarea')
      .each(function(){
        var name_attr = getCleanNameArgFromInput($(this));
        $(this).attr('data-repeatable-input-name', name_attr)
      });

    var field_group_clone = container.clone();
    container.remove();

    element.parent().find('.add-repeatable-element-button').click(function(){
      newRepeatableElement(container_holder, field_group_clone);
    });

    $('input[type=hidden][name='+field_name+']').first().on('CrudField:disable', function() {
      disableRepeatableContainerFields(container_holder);
    });

    $('input[type=hidden][name='+field_name+']').first().on('CrudField:enable', function() {
      enableRepeatableContainerFields(container_holder);
    });

    var container_rows = container_holder.children().length;
    var add_entry_button = element.parent().find('.add-repeatable-element-button');
    if(container_rows === 0) {
      for(let i = 0; i < Math.min(init_rows, max_rows || init_rows); i++) {
        container_rows++;
        add_entry_button.trigger('click');
      }
    }

    setupRepeatableNamesOnInputs(container_holder);

    setupElementRowsNumbers(container_holder);

    setupElementCustomSelectors(container_holder);

    setupRepeatableDeleteRowButtons(container_holder);

    setupRepeatableReorderButtons(container_holder);

    updateRepeatableRowCount(container_holder);

    setupFieldCallbacks(container_holder);

    setupFieldCallbacksListener(container_holder);

    setupRepeatableChangeEvent(container_holder);
  }

  function disableRepeatableContainerFields(container) {
    switchRepeatableInputsDisableState(container, false);
    container.parent().parent().find('.add-repeatable-element-button').attr('disabled', 'disabled')
    container.children().each(function(i, row) {
      row = $(row)
      row.find('.delete-element').attr('disabled', 'disabled');
      row.find('.move-element-up, .move-element-down').attr('disabled', 'disabled');
    });
  }

  function enableRepeatableContainerFields(container) {
    switchRepeatableInputsDisableState(container);
    container.parent().parent().find('.add-repeatable-element-button').removeAttr('disabled');
    container.children().each(function(i, row) {
      row = $(row)
      row.find('.delete-element').removeAttr('disabled');
      row.find('.move-element-up, .move-element-down').removeAttr('disabled');
    });
  }

  function switchRepeatableInputsDisableState(container, enable = true) {
    let subfields = JSON.parse(container.attr('data-subfield-names'));
    let repeatableName = container.attr('data-repeatable-holder');
    container.children().each(function(i, el) {
      subfields.forEach(function(name) {
        crud.field(repeatableName).subfield(name, i+1).enable(enable);
      });
    });
  }

  function setupRepeatableNamesOnInputs(container) {
    container.find('input, select, textarea')
      .each(function() {
        if (typeof $(this).attr('data-repeatable-input-name') === 'undefined') {
          var nameAttr = getCleanNameArgFromInput($(this));
          if(nameAttr) {
            $(this).attr('data-repeatable-input-name', nameAttr)
          }
        }
      });
  }

  /**
   * Adds a new field group to the repeatable input.
   */
  function newRepeatableElement(container_holder, field_group) {

    var new_field_group = field_group.clone();

    container_holder.append(new_field_group);

    // after appending to the container we reassure row numbers
    setupElementRowsNumbers(container_holder);

    // we also setup the custom selectors in the elements so we can use dependant functionality
    setupElementCustomSelectors(container_holder);

    setupRepeatableDeleteRowButtons(container_holder);

    setupRepeatableReorderButtons(container_holder);

    // updates the row count in repeatable and handle the buttons state
    updateRepeatableRowCount(container_holder);

    // re-index the array names for the fields
    updateRepeatableContainerNamesIndexes(container_holder);

    initializeFieldsWithJavascript(container_holder);

    setupFieldCallbacks(container_holder);

    setupRepeatableChangeEvent(container_holder);

    triggerRepeatableInputChangeEvent(container_holder);

    triggerFocusOnFirstInputField(getFirstFocusableField(new_field_group));
  }

  function setupRepeatableDeleteRowButtons(container) {
    container.children().each(function(i, repeatable_group) {
      setupRepeatableDeleteButtonEvent(repeatable_group);
    });
  }

  function setupRepeatableDeleteButtonEvent(repeatable_group) {
    let row = $(repeatable_group);
    let delete_button = row.find('.delete-element');

    // remove previous events on this button
    delete_button.off('click');

    delete_button.click(function(){

      let $repeatableElement = $(this).closest('.repeatable-element');
      let container = $('[data-repeatable-holder="'+$($repeatableElement).attr('data-repeatable-identifier')+'"]')

      row.find('input, select, textarea').each(function(i, el) {
        // we trigger this event so fields can intercept when they are beeing deleted from the page
        // implemented because of ckeditor instances that stayed around when deleted from page
        // introducing unwanted js errors and high memory usage.
        $(el).trigger('CrudField:delete');
      });

      $repeatableElement.remove();

      triggerRepeatableInputChangeEvent(container);

      // updates the row count and handle button state
      updateRepeatableRowCount(container);

      //we reassure row numbers on delete
      setupElementRowsNumbers(container);

      updateRepeatableContainerNamesIndexes(container);
    });
  }

  function setupRepeatableReorderButtons(container) {
    container.children().each(function(i, repeatable_group) {
      setupRepeatableReorderButtonEvent($(repeatable_group));
    });
  }

  function setupRepeatableReorderButtonEvent(repeatable_group) {
    let row = $(repeatable_group);
    let reorder_buttons = row.find('.move-element-up, .move-element-down');

    // remove previous events on this button
    reorder_buttons.off('click');

    reorder_buttons.click(function(e){

      let $repeatableElement = $(e.target).closest('.repeatable-element');
      let container = $('[data-repeatable-holder="'+$($repeatableElement).attr('data-repeatable-identifier')+'"]')

      // get existing values
      let elementIndex = positionIndex = $repeatableElement.index();

      positionIndex += $(this).is('.move-element-up') ? -1 : 1;

      if (positionIndex < 0) return;

      if($(this).is('.move-element-up')) {
        container.children().eq(positionIndex).before($repeatableElement)
      }else{
        container.children().eq(positionIndex).after($repeatableElement)
      }

      triggerRepeatableInputChangeEvent(container)

      // after appending to the container we reassure row numbers
      setupElementRowsNumbers(container);

      // re-index the array names for the fields
      updateRepeatableContainerNamesIndexes(container);

    });
  }

  // this function is responsible for managing rows numbers upon creation/deletion of elements
  function setupElementRowsNumbers(container) {
    var number_of_rows = 0;
    container.children().each(function(i, el) {
      var rowNumber = i+1;
      $(el).attr('data-row-number', rowNumber);
      //also attach the row number to all the input elements inside
      $(el).find('input, select, textarea').each(function(i, input) {
        // only add the row number to inputs that have name, so they are going to be submited in form
        if($(input).attr('name')) {
          $(input).attr('data-row-number', rowNumber);
        }

        if($(input).is('[data-reorder-input]')) {
          $(input).val(rowNumber);
        }
      });
      number_of_rows++;
    });

    container.attr('number-of-rows', number_of_rows);
  }

  // this function is responsible for adding custom selectors to repeatable inputs that are selects and could be used with
  // dependant fields functionality
  function setupElementCustomSelectors(container) {
    container.children().each(function(i, el) {
      // attach a custom selector to this elements
      $(el).find('select').each(function(i, select) {
        let selector = '[data-repeatable-input-name="%DEPENDENCY%"][data-row-number="%ROW%"],[data-repeatable-input-name="%DEPENDENCY%[]"][data-row-number="%ROW%"]';
        select.setAttribute('data-custom-selector', selector);
      });
    });
  }

  function updateRepeatableContainerNamesIndexes(container) {
    container.children().each(function(i, repeatable) {
      var index = $(repeatable).attr('data-row-number')-1;
      let repeatableName = $(repeatable).attr('data-repeatable-identifier');

      // updates the indexes in the array of repeatable inputs
      $(repeatable).find('input, select, textarea').each(function(i, el) {
        if(typeof $(el).attr('data-row-number') !== 'undefined') {
          let field_name = $(el).attr('data-repeatable-input-name') ?? $(el).attr('name') ?? $(el).parent().find('input[data-repeatable-input-name]').first().attr('data-repeatable-input-name');
          let suffix = '';
          // if there are more than one "[" character, that means we already have the repeatable name
          // we need to parse that name to get the "actual" field name.
          if(field_name.endsWith("[]")) {
            suffix = "[]";
            field_name = field_name.slice(0,-2);
          }

          if($(el).prop('multiple')) {
            suffix = "[]";
          }

          if(field_name.split('[').length - 1 > 1) {
            let field_name_position = field_name.lastIndexOf('[');
            // field name will contain the closing "]" that's why the last slice.
            field_name = field_name.substring(field_name_position + 1).slice(0,-1);
          }

          if(typeof $(el).attr('data-repeatable-input-name') === 'undefined') {
            $(el).attr('data-repeatable-input-name', field_name);
          }

          $(el).attr('name', container.attr('data-repeatable-holder')+'['+index+']['+field_name+']'+suffix);


        }
      });
    });

  }

  function triggerRepeatableInputChangeEvent(repeatable) {
    var values = [];
    repeatable.children().each(function(i, el) {
      values.push(repeatableElementToObj($(el)));
    });
    $('input[type=hidden][name='+$(repeatable).attr('data-repeatable-holder')+']').first().trigger('change', [values]);
  }

  function setupFieldCallbacks(container) {
    let subfields = JSON.parse(container.attr('data-subfield-names'));
    let repeatableName = container.attr('data-repeatable-holder');
    let fieldCallbacks = window.crud.subfieldsCallbacks[repeatableName] ?? false;

    if(!fieldCallbacks) {
      return;
    }

    container.children().each(function(i, el) {
      subfields.forEach(function(name) {
        let rowNumber = i + 1;
        let subfield = crud.field(repeatableName).subfield(name, rowNumber);
        let callbacksApplied = JSON.parse(subfield.input.dataset.callbacks ?? '[]');

        fieldCallbacks
          .filter(callback =>
            callback.field.name === name &&
            callback.field.parent.name === repeatableName
          )
          .forEach((callback, callbackID) => {
            if(callbacksApplied.includes(callbackID)) {
              return;
            }

            let bindedClosure = callback.closure.bind(subfield);
            let fieldChanged = (event, values) => bindedClosure(subfield, event, values);

            if(['INPUT', 'TEXTAREA'].includes(subfield.input?.nodeName)) {
              subfield.input?.addEventListener('input', fieldChanged, false);
            }

            subfield.$input.change(fieldChanged);

            if(callback.triggerChange) {
              subfield.$input.trigger('change');
            }

            callbacksApplied.push(callbackID);
          });

        subfield.input.dataset.callbacks = JSON.stringify(callbacksApplied);
      });
    });
  }

  function setupFieldCallbacksListener(container) {
    container
      .closest('[bp-field-wrapper]')
      .on('CrudField:subfieldCallbacksUpdated', () => setupFieldCallbacks(container));
  }

  function setupRepeatableChangeEvent(container) {
    let subfields = JSON.parse(container.attr('data-subfield-names'));
    let repeatableName = container.attr('data-repeatable-holder');
    container.children().each(function(i, el) {
      let rowNumber = i+1;
      subfields.forEach(function(name) {
        let subfield = crud.field(repeatableName).subfield(name, rowNumber);
        if(!subfield.input?.getAttribute('change-event-applied')) {
          subfield.onChange(function(event) {
            triggerRepeatableInputChangeEvent(container);
          });
          subfield.input?.setAttribute('change-event-applied', true);
        }
      });
    });
  }

  // return the clean name from the input
  function getCleanNameArgFromInput(element) {
    if (element.data('repeatable-input-name')) {
      fieldName = element.data('repeatable-input-name');
    }
    if (element.data('name')) {
      fieldName = element.data('name');
    } else if (element.attr('name')) {
      fieldName = element.attr('name');
    }

    if(typeof fieldName === 'undefined') {
      return false;
    }

    // if there are more than one "[" character, that means we already have the repeatable name
    // we need to parse that name to get the "actual" field name.
    if(fieldName.endsWith("[]")) {
      fieldName = fieldName.slice(0,-2);
    }
    if(fieldName.split('[').length - 1 > 1) {
      let fieldName_position = fieldName.lastIndexOf('[');
      // field name will contain the closing "]" that's why the last slice.
      fieldName = fieldName.substring(fieldName_position + 1).slice(0,-1);
    }
    return fieldName;
  }

  // update the container current number of rows and work out the buttons state
  function updateRepeatableRowCount(container) {
    let max_rows = Number(container.attr('data-max-rows')) || Infinity;
    let min_rows = Number(container.attr('data-min-rows')) || 0;

    let current_rows =  container.children().length;

    // show or hide delete button
    container.find('.delete-element').toggleClass('d-none', current_rows <= min_rows);

    // show or hide move buttons
    container.find('.move-element-up, .move-element-down').toggleClass('d-none', current_rows <= 1);

    // show or hide new item button
    container.parent().parent().find('.add-repeatable-element-button').toggleClass('d-none', current_rows >= max_rows);

  }
</script>
@endpush

@push('after_scripts')
    <script>
        $(document).ready(function() {

          function emptyModal(innerData) {

            return `
              <div class="modal fade show" id="inline-show-dialog" tabindex="0" data-backdrop="static" role="dialog" aria-labelledby="adv-block-inline-create-dialog-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adv-block-inline-create-dialog-label">
                                Просмотр
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body bg-light">
                            ${innerData}
                        </div>
                    </div>
                </div>
            </div>
          `}

            function bpFieldInitUploadElement(element) {
                let fileInput = element.find(".file_input");
                let fileClearButton = element.find(".file_clear_button");
                let fieldName = element.attr('data-field-name');
                let inputWrapper = element.find(".backstrap-file");
                let inputLabel = element.find(".backstrap-file-label");

                if (fileInput.attr('data-row-number')) {
                    $('<input type="hidden" class="order_uploads" name="_order_' + fieldName + '" value="' +
                        fileInput.data('filename') + '">').insertAfter(fileInput);

                    let observer = new MutationObserver(function(mutations) {

                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName == 'data-row-number') {
                                let field = $(mutation.target);
                                field = field.next('input[name="' + mutation.target.getAttribute(
                                    'name') + '"]');
                                field.attr('name', '_order_' + mutation.target.getAttribute(
                                'name'));
                                field.val(mutation.target.getAttribute('data-filename'));
                            }
                        });
                    });

                    observer.observe(fileInput[0], {
                        attributes: true,
                    });
                }

                fileClearButton.click(function(e) {
                    e.preventDefault();
                    $(this).parent().addClass('d-none');
                    // if the file input has a data-row-number attribute, it means it's inside a repeatable field
                    // in that case, will send the value of the cleared input to the server
                    if (fileInput.attr('data-row-number')) {
                        $("<input type='hidden' name='_clear_" + fieldName + "' value='" + fileInput.data(
                            'filename') + "'>").insertAfter(fileInput);
                        fileInput.siblings('.order_uploads').remove();
                    }
                    fileInput.parent().removeClass('d-none');
                    fileInput.attr("value", "").replaceWith(fileInput.clone(true));

                    // redo the selector, so we can use the same fileInput variable going forward
                    fileInput = element.find(".file_input");

                    // add a hidden input with the same name, so that the setXAttribute method is triggered
                    $("<input type='hidden' name='" + fieldName + "' value=''>").insertAfter(fileInput);
                });

                fileInput.change(function() {
                    let path = $(this).val();
                    path = path.replace("C:\\fakepath\\", "");
                    inputLabel.html(path);
                    // remove the hidden input
                    $(this).next("input[type=hidden]").remove();
                });

                element.on('CrudField:disable', function(e) {
                    element.children('.backstrap-file').find('input').prop('disabled', 'disabled');

                    let $deleteButton = element.children('.existing-file').children('a.file_clear_button');

                    if ($deleteButton.length > 0) {
                        $deleteButton.on('click.prevent', function(e) {
                            e.stopImmediatePropagation();
                            return false;
                        });
                        // make the event we just registered, the first to be triggered
                        $._data($deleteButton.get(0), "events").click.reverse();
                    }
                });

                element.on('CrudField:enable', function(e) {
                    element.children('.backstrap-file').find('input').removeAttr('disabled');
                    element.children('.existing-file').children('a.file_clear_button').unbind(
                        'click.prevent');
                });

            }



            function triggerModal(data) {
                initializeFieldsWithJavascript('form');
                let $fieldName = data.page;
                let $modal = $(data.modalId);
                let $modalSaveButton = $modal.find('#saveButton');
                let $modalCancelButton = $modal.find('#cancelButton');
                let $form = $(document.getElementById($fieldName + "-inline-create-form"));
                const entityId = data.entityId ? data.entityId : '';
                let $inlineCreateRoute = '<?php echo backpack_url(''); ?>' + '/' + data.page + '/inline/' + data.method +
                    '/' + entityId;
                $modal.modal();

                $modalCancelButton.on('click', function() {
                    $($modal).modal('hide');
                });

                $modal.on('hidden.bs.modal', function(e) {
                    $modal.remove();
                });

                if (data.method === 'show') {
                    let $inlineCreateRoute = '<?php echo backpack_url(''); ?>' + '/' + data.page + '/' + entityId +
                        '/' + data.method;
                    return
                }



                //when you hit save on modal save button.
                $modalSaveButton.on('click', function() {
                    $form = document.getElementById($fieldName + "-inline-create-form");

                    //this is needed otherwise fields like ckeditor don't post their value.
                    $($form).trigger('form-pre-serialize');

                    let $formData = new FormData($form);

                    let checkbox = $($form).find("input[type=checkbox]");
                    if (checkbox) {
                        $.each(checkbox, function(key, val) {
                            let name = $(val).attr('name');
                            let checkboxChecked = 0;
                            const closestHidden = $(val).siblings().filter('input[type=hidden]')
                                .first();
                            if (closestHidden) {
                                name = closestHidden.attr('name')
                            }
                            if ($(val).is(':checked')) {
                                checkboxChecked = 1;
                            }
                            $formData.set(name, checkboxChecked)
                        });
                    }


                    //we change button state so users know something is happening.
                    //we also disable it to prevent double form submition
                    let loadingText = '<i class="la la-spinner la-spin"></i><?php
                    echo e(trans('backpack::crud.inline_saving')); ?>';
                    if ($modalSaveButton.html() !== loadingText) {
                        $modalSaveButton.data('original-text', $(this).html());
                        $modalSaveButton.html(loadingText);
                        $modalSaveButton.prop('disabled', true);
                    }

                    $.ajax({
                        url: $inlineCreateRoute,
                        data: $formData,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        success: function(result) {
                            $createdEntity = result.data;
                            $modal.modal('hide');
                            //new Noty({
                            //  type: "info",
                            //   text: '<?php
                            //         echo __('models.success_create');
                            ?>',
                            // }).show();
                            window.location.reload()
                        },
                        error: function(result) {

                            let $errors = result?.responseJSON?.errors;
                            let message = '';
                            if ($errors) {

                                for (const [key, value] of Object.entries($errors)) {
                                    message +=
                                        `<b>${translateErrorText(key)}</b>: ${value[0]} <br> `
                                }

                                new Noty({
                                    type: "error",
                                    text: `<strong><?php
                                    echo __('models.error_save'); ?></strong><br>  ${message}`,
                                }).show();
                            }


                            //revert save button back to normal
                            $modalSaveButton.prop('disabled', false);
                            $modalSaveButton.html($modalSaveButton.data('original-text'));
                        }
                    });
                });
                return
            }

            window.setupInlineCreateButtons = function setupInlineCreateButtons(data) {
                let $fieldEntity = data.page;
                let entityId = ''

                let $inlineModalClass = 'modal-dialog';
                let $parentLoadedFields = '';
                let $includeMainFormFields = false;
                let $form = $('form');

                $('body main').on('click', 'a', function(e) {
                    e.preventDefault();
                    let $clickedElement = $(e.target);
                    if ($clickedElement.is('i')) {
                        $clickedElement = $clickedElement.parent().parent();
                    }

                    if ($clickedElement.is('span')) {
                        $clickedElement = $clickedElement.parent();
                    }

                    if ($clickedElement.attr('href')?.includes('/' + 'edit') && $clickedElement.attr(
                            'href')?.includes(data.page)) {
                        // if EDIT
                        let $inlineModalRoute = '<?php
                        echo backpack_url(''); ?>' +
                            '/' + data.page + '/inline/update/modal/';

                        const match = $clickedElement.attr('href')?.match(/\/(\d+)\//);
                        entityId = match[1];


                        if (typeof $includeMainFormFields === "boolean" && $includeMainFormFields ===
                            true) {
                            let $toPass = $form.serializeArray();
                        } else {
                            if (typeof $includeMainFormFields !== "boolean") {
                                let $fields = JSON.parse($includeMainFormFields);
                                let $serializedForm = $form.serializeArray();
                                let $toPass = [];

                                $fields.forEach(function(value, index) {
                                    $valueFromForm = $serializedForm.filter(function(field) {
                                        return field.name === value
                                    });
                                    $toPass.push($valueFromForm[0]);
                                });
                            }
                        }

                        $.ajax({
                            url: $inlineModalRoute + entityId,
                            data: (function() {
                                if (typeof $includeMainFormFields === 'array' ||
                                    $includeMainFormFields) {
                                    return {
                                        'entity': $fieldEntity,
                                        'modal_class': $inlineModalClass,
                                        'parent_loaded_assets': $parentLoadedFields,
                                        'main_form_fields': $toPass
                                    };
                                } else {
                                    return {
                                        'entity': $fieldEntity,
                                        'modal_class': $inlineModalClass,
                                        'parent_loaded_assets': $parentLoadedFields
                                    };
                                }
                            })(),
                            type: 'POST',
                            success: function(result) {
                                $('body').append(result);
                                triggerModal({
                                    method: 'update',
                                    page: data.page,
                                    entityId: entityId,
                                    modalId: '#inline-create-dialog',
                                    openButtonId: 'a',
                                    dataTableId: "#crudTable"
                                });

                            },
                            error: function(result) {
                                new Noty({
                                    type: "error",
                                    text: "<strong>{{ trans('backpack::crud.ajax_error_title') }}</strong><br>{{ trans('backpack::crud.ajax_error_text') }}"
                                }).show();
                                // $inlineCreateButtonElement.html($inlineCreateButtonElement.data('original-text'));
                            }
                        });
                    }
                    else if ($clickedElement.attr('href')?.includes('/' + 'create') && $clickedElement.attr('href')?.includes(data.page)) {
                        // if CREATE
                        let $inlineModalRoute = '<?php
                        echo backpack_url(''); ?>' +
                            '/' + data.page + '/inline/create/modal/';
                        $.ajax({
                            url: $inlineModalRoute,
                            data: (function() {
                                if (typeof $includeMainFormFields === 'array' ||
                                    $includeMainFormFields) {
                                    return {
                                        'entity': $fieldEntity,
                                        'modal_class': $inlineModalClass,
                                        'parent_loaded_assets': $parentLoadedFields,
                                        'main_form_fields': []
                                    };
                                } else {
                                    return {
                                        'entity': $fieldEntity,
                                        'modal_class': $inlineModalClass,
                                        'parent_loaded_assets': $parentLoadedFields
                                    };
                                }
                            })(),
                            type: 'POST',
                            success: function(result) {
                                $('body').append(result);
                                triggerModal({
                                    method: 'create',
                                    page: data.page,
                                    modalId: '#inline-create-dialog',
                                    openButtonId: 'a',
                                    dataTableId: "#crudTable"
                                });
                            },
                            error: function(result) {
                                new Noty({
                                    type: "error",
                                    text: "<strong>{{ trans('backpack::crud.ajax_error_title') }}</strong><br>{{ trans('backpack::crud.ajax_error_text') }}"
                                }).show();
                                // $inlineCreateButtonElement.html($inlineCreateButtonElement.data('original-text'));
                            }
                        });
                    } else if ($clickedElement.attr('href')?.includes('/' + 'show') && $clickedElement.attr(
                            'href')?.includes(data.page)) {
                        // if SHOW
                      const match = $clickedElement.attr('href')?.match(/\/(\d+)\//);
                      entityId = match[1];
                      let $inlineShowRoute = '<?php echo backpack_url(''); ?>' + '/' + data.page + '/' + entityId +
                        '/show';

                      $.ajax({
                        url: $inlineShowRoute,
                        data:[],
                        type: 'GET',
                        success: function(result) {
                          const modal = emptyModal($(result).find('table.table')[0].outerHTML)
                          $('body').append(modal);
                          //$('body').append($(result).find('table'));
                          triggerModal({
                            method: 'show',
                            page: data.page,
                            entityId: entityId,
                            modalId: '#inline-show-dialog',
                            openButtonId: 'a',
                            dataTableId: "#crudTable"
                          });

                        },
                        error: function(result) {
                          new Noty({
                            type: "error",
                            text: "<strong>{{ trans('backpack::crud.ajax_error_title') }}</strong><br>{{ trans('backpack::crud.ajax_error_text') }}"
                          }).show();
                        }
                      });
                    } else {
                      const href = $clickedElement.attr('href')
                      if(href?.includes('/' + 'create') || href?.includes('/' + 'edit') || href?.includes('/' + 'show')) {
                          return
                      }
                      window.location.href = $clickedElement.attr('href')
                    }
                });
            }


            window.setupInlineCreateButtons({
                page: '<?php echo $widget['content']['page']; ?>',
            })

            const secondPage = '<?php if (!empty($widget['content']['secondPage'])) {
                echo $widget['content']['secondPage'];
            } ?>';
            if (secondPage) {
                window.setupInlineCreateButtons({
                    page: secondPage,
                })
            }

            function translateErrorText(text) {
                const lang = '<?php echo backpack_user()->lang; ?>';
                const translateObjectEN = {
                    name: 'Name',
                    description: 'Description',
                    icon_path: 'Icon',
                    photo_path: 'Photo',
                    mini_pic_path: 'Mini picture',
                    slug: 'Slug'
                }
                const translateObjectRU = {
                    name: 'Название',
                    description: 'Описание',
                    icon_path: 'Иконка',
                    photo_path: 'Фотография',
                    mini_pic_path: 'Мини-картинка',
                    slug: 'Слаг'
                }

                const translateObject = lang === 'ru' ? translateObjectRU : translateObjectEN


                return translateObject[text] ? translateObject[text] : text
            }
        });
    </script>
    <style>
        .modal-dialog {
            min-width: 700px;
            margin: 5% auto;
        }

        @media screen and (max-width: 768px) {
            .modal-dialog {
                min-width: 95%;
                width: 95%;
            }
        }

        #inline-create-dialog .btn-close.close::after {
            content: "×";
            font-size: 20px;
            font-weight: bold;
            width: 12px;
            height: 12px;
        }
    </style>
@endpush
