@push('after_scripts')
    <script>
        $(document).ready(function() {


            function triggerModal(data) {
                let $fieldName = data.page;
                let $modal = $(data.modalId);
                let $modalSaveButton = $modal.find('#saveButton');
                let $modalCancelButton = $modal.find('#cancelButton');
                let $form = $(document.getElementById($fieldName + "-inline-create-form"));
                const entityId = data.entityId ? data.entityId : '';
                let $inlineCreateRoute = '<?php echo backpack_url(''); ?>' + '/'+data.page+'/inline/' + data.method +
                    '/' + entityId;
                $modal.modal();

                $modalCancelButton.on('click', function() {
                    $($modal).modal('hide');
                });

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
                          console.log("result ", result);
                            let $errors = result?.responseJSON?.errors;
                            let message = '';
                            if($errors) {
                              console.log("$errors", $errors)

                              for (const [key, value] of Object.entries($errors)) {
                                message += `<b>${key}</b>: ${value[0]} <br> `
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

                $modal.on('hidden.bs.modal', function(e) {
                    $modal.remove();
                });

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

                    if ($clickedElement.attr('href')?.includes('/' + 'edit') && $clickedElement.attr('href')?.includes(data.page)) {
                        // if EDIT
                        let $inlineModalRoute = '<?php
                        echo backpack_url(''); ?>' +
                            '/'+data.page+'/inline/update/modal/';

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

                    if ($clickedElement.attr('href')?.includes('/' + 'create')  && $clickedElement.attr('href')?.includes(data.page)) {
                        // if CREATE
                        let $inlineModalRoute = '<?php
                        echo backpack_url(''); ?>' +
                            '/'+data.page+'/inline/create/modal/';
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
                    }
                });
            }


            window.setupInlineCreateButtons({
              page: '<?php echo $widget['content']['page']; ?>',
            })

            const secondPage = '<?php if(!empty($widget['content']['secondPage'])) echo $widget['content']['secondPage']; ?>';
            if(secondPage) {
              window.setupInlineCreateButtons({
                page: secondPage,
              })
            }
        });
    </script>
@endpush
