<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @method static Builder|self betweenDate(Carbon|string $startDate, Carbon|string|null $endDate = null)
 * @method static Builder|self ofUser(int|string|Model $user)
 * @method static Builder|self byReceived(int|string|Model $user)
 * @method static Builder|self transferred(bool $switch = false)
 * @method static Builder|self forForReport1()
 */
class RequestHistory extends BaseModel
{
    /**
     * Move request `title` column
     */
    const MOVE_TO_FREEZE = 'move_to_freeze';

    /**
     * Move request `title` column
     */
    const MOVE_FROM_FREEZE = 'move_from_freeze';

    /**
     * Move request const of history title
     */
    const TITLE_MOVE_REQUEST = 'نقل الطلب';


    /**
     * Move request const of need to be turned request basket
     */
    const NEED_TO_TURNED_QUALITY = 'سلة التحويل(الجودة)';


    /**
     * Move request const of history title
     */
    const TITLE_MOVE_REQUEST_QUALITY = 'إضافة الطلب إلى الجودة';


    /**
     * Move request to archive basket
     */
    const TITLE_MOVE_REQUEST_TO_ARCHIVED_BASKET = 'نقل الطلب إلى سلة الأرشيف';

    /**
     * Move request to archive basket
     */
    const NEGATIVE_CLASS_QUALITY = 'تصنيف سلبي للجودة';


    /**
     * Move request auto content
     */
    const CONTENT_AUTO_MOVE = 'نقل تلقائي بواسطة النظام';

    /**
     * Move request to Quality auto content
     */
    const CONTENT_AUTO_MOVE_QUALITY = 'نقل تلقائي الى الجودة';

    /**
     * Mark the request as completed
     */
    const MARK_AS_COMPLETED = 'نقل تلقائي إلى سلة المكتملة';

    /**
     * DELETE QUALITY REQ AUTO
     */
    const DELETE_QUALITY_REQUEST = 'حذف تلقائي لطلب الجودة';

    /**
     * Mark the request as completed
     */
    const UPDATE_QUALITY_REQUEST = 'تحديث طلب الجودة';

    /**
     * Automatic move request to freeze
     * @var string
     */
    const CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO = 'سلة التجميد - تعذر الاتصال - تلقائي';

    /**
     * Automatic move request to freeze
     * @var string
     */
    const CONTENT_FROZEN_POSTPONED_COMMUNICATE_AUTO = 'سلة التجميد - أجل التواصل - تلقائي';

    /**
     * The customer is back after not connected
     * @var string
     */
    const CONTENT_SEND_MESSAGE_UNABLE_TO_COMMUNICATE = 'تعذر الاتصال - تم التواصل بواسطة العميل';

    /**
     * Customer is back from frozen
     * @var string
     */
    const CONTENT_FROZEN_NEW_BACK = 'سلة التجميد - تم اضافة الطلب بواسطة العميل';

    /**
     * Agent take frozen request
     * @var string
     */
    const CONTENT_AGENT_TAKE_FROZEN_REQUEST = 'سحب الطلب من التجميد';

    /**
     * Note: محتوى اطلب عملاء
     */
    const CONTENT_AGENT_ASK_REQUEST = 'اطلب عملاء';

    /**
     * Note: سلة التحويل خاصية اطلب عملاء
     */
    const CONTENT_ASK_REQUEST_TRANSFER_BASKET = 'ask_request_transfer_basket';
    /**
     * Note: سحب الطلب بواسطة الاستشاري، الطلب مؤرشف
     */
    const CONTENT_ARCHIVED_BASKET = 'سلة الأرشيف';

    /**
     * Note: سحب الطلب بواسطة الاستشاري، الاستشاري مؤرشف
     */
    const CONTENT_ARCHIVED_AGENT = 'استشاري مؤرشف';

    const CONTENT_EXISTED_IN_NEED_ACTION = 'سلة طلبات بحاجة للتحويل';

    const CONTENT_PENDING_REQUESTS = 'الطلبات المعلقة';
    /**
     * Note: نقل الطلب بواسطة الادارة من سلة التحويل جديدة
     */
    const CONTENT_ADMIN_TRANS_BASKET = 'admin_trans_basket';

    use BelongsToRequest;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'req_id',
        'user_id',
        'recive_id',
        'user_switch_id',
        'title',
        'content',
        'class_id_agent',
        'history_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'history_date' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class, 'req_id')->withDefault();
    }

    /**
     * Get history between dates
     *
     * @param  Builder  $builder
     * @param $startDate
     * @param  null  $endDate
     * @return Builder
     */
    public function scopeBetweenDate(Builder $builder, $startDate, $endDate = null): Builder
    {
        $startDate = Carbon::make($startDate);
        $endDate = $endDate ?: $startDate;
        $endDate = Carbon::make($endDate);
        $startDate->hours(0)->minutes(0)->seconds(0);
        $endDate->hours(23)->minutes(59)->seconds(59);
        return $builder->where(function (Builder $builder) use ($startDate, $endDate) {
            return $builder->whereBetween('history_date', [$startDate, $endDate]);
        });
    }

    /**
     * Get history of user 'user_id,recive_id'
     *
     * @param  Builder  $builder
     * @param $user
     * @return Builder
     */
    public function scopeOfUser(Builder $builder, $user): Builder
    {
        $user instanceof Model && ($user = $user->id);
        return $builder->where(function (Builder $builder) use ($user) {
            return $builder->where('user_id', $user)->orWhere('recive_id', $user);
        });
    }

    /**
     * Get history of user 'user_id'
     *
     * @param  Builder  $builder
     * @param $user
     * @return Builder
     */
    public function scopeByUser(Builder $builder, $user): Builder
    {
        $user instanceof Model && ($user = $user->id);
        return $builder->where(function (Builder $builder) use ($user) {
            return $builder->where('user_id', $user);
        });
    }

    /**
     * Get history of user 'recive_id'
     *
     * @param  Builder  $builder
     * @param $user
     * @return Builder
     */
    public function scopeByReceived(Builder $builder, $user): Builder
    {
        $user instanceof Model && ($user = $user->id);
        return $builder->where(function (Builder $builder) use ($user) {
            return $builder->where('recive_id', $user);
        });
    }

    /**
     * Only history of transferred
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeTransferred(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            return $builder->where('title', 'نقل الطلب');
        });
    }

    /**
     * Only history of transferred for report1
     *
     * @param  Builder|RequestHistory  $builder
     * @return Builder
     */
    public function scopeForReport1(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            return $builder->where(function (Builder $builder) {
                return $builder->transferred()->where(function (Builder $builder) {
                    return $builder->where('content', 'مدير النظام')->orWhere('content', 'اطلب عملاء');
                });
            });
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recive_id')->withDefault();
    }

    public function userSwitch(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_switch_id')->withDefault();
    }

}
