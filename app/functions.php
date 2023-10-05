<?php
/**
 * @Author: Ahmed Fayez
 **/

if (!function_exists('page_index_title')) {
    /**
     * Get Page Index title
     *
     * @param $var
     *
     * @return string
     */
    function page_index_title($var)
    {
        $text = __("replace.".(is_trashed_index() ? "trashed_" : "")."index", [
            "name" => trans_choice("choice.$var", 2),
        ]);
        return !is_string($text) ? "" : $text;
    }
}
if (!function_exists('page_create_title')) {
    /**
     * Get Create Page title
     *
     * @param  string  $var
     *
     * @return string
     */
    function page_create_title($var)
    {
        $name = trans_choice("choice.$var", 1);
        $isAr = app()->isLocale('ar');
        $addChar = false;
        $words = collect(explode(" ", $name));

        if ($isAr) {
            $words = $words->map(function ($word) {
                return starts_with($word, "ال") ? str_after($word, "ال") : $word;
            });
            $addChar = \Illuminate\Support\Str::endsWith(trim($words->first()), "ة");
            $name = $words->implode(' ');
        }

        $text = __("replace.add", ["name" => $name]);
        $addChar && ($text .= "ة");
        return !is_string($text) ? "" : $text;
    }
}
if (!function_exists('page_update_title')) {
    /**
     * Get update Page Title
     *
     * @param  string  $var
     * @param  null  $extraName
     *
     * @return string
     */
    function page_update_title($var, $extraName = null)
    {
        $name = trans_choice("choice.$var", 1);
        $text = __("replace.update", ["name" => $name]);
        if (!is_null(trim($extraName)) && strlen($extraName) > 0) {
            $text .= " - {$extraName}";
        }
        return !is_string($text) ? "" : $text;
    }
}
if (!function_exists('page_show_title')) {
    /**
     * Get show Page Title
     *
     * @param  string  $var
     * @param  null  $extraName
     *
     * @return string
     */
    function page_show_title($var, $extraName = null)
    {
        $name = trans_choice("choice.$var", 1);
        $text = __("replace.details", ["name" => $name]);
        if (!is_null(trim($extraName)) && strlen($extraName) > 0) {
            $text .= " - {$extraName}";
        }
        return !is_string($text) ? "" : $text;
    }
}
if (!function_exists('is_trashed_index')) {
    /**
     * Check if route has trashed
     * @param  Request  $request
     * @param  null  $route
     * @return bool
     */
    function is_trashed_index($route = null)
    {
        if (!$route) {
            $route = request()->route()->getName();
        }
        return \Illuminate\Support\Str::endsWith($route, '.trashed');
    }
}

/** Distribution Of requests */
if (!function_exists('getKeyOfLastAgentOfDistribution')) {
    /**
     * Setting key
     * @param  bool  $pending
     * @return string
     */
    function getKeyOfLastAgentOfDistribution(bool $pending = !1): string
    {
        return "last_agent_id";
        //return "last_agent_id".($pending ? "_pending" : '');
    }
}
if (!function_exists('getLastAgentOfDistribution')) {
    /**
     * Get last agent id of distribution
     * @param  bool  $pending
     * @return int
     */
    function getLastAgentOfDistribution(bool $pending = !1): int
    {
        $getNewId = fn() => \App\Models\User::forDistributionOnly()->min('id');
        $key = getKeyOfLastAgentOfDistribution($pending);
        // Get last one of storage.
        $id = (int) (setting($key) ?: 0);

        // No storage. get oldest agent id.
        if (!$id) {
            return $getNewId();
        }

        // Get Max ID.
        $max = \App\Models\User::forDistributionOnly()->max('id');

        // Check from last id with max ID. Then return first ID
        if ($id >= $max) {
            return (int) $getNewId();
        }

        // Get next agent.
        $next = (int) \App\Models\User::forDistributionOnly()->where('id', '>', $id)->min('id');
        if ($next > $id) {
            return $next;
        }
        else {
            return (int) $getNewId();
        }
    }
}
if (!function_exists('setLastAgentOfDistribution')) {
    /**
     * Set last agent id of distribution
     *
     * @param $id
     * @param  bool  $pending
     */
    function setLastAgentOfDistribution($id, bool $pending = !1): void
    {
        $key = getKeyOfLastAgentOfDistribution($pending);
        setting([$key => $id])->save();
    }
}

