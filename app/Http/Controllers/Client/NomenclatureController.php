<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Nomenclature;
use App\Models\NomenclatureTranslation;
use App\Http\Controllers\Client\BaseController;

class NomenclatureController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $names = $request->names;
        $loyalty_cards_language_ids = $request->loyalty_cards_language_ids;
        $loyalty_cards_names = $request->loyalty_cards_names;
        $takeaway_names = $request->takeaway_names;
        $search_names = $request->search_names;
        NomenClature::updateOrCreate(['id' => 1], ['label' => 'vendors']);
        NomenClature::updateOrCreate(['id' => 2], ['label' => 'Loyalty Cards']);
        NomenClature::updateOrCreate(['id' => 3], ['label' => 'Takeaway']);
        NomenClature::updateOrCreate(['id' => 4], ['label' => 'Search']);
        if (count($names) > 0) {
            $names_value_exists = [];
            foreach ($names as $name) {
                if ($name) {
                    $names_value_exists[] = $name;
                }
            }
            if (count($names_value_exists) > 0) {
                $this->validate($request, [
                    'names.0' => 'required|string',
                ]);
                NomenclatureTranslation::truncate();
                $language_ids = $request->language_ids;
                foreach ($names as $key => $name) {
                    if ($name) {
                        $nomenclature = NomenClature::where('label', 'vendors')->first();
                        NomenclatureTranslation::updateOrCreate(['language_id' => $language_ids[$key], 'nomenclature_id' => $nomenclature->id], ['name' => $name]);
                        $nomenclature_translation =  new NomenclatureTranslation();
                    }
                }
            } else {
                NomenclatureTranslation::where('nomenclature_id', 1)->delete();
            }
        }
        if (count($loyalty_cards_names) > 0) {
            $value_exists = [];
            foreach ($loyalty_cards_names as $loyalty_cards_name) {
                if ($loyalty_cards_name) {
                    $value_exists[] = $loyalty_cards_name;
                }
            }
            if (count($value_exists) > 0) {
                $this->validate($request, [
                    'loyalty_cards_names.0' => 'required|string',
                ]);
                foreach ($loyalty_cards_names as $ke => $loyalty_cards_name) {
                    if ($loyalty_cards_name) {
                        $nomenclature = NomenClature::where('label', 'Loyalty Cards')->first();
                        NomenclatureTranslation::updateOrCreate(['language_id' => $loyalty_cards_language_ids[$ke], 'nomenclature_id' => $nomenclature->id], ['name' => $loyalty_cards_name]);
                    }
                }
            } else {
                NomenclatureTranslation::where('nomenclature_id', 2)->delete();
            }
        }
        if (count($takeaway_names) > 0) {
            $names_value_exists = [];
            foreach ($takeaway_names as $name) {
                if ($name) {
                    $names_value_exists[] = $name;
                }
            }
            if (count($names_value_exists) > 0) {
                $this->validate($request, [
                    'takeaway_names.0' => 'required|string',
                ]);
                $takeaway_language_ids = $request->takeaway_language_ids;
                foreach ($takeaway_names as $takeaway_key => $takeaway_name) {
                    if ($takeaway_name) {
                        $nomenclature = NomenClature::where('label', 'Takeaway')->first();
                        NomenclatureTranslation::updateOrCreate(['language_id' => $takeaway_language_ids[$takeaway_key], 'nomenclature_id' => $nomenclature->id], ['name' => $takeaway_name]);
                    }
                }
            } else {
                NomenclatureTranslation::where('nomenclature_id', 3)->delete();
            }
        }
        if (count($search_names) > 0) {
            $names_value_exists = [];
            foreach ($search_names as $name) {
                if ($name) {
                    $names_value_exists[] = $name;
                }
            }
            if (count($names_value_exists) > 0) {
                $this->validate($request, [
                    'search_names.0' => 'required|string',
                ]);
                $search_language_ids = $request->search_language_ids;
                foreach ($search_names as $takeaway_key => $search_name) {
                    if ($search_name) {
                        $nomenclature = NomenClature::where('label', 'Search')->first();
                        NomenclatureTranslation::updateOrCreate(['language_id' => $search_language_ids[$takeaway_key], 'nomenclature_id' => $nomenclature->id], ['name' => $search_name]);
                    }
                }
            } else {
                NomenclatureTranslation::where('nomenclature_id', 4)->delete();
            }
        }
        return redirect()->route('configure.customize')->with('success', 'Nomenclature Saved Successfully!');
    }
}
