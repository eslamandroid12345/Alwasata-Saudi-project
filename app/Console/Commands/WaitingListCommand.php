<?php

namespace App\Console\Commands;

use App\Traits\General;
use App\WaitingRequest;
use Illuminate\Console\Command;

class WaitingListCommand extends Command
{
    use General;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waiting:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'waiting list push notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //WaitingRequest::whereHas('request', fn(Builder $builder) => $builder->where('statusReq', '!=', 0))->delete();
        //$users = \App\Models\User::where('role', 0)
        //    ->where('allow_recived', 1)->where('ready_receive', 1)
        //    ->whereHas('requests', fn(Builder $r) => $r->whereHas('waitingRequests', fn(Builder $w) => $w->waitingList()))->pluck('id');
        //$tokens = UserToken::where('tokenable_type', User::class)->whereIn('tokenable_id', $users->toArray());
        //dd($tokens->toArray());
        $waitingRequests = WaitingRequest::waitingList()->with(['request'])->latest('message_at')->get();

        foreach ($waitingRequests as $waitingRequest) {
            $request = $waitingRequest->request;
            $users = \App\Models\User::where('role', 0)->where('id', '!=', $request->user_id)->where('allow_recived', 1)->where('ready_receive', 1)->has('pushTokens')->get();
            //dd($users->count(),$users->pluck('id'));
            foreach ($users as $user) {
                if ($user->waitingRequestNotifications()->where('request_id', $waitingRequest->request_id)->exists()) {
                    continue;
                }
                //if (!$notification) {
                //if ($user->id == 301) {
                //    dd($user, $user->getPushTokens());
                //}
                if (!empty($user->getPushTokens())) {
                    //dd(32);
                    $this->fcm_send($user->getPushTokens(), " قائمة الانتظار ", "هناك طلب جديد فى قائمة الإنتظار ");
                    $user->waitingRequestNotifications()->create(['request_id' => $waitingRequest->request_id]);
                }
                //}
            }
        }
    }
}
