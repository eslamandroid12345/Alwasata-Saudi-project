@php
    $notifyWithoutReminders = $notifyWithoutReminders ?? collect();
    $notifyWithOnlyReminders  = $notifyWithOnlyReminders ?? collect();
    $notifyWithHelpdesk  = $notifyWithHelpdesk ?? collect();
    $received_task_count  = $received_task_count ?? 0;
    $unread_conversions  = $unread_conversions ?? 0;
    $unread_messages  = $unread_messages ?? collect();

    /////////////////// User information
    $top_side_user_info_children[]=[
        'title' => MyHelpers::admin_trans(auth()->user()->id,'Profile'),
        'url' => route('profile'),
        'icon' => '<i class="fas fa-user mr-3"></i>'
    ];

    if(session('user_is_switched')){
        $top_side_user_info_children[]=[
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Back to your account'),
            'url' => route('switch.userRestore'),
            'icon' => ''

        ];
    }

    if(!session('user_is_switched')){
        $top_side_user_info_children[]=[
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Logout'),
            'url' => route('logout'),
            'icon' => '<i class="fas fa-power-off mr-3"></i>'

        ];
    }

    /////////////////////////
    if (auth()->user() && in_array(auth()->user()->role,  [0,1,2,3,5,6])){

    }

    ////////////  NOTIFICATION WITHOUT REMINDERS
    $top_side_user_notificition_children=[];
    $top_side_user_notificitions=[];
    if (auth()->user()->role != '12' && auth()->user()->role != 13 ){
        // NOTIFICATION WITHOUT REMINDERS

        // if ($notifyWithoutReminders->where('recived_id', (auth()->user()->id))->whereIn('status', [0, 1])->count() > 0){
        //     $top_side_user_notificition_children[]=[
        //         'count' => $notifyWithoutReminders->where('recived_id', (auth()->user()->id))->whereIn('status', [0, 1])->count()
        //     ];
        // }if ($notifyWithoutReminders->count() > 0){
        //     $top_side_user_notificition_children[]=[
        //         'count' => $notifyWithoutReminders->count()
        //     ];
        // }

        foreach (array_slice($notifyWithoutReminders->toArray(), 0, 3) as $notify){
            if($notify->type != 20){
                if(auth()->user()->role != 8 && auth()->user()->role != 7 ){
                    if ($notify->type == 10){
                        $top_side_user_notificition_children[]=[
                            'url' => route('all.reqHistory',$notify->req_id),
                            'value' => $notify->value
                        ];

                    }else {
                        $top_side_user_notificition_children[]=[
                            'url' => route('all.openNotify', ['id'=>$notify->req_id, 'notify'=>$notify->id] ) ,
                            'value' => $notify->value
                        ];
                    }
                }elseif(auth()->user()->role == 7 && $notify->type != 8){
                    $top_side_user_notificition_children[]=[
                            'url' => route('all.notification'),
                            'value' => $notify->value
                    ];
                }elseif(auth()->user()->role == 8 && auth()->user()->accountant_type == 0){
                    $top_side_user_notificition_children[]=[
                            'url' => route('report.tsaheelAccountingReportWithNotifiy'),
                            'value' => $notify->value
                    ];
                }elseif(auth()->user()->role == 8 && auth()->user()->accountant_type == 1){
                    $top_side_user_notificition_children[]=[
                            'url' => route('report.wsataAccountingReportWithNotifiy'),
                            'value' => $notify->value
                    ];
                }
            }else {
                if(auth()->user()->role ==7){
                    $top_side_user_notificition_children[]=[
                            'url' => route('admin.suggestions.index').'?notify='.$notify->id,
                            'value' => $notify->value
                    ];
                }else {
                    $top_side_user_notificition_children[]=[
                            'url' => route('all.suggestions.index').'?notify='.$notify->id,
                            'value' => $notify->value
                    ];
                }
            }
        }

        $top_side_user_notificition_children[]=[
            'value' => MyHelpers::admin_trans(auth()->user()->id,'Notifications'),
            'url' => route('all.notification')
        ];


        //////////////// REMINDERS
        $top_side_user_reminder_children=[];
        // if($notifyWithOnlyReminders->count() > 0){
        //         $top_side_user_reminder_children[]=[
        //             'count' => $notifyWithOnlyReminders->count()
        //         ];
        // }

        foreach (array_slice($notifyWithOnlyReminders->toArray(), 0, 3) as $notify){
            if(auth()->user()->role != 8 && auth()->user()->role != 7 ){
                $top_side_user_reminder_children[]=[
                    'title' => $notify->value,
                    'url' => route('all.openNotify', ['id'=>$notify->req_id, 'notify'=>$notify->id] )
                ];
            }elseif(auth()->user()->role == 7 && $notify->type != 8){
                $top_side_user_reminder_children[]=[
                    'title' => $notify->value,
                    'url' => route('all.notification')
                ];
            }elseif(auth()->user()->role == 8 && auth()->user()->accountant_type == 0){
                $top_side_user_reminder_children[]=[
                    'title' => $notify->value,
                    'url' => route('report.tsaheelAccountingReportWithNotifiy' )
                ];
            }elseif(auth()->user()->role == 8 && auth()->user()->accountant_type == 1){
                $top_side_user_reminder_children[]=[
                    'title' => $notify->value,
                    'url' => route('report.wsataAccountingReportWithNotifiy' )
                ];
            }

        }
        $top_side_user_reminder_children[]=[
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Notifications'),
            'url' => route('all.notification')
        ];


        //////////////////////////// Tasks
        $top_side_user_tasks_children=[];
        // if($received_task_count+$notifyWithHelpdesk->count() > 0){
        //     $top_side_user_tasks_children[]=[
        //         'count' => $received_task_count+$notifyWithHelpdesk->count(),
        //     ];
        // }

        if( $received_task_count > 0 || $notifyWithHelpdesk->count() > 0){
            foreach($task->take(2) as $item){
                $top_side_user_tasks_children[]=[
                    'title' =>'مهمة جديدة تمت إضافتها',
                    'url' => route('all.show_users_task' , $item->id )
                ];
            }

            foreach($task_contents->take(2) as $task_content){
                $top_side_user_tasks_children[]=[
                    'title' =>'يوجد رد جديد على التذكرة',
                    'url' =>route('all.show_users_task' , $task_content->id )
                ];
            }

            foreach($notifyWithHelpdesk->take(2) as $helpDesk){
                if (auth()->user()->role == '7'){
                    $top_side_user_tasks_children[]=[
                        'title' => $helpDesk->value,
                        'url' =>url('admin/openhelpDeskPage/'.$helpDesk->req_id )
                    ];
                }else{
                    $top_side_user_tasks_children[]=[
                        'title' => $helpDesk->value,
                        'url' =>url('all/openhelpDeskPage/'.$helpDesk->req_id )
                    ];
                }

            }
        }

        if (auth()->user()->role == '5'){
            $top_side_user_tasks_children[]=[
                'title' =>'فتح جميع التذاكر',
                'url' => route('quality.manager.mytask')
            ];
        }else{
            $top_side_user_tasks_children[]=[
                'title' =>'فتح جميع التذاكر',
                'url' => route('all.recivedtask')
            ];
        }


        /////////////////////// MESSAGE
        $top_side_user_messages_children=[];
        // $senders= [];
        // if(count($unread_messages) != 0){
        //     foreach($unread_messages as $message){
        //         if(! in_array($message->from ,$senders) ){

        //         }
        //     }
        // }
        $top_side_user_messages_children=[
            'title' => MyHelpers::admin_trans(auth()->user()->id,'View all messages'),
            'url' => route('chat'),
        ];

    }// end of main if













