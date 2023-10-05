
            <div class="cd-timeline-block">
                <div class="cd-timeline-img cd-movie"></div> <!-- cd-timeline-img -->

                <div class="cd-timeline-content cd-timeline-content-{{$right_item->id}}" style=" background: rgb(30, 101, 149,0.6); float:right;">
                    <h2> {{@$right_item->User->name ?? ''}}</h2>

                    <p>
                        {{$right_item->descrebtion}}
                    </p>

                    <span class="cd-date" style="text-align:center; left: auto; right: 92%;">
                        {{Carbon\Carbon::parse($right_item->date_replay )->format('Y-m-d')}}
                        <br>
                        {{Carbon\Carbon::parse($right_item->date_replay )->format('H:i:s')}}
                    </span>
                </div>
                <style>
                    .cd-timeline-content-{{$right_item->id}}::before {
                        top: 24px;
                        left: auto;
                        right: 100%;
                        border-color: transparent;
                            border-right-color: transparent;
                        border-right-color: #40547b;
                        }
                </style>
            </div>