if (!function_exists('getKeyOfLastQualityOfDistribution')) {
    /**
     * Setting key
     * @return string
     */
    function getKeyOfLastQualityOfDistribution(): string
    {
        return "last_quality_id";
    }
}
if (!function_exists('getLastQualityOfDistribution')) {
    /**
     * Get last quality id of distribution
     * @return int
     */
    function getLastQualityOfDistribution(): int
    {
        $getNewId = fn() => \App\Models\User::allowedReceivedOnly()->qualitiesOnly()->min('id');
        $key = getKeyOfLastQualityOfDistribution();
        // Get last one of storage.
        $id = (int) (setting($key) ?: 0);

        // No storage. get oldest agent id.
        if (!$id) {
            return $getNewId();
        }

        // Get Max ID.
        $max = \App\Models\User::allowedReceivedOnly()->qualitiesOnly()->max('id');

        // Check from last id with max ID. Then return first ID
        if ($id >= $max) {
            return (int) $getNewId();
        }

        // Get next agent.
        $next = (int) \App\Models\User::allowedReceivedOnly()->qualitiesOnly()->where('id', '>', $id)->min('id');
        if ($next > $id) {
            return $next;
        }
        else {
            return (int) $getNewId();
        }
    }
}
if (!function_exists('setLastQualityOfDistribution')) {
    /**
     * Set last quality id of distribution
     *
     * @param $id
     */
    function setLastQualityOfDistribution($id): void
    {
        $key = getKeyOfLastQualityOfDistribution();
        setting([$key => $id])->save();
    }
}
/** Distribution Of requests */

if (!function_exists('sendSlackNotification')) {
    /**
     * Send New Slack Notification
     *
     * @param  string|null  $message
     * @param  string|null  $recipient
     */
    function sendSlackNotification(string $message, ?string $recipient = null, $toChannel = null): void
    {
        try {
            $url = config('config.slack.url');
            $notification = new \App\Notifications\SlackNotification($message, $recipient, $toChannel);
            \Illuminate\Support\Facades\Notification::route('slack', $url)->notifyNow($notification);
        }
        catch (\Exception $exception) {
            //dd($exception);
        }
    }
}