@endphp
<div class="main-header">
    <div class="main-header__topbar me-auto ms-lg-3">
      <div class="header__topbar-item order-4 order-lg-0">
        <div class="header__topbar-wrapper">
          <form class="search">
            <input class="search__toggle" id="toggleSearch" type="checkbox" hidden="" />
            <div class="search__field">
              <div class="input-icon">
                <input class="search__input" id="nav-search-input" type="text" placeholder="ابحث هنا" />
                <div class="icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="17.927" height="17.927" viewBox="0 0 17.927 17.927">
                    <g id="Group_3072" data-name="Group 3072" transform="translate(0.707 0.5)">
                      <path
                        id="Path_698"
                        data-name="Path 698"
                        d="M4.5,11.931A7.431,7.431,0,1,0,11.931,4.5,7.431,7.431,0,0,0,4.5,11.931Z"
                        transform="translate(-2.642 -4.5)"
                        fill="none"
                        stroke="#fff"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1"
                      ></path>
                      <path id="Path_699" data-name="Path 699" d="M24.975,29.016l4.041-4.041" transform="translate(-24.975 -12.296)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                    </g>
                  </svg>
                </div>
              </div>
              <label class="search__label" for="toggleSearch">
                <div class="search__button">
                  <div class="search__icon search__button--toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20.707" height="20.707" viewBox="0 0 20.707 20.707">
                      <g id="Group_11474" data-name="Group 11474" transform="translate(0.707 0.5)">
                        <path
                          id="Path_698"
                          data-name="Path 698"
                          d="M4.5,13.167A8.667,8.667,0,1,0,13.167,4.5,8.667,8.667,0,0,0,4.5,13.167Z"
                          transform="translate(-2.333 -4.5)"
                          fill="none"
                          stroke="#fff"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1"
                        ></path>
                        <path
                          id="Path_699"
                          data-name="Path 699"
                          d="M24.975,29.688l4.713-4.713"
                          transform="translate(-24.975 -10.188)"
                          fill="none"
                          stroke="#fff"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1"
                        ></path>
                      </g>
                    </svg>
                  </div>
                </div>
                <div class="search__button search__button--submit">
                  <svg width="10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path
                      fill="#fff"
                      d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"
                    ></path>
                  </svg>
                </div>
              </label>
            </div>
          </form>
        </div>
      </div>




      <div class="header__topbar-item">
        <div class="header__topbar-wrapper" data-bs-toggle="dropdown">
          <div class="header__topbar-icon">
            <i class="fa fa-comments text-white" aria-hidden="true"></i>
          </div>
        </div>
        <div class="dropdown-menu">
          <div class="dropdown-header border-bottom">
            <h5 class="text-center">الرسائل</h5>
          </div>
          <ul class="dropdown-notification">
            <li class="dropdown-item">
                <a class="dropdown-link py-3" href="{{ $top_side_user_messages_children['url'] ?? '' }}">
                <span class="dropdown-icon bg-primary">
                    <i class="fa fa-comments text-white" aria-hidden="true"></i>
                </span>
                <div class="d-flex flex-column col">
                    <div class="d-flex align-items-center justify-content-between mb-1"><span class="dropdown-title font-medium">{{ $top_side_user_messages_children['title'] ?? '' }}</span></div>
                </div>
                </a>
            </li>
          </ul>
        </div>
      </div>


      <div class="header__topbar-item">
        <div class="header__topbar-wrapper" data-bs-toggle="dropdown">
          <div class="header__topbar-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18.014" viewBox="0 0 18 18.014">
              <g id="ticket" transform="translate(-72.188 -72)">
                <path
                  id="Path_2758"
                  data-name="Path 2758"
                  d="M89.062,76.532a.489.489,0,0,0-.622-.059,1.956,1.956,0,0,1-2.711-2.721.5.5,0,0,0-.059-.617l-.563-.563A1.96,1.96,0,0,0,83.723,72h0a1.959,1.959,0,0,0-1.385.573l-9.582,9.611a1.96,1.96,0,0,0,0,2.765l.377.372a.494.494,0,0,0,.656.034,1.941,1.941,0,0,1,2.736,2.731.489.489,0,0,0,.029.661l.695.695a1.961,1.961,0,0,0,2.77,0h0l9.592-9.592a1.961,1.961,0,0,0,0-2.77ZM79.323,88.747a.981.981,0,0,1-1.385,0l-.4-.4a2.916,2.916,0,0,0-4-4l-.083-.083a.981.981,0,0,1,0-1.385l6.523-6.548,5.882,5.882Zm9.592-9.592-2.369,2.369-5.882-5.887,2.359-2.369a.962.962,0,0,1,.69-.289h0a.978.978,0,0,1,.69.284l.3.3a2.939,2.939,0,0,0,3.93,3.93l.279.279a.976.976,0,0,1,0,1.385Z"
                  transform="translate(0)"
                  fill="#fff"
                ></path>
                <path
                  id="Path_2759"
                  data-name="Path 2759"
                  d="M169.609,216.41l-2.373-2.374a.981.981,0,0,0-1.385,0h0l-2.315,2.315a.981.981,0,0,0,0,1.385l2.374,2.373a.981.981,0,0,0,1.385,0l2.315-2.315a.969.969,0,0,0,.024-1.375C169.629,216.41,169.624,216.4,169.609,216.41Zm-3,3-2.378-2.373,2.315-2.315h0l2.373,2.373Z"
                  transform="translate(-86.606 -134.813)"
                  fill="#fff"
                ></path>
              </g>
            </svg>
          </div>
        </div>
        <div class="dropdown-menu">
          <div class="dropdown-header border-bottom">
            <h5 class="text-center">التذاكر</h5>
          </div>
          <ul class="dropdown-notification">
            @foreach ($top_side_user_tasks_children as $top_side_user_tasks_child)
                <li class="dropdown-item">
                <a class="dropdown-link" href="{{ $top_side_user_tasks_child['url'] ?? '' }}">
                    <span class="dropdown-icon bg-green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18.014" viewBox="0 0 18 18.014">
                        <g id="ticket" transform="translate(-72.188 -72)">
                        <path
                            id="Path_2758"
                            data-name="Path 2758"
                            d="M89.062,76.532a.489.489,0,0,0-.622-.059,1.956,1.956,0,0,1-2.711-2.721.5.5,0,0,0-.059-.617l-.563-.563A1.96,1.96,0,0,0,83.723,72h0a1.959,1.959,0,0,0-1.385.573l-9.582,9.611a1.96,1.96,0,0,0,0,2.765l.377.372a.494.494,0,0,0,.656.034,1.941,1.941,0,0,1,2.736,2.731.489.489,0,0,0,.029.661l.695.695a1.961,1.961,0,0,0,2.77,0h0l9.592-9.592a1.961,1.961,0,0,0,0-2.77ZM79.323,88.747a.981.981,0,0,1-1.385,0l-.4-.4a2.916,2.916,0,0,0-4-4l-.083-.083a.981.981,0,0,1,0-1.385l6.523-6.548,5.882,5.882Zm9.592-9.592-2.369,2.369-5.882-5.887,2.359-2.369a.962.962,0,0,1,.69-.289h0a.978.978,0,0,1,.69.284l.3.3a2.939,2.939,0,0,0,3.93,3.93l.279.279a.976.976,0,0,1,0,1.385Z"
                            transform="translate(0)"
                            fill="#fff"
                        ></path>
                        <path
                            id="Path_2759"
                            data-name="Path 2759"
                            d="M169.609,216.41l-2.373-2.374a.981.981,0,0,0-1.385,0h0l-2.315,2.315a.981.981,0,0,0,0,1.385l2.374,2.373a.981.981,0,0,0,1.385,0l2.315-2.315a.969.969,0,0,0,.024-1.375C169.629,216.41,169.624,216.4,169.609,216.41Zm-3,3-2.378-2.373,2.315-2.315h0l2.373,2.373Z"
                            transform="translate(-86.606 -134.813)"
                            fill="#fff"
                        ></path>
                        </g>
                    </svg>
                    </span>
                    <span class="dropdown-title font-medium">{{ $top_side_user_tasks_child['title'] ?? '' }}</span>
                </a>
                </li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="header__topbar-item">
        <div class="header__topbar-wrapper" data-bs-toggle="dropdown">
          <div class="header__topbar-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="14.056" height="14.369" viewBox="0 0 14.056 14.369">
              <g id="bell" transform="translate(0.55 0.55)">
                <line id="Line_3" data-name="Line 3" x2="3" transform="translate(5.121 13.269)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1"></line>
                <path
                  id="Path_2642"
                  data-name="Path 2642"
                  d="M16.956,13.448H4a1.885,1.885,0,0,0,1.178-1.7V8.724A5.037,5.037,0,0,1,10.478,4h0a5.037,5.037,0,0,1,5.3,4.724v3.023a1.885,1.885,0,0,0,1.178,1.7Z"
                  transform="translate(-4 -4)"
                  fill="none"
                  stroke="#fff"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.1"
                ></path>
              </g>
            </svg>
          </div>
        </div>
        <div class="dropdown-menu">
          <div class="dropdown-header border-bottom">
            <h5 class="text-center">الاشعارات- التذكيرات</h5>
          </div>
          <ul class="dropdown-notification">
        @foreach ($top_side_user_reminder_children as $top_side_user_reminder_child)
           <li class="dropdown-item">
            <a class="dropdown-link" href="{{ $top_side_user_reminder_child['url'] ?? '' }}">
              <span class="dropdown-icon bg-blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="14.056" height="14.369" viewBox="0 0 14.056 14.369">
                  <g id="bell" transform="translate(0.55 0.55)">
                    <line id="Line_3" data-name="Line 3" x2="3" transform="translate(5.121 13.269)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1"></line>
                    <path
                      id="Path_2642"
                      data-name="Path 2642"
                      d="M16.956,13.448H4a1.885,1.885,0,0,0,1.178-1.7V8.724A5.037,5.037,0,0,1,10.478,4h0a5.037,5.037,0,0,1,5.3,4.724v3.023a1.885,1.885,0,0,0,1.178,1.7Z"
                      transform="translate(-4 -4)"
                      fill="none"
                      stroke="#fff"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.1"
                    ></path>
                  </g>
                </svg>
              </span>
              <span class="dropdown-title font-medium">{{$top_side_user_reminder_child['title'] ?? '' }}</span>
            </a>
          </li>
        @endforeach
          </ul>
        </div>
      </div>
      <div class="header__topbar-item">
        <div class="header__topbar-wrapper" data-bs-toggle="dropdown">
          <div class="header__topbar-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.152" height="20.152" viewBox="0 0 20.152 20.152">
              <g id="message_1_" data-name="message (1)" transform="translate(0.55 0.55)">
                <path
                  id="message_1_2"
                  data-name="message (1)"
                  d="M19.191,1.5H2.861A1.361,1.361,0,0,0,1.5,2.861V20.552l4.536-4.536H19.191a1.361,1.361,0,0,0,1.361-1.361V2.861A1.361,1.361,0,0,0,19.191,1.5Z"
                  transform="translate(-1.5 -1.5)"
                  fill="none"
                  stroke="#fff"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.1"
                ></path>
              </g>
            </svg>
          </div>
        </div>
        <div class="dropdown-menu">
          <div class="dropdown-header border-bottom">
            <h5 class="text-center">الاشعارات بدون تذكيرات</h5>
          </div>
          <ul class="dropdown-notification">
            @foreach ($top_side_user_notificition_children as $top_side_user_notificition_child)
            <li class="dropdown-item">
                <a class="dropdown-link py-3" href="{{ $top_side_user_notificition_child['url'] ?? '' }}">
                <span class="dropdown-icon bg-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14.638" height="14.638" viewBox="0 0 14.638 14.638">
                    <path
                        id="message_1_"
                        data-name="message (1)"
                        d="M14.071,1.5H2.467a.967.967,0,0,0-.967.967V15.038l3.223-3.223h9.348a.967.967,0,0,0,.967-.967V2.467A.967.967,0,0,0,14.071,1.5Z"
                        transform="translate(-0.95 -0.95)"
                        fill="none"
                        stroke="#fff"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.1"
                    ></path>
                    </svg>
                </span>
                <div class="d-flex flex-column col">
                    <div class="d-flex align-items-center justify-content-between mb-1"><span class="dropdown-title font-medium">{{  $top_side_user_notificition_child['value'] ?? ''}}</span></div>
                </div>
                </a>
            </li>
            @endforeach

            <li class="dropdown-item">
            </li>
          </ul>
        </div>
      </div>
      <div class="header__topbar-item dropdown order-5 order-lg-6 me-4">
        <div class="header__topbar-wrapper align-items-center p-1" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="header__topbar-user">
            <div class="symbol symbol-35"><img class="rounded-circle" src="/themes/theme1/assets/images/avatar.png" alt="" /></div>
          </div>
        </div>
        <div class="dropdown-menu dropdown-menu-small">
          <div class="dropdown-header d-flex align-items-center pt-3 px-4">
            <h5 class="text-center"> {{ auth()->user()->name }} اهلا بك</h5>
            <img class="px-2" src="/themes/theme1/assets/images/waving-hand.svg" alt="" />
          </div>
          <ul class="dropdown-notification">
            {{-- <li class="dropdown-item">
              <a class="py-3 px-4 d-flex align-items-center" href="">
                <svg xmlns="http://www.w3.org/2000/svg" width="15.91" height="17.773" viewBox="0 0 15.91 17.773">
                  <g id="Icon_feather-user" data-name="Icon feather-user" transform="translate(-5.5 -4)">
                    <path
                      id="Path_3967"
                      data-name="Path 3967"
                      d="M20.91,28.091V26.227A3.727,3.727,0,0,0,17.182,22.5H9.727A3.727,3.727,0,0,0,6,26.227v1.864"
                      transform="translate(0 -6.818)"
                      fill="none"
                      stroke="#00acf1"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1"
                    ></path>
                    <path
                      id="Path_3968"
                      data-name="Path 3968"
                      d="M19.455,8.227A3.727,3.727,0,1,1,15.727,4.5,3.727,3.727,0,0,1,19.455,8.227Z"
                      transform="translate(-2.273)"
                      fill="none"
                      stroke="#00acf1"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1"
                    ></path>
                  </g>
                </svg>
                <span class="pe-2 text-black">بيـانـاتي الشخصية</span>
              </a>
            </li> --}}
            @foreach ($top_side_user_info_children as $top_side_user_info)
                <li class="dropdown-item">
                <a class="py-3 px-4 d-flex align-items-center" href="{{ $top_side_user_info['url'] }}">
                    @if (isset($top_side_user_info['icon']))
                        {!! $top_side_user_info['icon'] !!}
                    @endif
                    <span class="pe-2 text-black" style="color: black">{{ $top_side_user_info['title'] }}</span>
                </a>
                </li>
            @endforeach

            {{-- <li class="dropdown-item">
              <a class="dropdown-link border-top py-3" href="">
                <span class="dropdown-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="17.074" height="15.58" viewBox="0 0 17.074 15.58">
                    <g id="logout" transform="translate(0 -22.397)">
                      <g id="Group_1006" data-name="Group 1006" transform="translate(4.975 28.398)">
                        <g id="Group_1005" data-name="Group 1005">
                          <path
                            id="Path_3975"
                            data-name="Path 3975"
                            d="M161.082,203.738l-1.827-1.288a.5.5,0,0,0-.788.409v.788H149.7a.5.5,0,1,0,0,1h8.772v.788a.5.5,0,0,0,.788.409l1.827-1.288A.5.5,0,0,0,161.082,203.738Z"
                            transform="translate(-149.195 -202.358)"
                            fill="#00acf1"
                          ></path>
                        </g>
                      </g>
                      <g id="Group_1008" data-name="Group 1008" transform="translate(0 22.397)">
                        <g id="Group_1007" data-name="Group 1007" transform="translate(0 0)">
                          <path
                            id="Path_3976"
                            data-name="Path 3976"
                            d="M14.355,33.4a.5.5,0,0,0-.683.183,6.79,6.79,0,1,1,0-6.789.5.5,0,1,0,.866-.5,7.79,7.79,0,1,0,0,7.791A.5.5,0,0,0,14.355,33.4Z"
                            transform="translate(0 -22.397)"
                            fill="#00acf1"
                          ></path>
                        </g>
                      </g>
                    </g>
                  </svg>
                </span>
                <span class="dropdown-title font-medium pb-1">تسجيل الخروج</span>
              </a>
            </li> --}}
          </ul>
        </div>
      </div>
    </div>
  </div>
