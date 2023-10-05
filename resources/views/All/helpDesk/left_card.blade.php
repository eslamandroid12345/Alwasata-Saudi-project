<div class="cd-timeline-block">
    <div class="cd-timeline-img cd-movie"></div>
    <div class="cd-timeline-content cd-timeline-content-2-{{$right_item->id}}" style=" background: rgb(86, 115, 148,0.6);float:left;">
        <h2>{{@$helpDesk->name}}</h2>
        <p>{{$left_item->descrebtion}} </p>
        <div class="row">
            @foreach ($left_item->Image()->get() as $f)
                @php
                    // try{

                $file2 = public_path('uploads/'.str_replace(url('/uploads').'/', '',$f->image_path));
                    // dd($file2);
                    $type = exif_imagetype($file2);
                    switch($type) {
                    case IMG_GIF:
                        $type = 'image/gif';
                        break;
                    case IMG_JPG:
                        $type = 'image/jpg';
                        break;
                    case IMG_JPEG:
                        $type = 'image/jpeg';
                        break;
                    case IMG_PNG:
                        $type = 'image/png';
                        break;
                    case IMG_WBMP:
                        $type = 'image/wbmp';
                        break;
                    case IMG_XPM:
                        $type = 'image/xpm';
                        break;
                    default:
                        $type = 'unknown';
                    }
                    $file = url($f->image_path);
                    if(    str_contains($type, 'image')    )
                    {
                        $type = 'image';
                    }
                    if ($type == 'image') {
                            echo '<div class="col-md-4"><a href="'.$file.'" target="_blank"><img src="'.$file.'" class="image-responsive" style="height:80px; width:80px;" /></a></div>';
                    }else{
                            echo '<div class="col-md-4"><a  href="'.$file.'" target="_blank"><i class="fa fa-file" aria-hidden="true" style=font-size:54px;></i>
                                </a></div>';
                    }
                // }catch(\Exception $e){
                // }

                @endphp
            @endforeach
        </div>
        <span class="cd-date" style="text-align:center">
            {{Carbon\Carbon::parse($left_item->created_at )->format('Y-m-d')}}
            <br>
            {{Carbon\Carbon::parse($left_item->created_at )->format('H:i:s')}}
        </span>
    </div>

    <style>
        .cd-timeline-content-2-{{$right_item->id}}::before {
            top: 24px;
            left: auto;
            right: 100%;
            border-color: transparent;
                border-right-color: transparent;
            border-right-color: #40547b;
            }
    </style>
</div>