if (!function_exists('sendPushTokenNotification')) {
    /**
     * Send New Push Notification
     *
     * @param  string|string[]|null  $tokens
     * @param  string  $title
     * @param  string  $body
     * @return bool|string|void|null
     */
    function sendPushTokenNotification($tokens, string $title = '', string $body = '')
    {
        try {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $api_key = 'AAAA8F3ereU:APA91bESPMz7qdvt5xoMX0cucM_-2iBAoEP0I9SXFr0rKJrQeGcM0Zv9VWKf-eZt5lG7ntx3vkj34bF_oROPCj6b39OS-bPA6a5E6hofWBRpJSfkQEpCD8pojrHMBxVpnc5TocMXZawM';
            //$getToken = $token;
            $tokens = (array) $tokens;
            if (empty($tokens)) {
                return;
            }
            $data = [
                "registration_ids" => $tokens,
                "notification"     => [
                    "title" => $title,
                    "body"  => $body,
                    "sound" => "default",
                ],
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key='.$api_key,
                'Content-Type: Application/json',
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $result = curl_exec($ch);
            if ($result === false) {
                return $result;
                //die('CURL Failed: '.curl_error($ch));
            }
            else {
                curl_close($ch);
                //dd($result);
                return $result ? json_decode($result, !0) : $result;
            }
        }
        catch (\Exception $exception) {
            //dd($exception);
        }
    }
}

if (!function_exists('requestsStatusesPay')) {
    /**
     * Requests pay statuses
     * @param  string|mixed  $getBy
     * @return bool|mixed|string|void|null
     */
    function requestsStatusesPay($getBy = 'empty')
    {
        $s = [
            MyHelpers::admin_trans(auth()->user()->id, 'draft in funding manager'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating for sales maanger'),
            MyHelpers::admin_trans(auth()->user()->id, 'funding manager canceled'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected from sales maanger'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating for sales agent'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating for mortgage maanger'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected from mortgage maanger'),
            MyHelpers::admin_trans(auth()->user()->id, 'approve from mortgage maanger'),
            MyHelpers::admin_trans(auth()->user()->id, 'mortgage manager canceled'),
            MyHelpers::admin_trans(auth()->user()->id, 'The prepayment is completed'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected from funding manager'),
            MyHelpers::admin_trans(auth()->user()->id, 'Undefined'),
        ];
        return $getBy == 'empty' ? $s : ($s[$getBy] ?? $s[28]);
    }
}

if (!function_exists('requestsStatuses')) {
    /**
     * Requests statuses
     * @param  string|mixed  $getBy
     * @return array|mixed
     */
    function requestsStatuses($getBy = 'empty')
    {
        $s = [
            MyHelpers::admin_trans(auth()->user()->id, 'new req'),
            MyHelpers::admin_trans(auth()->user()->id, 'open req'),
            MyHelpers::admin_trans(auth()->user()->id, 'archive in sales agent req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            MyHelpers::admin_trans(auth()->user()->id, 'draft in mortgage maanger'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating sales agent req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'cancel mortgage manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            MyHelpers::admin_trans(auth()->user()->id, 'Undefined'),
            MyHelpers::admin_trans(auth()->user()->id, 'Rejected and archived'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
        ];
        return $getBy == 'empty' ? $s : ($s[$getBy] ?? $s[35]);
    }
    /*-------------------------------------------------------------
            // Collaborates
    /*-------------------------------------------------------------*/
    if (!function_exists('getNextAgentForCollaborates')) {
        function getNextAgentForCollaborates($pending = false)
        {
            $scope = 'forDistributionOnly';
            if (\App\Models\User::collaboratorUsers()->forDistributionOnly()->count() == 0){
                $scope = 'agentsOnly';
            }
            $getNewId = fn() => \App\Models\User::collaboratorUsers()->$scope()->min('id');

            $key = getKeyOfLastAgentOfDistributionCollaborator($pending);

            // Get last one of storage.
            $id = (int) (setting($key) ?: 0);
            // No storage. get oldest agent id.
            if (!$id) {
                return $getNewId();
            }

            // Get Max ID.
            $max = \App\Models\User::collaboratorUsers()->$scope()->max('id');

            // Check from last id with max ID. Then return first ID
            if ($id >= $max) {
                return (int) $getNewId();
            }

            // Get next agent.
            $next = (int) \App\Models\User::collaboratorUsers()->$scope()->where('id', '>', $id)->min('id');
            if ($next > $id) {
                return $next;
            }
            else {
                return (int) $getNewId();
            }
        }
    }

    if (!function_exists('getKeyOfLastAgentOfDistributionCollaborator')) {
        /**
         * Setting key
         * @param  bool  $pending
         * @return string
         */
        function getKeyOfLastAgentOfDistributionCollaborator(bool $pending = !1): string
        {
            return "last_agent_id_collaborator".auth()->id();
            //return "last_agent_id".($pending ? "_pending" : '');
        }
    }
    if (!function_exists('setLastAgentOfDistributionCollaborator')) {
        /**
         * Set last agent id of distribution
         *
         * @param $id
         * @param  bool  $pending
         */
        function setLastAgentOfDistributionCollaborator($id, bool $pending = !1): void
        {
            $key = getKeyOfLastAgentOfDistributionCollaborator($pending);
            setting([$key => $id])->save();
        }
    }
}

