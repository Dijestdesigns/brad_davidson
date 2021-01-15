<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;

class UserSupplement extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'row_id', 'date', 'supplement', 'upon_waking', 'at_breakfast', 'at_lunch', 'at_dinner', 'before_bed', 'user_id'
    ];

    protected $casts = [
        'row_id' => 'integer',
        'date'   => 'date'
    ];

    const PAGINATE_RECORDS = 10;

    const TOTAL_ROWS = 3;

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        for ($row = 1; $row <= self::TOTAL_ROWS; $row++) {
            $totalRows[] = $row;
        }

        $validator = Validator::make($data, [
            '*.row_id'       => ['required', 'integer', 'in:' . implode(",", $totalRows)],
            '*.date'         => ['required', 'string', 'date_format:Y-m-d'],
            '*.supplement'   => ['nullable', 'string'],
            '*.upon_waking'  => ['nullable', 'string'],
            '*.at_breakfast' => ['nullable', 'string'],
            '*.at_lunch'     => ['nullable', 'string'],
            '*.at_dinner'    => ['nullable', 'string'],
            '*.before_bed'   => ['nullable', 'string'],
            '*.user_id'      => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
        ], [
            '*.row_id.required'  => __('The row_id field is required.'),
            '*.date.required'    => __('The date field is required.'),
            '*.date.date_format' => __('The date should be in Y-m-d format.'),
            '*.user_id.required' => __('The user_id field is required.'),
            '*.user_id.exists'   => __('The user_id field is required.')
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
