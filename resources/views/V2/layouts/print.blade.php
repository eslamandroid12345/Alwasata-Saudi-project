<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{$direction}}">
<head>
    @include("V2.app.meta")
    @include("V2.app.master_stylesheets")
    <style>
        .top-print {
            z-index: 5;
        }

        @media print {
            @page {
                size: auto;
            }

            body {
                min-width: 100% !important;
                overflow: hidden;
                padding: 0;
                margin: 0;
            }

            .container {
                min-width: 100% !important;
            }
        }

        @media screen {
            body {
                padding: 1em 2em;
            }
        }

    </style>
    @stack("styles")
</head>
<body>

<script src="{{asset('myth-plugins/jquery/jquery.min.js')}}"></script>
<script>
    window.printWindow = () => {
        const inputClassName = "d-print-none", className = "span-print";
        $(`.${className}`).remove();
        $(`:input.${inputClassName}`).each(function () {
            let elm = $(this), span = $("<span></span>");
            span.attr("class", className);
            span.html(elm.is("select") ? elm.find('option:selected').text() : elm.val().toString().replace(/\n/g, "<br />"));
            span.insertAfter(elm);
        });

        $(document).ready(function () {
            print();
            $(`.${className}`).remove();
            // $(".arabic-string").each((i, v) => {
            //     let s = arabicString($(v).text());
                // console.log(s);
                // if ($(v).text() && !$(v).find("input,textarea").length)
                //     $(v).text(arNum($(v).text()));
            // });
        });
    }
</script>

<div class="d-print-none col-md-6 mr-auto p-5 top-print">
    <div class="pt-2 pl-2" style="position:fixed;top: 0;{{$align}}: 0;">
        <a href="javascript:void(0);" class="btn btn-dark" onclick="printWindow()">
            {{--<i class="fa fa-print"></i>--}}
            @lang( 'global.print' )
        </a>

        <a href="javascript:void(0)"
           onclick="window.opener ? window.close() : location.href = '{{ redirect()->back()->getTargetUrl() }}'"
           class='btn btn-danger'>
            {{--<i class="fa fa-backward"></i>--}}
            @lang( 'global.back' )
        </a>
    </div>
</div>

@yield("print_content")

<script>
    const arabicString = str => {
        try {
            if (!str.toString().trim())
                return str;
            //console.log(str);
            let nStr =
                str.toString()
                    .replace(/9/g, '٩')
                    .replace(/8/g, '٨')
                    .replace(/7/g, '٧')
                    .replace(/6/g, '٦')
                    .replace(/5/g, '٥')
                    .replace(/4/g, '٤')
                    .replace(/3/g, '٣')
                    .replace(/2/g, '٢')
                    .replace(/1/g, '١')
                    .replace(/0/g, '٠');

            // Fix Hijri Date
            if (str.split('-').length === 3)
                nStr = nStr.replace(/-/g, '/').replace(/\/٠/g, '/');
            // console.log(nStr);
            return nStr;
        } catch (e) {

        }
        return str;
    };

    $(document).ready(function () {
        $(".arabic-string").each((i, v) => {
            let elm = $(v), e;
            (e = elm.text()) &&
            elm.text(arabicString(e));
        });
    });
</script>
@stack("scripts")
</body>
</html>
