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
     * The last submitted rule is treated as open-ended (no upper bound). Its
     * `distance_to` is therefore allowed to be missing/empty here, and the
     * after-validation hooks enforce the band-specific constraints.
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
            'rules.*.distance_to'           => 'nullable|numeric|min:0',
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
            $this->validateAscendingOrder($validator);
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

    /**
     * Validates each band individually:
     *  - non-last rows must declare a numeric distance_to strictly greater than distance_from
     *  - the last row may have an empty/null distance_to (open-ended)
     *  - time_without_rider must be greater than time_with_rider
     */
    private function validateIndividualBands(Validator $validator): void
    {
        $rules = $this->input('rules', []);
        if (!is_array($rules) || count($rules) < 1) {
            return;
        }

        $rules = array_values($rules);
        $lastIdx = count($rules) - 1;

        foreach ($rules as $idx => $row) {
            if (!is_array($row)) {
                continue;
            }

            $from = $this->numericOrNull($row['distance_from'] ?? null);
            $to = $this->numericOrNull($row['distance_to'] ?? null);
            $isLast = ($idx === $lastIdx);

            if (!$isLast) {
                if ($to === null) {
                    $validator->errors()->add(
                        'rules.' . $idx . '.distance_to',
                        __('Distance to is required for every band except the last one.')
                    );
                } elseif ($from !== null && $to <= $from) {
                    $validator->errors()->add(
                        'rules.' . $idx . '.distance_to',
                        __('Distance to must be greater than distance from.')
                    );
                }
            }

            $tw = array_key_exists('time_with_rider', $row) ? (int) $row['time_with_rider'] : null;
            $twr = array_key_exists('time_without_rider', $row) ? (int) $row['time_without_rider'] : null;
            if ($tw !== null && $twr !== null && $tw >= 1 && $twr >= 1 && $twr <= $tw) {
                $validator->errors()->add(
                    'rules.' . $idx . '.time_without_rider',
                    __('Time without rider must be greater than time with rider.')
                );
            }
        }
    }

    /**
     * Ensures the rows are submitted with strictly ascending distance_from values.
     * This makes the “last row is open-ended” contract deterministic and matches the UI.
     */
    private function validateAscendingOrder(Validator $validator): void
    {
        $rules = $this->input('rules', []);
        if (!is_array($rules) || count($rules) < 2) {
            return;
        }

        $rules = array_values($rules);
        for ($i = 1; $i < count($rules); $i++) {
            $prev = $this->numericOrNull($rules[$i - 1]['distance_from'] ?? null);
            $curr = $this->numericOrNull($rules[$i]['distance_from'] ?? null);
            if ($prev !== null && $curr !== null && $curr <= $prev) {
                $validator->errors()->add(
                    'rules',
                    __('Sort the bands by Distance from (km) ascending. Each row must start after the previous one ends.')
                );

                return;
            }
        }
    }

    /**
     * Ensures bounded bands do not overlap. The last band is open-ended; we only
     * require that its distance_from is at least as large as the previous band's distance_to.
     */
    private function validateNoBandOverlaps(Validator $validator): void
    {
        $rules = $this->input('rules', []);
        if (!is_array($rules) || count($rules) < 1) {
            return;
        }

        $rules = array_values($rules);
        $lastIdx = count($rules) - 1;

        for ($i = 0; $i < $lastIdx; $i++) {
            $isPrevLast = false;
            $prevTo = $this->numericOrNull($rules[$i]['distance_to'] ?? null);
            $nextFrom = $this->numericOrNull($rules[$i + 1]['distance_from'] ?? null);

            if ($prevTo === null || $nextFrom === null) {
                continue;
            }

            if ($prevTo > $nextFrom) {
                $validator->errors()->add(
                    'rules',
                    __('Distance bands overlap. Make sure each To (km) does not cross into the next band.')
                );

                return;
            }
        }
    }

    private function numericOrNull($value): ?float
    {
        if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
            return null;
        }
        if (!is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }
}
