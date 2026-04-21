<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DistanceSlaGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ignoreId = $this->routeIgnoreIdForUniqueName();
        $nameRule = $ignoreId !== null
            ? 'required|string|max:255|unique:distance_sla_groups,name,' . $ignoreId
            : 'required|string|max:255|unique:distance_sla_groups,name';

        return [
            'name'                          => $nameRule,
            'is_active'                     => 'nullable',
            'is_default'                    => 'nullable',
            'rules'                         => 'required|array|min:1',
            'rules.*.distance_from'         => 'required|numeric|min:0',
            'rules.*.distance_to'           => 'required|numeric',
            'rules.*.time_with_rider'       => 'required|integer|min:1',
            'rules.*.time_without_rider'    => 'required|integer|min:1',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $this->validateIndividualBands($validator);
            $this->validateNoBandOverlaps($validator);
        });
    }

    private function routeIgnoreIdForUniqueName(): ?int
    {
        $id = $this->route('distance_sla_group');
        if ($id === null || $id === '') {
            return null;
        }

        return (int) $id;
    }

    private function validateIndividualBands(Validator $validator): void
    {
        $rules = $this->input('rules', []);
        if (!is_array($rules) || count($rules) < 1) {
            return;
        }

        foreach ($rules as $idx => $row) {
            if (!is_array($row)) {
                continue;
            }

            $from = isset($row['distance_from']) ? (float) $row['distance_from'] : null;
            $to = isset($row['distance_to']) ? (float) $row['distance_to'] : null;

            if ($from !== null && $to !== null && $to <= $from) {
                $validator->errors()->add('rules.' . $idx . '.distance_to', __('Distance to must be greater than distance from.'));
            }

            $tw = array_key_exists('time_with_rider', $row) ? (int) $row['time_with_rider'] : null;
            $twr = array_key_exists('time_without_rider', $row) ? (int) $row['time_without_rider'] : null;
            if ($tw !== null && $twr !== null && $tw >= 1 && $twr >= 1 && $twr <= $tw) {
                $validator->errors()->add('rules.' . $idx . '.time_without_rider', __('Time without rider must be greater than time with rider.'));
            }
        }
    }

    private function validateNoBandOverlaps(Validator $validator): void
    {
        $rules = $this->input('rules', []);
        if (! is_array($rules) || count($rules) < 1) {
            return;
        }

        $sorted = collect($rules)->sortBy(function ($r) {
            return (float) ($r['distance_from'] ?? 0);
        })->values()->all();

        $n = count($sorted);
        for ($i = 0; $i < $n - 1; $i++) {
            $b1 = (float) ($sorted[$i]['distance_to'] ?? 0);
            $a2 = (float) ($sorted[$i + 1]['distance_from'] ?? 0);
            if ($b1 > $a2) {
                $validator->errors()->add('rules', __('Distance bands overlap. Sort rows by From (km) ascending and make sure each To (km) does not cross into the next band.'));

                return;
            }
        }
    }
}