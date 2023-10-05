// MyTh Js
(function () {
    'use strict';
    const FormInit = function (form) {
        let self = this;

        this.form = form;
        // console.log(form);
        this.form.classList.add('render');
        this.FormHasValidation = this.form.classList.contains('form-validation');

        if (
            this.FormHasValidation
            && !this.form.getAttribute('novalidate')
        ) {
            this.form.setAttribute('novalidate', 'novalidate');
        }

        this.form.addEventListener('submit', function (e) {
            self.FormValidation(e);
            self.FormAjax(e);
        }, true);
        // this.submit = $(this.form).find(':submit');
        return this;
    };
    FormInit.prototype = {
        ajaxIgnore: [
            "message",
            "messages",
            "error",
            "errors",
            "url",
            "time",
            "data",
        ],
        event: {},
        form: {},
        submit: $(),
        overlay: $(),
        FormHasValidation: false,
        FormCheckValidity: false,
        FormAjaxHeaders: false,
        reloadDataTable() {
            return !this.form.hasAttribute('data-reload-table');
        },
        ajaxRedirect: function (response) {
            if (!response)
                return;

            const time = "time" in response
                ? (parseFloat(response.time) > 0 ? parseFloat(response.time) * 1000 : 0)
                : 1000;

            if ("force_url" in response) {
                setTimeout(() => {
                    window.location = response.force_url;
                }, time);
            }
            else {
                if ("LaravelDataTables" in window) {
                    try {
                        if (this.reloadDataTable()) {
                            window.LaravelDataTables.dataTableBuilder.draw();
                        }
                    }
                    catch (e) {
                        window.location.reload();
                    }
                }
                else if ("url" in response && response.url) {
                    setTimeout(() => {
                        window.location = response.url;
                    }, time);
                }
                else if (('reload' in response && response.reload) || ('time' in response && response.time)) {
                    setTimeout(() => {
                        window.location.reload();
                    }, time);
                }
            }
        },
        appendString: function (mixed, string) {
            let temp = ("" + string) || "";

            // console.log(mixed);
            try {
                if (Array.isArray(mixed) || typeof mixed === "object") {
                    mixed = Object.values(mixed);
                    mixed.forEach((item) => {
                        if (Array.isArray(item) || typeof item === "object") {
                            temp = this.appendString(item, temp);
                        }
                        else {
                            temp += (temp.length ? '<br>' : '') + "" + item;
                        }
                    });
                }
                else {
                    temp += (temp.length ? '<br>' : '') + "" + mixed;
                }
            }
            catch (e) {
                console.log("MyTh.js Error appendString: ", e);
            }
            return temp;
        },
        parseResponse: function (response = {}) {
            let msg = "";

            if ("message" in response) {
                msg = this.appendString(response.message, msg);
            }

            if ("messages" in response) {
                msg = this.appendString(response.messages, msg);
            }
            if ("error" in response) {
                msg = this.appendString(response.error, msg);
            }
            if ("errors" in response) {
                msg = this.appendString(response.errors, msg);
            }
            return msg;
        },
        FormCanAjax: function () {
            return this.form.classList.contains('form-ajax') &&
                !this.submit.is(':disabled')
                && (
                    !this.FormHasValidation || (this.FormHasValidation && this.FormCheckValidity)
                );
        },
        haveConfirm() {
            const {form} = this || null;
            return form ? (form.hasAttribute('data-ajax-confirm') ? (form.getAttribute('data-ajax-confirm') ? form.getAttribute('data-ajax-confirm') : true) : false) : false;
        },
        FormValidation: function (event) {
            let e = event;
            try {
                this.FormHasValidation = this.form.classList.contains('form-validation');
                if (this.FormHasValidation) {
                    this.form.classList.add('needs-validation');
                    this.form.setAttribute('novalidate', 'novalidate');
                    this.FormCheckValidity = this.form.checkValidity();

                    if (this.FormCheckValidity === false) {
                        e.preventDefault();
                        e.stopPropagation();

                        let __elm = $(":invalid:not(form)").first();
                        // console.log(__elm);
                        if (__elm.length > 0) {
                            try {
                                let toTop = __elm.offset().top - ($("nav:first").outerHeight() + 50);
                                window.scrollTo({
                                    top: toTop,
                                    behavior: 'smooth'
                                });
                            }
                            catch (e) {
                            }
                        }
                    }
                    // else{
                    let
                        invalidCheckboxComponent = $(".checkboxComponent :invalid").parents('.checkboxComponent'),
                        validCheckboxComponent = $(".checkboxComponent :valid").parents('.checkboxComponent');

                    invalidCheckboxComponent.addClass('invalid');
                    invalidCheckboxComponent.removeClass('valid');
                    validCheckboxComponent.removeClass('invalid');
                    validCheckboxComponent.addClass('valid');
                    // }

                    this.form.classList.add('was-validated');
                }
            }
            catch (e) {
                console.log(e);
            }
        },
        FormElementsIni: function () {
            // Btn Submit
            let
                selfSubmitSelector = this.form.getAttribute('data-submit-selector') || null,
                submit,
                overlay;

            if (selfSubmitSelector) {
                submit = $(selfSubmitSelector);
            }
            else {
                submit = $(this.form).find('button[type="submit"],.submit,.form-submit').first();
            }

            if (submit.length) {
                // Overlay Button
                overlay = submit.find('.overlay');

                if (!overlay.length) {
                    overlay = $("<div class=\"overlay\">\n" +
                        "                                            <i class=\"fa fa-refresh fa-spin\"></i>\n" +
                        "                                        </div>");
                    overlay.appendTo(submit);
                }
            }
            this.submit = submit;
            this.overlay = overlay;
        },
        FormClearElementsClass: function () {
            this.submit.prop('disabled', false);
            this.submit.removeClass("loading");
            this.submit.removeClass("disabled");
            this.overlay.remove();
        },
        FormAjaxStart: function () {
            if (!this.FormAjaxHeaders) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        Language: $('html').attr('lang')
                    }
                });

                this.FormAjaxHeaders = true;
            }

            this.submit.prop('disabled', true);
            // this.submit.addClass("loading");
            this.submit.addClass("disabled");
            NNBV.overlay(null, true);
        },
        FormAjaxEnd: function () {
            this.FormClearElementsClass();
            NNBV.overlay(null, false);
            let __elm = $(":invalid:not(form)").first();
            // console.log(__elm);
            if (__elm.length > 0) {
                try {
                    window.scrollTo({
                        top: __elm.offset().top - 10,
                        behavior: 'smooth'
                    });
                }
                catch (e) {
                }
            }
        },
        FormAjaxNotify: function (res, type) {
            const _self = this;
            // return;
            type = type || 'success';
            const response = res;
            // let color = type === 'success' ? '#28a745' : (type === "danger" || type === "error" ? "#cd5e5e" : "");
            let redirect = true;
            let msg = this.parseResponse(response);


            if (msg.length) {
                if (typeof swal !== "undefined") {
                    let iconSwal = type === "danger" ? "error" : type;
                    swal({
                        title: "",
                        text: msg.replace(/<br>/g, '\n'),
                        icon: iconSwal,
                        buttons: {
                            // cancel: window.languages.cancel,
                            yes: {
                                text: window.languages.done,
                                value: "yes",
                                closeModal: false
                            },
                        },
                    })
                        .then((value) => {
                            if (value === 'yes') {
                                redirect = false;
                                this.ajaxRedirect(res);
                            }
                            throw null;
                        })
                        .catch(err => {
                            if (err) {
                                swal(window.languages.oh_noes, window.languages.there_is_an_error, "error")
                                    .finally(() => {
                                        _self.reloadDataTable() && $(".dt-button.buttons-reload").click()
                                    });
                            }
                            else {
                                swal.close();
                            }
                        })
                        .finally(() => swal.stopLoading());
                }
            }
            // return;
            if (redirect) {
                this.ajaxRedirect(res);
            }

            if (response && typeof response === "object") {

                let html, iframe, window_open;
                if ((html = response.html) && html.id) {
                    $("#" + html.id).html(html.value || '');
                }
                if ((iframe = response.iframe) && iframe.id) {
                    $("#" + iframe.id).attr("src", iframe.value || '');
                }
                // window.open.call(window,a)
                // console.log(response.window_open);
                if ((window_open = response.window_open) && window_open) {
                    if (typeof window_open === 'string') {
                        window.open(window_open);
                    }
                    else {
                        window.open.apply(window, window_open);
                    }
                }
            }

        },
        getCallbacks() {
            return {
                before: this.form.getAttribute('data-ajax-before') || null,
                done: this.form.getAttribute('data-ajax-done') || null,
                fail: this.form.getAttribute('data-ajax-fail') || null,
                always: this.form.getAttribute('data-ajax-always') || null,
            };
        },
        FormSubmitAjax: function (event, formData) {
            let self = this,
                form = this.form,
                method = form.getAttribute('method') || 'get',
                url = form.getAttribute('action') || '',
                callback = this.getCallbacks(),
                data = formData;

            method = method.toString().toLowerCase();

            // return;
            $.ajax({
                url,
                method,
                data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    self.FormAjaxStart();
                },
                error: function (data) {
                    // console.log(data,callback);
                    try {
                        self.FormAjaxNotify(data.responseJSON, 'danger');

                        if (typeof eval(callback.fail) === 'function') {
                            let _args = NNBV.merge(self.form, arguments);
                            eval(callback.fail).apply(null, _args);
                        }
                    }
                    catch (e) {
                        // console.log(e);
                    }

                },
                success: function (response) {
                    try {
                        self.FormAjaxNotify(response, 'success');
                        if (typeof eval(callback.done) === 'function') {
                            let _args = NNBV.merge(self.form, arguments);

                            eval(callback.done).apply(null, _args);
                            //eval( callback.done ).apply(null, arguments);
                        }
                    }
                    catch (e) {
                        console.log(e);
                    }
                },
                complete: function () {
                    try {
                        self.FormAjaxEnd();

                        if (typeof eval(callback.always) === 'function') {
                            let _args = NNBV.merge(self.form, arguments);
                            eval(callback.always).apply(null, _args);
                        }
                    }
                    catch (e) {
                        console.log(e);
                    }
                }
            });
            return;
            let
                ajax = $[method](url, data)
                    .done(function (response) {
                        try {
                            self.FormAjaxNotify(response, 'success');
                            if (typeof eval(callback.done) === 'function') {
                                let _arg = arguments;

                                eval(callback.done).apply(null, arg,);
                            }
                        }
                        catch (e) {
                            console.log(e);
                        }
                    })
                    .fail(function (data) {
                        // console.log(data);
                        try {
                            self.FormAjaxNotify(data.responseJSON, 'danger');
                            if (typeof eval(callback.fail) === 'function')
                                eval(callback.fail).apply(null, arguments);
                        }
                        catch (e) {
                            console.log(e);
                        }
                    })
                    .always(function () {
                        try {
                            // console.log("always",arguments);
                            self.FormAjaxEnd();
                            if (typeof eval(callback.always) === 'function')
                                eval(callback.always).apply(null, arguments);
                        }
                        catch (e) {
                            console.log(e);
                        }
                    });
        },
        FormAjax: function (event) {
            if (!this.FormCanAjax())
                return;
            event.preventDefault();

            let data = new FormData(this.form);

            if (typeof eval(this.getCallbacks().before) === 'function') {
                if (eval(this.getCallbacks().before).apply(this, [this, this.form, data]) === false) {
                    return;
                }
            }
            if (this.haveConfirm()) {
                NNBV.confirm(() => this.FormSubmitAjax(event, data));
            }
            else {
                this.FormSubmitAjax(event, data);
            }
        },
    };

    const NNBV = function () {
    };
    NNBV.overlay = function (elm, set) {
        let v;
        set = set || false;
        // elm = elm ? $(elm): $(document.body);
        if (!elm) {
            if ((v = $("#main-body")) && v.length)
                elm = v;
            else
                elm = $(document.body);
        }
        else
            elm = $(elm);
        // console.log(elm,arguments);
        let overlayId = "myth-overlay-elm";
        let overlayWrapper = "overlay-wrapper";

        if (set) {
            elm.addClass(overlayWrapper);
            elm.addClass('position-relative');
            $('<div style="position: fixed;z-index: 9988552;" class="overlay" id="' + overlayId + '"><i class="fas fa-3x fa-sync fa-spin"></i></div>').appendTo(elm);
        }
        else {
            elm.removeClass(overlayWrapper);
            elm.removeClass('position-relative');
            $("#" + overlayId).remove();
        }
        return elm;
    };
    NNBV.merge = function (newMerg, args) {
        let _m = [];
        for (let i = 0; i < args.length; i++) {
            _m[i + 1] = args[i];
        }
        _m[0] = newMerg;
        return _m;
    };
    NNBV.swalConfirm = function (callback = null, title = window.languages.are_you_sure, text = '', icon = 'warning') {
        // console.log(icon);
        if (typeof swal !== 'undefined')
            return swal({
                title,
                text,
                icon,
                buttons: {
                    cancel: window.languages.cancel,
                    yes: {
                        text: window.languages.yes,
                        value: "yes",
                        closeModal: false
                    },
                },
            })
                .then((value) => {
                    if (value === 'yes' && callback) {
                        let f;
                        if ((f = callback())) {
                            // console.log(f);
                            if (f && !f.__proto__)
                                return f;
                        }
                    }
                    // throw value === 'yes' && callback ? callback() : null;
                    throw  null;
                })
                .catch(err => {
                    if (err) {
                        swal(window.languages.oh_noes, window.languages.there_is_an_error, "error")
                    }
                    else {
                        swal.close();
                    }
                })
        // .finally(() => swal.stopLoading());
        else if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                icon: icon,
                // iconHtml: '؟',
                confirmButtonText: window.languages.yes,
                cancelButtonText: window.languages.cancel,
                showCancelButton: true
            })
                .then((value) => {
                    if (value.value) {
                        callback ? callback() : '';
                    }
                    throw null;
                })
                .catch(err => {
                    if (err) {
                        Swal.fire(
                            window.languages.oh_noes,
                            window.languages.there_is_an_error,
                            "error"
                        );
                    }
                    else {
                        // swal.stopLoading();
                        Swal.hideLoading();
                        Swal.close();
                    }
                });
        }
    };
    NNBV.confirm = NNBV.swalConfirm;
    NNBV.alert = function (text = '', icon = '') {
        if (typeof swal !== 'undefined')
            return swal({
                text,
                icon,
                buttons: {
                    yes: {text: window.languages.done}
                },
            });
        else if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                icon: icon,
                confirmButtonText: window.languages.done,
                showCancelButton: false
            });
        }
    };

    function readImageURL(input) {
        if (input.files && input.files[0]) {
            var
                reader = new FileReader();
            var preview = $(input).parents('.avatar-upload').find('.image-preview');
            reader.onload = function (e) {
                preview.css('background-image', 'url(' + e.target.result + ')');
                preview.hide();
                preview.fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function formIniDoc() {
        let forms = document.getElementsByTagName('form');
        Array.prototype.filter.call(forms, function (form) {
            new FormInit(form);
        });
    }

    window.addEventListener('load', function () {
        formIniDoc();
        $("input[type='file'].image-upload").change(function () {
            readImageURL(this);
        });
    }, false);
    NNBV.formIni = formIniDoc;
    window.MyTh = NNBV;
    window.FormInit = FormInit;
})();
