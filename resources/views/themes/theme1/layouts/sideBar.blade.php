@php
    $all_reqs_count = $all_reqs_count ?? 0;
    $received_reqs_count = $received_reqs_count ?? 0;
    $com_reqs_count = $com_reqs_count ?? 0;
    $prepay_reqs_count = $prepay_reqs_count ??  0;
    $mor_pur_reqs_count = $mor_pur_reqs_count ??  0;
    $agent_received_reqs_count = $agent_received_reqs_count ?? 0;
    $follow_reqs_count = $follow_reqs_count ?? 0;
    $star_reqs_count = $star_reqs_count ?? 0;
    $arch_reqs_count = $arch_reqs_count ?? 0;
    $pending_request_count = $pending_request_count ?? 0;
    $need_action_request_count = $need_action_request_count ?? 0;
    $sent_task_count = $sent_task_count ?? 0;
    $received_task_count = $received_task_count ?? 0;
    $completed_task_count = $completed_task_count ?? 0;
    $calculator_suggests = $calculator_suggests ?? 0;
    $onlineUsers = $onlineUsers ?? [];
    $freesCount = $freesCount ?? 0;

    // list
    $sidebar_list = [];
    if (auth()->user()->role == 13) {
        $sidebar_list[] = [
            'title' => 'طلبات الوساطة',
            'url' => route('V2.ExternalCustomer.requestes-of-wasata'),
            'icon' => ""
        ];
        $sidebar_list[] = [
            'title' => @lang('global.customers'),
            'url' => '#',
            'children' => [
                [
                    'title' => @lang('global.customers_list'),
                    'url' => route('V2.ExternalCustomer.index'),
                ],
                [
                    'title' => @lang('global.archived_customers'),
                    'url' => route('V2.ExternalCustomer.Archive.index'),
                ]
            ]
        ];
        $sidebar_list[] = [
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Reminders'),
            'url' => route('reminders'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M432 304c0 114.9-93.1 208-208 208S16 418.9 16 304c0-104 76.3-190.2 176-205.5V64h-28c-6.6 0-12-5.4-12-12V12c0-6.6 5.4-12 12-12h120c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-28v34.5c37.5 5.8 71.7 21.6 99.7 44.6l27.5-27.5c4.7-4.7 12.3-4.7 17 0l28.3 28.3c4.7 4.7 4.7 12.3 0 17l-29.4 29.4-.6.6C419.7 223.3 432 262.2 432 304zm-176 36V188.5c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12V340c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12z"/></svg>',
            'children' => [],
        ];
        // $sidebar_list[] = [
        //     'title' => ,
        //     'url' => ,
        //     'children' => [
        //         [
        //             'title' => ,
        //             'url' => ,
        //         ],
        //     ],
        // ];
    }
    if (auth()->user()->role == '0' || auth()->user()->role == '1' || auth()->user()->role == '2' || auth()->user()->role == '3' || auth()->user()->role == '4' || auth()->user()->role == '5' || auth()->user()->role == '7' || auth()->user()->role == '11'|| auth()->user()->role == '6' ) {
        if (auth()->user()->role == '0') {
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('agent.myRequests'),
            ];
        }elseif(auth()->user()->role == '1'){
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('sales.manager.myRequests'),
                        'req_count' => $all_reqs_count
            ];
        }elseif(auth()->user()->role == '2'){
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('funding.manager.myRequests'),
                        'req_count' => $all_reqs_count
            ];
        }elseif(auth()->user()->role == '3'){
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('mortgage.manager.myRequests'),
                        'req_count' => $all_reqs_count
            ];
        }elseif(auth()->user()->role == '4'){
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('general.manager.myRequests'),
                        'req_count' => $all_reqs_count
            ];
        }elseif(auth()->user()->role == '5'){
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('quality.manager.myRequests'),
                        'req_count' => $all_reqs_count
            ];
        }elseif(auth()->user()->role == '11'){
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('training.myRequests'),
                        'req_count' => $all_reqs_count
            ];
        }elseif(auth()->user()->role == '7'){
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('admin.myRequests'),
                        'req_count' => $all_reqs_count
            ];
        }elseif(auth()->user()->role == '6'){
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'All Requests'),
                        'url' => route('proper.requests'),
                        'req_count' => $all_reqs_count
            ];
            $children[] = [
                        'title' => "الطلبات النشطة",
                        'url' => route('proper.actives'),
                        'req_count' => $received_reqs_count
            ];
            $children[] = [
                        'title' => MyHelpers::admin_trans(auth()->user()->id,'Archived Requests'),
                        'url' => route('proper.archives'),
                        'req_count' => $arch_reqs_count
            ];
        }
        // recieved
        if (auth()->user()->role == '0'){
            $children[] = [
                'url' => route('agent.recivedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') ,
                'count' => $received_reqs_count,
                ];
        }elseif (auth()->user()->role == '1'){
            $children[] = [
                'url' => route('sales.manager.recivedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') ,
                'count' => $received_reqs_count,
                ];
        }elseif (auth()->user()->role == '2'){
            $children[] = [
                'url' => route('funding.manager.recivedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') ,
                'count' => $received_reqs_count,
                ];
        }elseif (auth()->user()->role == '3'){
            $children[] = [
                'url' => route('mortgage.manager.recivedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') ,
                'count' => $received_reqs_count,
                ];
        }elseif (auth()->user()->role == '4'){
            $children[] = [
                'url' => route('general.manager.recivedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') ,
                'count' => $received_reqs_count,
                ];
        }elseif (auth()->user()->role == '5'){
            $children[] = [
                'url' => route('quality.manager.recivedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') ,
                'count' => $received_reqs_count,
                ];
        }elseif (auth()->user()->role == '11'){
            $children[] = [
                'url' => route('training.recivedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') ,
                'count' => $received_reqs_count,
                ];
        }elseif (auth()->user()->role == '7'){
            $children[] = [
                'url' => route('admin.agentRecivedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') ,
                'count' => $agent_received_reqs_count,
                ];
        }
        // follow requests
        if (auth()->user()->role == '0'){
                $children[] = [
                'url' => route('agent.followedRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Following Requests'),
                'count' => $follow_reqs_count,
                ];
        }elseif (auth()->user()->role == '11'){
                $children[] = [
                'url' => route('training.followedRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Following Requests'),
                'count' => $follow_reqs_count,
                ];
        }elseif (auth()->user()->role == '5'){
                $children[] = [
                'url' => route('quality.manager.followRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Following Requests'),
                'count' => $follow_reqs_count,
                ];
        }elseif (auth()->user()->role == '7'){
                $children[] = [
                'url' => route('admin.followedRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Following Requests'),
                'count' => $follow_reqs_count,
                ];
        }
        // STAR REQUEST
        if (auth()->user()->role == '0'){
            $children[] = [
                'url' => route('agent.staredRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Stared Requests'),
                'count' => $star_reqs_count,
            ];
        }elseif (auth()->user()->role == '11'){
            $children[] = [
                'url' => route('training.staredRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Stared Requests'),
                'count' => $star_reqs_count,
            ];
        }elseif (auth()->user()->role == '7'){
            $children[] = [
                'url' => route('admin.staredRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Stared Requests'),
                'count' => $star_reqs_count,
            ];
        }
        // {{--ARCH REQUEST--}}

        if (auth()->user()->role == '0')
        {
            $children[] = [
                'url' => route('agent.archRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Archived Requests'),
                'count' => $arch_reqs_count
            ];
        }elseif (auth()->user()->role == '1')
        {
            $children[] = [
                'url' => route('sales.manager.archRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Following Requests'),
                'count' => $arch_reqs_count
            ];
        }elseif (auth()->user()->role == '2')
        {
            $children[] = [
                'url' => route('funding.manager.archRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Following Requests'),
                'count' => $arch_reqs_count
            ];
        }elseif (auth()->user()->role == '3')
        {
            $children[] = [
                'url' => route('mortgage.manager.archRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Archived Requests'),
                'count' => $arch_reqs_count
            ];
        }elseif (auth()->user()->role == '4')
        {
            $children[] = [
                'url' => route('general.manager.archRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Archived Requests'),
                'count' => $arch_reqs_count
            ];
        }elseif (auth()->user()->role == '5')
        {
            $children[] = [
                'url' => route('quality.manager.archRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Archived Requests'),
                'count' => $arch_reqs_count
            ];
        }elseif (auth()->user()->role == '11')
        {
            $children[] = [
                'url' => route('training.archRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Archived Requests'),
                'count' => $arch_reqs_count
            ];
        }elseif (auth()->user()->role == '7')
        {
            $children[] = [
                'url' => route('admin.archRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Archived Requests'),
                'count' => $arch_reqs_count
            ];
        }
        // {{--COMPLETE REQUEST--}}

        if (auth()->user()->role == '0'){
            $children[] = [
                'url' => route('agent.completedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Completed Requests'),
                'count' => $com_reqs_count
            ];
        }elseif (auth()->user()->role == '1'){
            $children[] = [
                'url' => route('sales.manager.completedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Completed Requests'),
                'count' => $com_reqs_count
            ];
        }elseif (auth()->user()->role == '2'){
            $children[] = [
                'url' => route('funding.manager.completedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Completed Requests'),
                'count' => $com_reqs_count
            ];
        }elseif (auth()->user()->role == '3'){
            $children[] = [
                'url' => route('mortgage.manager.completedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Completed Requests'),
                'count' => $com_reqs_count
            ];
        }elseif (auth()->user()->role == '4'){
            $children[] = [
                'url' => route('general.manager.completedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Completed Requests'),
                'count' => $com_reqs_count
            ];
        }elseif (auth()->user()->role == '5'){
            $children[] = [
                'url' => route('quality.manager.completedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Completed Requests'),
                'count' => $com_reqs_count
            ];
        }elseif (auth()->user()->role == '11'){
            $children[] = [
                'url' => route('training.completedRequests'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Completed Requests'),
                'count' => $com_reqs_count
            ];
        }

        if (auth()->user()->role == '0'){
            $children[] = [
            'url' => route('agent.PrepaymentReq'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'pur-pre'),
            'count' => $prepay_reqs_count
            ];
        }elseif (auth()->user()->role == '1'){
            $children[] = [
            'url' => route('sales.manager.PrepaymentReq'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'pur-pre'),
            'count' => $prepay_reqs_count
            ];
        }elseif (auth()->user()->role == '2'){
            $children[] = [
            'url' => route('funding.manager.PrepaymentReq'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'pur-pre'),
            'count' => $prepay_reqs_count
            ];
        }elseif (auth()->user()->role == '3'){
            $children[] = [
            'url' => route('mortgage.manager.PrepaymentReq'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'pur-pre'),
            'count' => $prepay_reqs_count
            ];
        }elseif (auth()->user()->role == '4'){
            $children[] = [
            'url' => route('general.manager.PrepaymentReq'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'pur-pre'),
            'count' => $prepay_reqs_count
            ];
        }
        if (auth()->user()->role == '0'){
            $children[] = [
            'url' => route('agent.morPurRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests'),
            'count' => $mor_pur_reqs_count
            ];
        }elseif (auth()->user()->role == '1'){
            $children[] = [
            'url' => route('sales.manager.morPurRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests'),
            'count' => $mor_pur_reqs_count
            ];
        }elseif (auth()->user()->role == '2'){
            $children[] = [
            'url' => route('funding.manager.morPurRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests'),
            'count' => $mor_pur_reqs_count
            ];
        }elseif (auth()->user()->role == '3'){
            $children[] = [
            'url' => route('mortgage.manager.morPurRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests'),
            'count' => $mor_pur_reqs_count
            ];
        }elseif (auth()->user()->role == '4'){
            $children[] = [
            'url' => route('general.manager.morPurRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests'),
            'count' => $mor_pur_reqs_count
            ];
        }


        if (auth()->user()->role == '7'){
            $children[] = [
                'url' => route('admin.PendingRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'PendingRequests'),
                'count' => $pending_request_count
                ];
        }
        if (auth()->user()->role == '0'){
            $children[] = [
                'url' => route('agent.additionalRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Additional Requests'),
                'count' => $pending_request_count
                ];
        }
        if (auth()->user()->role == '7'){
            $children[] = [
                'url' => route('admin.needActionRequestsNew'),
                'title' => " للتحويل - جديدة",
                'count' => $need_action_request_count
                ];
            $children[] = [
                'url' => route('admin.needActionRequestsDone'),
                'title' => " للتحويل - تمت معالجتها "
                ];
        }
        if (auth()->user()->role == '7'){
            $children[] = [
                'url' => route('admin.waitingReqsNew'),
                'title' => " الإنتظار - جديدة"
                ];
            $children[] = [
                'url' => route('admin.waitingReqsDone'),
                'title' => " الإنتظار - تمت معالجتها "
                ];
            $children[] = [
                'url' => route('V2.Admin.FreezeRequest.index'),
                'title' => trans_choice('choice.FreezeRequests',2),
                'count' => $freesCount
            ];
        }
        if (auth()->user()->role == '4'){
            $children[] = [
                'url' => route('general.manager.cancelRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Canceled Requests'),
                'count' => $cancel_reqs_count
            ];
        }
        if (auth()->user()->role == '1'){
            $children[] = [
                'url' => route('sales.manager.purReqs'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'requests') .' '.MyHelpers::admin_trans(auth()->user()->id,'Purchase'),
                'count' => $pur_reqs_count
            ];
            $children[] = [
                'url' => route('sales.manager.morReqs'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'requests').' '. MyHelpers::admin_trans(auth()->user()->id,'Mortgage'),
                'count' => $mor_reqs_count
            ];
        }
        if (auth()->user()->role == '2'){
            $children[] = [
                'url' => route('funding.manager.UnderProcessPage'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Financial Reports'),
                'count' => $under_reqs_count
            ];
        }elseif (auth()->user()->role == '3'){
            $children[] = [
                'url' => route('mortgage.manager.UnderProcessPage'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Mortgage Reports'),
                'count' =>  $under_reqs_count
                ];
        }
        if (auth()->user()->role == '1'){
            $children[] = [
                'url' => route('sales.manager.rejRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Rejected Requests'),
                'count' => $rej_reqs_count
            ];
        }elseif (auth()->user()->role == '2'){
            $children[] = [
                'url' => route('funding.manager.rejRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Rejected Requests'),
                'count' => $rej_reqs_count
            ];
        }elseif (auth()->user()->role == '3'){
            $children[] = [
                'url' => route('mortgage.manager.rejRequests'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Rejected Requests'),
                'count' => $rej_reqs_count
            ];
        }

        $sidebar_list[] = [
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Requests'),
            'url' => '#',
            'children' => $children
        ];
            $children = [];
    }
    if (auth()->user()->role == '1'){
        $children[] = [
            'url' => route('sales.manager.agentManager'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Sales Agents')
        ];
        $children[] = [
            'url' => route('sales.manager.dailyReq'),
            'title' => "كل الطلبات",
            'count' => $daily_reqs_count
            ];
        $children[] = [
            'url' => route('sales.manager.agentRecivedRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Recived Requests'),
            'count' => $agent_received_reqs_count
            ];
        $children[] = [
            'url' => route('sales.manager.staredRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Stared Requests'),
            'count' => $star_reqs_count
            ];
        $children[] = [
            'url' => route('sales.manager.followedRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Following Requests'),
            'count' => $follow_reqs_count
            ];
        $children[] = [
            'url' => route('sales.manager.agentCompletedRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Completed Requests'),
            'count' => $agent_com_reqs_count
            ];
        $children[] = [
            'url' => route('sales.manager.agentArchRequests'),
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Archived Requests'),
            'count' => $agent_arch_reqs_count
            ];

        $sidebar_list[] = [
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Sales Agents'),
            'url' => '#',
            'children' => $children
        ];
            $children = [];
    }
    if (auth()->user()->role == '0' || auth()->user()->role == '7' || auth()->user()->role == '5'|| auth()->user()->role == '6'){
                if (auth()->user()->role == '0'){
                    $children[] = [
                        'url' => route('agent.addCustomerWithReq'),
                        'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Add') .' ' .MyHelpers::admin_trans(auth()->user()->id,'Customer'),
                    ];
                }
                if (auth()->user()->role == '7'){
                    $children[] = [
                        'url' => route('admin.addCustomerWithReq'),
                        'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Add') .' ' .MyHelpers::admin_trans(auth()->user()->id,'Customer'),
                    ];
                    $children[] = [
                        'url' => route('admin.allCustomers'),
                        'title' =>  MyHelpers::admin_trans(auth()->user()->id,'customers_list'),
                    ];
                }
                if (auth()->user()->role == '5'){
                    $children[] = [
                        'url' => route('quality.manager.allCustomers'),
                        'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Search Customer'),
                    ];
                }
                if (auth()->user()->role == '6'){
                    $children[] = [
                        'url' => route('proper.customer.create'),
                        'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Add') .' ' .MyHelpers::admin_trans(auth()->user()->id,'Customer'),
                    ];
                }
                $sidebar_list[] = [
                    'title' => "العملاء",
                    'url' => '#',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18.574" height="16.641" viewBox="0 0 18.574 16.641">
                          <g id="Group_3168" data-name="Group 3168" transform="translate(0.5 0.5)">
                            <path
                              id="Path_30"
                              data-name="Path 30"
                              d="M14.228,591.566v-2.294a3.022,3.022,0,0,0-3.022-3.022H4.442a3.022,3.022,0,0,0-3.022,3.022v2.294"
                              transform="translate(-1.42 -575.975)"
                              fill="none"
                              stroke="#116a9d"
                              stroke-linecap="round"
                              stroke-miterlimit="10"
                              stroke-width="1"
                            ></path>
                            <circle id="Ellipse_14" data-name="Ellipse 14" cx="3.505" cy="3.505" r="3.505" transform="translate(2.899 0)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1"></circle>
                            <path
                              id="Path_31"
                              data-name="Path 31"
                              d="M23.58,566.05a3.5,3.5,0,0,1,0,7.01"
                              transform="translate(-12.308 -566.05)"
                              fill="none"
                              stroke="#116a9d"
                              stroke-linecap="round"
                              stroke-miterlimit="10"
                              stroke-width="1"
                            ></path>
                            <path
                              id="Path_32"
                              data-name="Path 32"
                              d="M30.47,585.3s3.23.727,2.747,5.8"
                              transform="translate(-15.693 -575.508)"
                              fill="none"
                              stroke="#116a9d"
                              stroke-linecap="round"
                              stroke-miterlimit="10"
                              stroke-width="1"
                            ></path>
                          </g>
                        </svg>',
                    'children' => $children
                ];
            $children = [];
    }
    if (auth()->user()->role == '7'){
        $sidebar_list[] = [
            'title' => "المستخدمين",
            'url' => route('admin.users')
        ];
    }

    if (auth()->user()->role == '7'){
        $children[] = [
            'url' => route('admin.customer_real_estate'),
            'title' =>  "طلبات العملاء",
        ];
        $children[] = [
            'url' => route('admin.collaborator_real_estate'),
            'title' =>  "المضافة-المتعاون",
        ];
        $sidebar_list[] = [
            'title' => "العقارات",
            'url' => '#',
            'children' => $children
        ];
            $children = [];
    }
    if (auth()->user()->role == '7'){
        $sidebar_list[] = [
                'title' => "ملفات الموظفين ",
                'url' => route('HumanResource.users.index'),
            ];
    }

    if (auth()->user()->role == '12' ){
        $children[] = [
            'url' => route('HumanResource.users.index'),
            'title' =>  "ملفات الموظفين",
        ];
        $children[] = [
            'url' => route('HumanResource.addUserPage'),
            'title' =>  " إضافة  مستخدم",
        ];
        $sidebar_list[] = [
            'title' => "ملفات الموظفين",
            'url' => '#',
            'children' => $children
        ];
            $children = [];
    }

    if (auth()->user()->role == '7' || auth()->user()->role == '4'){
        $sidebar_list[] = [
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Filter Engine'),
            'url' => route('filterEngine'),
        ];
    }
    if (auth()->user()->role == '3'){
        $sidebar_list[] = [
            'title' => MyHelpers::admin_trans(auth()->user()->id,'all_mortgage_applications'),
            'url' => route('mortgage.manager.allMortgageReqs'),
        ];
    }
    if (auth()->user()->role == '7'){

        $children[] = [
            'url' => route('all.notification'),
            'title' =>  "جديدة",
        ];
        $children[] = [
            'url' => route('all.notification_Done'),
            'title' =>  "تمت المعالجة",
        ];
        $sidebar_list[] = [
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Notifications'),
            'url' => '#',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M439.39 362.29c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71zM67.53 368c21.22-27.97 44.42-74.33 44.53-159.42 0-.2-.06-.38-.06-.58 0-61.86 50.14-112 112-112s112 50.14 112 112c0 .2-.06.38-.06.58.11 85.1 23.31 131.46 44.53 159.42H67.53zM224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64z"/></svg>',
            'children' => $children
        ];
            $children = [];
    }else{
        if (auth()->user()->role != '12' ){
            $sidebar_list[] = [
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Notifications'),
                'url' => route('all.notification'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M439.39 362.29c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71zM67.53 368c21.22-27.97 44.42-74.33 44.53-159.42 0-.2-.06-.38-.06-.58 0-61.86 50.14-112 112-112s112 50.14 112 112c0 .2-.06.38-.06.58.11 85.1 23.31 131.46 44.53 159.42H67.53zM224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64z"/></svg>'
            ];
        }
    }
    if(auth()->user()->role == 6){
        if(auth()->user()->allow_recived == 1 ){
            $children[] = [
                'url' => route('property.list'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'all_real_estate'),
            ];
            $sidebar_list[] = [
                'title' => MyHelpers::admin_trans(auth()->user()->id,'realEstate'),
                'url' => '#',
                'children' => $children
            ];
            $children = [];
        }else{
            $children[] = [
                'url' => route('property.list'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Real Estate'),
            ];
            $sidebar_list[] = [
                'title' => MyHelpers::admin_trans(auth()->user()->id,'realEstate'),
                'url' => '#',
                'children' => $children
            ];
            $children = [];

        }
    }
    if (auth()->user()->role != '12'  && auth()->user()->role != 13 ){
        if(auth()->user()->role != 6){
            $sidebar_list[] = [
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Messages'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M532 386.2c27.5-27.1 44-61.1 44-98.2 0-80-76.5-146.1-176.2-157.9C368.3 72.5 294.3 32 208 32 93.1 32 0 103.6 0 192c0 37 16.5 71 44 98.2-15.3 30.7-37.3 54.5-37.7 54.9-6.3 6.7-8.1 16.5-4.4 25 3.6 8.5 12 14 21.2 14 53.5 0 96.7-20.2 125.2-38.8 9.2 2.1 18.7 3.7 28.4 4.9C208.1 407.6 281.8 448 368 448c20.8 0 40.8-2.4 59.8-6.8C456.3 459.7 499.4 480 553 480c9.2 0 17.5-5.5 21.2-14 3.6-8.5 1.9-18.3-4.4-25-.4-.3-22.5-24.1-37.8-54.8zm-392.8-92.3L122.1 305c-14.1 9.1-28.5 16.3-43.1 21.4 2.7-4.7 5.4-9.7 8-14.8l15.5-31.1L77.7 256C64.2 242.6 48 220.7 48 192c0-60.7 73.3-112 160-112s160 51.3 160 112-73.3 112-160 112c-16.5 0-33-1.9-49-5.6l-19.8-4.5zM498.3 352l-24.7 24.4 15.5 31.1c2.6 5.1 5.3 10.1 8 14.8-14.6-5.1-29-12.3-43.1-21.4l-17.1-11.1-19.9 4.6c-16 3.7-32.5 5.6-49 5.6-54 0-102.2-20.1-131.3-49.7C338 339.5 416 272.9 416 192c0-3.4-.4-6.7-.7-10C479.7 196.5 528 238.8 528 288c0 28.7-16.2 50.6-29.7 64z"/></svg>',
                'url' => route('chat')
            ];
        }
        $sidebar_list[] = [
            'title' => MyHelpers::admin_trans(auth()->user()->id,'Reminders'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M432 304c0 114.9-93.1 208-208 208S16 418.9 16 304c0-104 76.3-190.2 176-205.5V64h-28c-6.6 0-12-5.4-12-12V12c0-6.6 5.4-12 12-12h120c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-28v34.5c37.5 5.8 71.7 21.6 99.7 44.6l27.5-27.5c4.7-4.7 12.3-4.7 17 0l28.3 28.3c4.7 4.7 4.7 12.3 0 17l-29.4 29.4-.6.6C419.7 223.3 432 262.2 432 304zm-176 36V188.5c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12V340c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12z"/></svg>',
            'url' => route('reminders')
        ];
        if (auth()->user()->role != '6'){
            if (auth()->user()->role == '5'){
                $children[] = [
                    'url' => route('quality.manager.sentTask'),
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id,'tasks').' ' .MyHelpers::admin_trans(auth()->user()->id,'sent'),
                    'count' => $sent_task_count
                    ];
                $children[] = [
                    'url' => route('quality.manager.mytask'),
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id,'tasks').' ' .MyHelpers::admin_trans(auth()->user()->id,'recived'),
                    'count' => $received_task_count
                    ];
                $children[] = [
                    'url' => route('quality.manager.completetask'),
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id,'completed Task'),
                    'count' => $completed_task_count
                    ];
            }else{
                $children[] = [
                    'url' => route('all.sentTask'),
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id,'tasks').' ' .MyHelpers::admin_trans(auth()->user()->id,'sent'),
                    'count' => $sent_task_count
                    ];
                $children[] = [
                    'url' => route('all.recivedtask'),
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id,'tasks').' ' .MyHelpers::admin_trans(auth()->user()->id,'recived'),
                    'count' => $received_task_count
                    ];
                $children[] = [
                    'url' => route('all.completedtask'),
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id,'completed Task'),
                    'count' => $completed_task_count
                    ];
            }
            $sidebar_list[] = [
                'title' => MyHelpers::admin_trans(auth()->user()->id,'tasks'),
                'url' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18.014" viewBox="0 0 18 18.014">
                          <g id="ticket" transform="translate(-72.188 -72)">
                            <path
                              id="Path_2758"
                              data-name="Path 2758"
                              d="M89.062,76.532a.489.489,0,0,0-.622-.059,1.956,1.956,0,0,1-2.711-2.721.5.5,0,0,0-.059-.617l-.563-.563A1.96,1.96,0,0,0,83.723,72h0a1.959,1.959,0,0,0-1.385.573l-9.582,9.611a1.96,1.96,0,0,0,0,2.765l.377.372a.494.494,0,0,0,.656.034,1.941,1.941,0,0,1,2.736,2.731.489.489,0,0,0,.029.661l.695.695a1.961,1.961,0,0,0,2.77,0h0l9.592-9.592a1.961,1.961,0,0,0,0-2.77ZM79.323,88.747a.981.981,0,0,1-1.385,0l-.4-.4a2.916,2.916,0,0,0-4-4l-.083-.083a.981.981,0,0,1,0-1.385l6.523-6.548,5.882,5.882Zm9.592-9.592-2.369,2.369-5.882-5.887,2.359-2.369a.962.962,0,0,1,.69-.289h0a.978.978,0,0,1,.69.284l.3.3a2.939,2.939,0,0,0,3.93,3.93l.279.279a.976.976,0,0,1,0,1.385Z"
                              transform="translate(0)"
                              fill="#116a9d"
                            ></path>
                            <path
                              id="Path_2759"
                              data-name="Path 2759"
                              d="M169.609,216.41l-2.373-2.374a.981.981,0,0,0-1.385,0h0l-2.315,2.315a.981.981,0,0,0,0,1.385l2.374,2.373a.981.981,0,0,0,1.385,0l2.315-2.315a.969.969,0,0,0,.024-1.375C169.629,216.41,169.624,216.4,169.609,216.41Zm-3,3-2.378-2.373,2.315-2.315h0l2.373,2.373Z"
                              transform="translate(-86.606 -134.813)"
                              fill="#116a9d"
                            ></path>
                          </g>
                        </svg>',
                'children' => $children
            ];
            $children = [];
        }
    }

    // Start From Khaled

    // one
    if (auth()->user()->role == '7' ){

        $children[] = [
            'url' => route('admin.asks.index'),
            'title' =>  "أسئلة التقييم",
        ];
        $children[] = [
            'url' => route('admin.asks.answers'),
            'title' =>  "الطلبات الملغاه",
        ];
        $sidebar_list[] = [
            'title' => 'التقييمات',
            'url' => '#',
            'children' => $children
        ];
            $children = [];
    }

    // two
    if (auth()->user()->role == '7' || auth()->user()->role == '4' || auth()->user()->role == '8'){
        if (auth()->user()->role == '7' || auth()->user()->role == '4' || (auth()->user()->role
                            == '8' && auth()->user()->accountant_type == 1)){
            $children[] = [
                'url' => route('report.wsataAccountingReport'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'to wsata'). '- مفرغة'
,
            ];
            $children[] = [
                'url' => route('report.wsataAccountingUnderReport'),
                'title' =>   MyHelpers::admin_trans(auth()->user()->id,'to wsata') .'- تحت المعالجة',
            ];
        }

        if (auth()->user()->role == '7' || auth()->user()->role == '4' || (auth()->user()->role
                            == '8' && auth()->user()->accountant_type == 0)){
            $children[] = [
                'url' => route('report.tsaheelAccountingReport'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'to tsaheel'). '- مفرغة'
,
            ];
            $children[] = [
                'url' => route('report.tsaheelAccountingUnderReport'),
                'title' =>   MyHelpers::admin_trans(auth()->user()->id,'to tsaheel') .' - تحت المعالجة'
            ];

        }

        if (auth()->user()->role == '7'){
            $children[] = [
                'url' => route('measurement_tools'),
                'title' => 'ادوات القياس'
            ];
        }
        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Reports') ,
            'url' => '#',
            'children' => $children
        ];
            $children = [];
    }

    // three
    if (auth()->user()->role == '1' || auth()->user()->role == '7' || auth()->user()->role == '4'
            ||auth()->user()->role == '0' ||auth()->user()->role == '11'){

        if (auth()->user()->role == '7' || auth()->user()->role == '4'){
            $children[] = [
                'url' => route('charts.sources'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'request_sources')
            ];
            $children[] = [
                'url' => route('charts.sources.wsata'),
                'title' => trans('language.request_sources') .'-وساطة'
            ];
            $children[] = [
                'url' => route('charts.sources.requests'),
                'title' => trans('language.request_sources') .'-معلقة'
            ];
            $children[] = [
                'url' => route('dailyPrefromenceChartR'),
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence')
            ];
            $children[] = [
                'url' => route('dailyPrefromenceChartQuality'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') .'- الجودة'
            ];
            $children[] = [
                'url' => route('websiteChartR'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Website')
            ];
            $children[] = [
                'url' => route('otaredUpdateChartR'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Otared Update')
            ];
            // $children[] = [
            //     'url' => route('requestChartR'),
            //     'title' => MyHelpers::admin_trans(auth()->user()->id,'Requests')
            // ];
            $children[] = [
                'url' => route('charts.requests.status'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Requests')
            ];
            $children[] = [
                'url' => route('movedRequestChartR'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'assigned_assignment')
            ];
            $children[] = [
                'url' => route('movedRequestWtihPostiveClass'),
                'title' => 'طلبات (مرفوع ، مكتمل)'
            ];
            $children[] = [
                'url' => route('admin.guests.index'),
                'title' => 'موقع الحاسبة'
            ];
            $children[] = [
                'url' => route('qualityTaskChartR'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Quality Task')
            ];
            $children[] = [
                'url' => route('qualityServayChartR'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Quality Servay Results')
            ];
            $children[] = [
                'url' => route('updateRequestChartR'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'req time line')
            ];
            $children[] = [
                'url' => route('finalResultChartR'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'final result')
            ];
            $children[] = [
                'url' => route('V2.Admin.report1'),
                'title' => trans('reports.report1')
            ];
            $children[] = [
                'url' => route('V2.Admin.report2'),
                'title' => trans('reports.report2')
            ];
            $children[] = [
                'url' => route('V2.Admin.report3'),
                'title' => trans('reports.report3')
            ];
            $children[] = [
                'url' => route('V2.Admin.report4'),
                'title' => trans('reports.report4')
            ];
        }elseif (auth()->user()->role == '1'){
            $children[] = [
                'url' => route('sales.manager.charts.sales.requests.status'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Requests')
            ];
            $children[] = [
                'url' => route('sales.manager.dailyPrefromenceChartRForSalesManager'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence')
            ];
        }elseif (auth()->user()->role == '0'){
            $children[] = [
                'url' => route('agent.charts.requests.status'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Requests')
            ];

        }elseif (auth()->user()->role == '5'){

        }elseif (auth()->user()->role == '11'){
            $children[] = [
                'url' => route('training.charts.requests.status'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Requests')
            ];
        }
        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Charts') ,
            'url' => '#',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
                          <g id="Analytics_2" data-name="Analytics 2" transform="translate(-1 -1)">
                            <rect
                              id="Rectangle_1796"
                              data-name="Rectangle 1796"
                              width="18"
                              height="18"
                              rx="1.5"
                              transform="translate(1.5 1.5)"
                              fill="none"
                              stroke="#116a9d"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1"
                            ></rect>
                            <rect id="Rectangle_1797" data-name="Rectangle 1797" width="2" height="12" transform="translate(9.5 4.5)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></rect>
                            <rect id="Rectangle_1798" data-name="Rectangle 1798" width="2" height="6" transform="translate(14.5 10.5)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></rect>
                            <rect id="Rectangle_1799" data-name="Rectangle 1799" width="2" height="9" transform="translate(4.5 7.5)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></rect>
                          </g>
                        </svg>',
            'children' => $children
        ];
            $children = [];

    }

    // four
    if (auth()->user()->role == '7'){
        $children[] = [
                'url' => url('admin/settings/form/askforconsultant'),
                'title' => 'طلب استشارة'
        ];
        $children[] = [
                'url' => url('admin/settings/form/askforfunding'),
                'title' => 'طلب تمويل'
        ];
        $children[] = [
                'url' => url('admin/settings/form/realEstateCalculator'),
                'title' => 'الحاسبة العقارية'
        ];
        $children[] = [
                'url' => route ('admin.requestConditionSettings'),
                'title' => 'شروط الطلبات'
        ];

        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Website Settings') ,
            'url' => '#',
            'children' => $children
        ];
        $children = [];

        $children[] = [
                'url' => route ('V2.Admin.ClassificationAlertSetting.index'),
                'title' => 'اعداد التنبيهات'
        ];

        $children[] = [
                'url' => route ('V2.Admin.Statistics.classifications'),
                'title' => trans('global.classificationsStatistics')
        ];

        $sidebar_list[] = [
            'title' =>  'التصنيفات' ,
            'url' => '#',
            'children' => $children
        ];
        $children = [];
    }

    // five
    if (auth()->user()->role == '7'){
        $children[] = [
                'url' => route ('admin.controls.index','company'),
                'title' => 'التحكم بالشركات'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','section'),
                'title' => 'التحكم بالأقسام'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','subsection'),
                'title' => 'التحكم بالأقسام الفرعية'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','nationality'),
                'title' => 'التحكم بالجنسيات'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','guaranty'),
                'title' => 'التحكم بالكفالة'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','insurances'),
                'title' => 'التحكم بالتأمينات'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','medical'),
                'title' => 'التحكم بالتأمين الطبى'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','work'),
                'title' => 'التحكم بطريقة العمل'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','identity'),
                'title' => 'التحكم بأنواع الهوية'
        ];
        $children[] = [
                'url' => route ('admin.controls.index','custody'),
                'title' => 'التحكم بالعهدة'
        ];
        $children[] = [
                'url' => route ('admin.vacancies.index'),
                'title' => 'التحكم بأنواع الأجازات'
        ];
        $children[] = [
                'url' => route ('admin.vacancies.count'),
                'title' => 'التحكم برصيد الأجازات'
        ];

        $sidebar_list[] = [
            'title' =>  'إعدادات الموارد البشرية' ,
            'url' => '#',
            'children' => $children
        ];
        $children = [];
    }

    // six
    if (auth()->user()->role == '7'){
        $children[] = [
                'url' => url('admin/settings/questions'),
                'title' => 'أسئلة التقييم'
        ];
        $children[] = [
                'url' => url('admin/settings/stutusRequest'),
                'title' => 'شروط الطلب'
        ];
        $children[] = [
                'url' => route('V2.Admin.report5'),
                'title' => trans('reports.report5')
        ];

        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Quality Settings') ,
            'url' => '#',
            'children' => $children
        ];
        $children = [];

    }

    //seven
    if (auth()->user()->role == '7'){
        $children[] = [
                'url' => route('admin.ips'),
                'title' => 'IpAddress'
        ];
        $children[] = [
                'url' => url('admin/settings/classifications'),
                'title' => 'تصنيفات الطلب'
        ];
        $children[] = [
                'url' => url('admin/settings/city'),
                'title' => 'المدن'
        ];
        $children[] = [
                'url' =>url('admin/settings/realtype'),
                'title' => 'أنواع العقار'
        ];
        $children[] = [
                'url' => url('admin/settings/agentAskRequest'),
                'title' => 'نقل الطلب'
        ];
        $children[] = [
                'url' => url('admin/settings/importExcelPage'),
                'title' => 'استيراد الطلبات'
        ];
        $children[] = [
                'url' => url('admin/settings/importExcelForTwoCloumnsPage'),
                'title' => 'استيراد الطلبات - عمودين'
        ];
        $children[] = [
                'url' => url('admin/settings/requestWithoutUpdate'),
                'title' => ' طلبات بدون تحديث'
        ];
        $children[] = [
                'url' =>url('admin/rejections'),
                'title' => 'أسباب الرفض'
        ];

        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Request Settings'),
            'url' => '#',
            'children' => $children
        ];
        $children = [];

    }

    // eight
    if(auth()->user()->role == 6){
        if(auth()->user()->allow_recived == 1 ){

            $children[] = [
                'url' => route ('property.list'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'all_real_estate')
            ];

            $sidebar_list[] = [
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'realEstate') ,
                'url' => '#',
                'children' => $children
            ];
            $children = [];

        }else {
            $children[] = [
                'url' => route ('property.list'),
                'title' => MyHelpers::admin_trans(auth()->user()->id,'Real Estate')
            ];

            $sidebar_list[] = [
                'title' =>  MyHelpers::admin_trans(auth()->user()->id,'realEstate') ,
                'url' => '#',
                'children' => $children
            ];
            $children = [];
        }
    }

    // nine
    if (auth()->user()->role == '7'){
        $children[] = [
                'url' => route ('admin.showToGuestCustomer'),
                'title' =>'إظهارها للعميل الغير مسجل'
        ];

        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Property Settings') ,
            'url' => '#',
            'children' => $children
        ];
        $children = [];
    }

    // ten
    if (auth()->user()->role == '7'){
        $children[] = [
                'url' => route ('admin.emails'),
                'title' =>'البريد الإلكتروني'
        ];
        $children[] = [
                'url' => route ('admin.announcements'),
                'title' =>'التعميمات'
        ];
        $children[] = [
                'url' => route ('admin.trainingPremtions'),
                'title' =>'صلاحيات الأكاديمي'
        ];
        $children[] = [
                'url' => route ('admin.settings.days_of_resubmit'),
                'title' =>'الأيام المتاحة لإستلام اشعارات تسجيل عميل مكرر'
        ];
        $children[] = [
                'url' => route ('admin.waiting_requests_settings'),
                'title' =>MyHelpers::admin_trans(auth()->user()->id,'Waiting Requests')
        ];

        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'System Settings') ,
            'url' => '#',
            'children' => $children
        ];
        $children = [];
    }

    // eleven
    if (auth()->user()->role == '7'){
        $children[] = [
                'url' => route ('admin.banks'),
                'title' =>'جهات التمويل'
        ];
        $children[] = [
                'url' => route ('admin.jobPositionIndex'),
                'title' =>'جهات العمل'
        ];
        $children[] = [
                'url' => route ('admin.extraFundingYearIndex'),
                'title' =>' تمديد السنوات'
        ];
        $children[] = [
                'url' => route ('admin.profitPercentageIndex'),
                'title' =>'نسب جهات التمويل'
        ];
        $children[] = [
                'url' => route ('admin.supportInstallment'),
                'title' =>'آلية اضافة قسط الدعم'
        ];

        $children[] = [
                'url' => route ('admin.availableExtended'),
                'title' =>'اتاحة احتساب برنامج ممتد'
        ];
        $children[] = [
                'url' => route ('admin.SalaryDeduction'),
                'title' =>'نسب الاستقطاع'
        ];
        $children[] = [
                'url' => route ('admin.SalaryEquation'),
                'title' =>'حساب صافي الراتب'
        ];
        $children[] = [
                'url' => route ('admin.firstBatchIndex'),
                'title' =>'نوع المنتج / نسب الدفعة الأولى'
        ];
        $children[] = [
                'url' => route ('admin.productTypeIndex'),
                'title' =>'نوع المنتج / أنواع المنتج'
        ];
        $children[] = [
                'url' => route ('admin.productTypeCheckTotalIndex'),
                'title' =>'إجمالي شيك نوع المنتج'
        ];
        $children[] = [
                'url' => route ('admin.calculatorRuleIndex'),
                'title' =>'إشتراطات الحاسبة'
        ];
        $children[] = [
                'url' => route ('admin.propertyStatusRuleIndex'),
                'title' =>'تخصيص حالات العقار'
        ];
        $children[] = [
                'url' => route ('admin.rulesWithoutTransferIndex'),
                'title' =>'شروط بدون تحويل الراتب'
        ];
        $children[] = [
                'url' => route ('admin.getCalculatorSettings'),
                'title' =>'إعدادات الحسبة'
        ];
        $children[] = [
                'url' => route ('ResultProgramsCustomize'),
                'title' =>'تخصيص نتائج البرامج'
        ];
        $children[] = [
                'url' => route ('admin.formula.results.page'),
                'title' =>'صلاحيات نتائج البرامج'
        ];
        $children[] = [
                'url' => route ('admin.scenarios.index'),
                'title' =>'إعدادت النتائج'
        ];
        $children[] = [
                'url' => url('admin/settings/difference'),
                'title' =>'تنبيهات فرق النتائج'
        ];

        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Calculator Settings') ,
            'url' => '#',
            'children' => $children
        ];
        $children = [];
    }

    // tweleve
    if (auth()->user()->role == '7'){
        $children[] = [
                'url' => route ('admin.formula.page'),
                'title' =>'صلاحيات التعديل'
        ];

        $children[] = [
                'url' => route ('admin.suggestions.index'),
                'title' =>'مقترحات المستخدمين'
        ];

        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Calculator Suggestion Settings'),
            'url' => '#',
            'children' => $children
        ];
        $children = [];
    }

    // thirteen
    if (\App\EditCalculationFormulaUser::where(['user_id' => auth()->user()->id,'type' => 1])->count() > 0){
        $children[] = [
                'url' => route ('ResultProgramsCustomize'),
                'title' =>' تخصيص نتائج البرامج'
        ];

        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Calculator Settings'),
            'url' => '#',
            'children' => $children
        ];
        $children = [];
    }

    // fourteen
    if (auth()->user()->role == '2' || auth()->user()->role == '1' || auth()->user()->role == '7'){
        $sidebar_list[] = [
            'title' =>  MyHelpers::admin_trans(auth()->user()->id,'Funding Calculater'),
            'url' => route('all.calculaterPage'),
        ];
    }

    // fifteen
    if (auth()->user()->role == '1'  && 0){
        $sidebar_list[] = [
            'title' =>  ' فريق العمل',
            'url' => route('sales.manager.staff_index'),
        ];
    }

    // sixteen
    if (\App\EditCalculationFormulaUser::where(['user_id' => auth()->user()->id,'type' => 0])->count() > 0){
        $children[] = [
                'url' => route ('all.suggestions.extraFundingYearIndex'),
                'title' =>'تمديد السنوات'
        ];

        $children[] = [
                'url' => route ('all.suggestions.profitPercentageIndex'),
                'title' =>'نسب البنوك'
        ];

        $children[] = [
                'url' => route ('all.suggestions.index'),
                'title' =>'مقترحات المستخدمين'
        ];

        $sidebar_list[] = [
            'title' =>  'اقتراحات الحاسبة',
            'url' =>'#',
            'children' => $children,
        ];
        $children = [];
    }

    // seventeen
    if(auth()->check() && auth()->user()->role == 0){
        $sidebar_list[] = [
            'title' =>  trans('global.app_chats'),
            'url' => route('V2.Agent.myChat'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="17.599" height="18.22" viewBox="0 0 17.599 18.22">
                          <g id="chat" transform="translate(-2.1 -1.2)">
                            <path
                              id="Path_2760"
                              data-name="Path 2760"
                              d="M11.565,37.775a3.256,3.256,0,0,0-2.366-1.6c-.769-.207-.739-.5-.71-.739l.059-.118a.029.029,0,0,1,.03-.03c.03-.059.089-.118.118-.177A1.807,1.807,0,0,0,8.9,34.7c.03-.059.03-.118.059-.177,0-.03.03-.059.03-.089l.089-.089.207-.237.03-.03a1.289,1.289,0,0,0,.177-.5v-.089c0-.089.03-.148.03-.237v-.177a.782.782,0,0,0-.3-.621.267.267,0,0,1,.03-.148,2.252,2.252,0,0,0,.089-.621,1.673,1.673,0,0,0-.237-.946,1.881,1.881,0,0,0-.8-.71c-.059-.03-.148-.059-.207-.089a3.218,3.218,0,0,0-1.183-.207H6.655a2.493,2.493,0,0,0-.887.177c-.03.03-.089.03-.089.059a1.023,1.023,0,0,0-.325.3l-.03.059c-.03.059-.03.059-.059.059a.394.394,0,0,1-.148.089.64.64,0,0,0-.355.325,1.889,1.889,0,0,0-.177.651,5.013,5.013,0,0,0,.03.651c0,.089.03.177.03.266v.03a.618.618,0,0,0-.325.621v.207c0,.089.03.177.03.237v.089a1.267,1.267,0,0,0,.148.473c.059.089.089.148.148.177a.784.784,0,0,0,.177.148,2.8,2.8,0,0,0,.325.739v.03l.03.03.059.089.03.03c.03.03.03.03.03.059l.03.03v.03c.03.207.089.5-.71.739a3.529,3.529,0,0,0-2.366,1.6,1.955,1.955,0,0,0-.148.769v.562a.392.392,0,0,0,.385.385h0a.392.392,0,0,0,.385-.385v-.532a1.854,1.854,0,0,1,.059-.473c.266-.621,1.183-.976,1.893-1.183q1.464-.4,1.242-1.6a.657.657,0,0,0-.148-.325l-.03-.03c0-.03-.03-.03-.03-.059L5.738,34.7a3.205,3.205,0,0,1-.237-.562.6.6,0,0,0-.237-.355l-.059-.03c-.03,0-.03-.03-.059-.059a.1.1,0,0,1-.03-.059.42.42,0,0,1-.059-.177l.059-.118c0-.059-.03-.118-.03-.177v-.089h.059l.089-.03a.462.462,0,0,0,.207-.385,1.746,1.746,0,0,0-.059-.414c0-.059-.03-.118-.03-.177a1.766,1.766,0,0,1,0-.562.636.636,0,0,1,.059-.3A1.221,1.221,0,0,0,5.738,31a.68.68,0,0,0,.207-.237l.03-.059a.231.231,0,0,1,.118-.118,2.1,2.1,0,0,1,.621-.118h.207a2.3,2.3,0,0,1,.917.177c.059.03.089.03.148.059a.821.821,0,0,1,.444.385,1.013,1.013,0,0,1,.118.532,2.589,2.589,0,0,1-.059.473,1.006,1.006,0,0,0-.03.3v.089a.505.505,0,0,0,.03.355.529.529,0,0,0,.3.266v.089a.375.375,0,0,1-.03.177.178.178,0,0,1-.03.118.25.25,0,0,1-.059.177l-.03.03-.059.03-.207.207a.879.879,0,0,0-.177.385c0,.03,0,.059-.03.059a2.748,2.748,0,0,1-.148.3c-.03.03-.03.059-.059.089l-.207.3a.557.557,0,0,0-.059.237q-.222,1.2,1.242,1.6a2.793,2.793,0,0,1,1.893,1.183,1.3,1.3,0,0,1,.059.473v.532a.373.373,0,0,0,.385.385h0a.392.392,0,0,0,.385-.385v-.562A1.135,1.135,0,0,0,11.565,37.775Zm-.237,1.568Zm8.37-.8a1.865,1.865,0,0,0-.148-.769,3.256,3.256,0,0,0-2.4-1.6c-.769-.207-.739-.5-.71-.739l.059-.148.03-.03c.03-.059.089-.118.118-.177a2.686,2.686,0,0,0,.207-.414,1.233,1.233,0,0,0,.059-.177V34.4c.03-.03.059-.059.089-.059l.03-.03.03-.03.177-.207.03-.03a1.289,1.289,0,0,0,.177-.5v-.089c0-.089.03-.148.03-.237v-.177a.782.782,0,0,0-.3-.621.267.267,0,0,1,.03-.148,2.607,2.607,0,0,0,.089-.621,1.673,1.673,0,0,0-.237-.946,1.881,1.881,0,0,0-.8-.71v-.03a.5.5,0,0,0-.148-.059,3.113,3.113,0,0,0-1.183-.207H14.67a2.06,2.06,0,0,0-.887.177c-.059.03-.089.059-.118.059a1.55,1.55,0,0,0-.325.3l-.03.089-.03.03a.029.029,0,0,1-.03.03.27.27,0,0,1-.148.089.64.64,0,0,0-.355.325,1.889,1.889,0,0,0-.177.651V32.1c0,.089.03.177.03.266v.03a.618.618,0,0,0-.325.621v.177a.912.912,0,0,0,.03.266v.089a2.216,2.216,0,0,0,.148.473c.059.059.089.148.148.177a.784.784,0,0,0,.177.148,2.43,2.43,0,0,0,.325.739v.03l.089.118.03.03c.03.03.03.03.03.059l.03.059c.03.207.089.5-.71.739a6.474,6.474,0,0,0-1.213.473l-.03.03a3.545,3.545,0,0,1,.5.621,5.782,5.782,0,0,1,.946-.355q1.464-.4,1.242-1.6a.657.657,0,0,0-.148-.325l-.03-.03c0-.03-.03-.03-.03-.059l-.118-.148a3.205,3.205,0,0,1-.237-.562.589.589,0,0,0-.237-.355l-.059-.03a.064.064,0,0,1-.059-.059.1.1,0,0,1-.03-.059.42.42,0,0,1-.059-.177l.089-.148c0-.059-.03-.118-.03-.177v-.089h.059l.089-.03a.4.4,0,0,0,.207-.385,1.79,1.79,0,0,0-.059-.444c0-.059-.03-.089-.03-.148a1.766,1.766,0,0,1,0-.562.742.742,0,0,1,.059-.3h0A1.99,1.99,0,0,0,13.724,31a1.293,1.293,0,0,0,.207-.237l.03-.059a.231.231,0,0,1,.118-.118,1.75,1.75,0,0,1,.592-.118h.207a2.3,2.3,0,0,1,.917.177c.059.03.089.03.148.059a.941.941,0,0,1,.444.385,1.013,1.013,0,0,1,.118.532,2.589,2.589,0,0,1-.059.473.819.819,0,0,0-.03.266v.089a.505.505,0,0,0,.03.355.529.529,0,0,0,.3.266v.089a.375.375,0,0,1-.03.177l-.03.118a.269.269,0,0,1-.089.207l-.03.089-.177.177a.842.842,0,0,0-.177.414c0,.03,0,.03-.03.059a1.363,1.363,0,0,1-.148.3c0,.03-.03.059-.059.089h-.03l-.089.148-.059.089v.059a.557.557,0,0,0-.059.237q-.222,1.2,1.242,1.6A2.793,2.793,0,0,1,18.87,38.1a1.34,1.34,0,0,1,.089.473v.532a.413.413,0,0,0,.089.266.321.321,0,0,0,.266.118h0a.373.373,0,0,0,.385-.385v-.118C19.7,38.6,19.7,38.544,19.7,38.544Z"
                              transform="translate(0 -20.07)"
                              fill="#116a9d"
                            ></path>
                            <path
                              id="Path_2761"
                              data-name="Path 2761"
                              d="M10.973,9.008a.467.467,0,0,1-.473-.473V3.359A2.168,2.168,0,0,1,12.659,1.2h.03c1.183.03,1.923,0,2.78,0h1.508a2.184,2.184,0,0,1,1.331.444,2.135,2.135,0,0,1,.858,1.627A4.13,4.13,0,0,1,18.9,5.1a3.371,3.371,0,0,1-.532.887A3.54,3.54,0,0,1,16.5,7.263a3.207,3.207,0,0,1-1.065.148H12.748L11.683,8.476l-.355.355A.416.416,0,0,1,10.973,9.008Zm1.686-7.069a1.444,1.444,0,0,0-1.449,1.42V7.973l1.035-1.035.03-.03a.584.584,0,0,1,.414-.177h2.751a3.849,3.849,0,0,0,.887-.118,3.017,3.017,0,0,0,1.479-1.035,2.622,2.622,0,0,0,.414-.739A4.216,4.216,0,0,0,18.427,3.3a1.346,1.346,0,0,0-.562-1.094,1.4,1.4,0,0,0-.887-.3H15.5c-.917.03-1.656.03-2.839.03Z"
                              transform="translate(-5.916)"
                              fill="#116a9d"
                            ></path>
                            <path
                              id="Path_2762"
                              data-name="Path 2762"
                              d="M41.568,3.511V8.6a.424.424,0,0,1-.148.325.445.445,0,0,1-.325.118.62.62,0,0,1-.325-.118l-.562-.562c-.3-.3-.562-.562-.769-.739H36.421a1.109,1.109,0,0,1-.325-.03l-.3-.03a5.069,5.069,0,0,0,.739-.651h2.928a.584.584,0,0,1,.414.177l.03.03.562.562.266.266.089.059v-4.5A1.3,1.3,0,0,0,39.5,2.21H37.279a2.04,2.04,0,0,0-.532-.71h2.78A2.035,2.035,0,0,1,41.568,3.511Z"
                              transform="translate(-23.732 -0.211)"
                              fill="#116a9d"
                            ></path>
                          </g>
                        </svg>'
        ];
    }

    // eightteen
    if(auth()->user()->role != '12' && auth()->user()->role != 13 ){
        $sidebar_list[] = [
            'title' =>  'سجل التعميمات',
            'url' => route('all.announcements'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="17.599" height="17.468" viewBox="0 0 17.599 17.468">
                          <g id="_Group_" data-name=" Group " transform="translate(-38 -39.966)">
                            <path
                              id="_Compound_Path_"
                              data-name=" Compound Path "
                              d="M53.721,39.966a1.884,1.884,0,0,0-1.864,1.639c-.741.56-4.107,2.964-7.288,2.964H40a2.006,2.006,0,0,0-2,2.008v3.132a2,2,0,0,0,1.99,2h.268l1.309,4.272a2,2,0,1,0,3.843-1.1,13.524,13.524,0,0,1-.432-3.144c3.185.182,6.2,2.409,6.882,2.944a1.876,1.876,0,0,0,3.738-.24V41.85a1.88,1.88,0,0,0-1.876-1.884Zm-9.546,10.94H40.987v-5.53h3.189Zm-5.368-1.2V46.584A1.2,1.2,0,0,1,40,45.376h.184v5.53H40a1.189,1.189,0,0,1-1.2-1.181Zm5.066,6.822a1.183,1.183,0,0,1-.932-.061,1.2,1.2,0,0,1-.6-.722L41.1,51.713h3.069a14.457,14.457,0,0,0,.46,3.375,1.2,1.2,0,0,1-.758,1.443Zm1.109-5.6V45.362a12.081,12.081,0,0,0,5.044-1.622,19.53,19.53,0,0,0,1.818-1.127V53.661A17.7,17.7,0,0,0,50.1,52.554,12.316,12.316,0,0,0,44.983,50.93Zm9.808,3.513a1.07,1.07,0,1,1-2.139,0V41.849a1.07,1.07,0,0,1,2.139,0Z"
                              transform="translate(0)"
                              fill="#116a9d"
                            ></path>
                          </g>
                        </svg>'
        ];
    }


    if(auth()->user()->role == 7  ){
        $sidebar_list[] = [
            'title' =>  'إعدادات الرسائل الترحيبيبة',
            'url' => route('admin.welcomeMessage'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"/></svg>'
        ];
    }

    if(auth()->user() && in_array(auth()->user()->role,  [0,1,2,3,5,6])){
        $sidebar_list[] = [
            'title' =>  'الدعم الفني',
            'url' => '',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="17.599" height="17.599" viewBox="0 0 17.599 17.599">
                          <path
                            id="support"
                            d="M20.746,12.41v-.463a6.947,6.947,0,1,0-13.894,0v.463A1.852,1.852,0,0,0,5,14.262v2.779a1.852,1.852,0,0,0,1.852,1.852h.083a1.389,1.389,0,0,0,2.7-.463V12.873a1.389,1.389,0,0,0-1.852-1.311,6.021,6.021,0,0,1,12,0,1.389,1.389,0,0,0-1.815,1.311v5.557a1.389,1.389,0,0,0,1.389,1.389h.153a3.242,3.242,0,0,1-2.932,1.852H15.189v-.463A1.389,1.389,0,1,0,13.8,22.6h2.779a4.168,4.168,0,0,0,4.14-3.7h.028A1.852,1.852,0,0,0,22.6,17.041V14.262A1.852,1.852,0,0,0,20.746,12.41ZM5.926,17.041V14.262a.926.926,0,0,1,.926-.926v4.631A.926.926,0,0,1,5.926,17.041ZM8.242,12.41a.463.463,0,0,1,.463.463v5.557a.463.463,0,1,1-.926,0V12.873A.463.463,0,0,1,8.242,12.41ZM13.8,21.672a.463.463,0,1,1,.463-.463v.463Zm5.557-2.779a.463.463,0,0,1-.463-.463V12.873a.463.463,0,0,1,.926,0v5.557A.463.463,0,0,1,19.357,18.894Zm2.316-1.852a.926.926,0,0,1-.926.926V13.336a.926.926,0,0,1,.926.926Z"
                            transform="translate(-5 -5)"
                            fill="#116a9d"
                          ></path>
                        </svg>'
        ];
    }
    // End From Khaled


    // *************************************
@endphp
<div class="main-aside-menu-wrapper">
    <div class="main-aside-menu scroll">
      <ul class="main-menu__nav">
          @foreach ($sidebar_list as $item)
          <li class="main-menu__item">
                @if (isset($item['children']) && sizeof($item['children']) > 0)
                <a class="main-menu__link menu-toggle" href="javascript:;">
                    <span class="main-menu__link-icon">
                        @if (isset($item['icon']))
                            {!! $item['icon'] !!}
                        @else
                        <svg id="Group_3186" data-name="Group 3186" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <g id="Rectangle_1795" data-name="Rectangle 1795" fill="#fff" stroke="#116a9d" stroke-width="1">
                                <rect width="18" height="18" rx="2" stroke="none"></rect>
                                <rect x="0.5" y="0.5" width="17" height="17" rx="1.5" fill="none"></rect>
                            </g>
                            <g id="Group_3185" data-name="Group 3185" transform="translate(3.18 4.5)">
                                <line id="Line_4" data-name="Line 4" x2="11.64" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                                <line id="Line_5" data-name="Line 5" x2="11.64" transform="translate(0 3)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                                <line id="Line_6" data-name="Line 6" x2="11.64" transform="translate(0 6)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                                <line id="Line_7" data-name="Line 7" x2="11.64" transform="translate(0 9)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                            </g>
                        </svg>

                        @endif
                    </span>
                    <span class="main-menu__link-text">{{$item['title']}} </span>
                    <span class="main-menu__ver-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="6.291" height="11.003" viewBox="0 0 6.291 11.003">
                        <path
                            id="Icon_ionic-ios-arrow-back"
                            data-name="Icon ionic-ios-arrow-back"
                            d="M1.9,5.5,6.06,1.34A.786.786,0,0,0,4.946.23L.229,4.943A.785.785,0,0,0,.206,6.028l4.737,4.746a.786.786,0,0,0,1.114-1.11Z"
                            transform="translate(0)"
                            fill="#2c2c2c"
                        ></path>
                        </svg>
                    </span>
                    </a>
                    <div class="menu-submenu">
                    <ul class="menu-subnav">
                        @foreach ($item['children'] as $sub_i)
                        <li class="main-menu__item">
                            <a class="main-menu__link" href="{{$sub_i['url']}}">
                            <div class="main-menu__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                                <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                                </svg>
                            </div>
                            <span class="main-menu__link-text">{{$sub_i['title']}}</span>
                            </a>
                        </li>

                        @endforeach

                    </ul>
                    </div>
                @else
                <a class="main-menu__link" href="{{$item['url']}}">
                    <span class="main-menu__link-icon">
                        @if (isset($item['icon']))
                            {!! $item['icon'] !!}
                        @else
                        <svg id="Group_3186" data-name="Group 3186" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <g id="Rectangle_1795" data-name="Rectangle 1795" fill="#fff" stroke="#116a9d" stroke-width="1">
                                <rect width="18" height="18" rx="2" stroke="none"></rect>
                                <rect x="0.5" y="0.5" width="17" height="17" rx="1.5" fill="none"></rect>
                            </g>
                            <g id="Group_3185" data-name="Group 3185" transform="translate(3.18 4.5)">
                                <line id="Line_4" data-name="Line 4" x2="11.64" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                                <line id="Line_5" data-name="Line 5" x2="11.64" transform="translate(0 3)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                                <line id="Line_6" data-name="Line 6" x2="11.64" transform="translate(0 6)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                                <line id="Line_7" data-name="Line 7" x2="11.64" transform="translate(0 9)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                            </g>
                        </svg>

                        @endif
                    </span>
                    <span class="main-menu__link-text">{{$item['title']}}</span>

                    </a>
                @endif
            </li>
            @endforeach


      </ul>
    </div>
  </div>

{{-- *************************************************************************** --}}
@if (0)
<ul class="list-unstyled">



{{-- ***************************** --}}


    @if (auth()->user()->role == '7' )
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span><i class="fas fa-question-circle" style="color: #55552b;font-size:large;"></i></span>
                    التقييمات </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('admin.asks.index') }}">أسئلة التقييم
                    </a>

                    <a class="dropdown-item" href="{{ route('admin.asks.answers') }}"> الطلبات الملغاه
                    </a>
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '7' || auth()->user()->role == '4' || auth()->user()->role == '8')
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-chart-line" style="color: #4d0026;font-size:large;"></i> </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Reports') }} </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                    @if (auth()->user()->role == '7' || auth()->user()->role == '4' || (auth()->user()->role
                    == '8' && auth()->user()->accountant_type == 1))
                        <a class="dropdown-item" href="{{ route('report.wsataAccountingReport') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'to wsata') }} - مفرغة
                        </a>
                        <a class="dropdown-item" href="{{ route('report.wsataAccountingUnderReport') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'to wsata') }} - تحت المعالجة
                        </a>
                    @endif
                    @if (auth()->user()->role == '7' || auth()->user()->role == '4' || (auth()->user()->role
                    == '8' && auth()->user()->accountant_type == 0))
                        <a class="dropdown-item" href="{{ route('report.tsaheelAccountingReport') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'to tsaheel') }} - مفرغة
                        </a>
                        <a class="dropdown-item" href="{{ route('report.tsaheelAccountingUnderReport') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'to tsaheel') }} - تحت المعالجة
                        </a>
                    @endif

                    @if (auth()->user()->role == '7')

                    <a class="dropdown-item" href="{{ route('measurement_tools') }}">
                        ادوات القياس
                    </a>
                    @endif
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '1' || auth()->user()->role == '7' || auth()->user()->role == '4'
        ||auth()->user()->role == '0' ||auth()->user()->role == '11')
            {{--||auth()->user()->role == '5'--}}
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-chart-bar" style="color:  #4d9900;font-size:large;"></i></span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @if (auth()->user()->role == '7' || auth()->user()->role == '4')

                        <a class="dropdown-item" href="{{ route('charts.sources') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'request_sources') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('charts.sources.wsata') }}">
                            @lang('language.request_sources') -
                            وساطة
                        </a>
                        <a class="dropdown-item" href="{{ route('charts.sources.requests') }}">
                            @lang('language.request_sources') -
                            معلقة
                        </a>
                        <a class="dropdown-item" href="{{ route('dailyPrefromenceChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('dailyPrefromenceChartQuality') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }} - الجودة
                        </a>
                        <a class="dropdown-item" href="{{ route('websiteChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Website') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('otaredUpdateChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Otared Update') }}
                        </a>
                       {{-- <a class="dropdown-item" href="{{ route('requestChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                        </a>--}}

                        <a class="dropdown-item" href="{{ route('charts.requests.status') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                        </a>

                        <a class="dropdown-item" href="{{ route('movedRequestChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'assigned_assignment') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('movedRequestWtihPostiveClass') }}">
                            طلبات (مرفوع ، مكتمل)
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.guests.index') }}">
                            موقع الحاسبة
                        </a>
                        <a class="dropdown-item" href="{{ route('qualityTaskChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Task') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('qualityServayChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Servay Results') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('updateRequestChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'req time line') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('finalResultChartR') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'final result') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('V2.Admin.report1') }}">
                            @lang('reports.report1')
                        </a>
                        <a class="dropdown-item" href="{{ route('V2.Admin.report2') }}">
                            @lang('reports.report2')
                        </a>
                        <a class="dropdown-item" href="{{ route('V2.Admin.report3') }}">
                            @lang('reports.report3')
                        </a>
                        <a class="dropdown-item" href="{{ route('V2.Admin.report4') }}">
                            @lang('reports.report4')
                        </a>
                    @elseif (auth()->user()->role == '1')
                        {{--<a class="dropdown-item"
                           href="{{ route('sales.manager.requestChartRForSalesManager') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                        </a>--}}

                        <a class="dropdown-item" href="{{ route('sales.manager.charts.sales.requests.status') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                        </a>
                        <a class="dropdown-item"
                           href="{{ route('sales.manager.dailyPrefromenceChartRForSalesManager') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
                        </a>


                    @elseif (auth()->user()->role == '0')
                       {{-- <a class="dropdown-item" href="{{ route('agent.requestChartRForAgent') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                        </a>--}}
                       <a class="dropdown-item" href="{{ route('agent.charts.requests.status') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                        </a>
                    @elseif (auth()->user()->role == '5')
                    {{--    <a class="dropdown-item"
                           href="{{ route('quality.manager.dailyPrefromenceChartQuality') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
                        </a>--}}
                    @elseif (auth()->user()->role == '11')
                        <a class="dropdown-item" href="{{ route('training.charts.requests.status') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                        </a>

                        {{--<a class="dropdown-item" href="{{ route('training.requestChartRForTraining') }}">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                        </a>--}}
                    @endif
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '7')
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-cog" style="color:darkgrey;font-size:large;"></i> </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Website Settings') }} </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ url('admin/settings/form/askforconsultant') }}">
                        طلب استشارة
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/form/askforfunding') }}">
                        طلب تمويل
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/form/realEstateCalculator') }}">
                        الحاسبة العقارية
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.requestConditionSettings') }}">
                        شروط الطلبات
                    </a>
                </div>
            </div>
        </li>

        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-cog" style="color:darkgrey;font-size:large;"></i> </span>
                    التصنيفات
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('V2.Admin.ClassificationAlertSetting.index') }}">
                        اعداد التنبيهات
                    </a>
                    <a class="dropdown-item" href="{{ route('V2.Admin.Statistics.classifications') }}">
                        @lang('global.classificationsStatistics')
                    </a>
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '7')
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-cog" style="color:darkgrey;font-size:large;"></i> </span>إعدادات
                    الموارد البشرية </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','company') }}">
                        التحكم بالشركات
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','section') }}">
                        التحكم بالأقسام
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','subsection') }}">
                        التحكم بالأقسام الفرعية
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','nationality') }}">
                        التحكم بالجنسيات
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','guaranty') }}">
                        التحكم بالكفالة
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','insurances') }}">
                        التحكم بالتأمينات
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','medical') }}">
                        التحكم بالتأمين الطبى
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','work') }}">
                        التحكم بطريقة العمل
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','identity') }}">
                        التحكم بأنواع الهوية
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.controls.index','custody') }}">
                        التحكم بالعهدة
                    </a>
                    <hr>
                    <a class="dropdown-item" href="{{ route ('admin.vacancies.index') }}">
                        التحكم بأنواع الأجازات
                    </a>
                    <a class="dropdown-item" href="{{ route ('admin.vacancies.count') }}">
                        التحكم برصيد الأجازات
                    </a>
                </div>
            </div>
        </li>
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-user-cog" style="color:coral;font-size:large;"></i> </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Account Settings') }} </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ url('admin/settings/form/customerReq') }}">
                        محتوى طلب العميل
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.helpDeskPage') }}">
                        الدعم الفني
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.rates') }}">
                        تقييمات الخدمة</a>
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '7')
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-certificate"
                              style="color:palevioletred;font-size:large;"></i></span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Settings') }} </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ url('admin/settings/questions') }}">
                        أسئلة التقييم
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/stutusRequest') }}">
                        شروط الطلب
                    </a>
                    <a class="dropdown-item" href="{{ route('V2.Admin.report5') }}">
                        @lang('reports.report5')
                    </a>
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '7')
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-cogs" style="color:slateblue;font-size:large;"></i> </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Request Settings') }}</a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('admin.ips') }}">
                        IpAddress
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/classifications') }}">
                        تصنيفات الطلب
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/city') }}">
                        المدن
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/realtype') }}">
                        أنواع العقار
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/agentAskRequest') }}">
                        نقل الطلب
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/importExcelPage') }}">
                        استيراد الطلبات
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/importExcelForTwoCloumnsPage') }}">
                        استيراد الطلبات - عمودين
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/requestWithoutUpdate') }}">
                        طلبات بدون تحديث
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/rejections') }}">
                        أسباب الرفض
                    </a>
                </div>
            </div>
        </li>
    @endif

    {{--            @if(auth()->user()->role == '10')--}}


    @if (auth()->user()->role == '7')
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-city" style="color:steelblue;"></i></span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Property Settings') }}</a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('admin.showToGuestCustomer')}}">
                        إظهارها للعميل <br> الغير مسجل
                    </a>
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '7')
        <li class="dropdown">
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-wrench" style="color: black;"></i></span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'System Settings') }}</a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('admin.emails')}}">
                        البريد الإلكتروني
                    </a>
                    <a class="dropdown-item" href="{{route('admin.announcements')}}">
                        التعميمات
                    </a>
                    <a class="dropdown-item" href="{{route('admin.trainingPremtions')}}">
                        صلاحيات الأكاديمي
                    </a>
                    <a class="dropdown-item" href="{{route('admin.settings.days_of_resubmit')}}">
                        الأيام المتاحة لإستلام <br>اشعارات تسجيل عميل مكرر
                    </a>
                    <a class="dropdown-item" href="{{route('admin.waiting_requests_settings')}}">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Waiting Requests') }}
                    </a>
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '7')
        <li>
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-calculator" style="color:orangered;"></i></span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Calculator Settings') }}</a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('admin.banks')}}" style="font-size:small;">
                        جهات التمويل
                    </a>
                    <a class="dropdown-item" href="{{route('admin.jobPositionIndex')}}"
                       style="font-size:small;">
                        جهات العمل
                    </a>
                    <a class="dropdown-item" href="{{route('admin.extraFundingYearIndex')}}"
                       style="font-size:small;">
                        تمديد السنوات
                    </a>
                    <a class="dropdown-item" href="{{route('admin.profitPercentageIndex')}}"
                       style="font-size:small;">
                        نسب جهات التمويل
                    </a>
                    <a class="dropdown-item" href="{{route('admin.supportInstallment')}}"
                       style="font-size:small;">
                        آلية اضافة قسط الدعم
                    </a>
                    <a class="dropdown-item" href="{{route('admin.availableExtended')}}"
                       style="font-size:small;">
                        اتاحة احتساب برنامج ممتد
                    </a>
                    <a class="dropdown-item" href="{{route('admin.SalaryDeduction')}}" style="font-size:small;">
                        نسب الاستقطاع
                    </a>
                    <a class="dropdown-item" href="{{route('admin.SalaryEquation')}}" style="font-size:small;">
                        حساب صافي الراتب
                    </a>
                    <a class="dropdown-item" href="{{route('admin.firstBatchIndex')}}" style="font-size:small;">
                        نوع المنتج / نسب الدفعة الأولى
                    </a>

                    <a class="dropdown-item" href="{{route('admin.productTypeIndex')}}"
                       style="font-size:small;">
                        نوع المنتج / أنواع المنتج
                    </a>

                    <a class="dropdown-item" href="{{route('admin.productTypeCheckTotalIndex')}}"
                       style="font-size:small;">
                        إجمالي شيك نوع المنتج
                    </a>
                    <a class="dropdown-item" href="{{route('admin.calculatorRuleIndex')}}"
                       style="font-size:small;">
                        إشتراطات الحاسبة
                    </a>
                    <a class="dropdown-item" href="{{route('admin.propertyStatusRuleIndex')}}"
                       style="font-size:small;">
                        تخصيص حالات العقار
                    </a>
                    <a class="dropdown-item" href="{{route('admin.rulesWithoutTransferIndex')}}"
                       style="font-size:small;">
                        شروط بدون تحويل الراتب
                    </a>

                    <a class="dropdown-item" href="{{route('admin.getCalculatorSettings')}}"
                       style="font-size:small;">
                        إعدادات الحسبة
                    </a>
                    <a class="dropdown-item" href="{{route('ResultProgramsCustomize')}}"
                       style="font-size:small;">
                        تخصيص نتائج البرامج
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.formula.results.page') }}"
                       style="font-size:small;">
                        صلاحيات نتائج البرامج
                    </a>
                    <a class="dropdown-item" href="{{route('admin.scenarios.index')}}" style="font-size:small;">
                        إعدادت النتائج
                    </a>
                    <a class="dropdown-item" href="{{ url('admin/settings/difference') }}"
                       style="font-size:small;">
                        تنبيهات فرق النتائج
                    </a>

                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '7')
        <li>
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-cog" style="color:crimson;"></i></span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Calculator Suggestion Settings') }}  @if ($calculator_suggests != 0)
                        <span class="Red3"
                              style="  color:white;width: 30px;height: 30px;background: #F6F6F6;text-align: center;border-radius: 50px;display: inline-block;line-height: 30px;margin-right: 8px;transition: .3s;"> {{$calculator_suggests}}</span>
                    @endif</a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('admin.formula.page')}}">
                        صلاحيات التعديل
                    </a>
                    <a class="dropdown-item" href="{{route('admin.suggestions.index')}}">
                        مقترحات المستخدمين
                    </a>
                </div>
            </div>
        </li>
    @endif

    @if(\App\EditCalculationFormulaUser::where(['user_id' => auth()->user()->id,'type' => 1])->count() > 0)
        <li>
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-calculator" style="color:orangered;"></i></span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Calculator Settings') }}</a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('ResultProgramsCustomize')}}">
                        تخصيص نتائج البرامج
                    </a>
                </div>
            </div>
        </li>
    @endif

    @if (auth()->user()->role == '2' || auth()->user()->role == '1' || auth()->user()->role == '7')
        <li>
            <a href="{{ route('all.calculaterPage') }}"> <span> <i class="fas fa-calculator"
                                                                   style="color:darkcyan;font-size:large;"></i> </span>
                {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Calculater') }} </a>
        </li>
    @endif

        @if (auth()->user()->role == '1'  && 0)
            <li>
                <a href="{{ route('sales.manager.staff_index') }}"> <span> <i class="fas fa-users"
                                                                       style="color:darkcyan;font-size:large;"></i> </span>
                    فريق العمل</a>
            </li>
        @endif

    @if(\App\EditCalculationFormulaUser::where(['user_id' => auth()->user()->id,'type' => 0])->count() > 0)
        <li>
            <div>
                <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fas fa-calculator" style="color:#0b2e13;"></i></span>
                    اقتراحات الحاسبة @if ($calculator_suggests != 0)
                        <span class="Red3"
                              style="  color:white;width: 32px;height: 32px;background: #F6F6F6;text-align: center;border-radius: 50px;display: inline-block;line-height: 30px;margin-right: 8px;transition: .3s;"> {{$calculator_suggests}}</span>
                    @endif
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('all.suggestions.extraFundingYearIndex')}}">
                        تمديد السنوات
                    </a>
                    <a class="dropdown-item" href="{{route('all.suggestions.profitPercentageIndex')}}">
                        نسب البنوك
                    </a>
                    <a class="dropdown-item" href="{{route('all.suggestions.index')}}">
                        مقترحات المستخدمين
                    </a>
                </div>
            </div>
        </li>
    @endif

    @if(auth()->check() && auth()->user()->role == 0)
        <li>
            <a href="{{ route('V2.Agent.myChat') }}">
                <span> <i class="fas fa-comment-alt" style="color:#333;font-size:large;"></i></span>
                {!! __("global.app_chats") !!}
            </a>
        </li>
    @endif

    {{--For Hr Prevent--}}
    @if (auth()->user()->role != '12' && auth()->user()->role != 13 )
        <li>
            <a href="{{ route('all.announcements') }}">
                <span> <i class="fas fa-history" style="color:#333;font-size:large;"></i></span>
                سجل التعميمات </a>
        </li>
    @endif

    @if(auth()->user()->role == 7 )
        <li>
            <a href="{{ route('admin.welcomeMessage') }}">
                <span> <i class="fas fa-send" style="color:#333;font-size:large;"></i></span>
                إعدادات الرسائل الترحيبيبة </a>
        </li>
        @endif

        @if (auth()->user() && in_array(auth()->user()->role,  [0,1,2,3,5,6]))
        <li>
            <a href="javascript:void();"  data-toggle="modal" data-target="#exampleModal">
                <span> <i class="fa fa-comments" style="color:#333;font-size:large;"></i></span>
                الدعم الفني</a>
        </li>
    @endif

</ul>
@endif

