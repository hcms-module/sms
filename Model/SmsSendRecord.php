<?php

declare (strict_types=1);

namespace App\Application\Sms\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int            $record_id
 * @property string         $sms_platform_id
 * @property string         $phone
 * @property string         $template
 * @property string         $content
 * @property string         $param_data
 * @property string         $result_status
 * @property string         $result_info
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SmsSendRecord extends Model
{
    protected string $primaryKey = 'record_id';
    /**
     * The table associated with the model.
     *
     * @var ?string
     */
    protected ?string $table = 'sms_send_record';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = ['record_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}