@if (0)

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>alert</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
    body {
    margin: 0;
    }
    #shade,
    #modal {
    display: none;
    }
    #shade {
    position: fixed;
    z-index: 100;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    }
    #modal {
    position: fixed;
    z-index: 101;
    top: 33%;
    left: 25%;
    width: 50%;
    }
    #shade {
    background: silver;
    opacity: 0.5;
    filter: alpha(opacity=50);
    }
</style>
</head>
<body>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تنبيه بضروره الرد ع التذكيرات </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
				<center>
                    <p style="color:red">يُرجى الرد ع التذكيرات اولا  لمتابعه العمل بشكل سليم </p>
                    <br>
                    <div class="addBtn">
                        <a href="{{ route('all.recivedtask')}}">
                            <button class="mr-2 Cloud">
                                للرد ع التذكيرات
                            </button>
                        </a>
                    </div>
                </center>

            </div>
        </div>
    </div>
</div>
<div id="shade"></div>
</body>
</html>
<script>

$(document).ready(function(){

    var modal = document.getElementById('myModal');
    var shade = document.getElementById('shade');

    $("#myModal").modal('show');
    modal.style.display =shade.style.display = 'block';

    // This code is a workaround for IE6's lack of support for the
    // position: fixed style.
    //
    if (!('maxHeight' in document.body.style)) {
    function modalsize() {
        var top = document.documentElement.scrollTop;
        var winsize = document.documentElement.offsetHeight;
        var docsize = document.documentElement.scrollHeight;
        shade.style.height = Math.max(winsize, docsize) + 'px';
        modal.style.top = top + Math.floor(winsize / 3) + 'px';
    };
    modal.style.position = shade.style.position = 'absolute';
    window.onscroll = window.onresize = modalsize;
    modalsize();
    }
});
</script>

@endif
