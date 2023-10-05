<script>
    window._token = '{!! csrf_token() !!}';
    window.locale = '{{ $locale }}';
    window.align = '{{$align}}';
    window.align2 = '{{$align2}}';
    window.direction = '{{$direction}}';
    window.direction2 = '{{$direction2}}';
    window.languages = @json(__('global')) ;
    window.languages.success = "@lang('messages.success')";
    window.languages.fail = "@lang('messages.fail')";
</script>

@push('scripts')
    <script>
        function statusElmVal(id, value, max) {
            value = parseInt(value);
            max = parseInt(max) || 0;
            let val = value ? (value === 1 ? max : (value === 4 ? 0 : (max ? max / 2 : 0))) : 0;
            $(`#${id}`).val(val);
        }

        let iniDocument = function () {
                // $(document).on('blur', '.calendar input.datatable-btn-filter', function (e) {
                //     console.log($(this));
                //     if( typeof window.LaravelDataTables !== 'undefined'){
                //         try{
                //             window.LaravelDataTables.dataTableBuilder.draw();
                //         }
                //         catch (e) {}
                //     }
                // });
                iniSelect2('.select2');
                iniSwitch();
                // $('.select2',document).select2();

                // $.fn.iCheck &&
                // $('input[type="checkbox"]:not(.normal), input[type="radio"]:not(.normal)').iCheck({
                //     checkboxClass: 'icheckbox_square-blue',
                //     radioClass: 'iradio_square-blue',
                //     increaseArea: '20%' // optional
                // })
                // // .on('ifChanged ifChecked ifUnchecked', function (event) { $(this).trigger("change", event); });
                //     .on('ifChanged', function (event) {
                //         $(this).trigger("change", event);
                //     });
            },
            iniSelect2 = function (selector) {

                selector = selector ? selector : ".select2";

                //Initialize Select2 Elements
                $(selector).each(function (k, elm) {
                    let e = $(elm),
                        parent = e.parent(),
                        PlaceHolder = e.attr('data-placeholder') || "",
                        classes = [".valid-feedback", ".invalid-feedback"];

                    e.select2({
                        language: '{{$locale}}',
                        dir: "{{$direction}}",
                        direction: "{{$direction}}",
                        placeholder: PlaceHolder,
                        width: '',
                        // theme: 'bootstrap4',
                        dropdownAutoWidth: true,
                    });
                    if (e.next()) {
                        e.next().addClass("form-control");
                    }
                    for (i = 0; i < classes.length; ++i) {
                        if (parent.next().is(classes[i])) {
                            parent.append(parent.next());
                        }
                        // console.log(parent.next());
                    }
                });
            },
            iniSwitch = function (selector = "input[data-bootstrap-switch]") {

                $(selector).each(function () {
                    // elm = ;
                    $(this).bootstrapSwitch({
                        'state': $(this).prop('checked'),
                        onSwitchChange: function (event, state) {
                            // console.log($(this));
                            // console.log(state);
                            // console.log(event);
                        }
                    });
                });
            };

        const ReadQrCode = node => {
            // console.log(node,);
            // return;
            const callback = node.getAttribute('callbackqrcode');
            const reader = new FileReader();
            // console.log(callback, window[callback]);
            reader.onload = function () {
                node.value = "";
                qrcode.callback = result => {
                    if (result instanceof Error) {
                        swal({
                            icon: 'error',
                            text: '@lang('messages.error_read_qr')',
                            timer: 1500,
                            buttons: false,
                        });
                    }
                    else {
                        if (callback) {
                            if (typeof window[callback] === 'function') {
                                window[callback](result, node);
                            }
                            else if (typeof eval('typeof ' + callback) === 'function') {
                                eval(callback)(result, node);
                            }
                        }
                    }
                };
                qrcode.decode(reader.result);
            };
            reader.readAsDataURL(node.files[0]);
        };

        /**
         * Shuffles array in place. ES6 version
         * @param {Array} t items An array containing the items.
         */
        const shuffle = (t = []) => {
            let a = [...t], ctr = a.length, temp, index;

            // While there are elements in the array
            while (ctr > 0) {
                // Pick a random index
                index = Math.floor(Math.random() * ctr);
                // Decrease ctr by 1
                ctr--;
                // And swap the last element with it
                temp = a[ctr];
                a[ctr] = a[index];
                a[index] = temp;
            }
            return a;
        };

        $(document).on('change', '.datatable-btn-filter.auto', function (e) {
            try {
                window.LaravelDataTables.dataTableBuilder.draw();
            }
            catch (e) {
            }
        });
        $(document).on('click', 'button.datatable-btn-filter', function (e) {
            try {
                window.LaravelDataTables.dataTableBuilder.draw();
            }
            catch (e) {
            }
        });

        $(document).on('click', 'button.datatable-btn-filter-clear', function (e) {
            try {
                $(".DataTable-container-filter :input:not([name='semester_id']):not([name='school_year_id'])").val('').change();
                window.LaravelDataTables.dataTableBuilder.draw();

            }
            catch (e) {
            }
        });
        $(document).on('click', '.hasDelete', function (e) {
            e.preventDefault();

            let
                button = $(this),
                method = "POST",
                url = button.data('form-url');

            method = method.toString().trim().toLocaleUpperCase();

            if (url) {

                swal({
                    title: window.languages.are_you_sure,
                    text: window.languages.data_will_be_deleted,
                    icon: "warning",
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
                        if (value === 'yes') {
                            return $.post(url, "_method=DELETE&_token=" + window._token);
                        }
                        throw null;
                    })
                    .then((res) => {
                        // console.log(res);
                        return swal(window.languages.success, res.message, "success")
                            .then(() => {
                                $(".dt-button.buttons-reload").click();
                            });
                    })
                    .catch(err => {
                        console.log(err);
                        if (err) {
                            var mm = err.responseJSON.message ? err.responseJSON.message : "@lang('global.fail')";
                            // $(".dt-button.buttons-reload").click();
                            swal("@lang('global.oh_noes')", mm, "error")
                                .then(() => {
                                    $(".dt-button.buttons-reload").click();
                                });
                        }
                        else {
                            swal.stopLoading();
                            swal.close();
                        }
                    });
            }
        });
        $(document).on('change', "input[type='file'].custom-file-input", function (e) {
            //console.log($(this).val());
            let fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

        });

        $(document).on('keypress', ".text-number-event", function (e) {
            let value = e.key || "";
            if (!$.isNumeric(value) && value !== '.') {
                e.preventDefault();
                // return;
            }
            // console.log($(this).val());
        });

        $(document).on('submit', 'form.form-ajax:not(.render)', function (e) {
            let form = new FormInit($(this)[0]);
            e.preventDefault();
            let btn = $(form.form).find(':submit');

            if (!btn.length) {
                btn = $("<button type='submit'>");
                $(form.form).append(btn);
            }
            btn.click();
        });
        $(document).on('click', '.hasForm', function (e) {
            e.preventDefault();

            let
                button = $(this),
                data = button.attr('form-data') || "",
                method = button.attr('form-method') || "GET",
                url = button.attr('form-url'),
                hasConfirm = button.attr('form-confirm');

            method = method.toString().trim().toLocaleUpperCase();

            if (url) {
                $(".form-has-form").remove();
                let html = $("<form class='form-has-form d-none form-ajax' method='" + (method !== 'GET' ? 'POST' : 'GET') + "' action='" + url + "'><input name='_method' value='" + method + "'/><input name='_token' value='{!! csrf_token()!!}'/></form>");

                function submit__form(form, _button) {
                    form.insertAfter(_button);
                    return form.submit();
                }

                data.length && data.toString().trim().split('&').forEach(function (string) {
                    let str = string.toString().trim().split('=');
                    html.append("<input type='hidden' name='" + str[0] + "' value='" + str[1] + "'/>");
                });

                if (hasConfirm !== undefined) {
                    swal({
                        title: window.languages.are_you_sure,
                        text: hasConfirm ? hasConfirm : '',
                        icon: "warning",
                        buttons: {
                            cancel: window.languages.cancel,
                            yes: {
                                text: window.languages.yes,
                                value: "yes",
                                closeModal: false,
                            },
                        },
                    })
                        .then((value) => {
                            if (value === 'yes') {
                                return submit__form(html, button);
                            }
                            throw null;
                        })
                        .catch(err => {
                            if (err) {
                                swal(window.languages.oh_noes, window.languages.there_is_an_error, "error")
                                    .finally(() => {
                                        $(".dt-button.buttons-reload").click();
                                    });
                            }
                            else {
                                swal.stopLoading();
                                swal.close();
                            }
                        });
                }
                else {
                    return submit__form(html, button);
                }
            }
        });

        // dt-button buttons-reload
        $(document).ready(function () {

            iniDocument();
            // console.log(1);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    Language: $('html').attr('lang')
                },
                beforeSend: function () {
                    MyTh.overlay(null, false);
                    MyTh.overlay(null, true);
                },
                complete: function () {
                    MyTh.overlay(null, false);
                    // iniSelect2('.select2');
                }
            });


            {{--new FroalaEditor('.editor:not(.disabled):not(:disabled):not([readonly])',{--}}
            {{--direction: "{{$direction}}",--}}
            {{--language: "{{$locale}}",--}}
            {{--});--}}

        });

        $(document).on("click", ".data-table-ajax", function (e) {
            e.preventDefault();
            var self = $(this),
                url = self.data('url');

            swal({
                text: window.languages.are_you_sure,
                icon: "warning",
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
                    if (value === 'yes') {
                        return fetch(url);
                    }
                    throw null;
                })
                .then(results => {
                    return results ? results.json() : null;
                })
                .then(json => {
                    if (json.message !== undefined)
                        swal({
                            title: json.message,
                            text: "",
                            icon: "success",
                            buttons: {
                                cancel: window.languages.done,
                            },
                            closeModal: false,
                        });

                    $(".dt-button.buttons-reload").click();
                })
                .catch(err => {
                    // console.log(err);
                    if (err) {
                        $(".dt-button.buttons-reload").click();
                        swal("@lang('global.oh_noes')", "@lang('global.there_is_an_error')", "error");
                    }
                    else {
                        swal.stopLoading();
                        swal.close();
                    }
                });
        });
        $(document).on('click', '.tooltip-printed', function () {
            let t = $(this);
            t.attr('data-toggle', 'tooltip');
            t.attr('data-placement', 'top');
            t.attr('title', '@lang('admin::global.template_printed')');
            t.tooltip();
        });

        $(document).on('show.bs.modal', '.modal-ajax', function (event) {
            let
                button = $(event.relatedTarget), // Button that triggered the modal
                modal = $(this);
            if (button.data('url')) {
                $.get(button.data('url'))
                    .done(x => {
                        try {
                            modal.find('.modal-body').html(x.html);
                        }
                        catch (e) {
                            console.log(e);
                        }
                    });
            }
        });
        $(document).on('hidden.bs.modal', '.modal-ajax', function (event) {
            let modal = $(this);
            modal.find('.modal-body').html('');
        });
    </script>
@endpush