@if (0)

<div class="contHeader">
    <div class="headContAria">

        <header class="single-head user_head">

            <div class="user-nav container-fluid px-lg-5">

                <div class="UserNav-cont d-flex  ">

                    <div class="userName d-flex">

                        <div class="dropdown">

                            <button class="user_btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{auth()->user()->name}}
                            </button>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{route('profile')}}"> <i class="fas fa-user mr-3"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Profile') }}
                                </a>

                                @if(!session('user_is_switched'))
                                    <a class="dropdown-item logout" href="{{route('logout')}}"> <i class="fas fa-power-off mr-3"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Logout') }}
                                    </a>
                                @endif

                            </div>

                        </div>

                    </div>

                    <div class="userNote d-flex">

                        @if(session('user_is_switched'))
                            <div class="notifactions mr-4 not_bar tableAdminOption">
                            <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ MyHelpers::admin_trans(auth()->user()->id,'Back to your account') }}">
                                <a href="{{ route('switch.userRestore') }}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Back to your account') }}" class="text-white">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </span>
                            </div>
                        @endif
                    @if (auth()->user() && in_array(auth()->user()->role,  [0,1,2,3,5,6]))

                    <div class="notifactions mr-4 not_bar ">
                        <span title="الدعم الفني" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-comments" aria-hidden="true"></i>
                        </span>
                    </div>

                    @endif
                        @if (auth()->user()->role != '12' && auth()->user()->role != 13 )
                            {{--NOTIFICATION WITHOUT REMINDERS--}}
                            <div class="notifactions mr-4 not_bar notfi_call">
                                <i class="fas fa-plus-square"></i>
                                @if ($notifyWithoutReminders->where('recived_id', (auth()->user()->id))->whereIn('status', [0, 1])->count() > 0)
                                    <span class="note-msg">{{$notifyWithoutReminders->where('recived_id', (auth()->user()->id))->whereIn('status', [0, 1])->count()}}</span>
                                @endif
                                <!-- @if ($notifyWithoutReminders->count() > 0)
                                    <span class="note-msg">{{$notifyWithoutReminders->count()}}</span>
                                @endif -->
                                <ul class="list-unstyled notfi_ul">
                                    @foreach (array_slice($notifyWithoutReminders->toArray(), 0, 3) as $notify)
                                        <li>
                                            <div class="single_Pop d-flex">
                                                @if ($notify->type == 0)
                                                    <div class="popIcon">
                                                        <i class="fas fa-file-medical"></i>
                                                    </div>

                                                @elseif ($notify->type == 1)
                                                    <div class="goldIcon">
                                                        <i class="fas fa-lightbulb"></i>
                                                    </div>
                                                @elseif ($notify->type == 3)
                                                    <div class="greenIcon">
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                @elseif ($notify->type == 5)
                                                    <div class="darkRed Icon">
                                                        <button data-notify="{{json_encode($notify,JSON_UNESCAPED_UNICODE)}}" class="notify-modal-btn mr-2 py-2 rounded-circle btn btn-info btn-sm" title="تفاصيل سريعة">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        {{--                                                        <i class="fas fa-exclamation-circle"></i>--}}
                                                    </div>
                                                @elseif ($notify->type == 6)
                                                    <div class="silverIcon">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                @elseif ($notify->type == 20)
                                                    <div class="orangIcon">
                                                        <i class="fas fa-calculator"></i>
                                                    </div>
                                                @endif

                                                @if ($notify->type != 20)
                                                    @if(auth()->user()->role != 8 && auth()->user()->role != 7 )
                                                        {{-- Service evaluation for the customer --}}
                                                        @if ($notify->type == 10)
                                                            <div class="popCont">
                                                                <a href="{{ route('all.reqHistory',$notify->req_id) }}">
                                                                    {{$notify->value}}
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="popCont">
                                                                <a href="{{ route('all.openNotify', ['id'=>$notify->req_id, 'notify'=>$notify->id] ) }}">
                                                                    {{$notify->value}}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @elseif(auth()->user()->role == 7 && $notify->type != 8)
                                                        <div class="popCont">
                                                            <a href="{{ route('all.notification') }}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>
                                                    @elseif(auth()->user()->role == 8 && auth()->user()->accountant_type == 0)
                                                        <div class="popCont">
                                                            <a href="{{ route('report.tsaheelAccountingReportWithNotifiy' ) }}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>
                                                    @elseif(auth()->user()->role == 8 && auth()->user()->accountant_type == 1)
                                                        <div class="popCont">
                                                            <a href="{{ route('report.wsataAccountingReportWithNotifiy' ) }}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>

                                                    @endif
                                                @else
                                                    @if(auth()->user()->role ==7)
                                                        <div class="popCont">
                                                            <a href="{{ route('admin.suggestions.index') }}?notify={{$notify->id}}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="popCont">
                                                            <a href="{{ route('all.suggestions.index') }}?notify={{$notify->id}}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>
                                                    @endif


                                                @endif

                                                {{--                                                @if($notify->type == 5)--}}
                                                {{--                                                    <div>--}}
                                                {{--                                                        <button class="btn btn-info btn-sm" title="تفاصيل سريعة">--}}
                                                {{--                                                            <i class="fa fa-eye"></i>--}}
                                                {{--                                                        </button>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                @endif--}}
                                            </div>
                                        </li>
                                    @endforeach

                                    <li>
                                        <div class="all-note text-center">
                                            <a href="{{ route('all.notification') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All') }}
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Notifications') }}</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            {{--NOTIFICATION WITHOUT REMINDERS--}}

                            {{--REMINDERS--}}
                            <div class="notifactions mr-4 not_bar notf_call">
                                <i class="fas fa-bell"></i>
                                @if ($notifyWithOnlyReminders->count() > 0)
                                    <span class="note-msg">{{$notifyWithOnlyReminders->count()}}</span>
                                @endif
                                <ul class="list-unstyled note_ul">

                                    @foreach (array_slice($notifyWithOnlyReminders->toArray(), 0, 3) as
                                    $notify)
                                        <li>
                                            <div class="single_Pop d-flex">

                                                @if ($notify->type == 0)
                                                    <div class="popIcon">
                                                        <i class="fas fa-file-medical"></i>
                                                    </div>

                                                @elseif ($notify->type == 1)
                                                    <div class="goldIcon">
                                                        <i class="fas fa-lightbulb"></i>
                                                    </div>
                                                @elseif ($notify->type == 3)
                                                    <div class="greenIcon">
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                @elseif ($notify->type == 5)
                                                    <div class="darkRedIcon">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                    </div>
                                                @elseif ($notify->type == 6)
                                                    <div class="silverIcon">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                @endif

                                                @if(auth()->user()->role != 8 && auth()->user()->role != 7 )
                                                    <div class="popCont">
                                                        <a href="{{ route('all.openNotify', ['id'=>$notify->req_id, 'notify'=>$notify->id] ) }}">
                                                            {{$notify->value}}
                                                        </a>
                                                    </div>
                                                @elseif(auth()->user()->role == 7 && $notify->type != 8)
                                                    <div class="popCont">
                                                        <a href="{{ route('all.notification') }}">
                                                            {{$notify->value}}
                                                        </a>
                                                    </div>
                                                @elseif(auth()->user()->role == 8 && auth()->user()->accountant_type
                                                == 0)
                                                    <div class="popCont">
                                                        <a href="{{ route('report.tsaheelAccountingReportWithNotifiy' ) }}">
                                                            {{$notify->value}}
                                                        </a>
                                                    </div>
                                                @elseif(auth()->user()->role == 8 && auth()->user()->accountant_type
                                                == 1)
                                                    <div class="popCont">
                                                        <a href="{{ route('report.wsataAccountingReportWithNotifiy' ) }}">
                                                            {{$notify->value}}
                                                        </a>
                                                    </div>

                                                @endif

                                            </div>
                                        </li>
                                    @endforeach

                                    <li>
                                        <div class="all-note text-center">
                                            <a href="{{ route('all.notification') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All') }}
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Notifications') }}</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            {{--TASKS--}}
                            <div class="notifactions mr-4 not_bar msg_call">
                                <i class="fas fa-comment-alt"></i>
                                @if($received_task_count+$notifyWithHelpdesk->count() > 0)
                                    <span class="note-msg">{{  $received_task_count+$notifyWithHelpdesk->count() }}</span>
                                @endif
                                <ul class="list-unstyled msg_ul">

                                    @if( $received_task_count > 0 || $notifyWithHelpdesk->count() > 0)
                                        @foreach($task->take(2) as $item)
                                            <li>
                                                <div class="single_Pop d-flex">

                                                    <div class="pinkIcon">
                                                        <i class="fas fa-comment-alt"></i>
                                                    </div>

                                                    <div class="popCont">
                                                        <a href="{{ route('all.show_users_task' , $item->id )}}">
                                                            مهمة جديدة تمت إضافتها
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        @foreach($task_contents->take(2) as $task_content)
                                            <li>
                                                <div class="single_Pop d-flex">

                                                    <div class="greenIcon">
                                                        <i class="fas fa-comments"></i>
                                                    </div>

                                                    <div class="popCont">
                                                        <a href="{{ route('all.show_users_task' , $task_content->id )}}">
                                                            يوجد رد جديد على التذكرة
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        @foreach($notifyWithHelpdesk->take(2) as $helpDesk)
                                            <li>
                                                <div class="single_Pop d-flex">

                                                    <div class="darkBlueIcon">
                                                        <i class="fas fa-cog"></i>
                                                    </div>

                                                    <div class="popCont">
                                                        @if (auth()->user()->role == '7')
                                                            <a href="{{ url('admin/openhelpDeskPage/'.$helpDesk->req_id )}}">
                                                                {{$helpDesk->value}}
                                                            </a>
                                                            @else
                                                            <a href="{{ url('all/openhelpDeskPage/'.$helpDesk->req_id )}}">
                                                                {{$helpDesk->value}}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif

                                    <li>
                                        <div class="all-note text-center">
                                            @if (auth()->user()->role == '5')
                                                <a href="{{ route('quality.manager.mytask') }}">فتح جميع التذاكر</a>
                                            @else
                                                <a href="{{ route('all.recivedtask') }}">فتح جميع التذاكر</a>
                                            @endif
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            {{--TASKS--}}



                            {{--
    @if (auth()->user()->role == '0')
                            <div class="notifactions mr-4 not_bar client_message_call" title="تواصل مع العملاء">
                                <i class="fas fa-comments"></i>
                                    @if($count_unread_message_from_client > 0)
                                        <span class="note-msg">{{ $count_unread_message_from_client }}</span>
                                    @endif
                                    <ul class="list-unstyled message_ul_client" style="overflow-y: scroll; height:300px;">
                                        @php
                                            $senders= [];
                                        @endphp
                                        @if(count($get_all_unread_messages) != 0)
                                            @foreach($get_all_unread_messages as $message)
                                                @if(! in_array($message['senderId'] ,$senders) )
                                                    <form method="post" action="{{route('chatClientInbox')}}">
                                                        @csrf
                                                        <input type="hidden" name="receivers[]" value="{{ $message['senderId'] }}" />
                                                        @if ($message['from_type'] == 'App\customer')
                                                            <input type="hidden" name="receiver_model_type" value="App\customer" />
                                                        @endif
                                                        <li>
                                                            <div class="single_Pop d-flex" onclick="$(this).closest('form').submit();">
                                                                <div class="popIcon">
                                                                        <img src="{{ asset('interface_style/images/customer-icon.png')}}" alt="عميل" width="35" />
                                                                </div>
                                                                <div class="popCont" style="padding-right: 7px">
                                                                        <b> العميل : {{ $message['senderName'] }}</b>
                                                                    @if ($message['message_type'] == 'file')
                                                                        <p> مستند مُستلم</p>
                                                                    @elseif ($message['message_type'] == 'image')
                                                                        <p> صورة مستلمة</p>
                                                                    @elseif ($message['message_type'] == 'video')
                                                                        <p> مقطع مستلم</p>
                                                                    @else
                                                                        <p>{{$message['text']}}</p>
                                                                    @endif
                                                                    <small class="time">
                                                                        <i class="fas fa-click-o"></i>
                                                                        {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </form>
                                                @endif
                                                @php
                                                    array_push($senders , $message['senderId']);
                                                @endphp
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                @endif


                                --}}



                            {{--MESSAGE--}}
                            <div class="notifactions not_bar mail_call" title="الرسائل">
                                <i class="fas fa-envelope"></i>
                                @if ($unread_conversions > 0)
                                    <span class="note-msg">{{$unread_conversions}}</span>
                                @endif
                                <ul class="list-unstyled mail_ul" style="overflow-y: scroll; height:300px;">
                                    @php
                                        $senders= [];
                                    @endphp
                                    @if(count($unread_messages) != 0)
                                        @foreach($unread_messages as $message)
                                            @if(! in_array($message->from ,$senders) )
                                                <form method="post" action="{{route('newChat')}}">
                                                    @csrf
                                                    <input type="hidden" name="receivers[]" value="{{ $message->from }}"/>
                                                    @if ($message->from_type == 'App\customer')
                                                        <input type="hidden" name="receiver_model_type" value="App\customer"/>
                                                    @endif
                                                    <li>
                                                        <div class="single_Pop d-flex" onclick="$(this).closest('form').submit();">
                                                            <div class="popIcon">
                                                                @if ($message->from_type != 'App\customer')
                                                                    <img src="{{ @$message->sender->avatar }}" alt="{{ @$message->sender->name }}" width="35"/>
                                                                @else
                                                                    <img src="{{ asset('interface_style/images/customer-icon.png')}}" alt="عميل" width="35"/>
                                                                @endif
                                                            </div>
                                                            <div class="popCont" style="padding-right: 7px">
                                                                @if ($message->from_type != 'App\customer')
                                                                    <b>{{ @$message->sender->name }}</b>
                                                                @else
                                                                    <b> العميل : {{ @$message->senderCustomer->name }}</b>
                                                                @endif
                                                                @if ($message->message_type == 'file')
                                                                    <p> مستند مُستلم</p>
                                                                @elseif ($message->message_type == 'image')
                                                                    <p> صورة مستلمة</p>
                                                                @elseif ($message->message_type == 'video')
                                                                    <p> مقطع مستلم</p>
                                                                @else
                                                                    <p>{{$message->message}}</p>
                                                                @endif
                                                                <small class="time">
                                                                    <i class="fas fa-click-o"></i>
                                                                    {{ $message->created_at->diffForHumans() }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </form>
                                            @endif
                                            @php
                                                array_push($senders , $message->from);
                                            @endphp
                                        @endforeach
                                    @endif
                                    <li>
                                        <div class="all-note text-center">
                                            <a href="{{route('chat')}}">{{ MyHelpers::admin_trans(auth()->user()->id,'View all messages') }}</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            {{--MESSAGE--}}
                        @endif
                    </div>
                </div>
            </div>
        </header>
    </div>
    <div data-backdrop="static" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="notify-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered " role="document" data-backdrop="static">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">بيانات الطلب</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="notify-modal-loading"> جار جلب البيانات...</div>
                    <div id="notify-modal-content">
                        <div class="container">
                            <div class="row" id="rows-container"></div>
                            <div class="row my-2">
                                <div class="col-12">
                                    <h4>تتبع الطلب</h4>
                                </div>
                            </div>
                            <div class="row" id="history-container"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="modal-btn-no7" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
{{--                    <button type="button" id="submitMove" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Move') }}</button>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@push('scripts')
    <script>
        window.notifyModal = null

        $(document).on('click', '.notify-modal-btn', function (e) {
            window.notifyModal = JSON.parse($(this).attr('data-notify'));
            $('#notify-modal').modal();
        })

        $('#notify-modal')
            .on('hidden.bs.modal', function (e) {
                $("#notify-modal-loading").addClass('d-none');
                $("#notify-modal-content").addClass('d-none');
                $("#rows-container").html('');
                $("#history-container").html('');
                window.notifyModal = null
            })
            .on('show.bs.modal', function (e) {
                const loading = $("#notify-modal-loading");
                const content = $("#notify-modal-content");
                loading.removeClass('d-none');
                content.addClass('d-none');
                setTimeout(() => {
                    if (!window.notifyModal) {
                        alertError("حدث خطأ الرجاء المحاولة لاحقا")
                        return;
                    }
                    const n = window.notifyModal;
                    $.get("{{route('getRequestInfo')}}", {request_id: n.req_id})
                        .done(r => {
                            loading.addClass('d-none');
                            content.removeClass('d-none');
                            const rows = r.rows || [];
                            let historyRows = r.historyRows || [];
                            const rowsContainer = $("#rows-container");
                            const historyContainer = $("#history-container");
                            $.each(rows, (i, e) => {
                                $(`<div class="col-12 col-md-6"><span>-${e.text}: </span><span>${e.value} </span></div>`).appendTo(rowsContainer)
                            })
                            // console.log(historyRows)
                            // historyRows = historyRows.sort( (a,b) => b.date - a.date )
                            $.each(historyRows, (i, e) => {
                                $(`<div class="col-12 mb-2"><span class="font-weight-bold">${e.date} </span><span>${e.text}: </span><span>${e.value} </span></div>`).appendTo(historyContainer)
                            })
                        })
                        .catch(e => {
                            alertError(e)
                        })
                }, 1000)
            })
    </script>
@endpush
