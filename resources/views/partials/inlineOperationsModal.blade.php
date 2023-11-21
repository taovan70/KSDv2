@push('after_scripts')
    <script> function setPreviewCookie (e) {
        document.cookie = 'article-preview=<?php echo  env('PREVIEW_ARTICLE_TOKEN');?>; expires=Fri, 31 Dec 9999 23:59:59 GMT"; path=/';
      }</script>
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

          const translateObjectEN = {
            name: 'Name',
            description: 'Description',
            icon_path: 'Icon',
            photo_path: 'Photo',
            mini_pic_path: 'Mini picture',
            slug: 'Slug',
            address: 'Address',
            age: 'Age',
            surname: 'Surname',
            biography: 'Biography',
            password: 'Password',
            password_confirmation: 'Password confirmation',
            article_id: 'Article',
            category_id: 'Category',
            text: 'Text',
            question: 'Question',
            answer: 'Answer',
            menu_order: 'Menu order',
            number_one : 'Number one',
            number_two : 'Number two',
            number_three : 'Number three',
            text_one:'Text one',
            text_two:'Text two',
            text_three:'Text three',
            article_one:'Article one',
            article_two:'Article two',
            content : 'Content',
            video_path: 'Video path',
            background_photo_path : 'Background photo',
            'month_data.0.article_id': 'Article first',
            'month_data.1.article_id': 'Article second',
            'month_data.2.article_id': 'Article third',
            'month_data.3.article_id': 'Article fourth',
            'month_data.4.article_id': 'Article fifth',
            'month_data.5.article_id': 'Article sixth',
            'month_data.6.article_id': 'Article seventh',
            'month_data.7.article_id': 'Article eighth',
            'month_data.8.article_id': 'Article ninth',
            'month_data.9.article_id': 'Article tenth',
            'month_data.10.article_id': 'Article eleventh',
            'month_data.11.article_id': 'Article twelfth',

            'month_data.0.text': 'Text first',
            'month_data.1.text': 'Text second',
            'month_data.2.text': 'Text third',
            'month_data.3.text': 'Text fourth',
            'month_data.4.text': 'Text fifth',
            'month_data.5.text': 'Text sixth',
            'month_data.6.text': 'Text seventh',
            'month_data.7.text': 'Text eighth',
            'month_data.8.text': 'Text ninth',
            'month_data.9.text': 'Text tenth',
            'month_data.10.text': 'Text eleventh',
            'month_data.11.text': 'Text twelfth',

            'month_data.0.name': 'Name first',
            'month_data.1.name': 'Name second',
            'month_data.2.name': 'Name third',
            'month_data.3.name': 'Name fourth',
            'month_data.4.name': 'Name fifth',
            'month_data.5.name': 'Name sixth',
            'month_data.6.name': 'Name seventh',
            'month_data.7.name': 'Name eighth',
            'month_data.8.name': 'Name ninth',
            'month_data.9.name': 'Name tenth',
            'month_data.10.name': 'Name eleventh',
            'month_data.11.name': 'Name twelfth',

            'block_data.0.name': 'Name block first',
            'block_data.1.name': 'Name block second',
            'block_data.2.name': 'Name block third',
            'block_data.3.name': 'Name block fourth',
            'block_data.4.name': 'Name block fifth',
            'block_data.5.name': 'Name block sixth',
            'block_data.6.name': 'Name block seventh',
            'block_data.7.name': 'Name block eighth',
            'block_data.8.name': 'Name block ninth',
            'block_data.9.name': 'Name block tenth',
            'block_data.10.name': 'Name block eleventh',
            'block_data.11.name': 'Name block twelfth',

            'block_data.0.photo_path': 'Photo block first',
            'block_data.1.photo_path': 'Photo block second',
            'block_data.2.photo_path': 'Photo block third',
            'block_data.3.photo_path': 'Photo block fourth',
            'block_data.4.photo_path': 'Photo block fifth',
            'block_data.5.photo_path': 'Photo block sixth',
            'block_data.6.photo_path': 'Photo block seventh',
            'block_data.7.photo_path': 'Photo block eighth',
            'block_data.8.photo_path': 'Photo block ninth',
            'block_data.9.photo_path': 'Photo block tenth',
            'block_data.10.photo_path': 'Photo block eleventh',
            'block_data.11.photo_path': 'Photo block twelfth',

            'block_data.0.article_one_id': 'Article one block first',
            'block_data.1.article_one_id': 'Article one block second',
            'block_data.2.article_one_id': 'Article one block third',
            'block_data.3.article_one_id': 'Article one block fourth',
            'block_data.4.article_one_id': 'Article one block fifth',
            'block_data.5.article_one_id': 'Article one block sixth',
            'block_data.6.article_one_id': 'Article one block seventh',
            'block_data.7.article_one_id': 'Article one block eighth',
            'block_data.8.article_one_id': 'Article one block ninth',
            'block_data.9.article_one_id': 'Article one block tenth',
            'block_data.10.article_one_id': 'Article one block eleventh',
            'block_data.11.article_one_id': 'Article one block twelfth',

            'block_data.0.article_two_id': 'Article second block first ',
            'block_data.1.article_two_id': 'Article second block second',
            'block_data.2.article_two_id': 'Article second block third',
            'block_data.3.article_two_id': 'Article second block fourth',
            'block_data.4.article_two_id': 'Article second block fifth',
            'block_data.5.article_two_id': 'Article second block sixth',
            'block_data.6.article_two_id': 'Article second block seventh',
            'block_data.7.article_two_id': 'Article second block eighth',
            'block_data.8.article_two_id': 'Article second block ninth',
            'block_data.9.article_two_id': 'Article second block tenth',
            'block_data.10.article_two_id': 'Article second block eleventh',
            'block_data.11.article_two_id': 'Article second block twelfth',
          }
          const translateObjectRU = {
            name: 'Название (Имя)',
            description: 'Описание',
            icon_path: 'Иконка',
            photo_path: 'Фотография',
            mini_pic_path: 'Мини-картинка',
            slug: 'Слаг',
            address: 'Адрес',
            age: 'Возраст',
            surname: 'Фамилия',
            biography: 'Биография',
            password: 'Пароль',
            password_confirmation: 'Подтверждение пароля',
            article_id: 'Статья',
            category_id: 'Категория',
            text: 'Текст (Контент)',
            question: 'Вопрос',
            answer: 'Ответ',
            menu_order: 'Порядок в меню',
            number_one : 'Число первое',
            number_two : 'Число второе',
            number_three : 'Число третье',
            text_one : 'Текст первый',
            text_two : 'Текст второй',
            text_three : 'Текст третий',
            article_one : 'Статья первая',
            article_two : 'Статья вторая',
            content : 'Контент',
            video_path: 'Путь к видео',
            background_photo_path:'Фоновое изображение',
            'month_data.0.article_id': 'Статья первая',
            'month_data.1.article_id': 'Статья вторая',
            'month_data.2.article_id': 'Статья третья',
            'month_data.3.article_id': 'Статья четвертая',
            'month_data.4.article_id': 'Статья пятая',
            'month_data.5.article_id': 'Статья шестая',
            'month_data.6.article_id': 'Статья седьмая',
            'month_data.7.article_id': 'Статья восьмая',
            'month_data.8.article_id': 'Статья девятая',
            'month_data.9.article_id': 'Статья десятая',
            'month_data.10.article_id': 'Статья одиннадцатая',
            'month_data.11.article_id': 'Статья двенадцатая',

            'month_data.0.text': 'Текст первый',
            'month_data.1.text': 'Текст второй',
            'month_data.2.text': 'Текст третий',
            'month_data.3.text': 'Текст четвертый',
            'month_data.4.text': 'Текст пятый',
            'month_data.5.text': 'Текст шестой',
            'month_data.6.text': 'Текст седьмой',
            'month_data.7.text': 'Текст восьмой',
            'month_data.8.text': 'Текст девятый',
            'month_data.9.text': 'Текст десятый',
            'month_data.10.text': 'Текст одиннадцатый',
            'month_data.11.text': 'Текст двенадцатый',

            'month_data.0.name': 'Имя первое',
            'month_data.1.name': 'Имя второе',
            'month_data.2.name': 'Имя третье',
            'month_data.3.name': 'Имя четвертое',
            'month_data.4.name': 'Имя пятое',
            'month_data.5.name': 'Имя шестое',
            'month_data.6.name': 'Имя седьмое',
            'month_data.7.name': 'Имя восьмое',
            'month_data.8.name': 'Имя девятое',
            'month_data.9.name': 'Имя десятое',
            'month_data.10.name': 'Имя одиннадцатое',
            'month_data.11.name': 'Имя двенадцатое',

            'block_data.0.name': 'Имя блок первый',
            'block_data.1.name': 'Имя блок второй',
            'block_data.2.name': 'Имя блок третий',
            'block_data.3.name': 'Имя блок четвертый',
            'block_data.4.name': 'Имя блок пятый',
            'block_data.5.name': 'Имя блок шестой',
            'block_data.6.name': 'Имя блок седьмой',
            'block_data.7.name': 'Имя блок восьмой',
            'block_data.8.name': 'Имя блок девятый',
            'block_data.9.name': 'Имя блок десятый',
            'block_data.10.name': 'Имя блок одиннадцатый',
            'block_data.11.name': 'Имя блок двенадцатый',

            'block_data.0.photo_path': 'Фотография блок первый',
            'block_data.1.photo_path': 'Фотография блок второй',
            'block_data.2.photo_path': 'Фотография блок третий',
            'block_data.3.photo_path': 'Фотография блок четвертый',
            'block_data.4.photo_path': 'Фотография блок пятый',
            'block_data.5.photo_path': 'Фотография блок шестой',
            'block_data.6.photo_path': 'Фотография блок седьмой',
            'block_data.7.photo_path': 'Фотография блок восьмой',
            'block_data.8.photo_path': 'Фотография блок девятый',
            'block_data.9.photo_path': 'Фотография блок десятый',
            'block_data.10.photo_path': 'Фотография блок одиннадцатый',
            'block_data.11.photo_path': 'Фотография блок двенадцатый',

            'block_data.0.article_one_id': 'Статья первая блок первый',
            'block_data.1.article_one_id': 'Статья первая блок второй',
            'block_data.2.article_one_id': 'Статья первая блок третий',
            'block_data.3.article_one_id': 'Статья первая блок четвертый',
            'block_data.4.article_one_id': 'Статья первая блок пятый',
            'block_data.5.article_one_id': 'Статья первая блок шестой',
            'block_data.6.article_one_id': 'Статья первая блок седьмой',
            'block_data.7.article_one_id': 'Статья первая блок восьмой',
            'block_data.8.article_one_id': 'Статья первая блок девятый',
            'block_data.9.article_one_id': 'Статья первая блок десятый',
            'block_data.10.article_one_id': 'Статья первая блок одиннадцатый',
            'block_data.11.article_one_id': 'Статья первая блок двенадцатый',

            'block_data.0.article_two_id': 'Статья вторая блок первый',
            'block_data.1.article_two_id': 'Статья вторая блок второй',
            'block_data.2.article_two_id': 'Статья вторая блок третий',
            'block_data.3.article_two_id': 'Статья вторая блок четвертый',
            'block_data.4.article_two_id': 'Статья вторая блок пятый',
            'block_data.5.article_two_id': 'Статья вторая блок шестой',
            'block_data.6.article_two_id': 'Статья вторая блок седьмой',
            'block_data.7.article_two_id': 'Статья вторая блок восьмой',
            'block_data.8.article_two_id': 'Статья вторая блок девятый',
            'block_data.9.article_two_id': 'Статья вторая блок десятый',
            'block_data.10.article_two_id': 'Статья вторая блок одиннадцатый',
            'block_data.11.article_two_id': 'Статья вторая блок двенадцатый',

          }

            function nestedSortableJS() {
                (function(factory) {
                    "use strict";

                    var define = window.define;

                    if (typeof define === "function" && define.amd) {

                        // AMD. Register as an anonymous module.
                        define([
                            "jquery",
                            "jquery-ui/sortable"
                        ], factory);
                    } else {

                        // Browser globals
                        factory(window.jQuery);
                    }
                }(function($) {
                    "use strict";

                    function isOverAxis(x, reference, size) {
                        return (x > reference) && (x < (reference + size));
                    }

                    $.widget("mjs.nestedSortable", $.extend({}, $.ui.sortable.prototype, {

                        options: {
                            disableParentChange: false,
                            doNotClear: false,
                            expandOnHover: 700,
                            isAllowed: function() {
                                return true;
                            },
                            isTree: false,
                            listType: "ol",
                            maxLevels: 0,
                            protectRoot: false,
                            rootID: null,
                            rtl: false,
                            startCollapsed: false,
                            tabSize: 20,

                            branchClass: "mjs-nestedSortable-branch",
                            collapsedClass: "mjs-nestedSortable-collapsed",
                            disableNestingClass: "mjs-nestedSortable-no-nesting",
                            errorClass: "mjs-nestedSortable-error",
                            expandedClass: "mjs-nestedSortable-expanded",
                            hoveringClass: "mjs-nestedSortable-hovering",
                            leafClass: "mjs-nestedSortable-leaf",
                            disabledClass: "mjs-nestedSortable-disabled"
                        },

                        _create: function() {
                            var self = this,
                                err;

                            this.element.data("ui-sortable", this.element.data(
                                "mjs-nestedSortable"));

                            // mjs - prevent browser from freezing if the HTML is not correct
                            if (!this.element.is(this.options.listType)) {
                                err = "nestedSortable: " +
                                    "Please check that the listType option is set to your actual list type";

                                throw new Error(err);
                            }

                            // if we have a tree with expanding/collapsing functionality,
                            // force 'intersect' tolerance method
                            if (this.options.isTree && this.options.expandOnHover) {
                                this.options.tolerance = "intersect";
                            }

                            $.ui.sortable.prototype._create.apply(this, arguments);

                            // prepare the tree by applying the right classes
                            // (the CSS is responsible for actual hide/show functionality)
                            if (this.options.isTree) {
                                $(this.items).each(function() {
                                    var $li = this.item,
                                        hasCollapsedClass = $li.hasClass(self
                                            .options.collapsedClass),
                                        hasExpandedClass = $li.hasClass(self.options
                                            .expandedClass);

                                    if ($li.children(self.options.listType)
                                        .length) {
                                        $li.addClass(self.options.branchClass);
                                        // expand/collapse class only if they have children

                                        if (!hasCollapsedClass && !
                                            hasExpandedClass) {
                                            if (self.options.startCollapsed) {
                                                $li.addClass(self.options
                                                    .collapsedClass);
                                            } else {
                                                $li.addClass(self.options
                                                    .expandedClass);
                                            }
                                        }
                                    } else {
                                        $li.addClass(self.options.leafClass);
                                    }
                                });
                            }
                        },

                        _destroy: function() {
                            this.element
                                .removeData("mjs-nestedSortable")
                                .removeData("ui-sortable");
                            return $.ui.sortable.prototype._destroy.apply(this, arguments);
                        },

                        _mouseDrag: function(event) {
                            var i,
                                item,
                                itemElement,
                                intersection,
                                self = this,
                                o = this.options,
                                scrolled = false,
                                $document = $(document),
                                previousTopOffset,
                                parentItem,
                                level,
                                childLevels,
                                itemAfter,
                                itemBefore,
                                newList,
                                method,
                                a,
                                previousItem,
                                nextItem,
                                helperIsNotSibling;

                            //Compute the helpers position
                            this.position = this._generatePosition(event);
                            this.positionAbs = this._convertPositionTo("absolute");

                            if (!this.lastPositionAbs) {
                                this.lastPositionAbs = this.positionAbs;
                            }

                            //Do scrolling
                            if (this.options.scroll) {
                                if (this.scrollParent[0] !== document && this.scrollParent[
                                        0].tagName !== "HTML") {

                                    if (
                                        (
                                            this.overflowOffset.top +
                                            this.scrollParent[0].offsetHeight
                                        ) -
                                        event.pageY <
                                        o.scrollSensitivity
                                    ) {
                                        scrolled = this.scrollParent.scrollTop() + o
                                            .scrollSpeed;
                                        this.scrollParent.scrollTop(scrolled);
                                    } else if (
                                        event.pageY -
                                        this.overflowOffset.top <
                                        o.scrollSensitivity
                                    ) {
                                        scrolled = this.scrollParent.scrollTop() - o
                                            .scrollSpeed;
                                        this.scrollParent.scrollTop(scrolled);
                                    }

                                    if (
                                        (
                                            this.overflowOffset.left +
                                            this.scrollParent[0].offsetWidth
                                        ) -
                                        event.pageX <
                                        o.scrollSensitivity
                                    ) {
                                        scrolled = this.scrollParent.scrollLeft() + o
                                            .scrollSpeed;
                                        this.scrollParent.scrollLeft(scrolled);
                                    } else if (
                                        event.pageX -
                                        this.overflowOffset.left <
                                        o.scrollSensitivity
                                    ) {
                                        scrolled = this.scrollParent.scrollLeft() - o
                                            .scrollSpeed;
                                        this.scrollParent.scrollLeft(scrolled);
                                    }

                                } else {

                                    if (
                                        event.pageY -
                                        $document.scrollTop() <
                                        o.scrollSensitivity
                                    ) {
                                        scrolled = $document.scrollTop() - o.scrollSpeed;
                                        $document.scrollTop(scrolled);
                                    } else if (
                                        $(window).height() -
                                        (
                                            event.pageY -
                                            $document.scrollTop()
                                        ) <
                                        o.scrollSensitivity
                                    ) {
                                        scrolled = $document.scrollTop() + o.scrollSpeed;
                                        $document.scrollTop(scrolled);
                                    }

                                    if (
                                        event.pageX -
                                        $document.scrollLeft() <
                                        o.scrollSensitivity
                                    ) {
                                        scrolled = $document.scrollLeft() - o.scrollSpeed;
                                        $document.scrollLeft(scrolled);
                                    } else if (
                                        $(window).width() -
                                        (
                                            event.pageX -
                                            $document.scrollLeft()
                                        ) <
                                        o.scrollSensitivity
                                    ) {
                                        scrolled = $document.scrollLeft() + o.scrollSpeed;
                                        $document.scrollLeft(scrolled);
                                    }

                                }

                                if (scrolled !== false && $.ui.ddmanager && !o
                                    .dropBehaviour) {
                                    $.ui.ddmanager.prepareOffsets(this, event);
                                }
                            }

                            //Regenerate the absolute position used for position checks
                            this.positionAbs = this._convertPositionTo("absolute");

                            // mjs - find the top offset before rearrangement,
                            previousTopOffset = this.placeholder.offset().top;

                            //Set the helper position
                            if (!this.options.axis || this.options.axis !== "y") {
                                this.helper[0].style.left = this.position.left + "px";
                            }
                            if (!this.options.axis || this.options.axis !== "x") {
                                this.helper[0].style.top = (this.position.top) + "px";
                            }

                            // mjs - check and reset hovering state at each cycle
                            this.hovering = this.hovering ? this.hovering : null;
                            this.mouseentered = this.mouseentered ? this.mouseentered :
                                false;

                            // mjs - let's start caching some variables
                            (function() {
                                var _parentItem = this.placeholder.parent().parent();
                                if (_parentItem && _parentItem.closest(".ui-sortable")
                                    .length) {
                                    parentItem = _parentItem;
                                }
                            }.call(this));

                            level = this._getLevel(this.placeholder);
                            childLevels = this._getChildLevels(this.helper);
                            newList = document.createElement(o.listType);

                            // dragDirection object is required by jquery.ui.sortable.js 1.13+
                            this.dragDirection = {
                                vertical: this._getDragVerticalDirection(),
                                horizontal: this._getDragHorizontalDirection()
                            };

                            //Rearrange
                            for (i = this.items.length - 1; i >= 0; i--) {

                                //Cache variables and intersection, continue if no intersection
                                item = this.items[i];
                                itemElement = item.item[0];
                                intersection = this._intersectsWithPointer(item);
                                if (!intersection) {
                                    continue;
                                }

                                // Only put the placeholder inside the current Container, skip all
                                // items form other containers. This works because when moving
                                // an item from one container to another the
                                // currentContainer is switched before the placeholder is moved.
                                //
                                // Without this moving items in "sub-sortables" can cause the placeholder to jitter
                                // beetween the outer and inner container.
                                if (item.instance !== this.currentContainer) {
                                    continue;
                                }

                                // No action if intersected item is disabled
                                // and the element above or below in the direction we're going is also disabled
                                if (itemElement.className.indexOf(o.disabledClass) !== -1) {
                                    // Note: intersection hardcoded direction values from
                                    // jquery.ui.sortable.js:_intersectsWithPointer
                                    if (intersection === 2) {
                                        // Going down
                                        itemAfter = this.items[i + 1];
                                        if (itemAfter && itemAfter.item.hasClass(o
                                                .disabledClass)) {
                                            continue;
                                        }

                                    } else if (intersection === 1) {
                                        // Going up
                                        itemBefore = this.items[i - 1];
                                        if (itemBefore && itemBefore.item.hasClass(o
                                                .disabledClass)) {
                                            continue;
                                        }
                                    }
                                }

                                method = intersection === 1 ? "next" : "prev";

                                // cannot intersect with itself
                                // no useless actions that have been done before
                                // no action if the item moved is the parent of the item checked
                                if (itemElement !== this.currentItem[0] &&
                                    this.placeholder[method]()[0] !== itemElement &&
                                    !$.contains(this.placeholder[0], itemElement) &&
                                    (
                                        this.options.type === "semi-dynamic" ?
                                        !$.contains(this.element[0], itemElement) :
                                        true
                                    )
                                ) {

                                    // mjs - we are intersecting an element:
                                    // trigger the mouseenter event and store this state
                                    if (!this.mouseentered) {
                                        $(itemElement).mouseenter();
                                        this.mouseentered = true;
                                    }

                                    // mjs - if the element has children and they are hidden,
                                    // show them after a delay (CSS responsible)
                                    if (o.isTree && $(itemElement).hasClass(o
                                            .collapsedClass) && o.expandOnHover) {
                                        if (!this.hovering) {
                                            $(itemElement).addClass(o.hoveringClass);
                                            this.hovering = window.setTimeout(function() {
                                                $(itemElement)
                                                    .removeClass(o.collapsedClass)
                                                    .addClass(o.expandedClass);

                                                self.refreshPositions();
                                                self._trigger("expand", event, self
                                                    ._uiHash());
                                            }, o.expandOnHover);
                                        }
                                    }

                                    this.direction = intersection === 1 ? "down" : "up";

                                    // mjs - rearrange the elements and reset timeouts and hovering state
                                    if (this.options.tolerance === "pointer" || this
                                        ._intersectsWithSides(item)) {
                                        $(itemElement).mouseleave();
                                        this.mouseentered = false;
                                        $(itemElement).removeClass(o.hoveringClass);
                                        if (this.hovering) {
                                            window.clearTimeout(this.hovering);
                                        }
                                        this.hovering = null;

                                        // mjs - do not switch container if
                                        // it's a root item and 'protectRoot' is true
                                        // or if it's not a root item but we are trying to make it root
                                        if (o.protectRoot &&
                                            !(
                                                this.currentItem[0].parentNode === this
                                                .element[0] &&
                                                // it's a root item
                                                itemElement.parentNode !== this.element[0]
                                                // it's intersecting a non-root item
                                            )
                                        ) {
                                            if (this.currentItem[0].parentNode !== this
                                                .element[0] &&
                                                itemElement.parentNode === this.element[0]
                                            ) {

                                                if (!$(itemElement).children(o.listType)
                                                    .length) {
                                                    itemElement.appendChild(newList);
                                                    if (o.isTree) {
                                                        $(itemElement)
                                                            .removeClass(o.leafClass)
                                                            .addClass(o.branchClass + " " +
                                                                o.expandedClass);
                                                    }
                                                }

                                                if (this.direction === "down") {
                                                    a = $(itemElement).prev().children(o
                                                        .listType);
                                                } else {
                                                    a = $(itemElement).children(o.listType);
                                                }

                                                if (a[0] !== undefined) {
                                                    this._rearrange(event, null, a);
                                                }

                                            } else {
                                                this._rearrange(event, item);
                                            }
                                        } else if (!o.protectRoot) {
                                            this._rearrange(event, item);
                                        }
                                    } else {
                                        break;
                                    }

                                    // Clear emtpy ul's/ol's
                                    this._clearEmpty(itemElement);

                                    this._trigger("change", event, this._uiHash());
                                    break;
                                }
                            }

                            // mjs - to find the previous sibling in the list,
                            // keep backtracking until we hit a valid list item.
                            (function() {
                                var _previousItem = this.placeholder.prev();
                                if (_previousItem.length) {
                                    previousItem = _previousItem;
                                } else {
                                    previousItem = null;
                                }
                            }.call(this));

                            if (previousItem != null) {
                                while (
                                    previousItem[0].nodeName.toLowerCase() !== "li" ||
                                    previousItem[0].className.indexOf(o.disabledClass) !== -
                                    1 ||
                                    previousItem[0] === this.currentItem[0] ||
                                    previousItem[0] === this.helper[0]
                                ) {
                                    if (previousItem[0].previousSibling) {
                                        previousItem = $(previousItem[0].previousSibling);
                                    } else {
                                        previousItem = null;
                                        break;
                                    }
                                }
                            }

                            // mjs - to find the next sibling in the list,
                            // keep stepping forward until we hit a valid list item.
                            (function() {
                                var _nextItem = this.placeholder.next();
                                if (_nextItem.length) {
                                    nextItem = _nextItem;
                                } else {
                                    nextItem = null;
                                }
                            }.call(this));

                            if (nextItem != null) {
                                while (
                                    nextItem[0].nodeName.toLowerCase() !== "li" ||
                                    nextItem[0].className.indexOf(o.disabledClass) !== -1 ||
                                    nextItem[0] === this.currentItem[0] ||
                                    nextItem[0] === this.helper[0]
                                ) {
                                    if (nextItem[0].nextSibling) {
                                        nextItem = $(nextItem[0].nextSibling);
                                    } else {
                                        nextItem = null;
                                        break;
                                    }
                                }
                            }

                            this.beyondMaxLevels = 0;

                            // mjs - if the item is moved to the left, send it one level up
                            // but only if it's at the bottom of the list
                            if (parentItem != null &&
                                nextItem == null &&
                                !(o.protectRoot && parentItem[0].parentNode == this.element[
                                    0]) &&
                                (
                                    o.rtl &&
                                    (
                                        this.positionAbs.left +
                                        this.helper.outerWidth() > parentItem.offset()
                                        .left +
                                        parentItem.outerWidth()
                                    ) ||
                                    !o.rtl && (this.positionAbs.left < parentItem.offset()
                                        .left)
                                )
                            ) {

                                parentItem.after(this.placeholder[0]);
                                helperIsNotSibling = !parentItem
                                    .children(o.listItem)
                                    .children("li:visible:not(.ui-sortable-helper)")
                                    .length;
                                if (o.isTree && helperIsNotSibling) {
                                    parentItem
                                        .removeClass(this.options.branchClass + " " + this
                                            .options.expandedClass)
                                        .addClass(this.options.leafClass);
                                }
                                if (typeof parentItem !== 'undefined')
                                    this._clearEmpty(parentItem[0]);
                                this._trigger("change", event, this._uiHash());
                                // mjs - if the item is below a sibling and is moved to the right,
                                // make it a child of that sibling
                            } else if (previousItem != null &&
                                !previousItem.hasClass(o.disableNestingClass) &&
                                (
                                    previousItem.children(o.listType).length &&
                                    previousItem.children(o.listType).is(":visible") ||
                                    !previousItem.children(o.listType).length
                                ) &&
                                !(o.protectRoot && this.currentItem[0].parentNode === this
                                    .element[0]) &&
                                (
                                    o.rtl &&
                                    (
                                        this.positionAbs.left +
                                        this.helper.outerWidth() <
                                        previousItem.offset().left +
                                        previousItem.outerWidth() -
                                        o.tabSize
                                    ) ||
                                    !o.rtl &&
                                    (this.positionAbs.left > previousItem.offset().left + o
                                        .tabSize)
                                )
                            ) {

                                this._isAllowed(previousItem, level, level + childLevels +
                                    1);

                                if (!previousItem.children(o.listType).length) {
                                    previousItem[0].appendChild(newList);
                                    if (o.isTree) {
                                        previousItem
                                            .removeClass(o.leafClass)
                                            .addClass(o.branchClass + " " + o
                                                .expandedClass);
                                    }
                                }

                                // mjs - if this item is being moved from the top, add it to the top of the list.
                                if (previousTopOffset && (previousTopOffset <= previousItem
                                        .offset().top)) {
                                    previousItem.children(o.listType).prepend(this
                                        .placeholder);
                                } else {
                                    // mjs - otherwise, add it to the bottom of the list.
                                    previousItem.children(o.listType)[0].appendChild(this
                                        .placeholder[0]);
                                }
                                if (typeof parentItem !== 'undefined')
                                    this._clearEmpty(parentItem[0]);
                                this._trigger("change", event, this._uiHash());
                            } else {
                                this._isAllowed(parentItem, level, level + childLevels);
                            }

                            //Post events to containers
                            this._contactContainers(event);

                            //Interconnect with droppables
                            if ($.ui.ddmanager) {
                                $.ui.ddmanager.drag(this, event);
                            }

                            //Call callbacks
                            this._trigger("sort", event, this._uiHash());

                            this.lastPositionAbs = this.positionAbs;
                            return false;

                        },

                        _mouseStop: function(event) {
                            // mjs - if the item is in a position not allowed, send it back
                            if (this.beyondMaxLevels) {

                                this.placeholder.removeClass(this.options.errorClass);

                                if (this.domPosition.prev) {
                                    $(this.domPosition.prev).after(this.placeholder);
                                } else {
                                    $(this.domPosition.parent).prepend(this.placeholder);
                                }

                                this._trigger("revert", event, this._uiHash());

                            }

                            // mjs - clear the hovering timeout, just to be sure
                            $("." + this.options.hoveringClass)
                                .mouseleave()
                                .removeClass(this.options.hoveringClass);

                            this.mouseentered = false;
                            if (this.hovering) {
                                window.clearTimeout(this.hovering);
                            }
                            this.hovering = null;

                            this._relocate_event = event;
                            this._pid_current = $(this.domPosition.parent).parent().attr(
                                "id");
                            this._sort_current = this.domPosition.prev ? $(this.domPosition
                                .prev).next().index() : 0;
                            $.ui.sortable.prototype._mouseStop.apply(this,
                                arguments
                                ); //asybnchronous execution, @see _clear for the relocate event.
                        },

                        // mjs - this function is slightly modified
                        // to make it easier to hover over a collapsed element and have it expand
                        _intersectsWithSides: function(item) {

                            var half = this.options.isTree ? .8 : .5,
                                isOverBottomHalf = isOverAxis(
                                    this.positionAbs.top + this.offset.click.top,
                                    item.top + (item.height * half),
                                    item.height
                                ),
                                isOverTopHalf = isOverAxis(
                                    this.positionAbs.top + this.offset.click.top,
                                    item.top - (item.height * half),
                                    item.height
                                ),
                                isOverRightHalf = isOverAxis(
                                    this.positionAbs.left + this.offset.click.left,
                                    item.left + (item.width / 2),
                                    item.width
                                ),
                                verticalDirection = this._getDragVerticalDirection(),
                                horizontalDirection = this._getDragHorizontalDirection();

                            if (this.floating && horizontalDirection) {
                                return (
                                    (horizontalDirection === "right" &&
                                        isOverRightHalf) ||
                                    (horizontalDirection === "left" && !isOverRightHalf)
                                );
                            } else {
                                return verticalDirection && (
                                    (verticalDirection === "down" &&
                                        isOverBottomHalf) ||
                                    (verticalDirection === "up" && isOverTopHalf)
                                );
                            }

                        },

                        _contactContainers: function() {

                            if (this.options.protectRoot && this.currentItem[0]
                                .parentNode === this.element[0]) {
                                return;
                            }

                            $.ui.sortable.prototype._contactContainers.apply(this,
                                arguments);

                        },

                        _clear: function() {
                            var i,
                                item;

                            $.ui.sortable.prototype._clear.apply(this, arguments);

                            //relocate event
                            if (!(this._pid_current === this._uiHash().item.parent()
                                    .parent().attr("id") &&
                                    this._sort_current === this._uiHash().item.index())) {
                                this._trigger("relocate", this._relocate_event, this
                                    ._uiHash());
                            }

                            // mjs - clean last empty ul/ol
                            for (i = this.items.length - 1; i >= 0; i--) {
                                item = this.items[i].item[0];
                                this._clearEmpty(item);
                            }

                        },

                        serialize: function(options) {

                            var o = $.extend({}, this.options, options),
                                items = this._getItemsAsjQuery(o && o.connected),
                                str = [];

                            $(items).each(function() {
                                var res = ($(o.item || this).attr(o.attribute ||
                                        "id") || "")
                                    .match(o.expression || (/(.+)[-=_](.+)/)),
                                    pid = ($(o.item || this).parent(o.listType)
                                        .parent(o.items)
                                        .attr(o.attribute || "id") || "")
                                    .match(o.expression || (/(.+)[-=_](.+)/));

                                if (res) {
                                    str.push(
                                        (
                                            (o.key || res[1]) +
                                            "[" +
                                            (o.key && o.expression ? res[1] :
                                                res[2]) + "]"
                                        ) +
                                        "=" +
                                        (pid ? (o.key && o.expression ? pid[1] :
                                            pid[2]) : o.rootID));
                                }
                            });

                            if (!str.length && o.key) {
                                str.push(o.key + "=");
                            }

                            return str.join("&");

                        },

                        toHierarchy: function(options) {

                            var o = $.extend({}, this.options, options),
                                ret = [];

                            $(this.element).children(o.items).each(function() {
                                var level = _recursiveItems(this);
                                ret.push(level);
                            });

                            return ret;

                            function _recursiveItems(item) {
                                var id = ($(item).attr(o.attribute || "id") || "").match(o
                                        .expression || (/(.+)[-=_](.+)/)),
                                    currentItem;
                                if (id) {
                                    currentItem = {
                                        "id": id[2]
                                    };

                                    if ($(item).children(o.listType).children(o.items)
                                        .length > 0) {
                                        currentItem.children = [];
                                        $(item).children(o.listType).children(o.items).each(
                                            function() {
                                                var level = _recursiveItems(this);
                                                currentItem.children.push(level);
                                            });
                                    }
                                    return currentItem;
                                }
                            }
                        },

                        toArray: function(options) {

                            var o = $.extend({}, this.options, options),
                                sDepth = o.startDepthCount || 0,
                                ret = [],
                                left = 1;

                            if (!o.excludeRoot) {
                                ret.push({
                                    "item_id": o.rootID,
                                    "parent_id": null,
                                    "depth": sDepth,
                                    "left": left,
                                    "right": ($(o.items, this.element).length + 1) *
                                        2
                                });
                                left++;
                            }

                            $(this.element).children(o.items).each(function() {
                                left = _recursiveArray(this, sDepth + 1, left);
                            });

                            ret = ret.sort(function(a, b) {
                                return (a.left - b.left);
                            });

                            return ret;

                            function _recursiveArray(item, depth, _left) {

                                var right = _left + 1,
                                    id,
                                    pid,
                                    parentItem;

                                if ($(item).children(o.listType).children(o.items).length >
                                    0) {
                                    depth++;
                                    $(item).children(o.listType).children(o.items).each(
                                        function() {
                                            right = _recursiveArray($(this), depth,
                                                right);
                                        });
                                    depth--;
                                }

                                id = ($(item).attr(o.attribute || "id")).match(o
                                    .expression || (/(.+)[-=_](.+)/));

                                if (depth === sDepth + 1) {
                                    pid = o.rootID;
                                } else {
                                    parentItem = ($(item).parent(o.listType)
                                            .parent(o.items)
                                            .attr(o.attribute || "id"))
                                        .match(o.expression || (/(.+)[-=_](.+)/));
                                    pid = parentItem[2];
                                }

                                if (id) {
                                    ret.push({
                                        "item_id": id[2],
                                        "parent_id": pid,
                                        "depth": depth,
                                        "left": _left,
                                        "right": right
                                    });
                                }

                                _left = right + 1;
                                return _left;
                            }

                        },

                        _clearEmpty: function(item) {
                            function replaceClass(elem, search, replace, swap) {
                                if (swap) {
                                    search = [replace, replace = search][0];
                                }

                                $(elem).removeClass(search).addClass(replace);
                            }

                            var o = this.options,
                                childrenList = $(item).children(o.listType),
                                hasChildren = childrenList.is(':not(:empty)');

                            var doNotClear =
                                o.doNotClear ||
                                hasChildren ||
                                o.protectRoot && $(item)[0] === this.element[0];

                            if (o.isTree) {
                                replaceClass(item, o.branchClass, o.leafClass, doNotClear);
                            }

                            if (!doNotClear) {
                                childrenList.remove();
                            }
                        },

                        _getLevel: function(item) {

                            var level = 1,
                                list;

                            if (this.options.listType) {
                                list = item.closest(this.options.listType);
                                while (list && list.length > 0 && !list.is(
                                        ".ui-sortable")) {
                                    level++;
                                    list = list.parent().closest(this.options.listType);
                                }
                            }

                            return level;
                        },

                        _getChildLevels: function(parent, depth) {
                            var self = this,
                                o = this.options,
                                result = 0;
                            depth = depth || 0;

                            $(parent).children(o.listType).children(o.items).each(function(
                                index, child) {
                                result = Math.max(self._getChildLevels(child,
                                    depth + 1), result);
                            });

                            return depth ? result + 1 : result;
                        },

                        _isAllowed: function(parentItem, level, levels) {
                            var o = this.options,
                                // this takes into account the maxLevels set to the recipient list
                                maxLevels = this
                                .placeholder
                                .closest(".ui-sortable")
                                .nestedSortable("option", "maxLevels"),

                                // Check if the parent has changed to prevent it, when o.disableParentChange is true
                                oldParent = this.currentItem.parent().parent(),
                                disabledByParentchange = o.disableParentChange && (
                                    //From somewhere to somewhere else, except the root
                                    typeof parentItem !== 'undefined' && !oldParent.is(
                                        parentItem) ||
                                    typeof parentItem === 'undefined' && oldParent.is(
                                        "li") //From somewhere to the root
                                );
                            // mjs - is the root protected?
                            // mjs - are we nesting too deep?
                            if (
                                disabledByParentchange ||
                                !o.isAllowed(this.placeholder, parentItem, this.currentItem)
                            ) {
                                this.placeholder.addClass(o.errorClass);
                                if (maxLevels < levels && maxLevels !== 0) {
                                    this.beyondMaxLevels = levels - maxLevels;
                                } else {
                                    this.beyondMaxLevels = 1;
                                }
                            } else {
                                if (maxLevels < levels && maxLevels !== 0) {
                                    this.placeholder.addClass(o.errorClass);
                                    this.beyondMaxLevels = levels - maxLevels;
                                } else {
                                    this.placeholder.removeClass(o.errorClass);
                                    this.beyondMaxLevels = 0;
                                }
                            }
                        }

                    }));

                    $.mjs.nestedSortable.prototype.options = $.extend({},
                        $.ui.sortable.prototype.options,
                        $.mjs.nestedSortable.prototype.options
                    );
                }));
            }

            function reorderInitialize(url) {
                nestedSortableJS()
                var isRtl = Boolean("");
                if (isRtl) {
                    $(" <style> .ui-sortable ol {margin: 0;padding: 0;padding-right: 30px;}ol.sortable, ol.sortable ol {margin: 0 25px 0 0;padding: 0;list-style-type: none;}.ui-sortable dd {margin: 0;padding: 0 1.5em 0 0;}</style>")
                        .appendTo("head")
                }
                // initialize the nested sortable plugin
                $('.sortable').nestedSortable({
                    forcePlaceholderSize: true,
                    handle: 'div',
                    helper: 'clone',
                    items: 'li',
                    opacity: .6,
                    placeholder: 'placeholder',
                    revert: 250,
                    tabSize: 25,
                    rtl: isRtl,
                    tolerance: 'pointer',
                    toleranceElement: '> div',
                    maxLevels: 100,
                    isTree: true,
                    expandOnHover: 700,
                    startCollapsed: false
                });

                $('.disclose').on('click', function() {
                    $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass(
                        'mjs-nestedSortable-expanded');
                });

                $('#toArray').click(function(e) {
                    // get the current tree order
                    arraied = $('ol.sortable').nestedSortable('toArray', {
                        startDepthCount: 0,
                        expression: /(.+)_(.+)/
                    });

                    // log it
                    //console.log(arraied);

                    // send it with POST
                    $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                tree: JSON.stringify(arraied)
                            },
                        })
                        .done(function() {
                            new Noty({
                                type: "success",
                                text: "<strong>Готово</strong><br>Порядок был сохранен."
                            }).show();
                        })
                        .fail(function() {
                            new Noty({
                                type: "error",
                                text: "<strong>Ошибка</strong><br>Порядок не был сохранен."
                            }).show();
                        })
                        .always(function() {
                            window.location.reload()
                        });

                });

                $.ajaxPrefilter(function(options, originalOptions, xhr) {
                    var token = $('meta[name="csrf_token"]').attr('content');

                    if (token) {
                        return xhr.setRequestHeader('X-XSRF-TOKEN', token);
                    }
                });

            }

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

                    $($form).trigger('form-pre-serialize');
                    //this is needed otherwise fields like ckeditor don't post their value.
                    const submitEvent = new Event("submit");
                    $($form)[0].dispatchEvent(submitEvent);

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

                        // article edit not in modal
                        if (data.page === 'article') {
                            window.location.href = $clickedElement.attr('href')
                            return
                        }

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
                                // fix cjeditor error - to make 404
                                result = result.replace('cdn.ckeditor.com',
                                    'cdn.ckeditor.com/test/test')
                                result = result.replace('onsubmit="return false"', '')
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
                                // fix cjeditor error - to make 404
                                result = result.replace('cdn.ckeditor.com',
                                    'cdn.ckeditor.com/test/test')
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
                    } else if ($clickedElement.attr('href')?.includes('/' + 'show')) {
                        // if SHOW
                        const match = $clickedElement.attr('href')?.match(/\/(\d+)\//);
                        entityId = match[1];
                        let $inlineShowRoute = $clickedElement.attr('href');

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
                                    page: null,
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
                    } else if ($clickedElement.attr('href')?.includes('/' + 'reorder')) {
                        // if REORDER
                        let $inlineShowRoute = $clickedElement.attr('href');

                        $.ajax({
                            url: $inlineShowRoute,
                            data: [],
                            type: 'GET',
                            success: function(result) {
                                const modal = emptyModal($(result).find(
                                        '.main.pt-2 .row.mt-4 .col-md-8.col-md-offset-2'
                                    )[0]
                                    .outerHTML)
                                $('body').append(modal);
                                //$('body').append($(result).find('table'));
                                triggerModal({
                                    method: 'show',
                                    page: null,
                                    entityId: null,
                                    modalId: '#inline-show-dialog',
                                    openButtonId: 'a',
                                    dataTableId: "#crudTable"
                                });
                                reorderInitialize($inlineShowRoute)
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

        .modal-dialog .modal-body .col-md-8.col-md-offset-2 {
            width: 100%;
            max-width: 100%;
        }

        .select2-search__field {
            width: auto !important;
        }

        .select2-search.select2-search--dropdown .select2-search__field {
            width: 100% !important;
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

    <style>
        .ui-sortable .placeholder {
            outline: 1px dashed #4183C4;
            /*-webkit-border-radius: 3px;
                    -moz-border-radius: 3px;
                    border-radius: 3px;
                    margin: -1px;*/
        }

        .ui-sortable .mjs-nestedSortable-error {
            background: #fbe3e4;
            border-color: transparent;
        }

        .ui-sortable ol {
            margin: 0;
            padding: 0;
            padding-left: 30px;
        }

        ol.sortable,
        ol.sortable ol {
            margin: 0 0 0 25px;
            padding: 0;
            list-style-type: none;
        }

        ol.sortable {
            margin: 2em 0;
        }

        .sortable li {
            margin: 5px 0 0 0;
            padding: 0;
        }

        .sortable li div {
            border: 1px solid #ddd;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            padding: 6px;
            margin: 0;
            cursor: move;
            background-color: #f4f4f4;
            color: #444;
            border-color: #00acd6;
        }

        .sortable li.mjs-nestedSortable-branch div {
            /*background-color: #00c0ef;*/
            /*border-color: #00acd6;*/
        }

        .sortable li.mjs-nestedSortable-leaf div {
            /*background-color: #00c0ef;*/
            border: 1px solid #ddd;
        }

        li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
            border-color: #999;
            background: #fafafa;
        }

        .ui-sortable .disclose {
            cursor: pointer;
            width: 10px;
            display: none;
        }

        .sortable li.mjs-nestedSortable-collapsed>ol {
            display: none;
        }

        .sortable li.mjs-nestedSortable-branch>div .disclose {
            display: inline-block;
        }

        .sortable li.mjs-nestedSortable-collapsed>div .disclose>span:before {
            content: '+ ';
        }

        .sortable li.mjs-nestedSortable-expanded>div .disclose>span:before {
            content: '- ';
        }

        .ui-sortable h1 {
            font-size: 2em;
            margin-bottom: 0;
        }

        .ui-sortable h2 {
            font-size: 1.2em;
            font-weight: normal;
            font-style: italic;
            margin-top: .2em;
            margin-bottom: 1.5em;
        }

        .ui-sortable h3 {
            font-size: 1em;
            margin: 1em 0 .3em;
            ;
        }

        .ui-sortable p,
        .ui-sortable ol,
        .ui-sortable ul,
        .ui-sortable pre,
        .ui-sortable form {
            margin-top: 0;
            margin-bottom: 1em;
        }

        .ui-sortable dl {
            margin: 0;
        }

        .ui-sortable dd {
            margin: 0;
            padding: 0 0 0 1.5em;
        }

        .ui-sortable code {
            background: #e5e5e5;
        }

        .ui-sortable input {
            vertical-align: text-bottom;
        }

        .ui-sortable .notice {
            color: #c33;
        }

        .ui-sortable-handle {
            display: flex;
            justify-content: space-between;
        }

        .sub_categories_number {
            padding-right: 10px;
        }
    </style>
@endpush
