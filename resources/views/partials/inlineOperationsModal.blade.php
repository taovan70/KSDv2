@push('after_scripts')
    <script>
        // IN THIS SCRIPT BLOCK ONLY MODAL FIELDS INITIALIZERS!
        function initializeFieldsWithJavascript(container) {
            let selector;
            if (container instanceof jQuery) {
                selector = container;
            } else {
                selector = $(container);
            }
            selector.find("[data-init-function]").not("[data-initialized=true]").each(function() {
                let element = $(this);
                let functionName = element.data('init-function');

                if (typeof window[functionName] === "function") {
                    window[functionName](element);

                    element.attr('data-initialized', 'true');
                }
            });
        }


        window.crud = {
            ...window.crud,

            subfieldsCallbacks: [],

            field: name => new CrudField(name),

            fields: names => names.map(window.crud.field),
        };

        class CrudField {
            constructor(name) {
                this.name = name;
                this.$input = this.activeInput;
                this.wrapper = this.inputWrapper;

                this.$input = this.mainInput;

                if (this.wrapper.length === 0) {
                    console.error(`CrudField error! Could not select WRAPPER for "${this.name}"`);
                }

                if (this.$input.length === 0) {
                    console.error(`CrudField error! Could not select INPUT for "${this.name}"`);
                }

                this.input = this.$input[0];
                this.type = this.wrapper.attr('bp-field-type');

                return this;

            }

            get activeInput() {
                this.$input = $(
                    `input[name="${this.name}"], textarea[name="${this.name}"], select[name="${this.name}"], select[name="${this.name}[]"]`
                    );
                let possibleInput = this.$input.length === 1 ? this.$input : this.$input.filter(function() {
                    return $(this).closest('[id=inline-create-dialog]').length
                });
                return possibleInput.length === 1 ? possibleInput : this.$input.first();
            }

            get mainInput() {
                let input = this.wrapper.find('[bp-field-main-input]').first();

                if (input.length !== 0) {
                    return input;
                }

                if (this.$input.length === 0) {
                    input = this.wrapper.find(
                        `input[bp-field-name="${this.name}"], textarea[bp-field-name="${this.name}"], select[bp-field-name="${this.name}"], select[bp-field-name="${this.name}[]"]`
                        ).first();

                    if (input.length === 0) {
                        input = this.wrapper.find('input, textarea, select').first();
                    }

                    return input;
                }

                return this.$input;

            }

            get value() {
                return this.$input.val();
            }

            get inputWrapper() {
                let wrapper = this.$input.closest('[bp-field-wrapper]');
                if (wrapper.length === 0) {
                    wrapper = $(`[bp-field-name="${this.name}"][bp-field-wrapper]`).first();
                }
                return wrapper;
            }

            onChange(closure) {
                const bindedClosure = closure.bind(this);
                const fieldChanged = (event, values) => bindedClosure(this, event, values);

                if (this.isSubfield) {
                    window.crud.subfieldsCallbacks[this.parent.name] ??= [];
                    window.crud.subfieldsCallbacks[this.parent.name].push({
                        closure,
                        field: this
                    });
                    this.wrapper.trigger('CrudField:subfieldCallbacksUpdated');
                    return this;
                }

                if (['INPUT', 'TEXTAREA'].includes(this.input?.nodeName)) {
                    this.input?.addEventListener('input', fieldChanged, false);
                }
                this.$input.change(fieldChanged);

                return this;
            }

            change() {
                if (this.isSubfield) {
                    window.crud.subfieldsCallbacks[this.parent.name]?.forEach(callback => callback.triggerChange =
                    true);
                } else {
                    let event = new Event('change');
                    this.input?.dispatchEvent(event);
                }

                return this;
            }

            show(value = true) {
                this.wrapper.toggleClass('d-none', !value);
                let event = new Event(`CrudField:${value ? 'show' : 'hide'}`);
                this.input?.dispatchEvent(event);
                return this;
            }

            hide(value = true) {
                return this.show(!value);
            }

            enable(value = true) {
                this.$input.attr('disabled', !value && 'disabled');
                let event = new Event(`CrudField:${value ? 'enable' : 'disable'}`);
                this.input?.dispatchEvent(event);
                return this;
            }

            disable(value = true) {
                return this.enable(!value);
            }

            require(value = true) {
                this.wrapper.toggleClass('required', value);
                let event = new Event(`CrudField:${value ? 'require' : 'unrequire'}`);
                this.input?.dispatchEvent(event);
                return this;
            }

            unrequire(value = true) {
                return this.require(!value);
            }

            check(value = true) {
                this.wrapper.find('input[type=checkbox]').prop('checked', value).trigger('change');
                return this;
            }

            uncheck(value = true) {
                return this.check(!value);
            }

            subfield(name, rowNumber = false) {
                let subfield = new CrudField(this.name);
                subfield.name = name;

                if (!rowNumber) {
                    subfield.isSubfield = true;
                    subfield.subfieldHolder = this.name; // deprecated
                    subfield.parent = this;
                } else {
                    subfield.rowNumber = rowNumber;
                    subfield.wrapper = $(`[data-repeatable-identifier="${this.name}"][data-row-number="${rowNumber}"]`)
                        .find(`[bp-field-wrapper][bp-field-name$="${name}"]`);
                    subfield.$input = subfield.wrapper.find(
                        `[data-repeatable-input-name$="${name}"][bp-field-main-input]`);
                    if (subfield.$input.length == 0) {
                        subfield.$input = subfield.wrapper.find(
                            `input[data-repeatable-input-name$="${name}"], textarea[data-repeatable-input-name$="${name}"], select[data-repeatable-input-name$="${name}"]`
                            ).first();
                    }

                    subfield.input = subfield.$input[0];
                }
                return subfield;
            }
        }


        function getFirstFocusableField(form) {
            return form.find('input, select, textarea, button')
                .not('.close')
                .not('[disabled]')
                .filter(':visible:first');
        }

        function triggerFocusOnFirstInputField(firstField) {
            if (firstField.hasClass('select2-hidden-accessible')) {
                return handleFocusOnSelect2Field(firstField);
            }

            firstField.trigger('focus');
        }
    </script>
@endpush

@push('after_scripts')
    <script>
      // IN THIS SCRIPT BLOCK MAIN LOGIC OF MODALS
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
          `
            }

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
                    } else if ($clickedElement.attr('href')?.includes('/' + 'create') && $clickedElement
                        .attr('href')?.includes(data.page)) {
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
                    } else if ($clickedElement.attr('href')?.includes('/' + 'show') && $clickedElement
                        .attr(
                            'href')?.includes(data.page)) {
                        // if SHOW
                        const match = $clickedElement.attr('href')?.match(/\/(\d+)\//);
                        entityId = match[1];
                        let $inlineShowRoute = '<?php echo backpack_url(''); ?>' + '/' + data.page + '/' +
                            entityId +
                            '/show';

                        $.ajax({
                            url: $inlineShowRoute,
                            data: [],
                            type: 'GET',
                            success: function(result) {
                                const modal = emptyModal($(result).find('table.table')[0]
                                    .outerHTML)
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
                        if (href?.includes('/' + 'create') || href?.includes('/' + 'edit') || href
                            ?.includes('/' + 'show')) {
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
