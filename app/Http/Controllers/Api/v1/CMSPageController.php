<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\ProhibitedItem;
use App\Models\ClientLanguage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class CMSPageController extends Controller
{

    use ApiResponser;

    public function getPageList(Request $request)
    {
        $requestedLanguage = $request->header('language');
        $primaryLanguageId = ClientLanguage::where('is_primary', 1)->value('language_id');

        $languageRecord = null;
        if ($requestedLanguage !== null) {
            if (is_numeric($requestedLanguage)) {
                $languageRecord = ClientLanguage::with('language:id,sort_code')
                    ->where('language_id', $requestedLanguage)
                    ->first();
            } else {
                $languageRecord = ClientLanguage::with('language:id,sort_code')
                    ->whereHas('language', function ($query) use ($requestedLanguage) {
                        $query->where('sort_code', $requestedLanguage);
                    })
                    ->first();
            }
        }

        if (!$languageRecord && $primaryLanguageId) {
            $languageRecord = ClientLanguage::with('language:id,sort_code')
                ->where('language_id', $primaryLanguageId)
                ->first();
        }

        if (!$languageRecord) {
            $languageRecord = ClientLanguage::with('language:id,sort_code')
                ->orderByDesc('is_active')
                ->orderByDesc('is_primary')
                ->first();
        }

        $languageId = optional($languageRecord)->language_id ?? 1;

        $languagePriority = array_values(array_unique(array_filter([
            $languageId,
            ($primaryLanguageId && $primaryLanguageId !== $languageId) ? $primaryLanguageId : null,
        ])));

        $pages = Page::select('id', 'slug')
            ->orderByDesc('id')
            ->get();

        if ($pages->isEmpty()) {
            return $this->successResponse([], '', 201);
        }

        $translations = PageTranslation::whereIn('page_id', $pages->pluck('id'))
            ->where('is_published', 1)
            ->orderBy('page_id')
            ->get()
            ->groupBy('page_id');

        $response = $pages->map(function ($page) use ($translations, $languagePriority) {
            $pageTranslations = $translations->get($page->id);

            if (!$pageTranslations) {
                return null;
            }

            $selectedTranslation = null;
            foreach ($languagePriority as $preferredLanguageId) {
                $selectedTranslation = $pageTranslations->firstWhere('language_id', (int) $preferredLanguageId);
                if ($selectedTranslation) {
                    break;
                }
            }

            if (!$selectedTranslation) {
                $selectedTranslation = $pageTranslations->sortBy('id')->first();
            }

            if (!$selectedTranslation) {
                return null;
            }

            return [
                'id' => $selectedTranslation->id,
                'slug' => $page->slug,
                'title' => $selectedTranslation->title,
            ];
        })->filter()->values();

        return $this->successResponse($response, '', 201);
    }

    public function getPageDetail(Request $request)
    {
        $requestedLanguage = $request->header('language');
        $primaryLanguageId = ClientLanguage::where('is_primary', 1)->value('language_id');

        $languageRecord = null;
        if ($requestedLanguage !== null) {
            if (is_numeric($requestedLanguage)) {
                $languageRecord = ClientLanguage::with('language:id,sort_code')
                    ->where('language_id', $requestedLanguage)
                    ->first();
            } else {
                $languageRecord = ClientLanguage::with('language:id,sort_code')
                    ->whereHas('language', function ($query) use ($requestedLanguage) {
                        $query->where('sort_code', $requestedLanguage);
                    })
                    ->first();
            }
        }

        if (!$languageRecord && $primaryLanguageId) {
            $languageRecord = ClientLanguage::with('language:id,sort_code')
                ->where('language_id', $primaryLanguageId)
                ->first();
        }

        if (!$languageRecord) {
            $languageRecord = ClientLanguage::with('language:id,sort_code')
                ->orderByDesc('is_active')
                ->orderByDesc('is_primary')
                ->first();
        }

        $languageId = optional($languageRecord)->language_id ?? 1;

        $page = null;
        $translation = null;
        $pageIdentifier = $request->page_id;

        if (!$pageIdentifier && !$request->filled('slug')) {
            $pageIdentifier = 3;
        }

        if ($request->filled('slug')) {
            $page = Page::where('slug', $request->slug)->first();
        }

        if (!$page && $pageIdentifier) {
            $translation = PageTranslation::find($pageIdentifier);
            if ($translation) {
                $page = Page::find($translation->page_id);
            } else {
                $page = Page::find($pageIdentifier);
            }
        }

        if (!$page) {
            return $this->successResponse(null, '', 201);
        }

        $translation = PageTranslation::where('page_id', $page->id)
            ->where('language_id', $languageId)
            ->where('is_published', 1)
            ->first();

        if (!$translation && $primaryLanguageId && $primaryLanguageId != $languageId) {
            $translation = PageTranslation::where('page_id', $page->id)
                ->where('language_id', $primaryLanguageId)
                ->where('is_published', 1)
                ->first();
        }

        if (!$translation) {
            $translation = PageTranslation::where('page_id', $page->id)
                ->where('is_published', 1)
                ->orderBy('id')
                ->first();
        }

        if (!$translation) {
            return $this->successResponse(null, '', 201);
        }

        $response = [
            'id' => $translation->id,
            'slug' => $page->slug,
            'title' => $translation->title,
            'description' => $translation->description,
            'meta_title' => $translation->meta_title,
            'meta_keyword' => $translation->meta_keyword,
            'meta_description' => $translation->meta_description,
            'bottom_title' => $translation->bottom_title ?? null,
            'bottom_description' => $translation->bottom_description ?? null,
        ];

        return $this->successResponse($response, '', 201);
    }
    public function getProhibiteditems(Request $request)
    {
        $requestedLanguage = $request->header('language');
        $primaryLanguageId = ClientLanguage::where('is_primary', 1)->value('language_id');

        $languageRecord = null;
        if ($requestedLanguage !== null) {
            if (is_numeric($requestedLanguage)) {
                $languageRecord = ClientLanguage::with('language:id,sort_code')
                    ->where('language_id', $requestedLanguage)
                    ->first();
            } else {
                $languageRecord = ClientLanguage::with('language:id,sort_code')
                    ->whereHas('language', function ($query) use ($requestedLanguage) {
                        $query->where('sort_code', $requestedLanguage);
                    })
                    ->first();
            }
        }

        if (!$languageRecord && $primaryLanguageId) {
            $languageRecord = ClientLanguage::with('language:id,sort_code')
                ->where('language_id', $primaryLanguageId)
                ->first();
        }

        if (!$languageRecord) {
            $languageRecord = ClientLanguage::with('language:id,sort_code')
                ->orderByDesc('is_active')
                ->orderByDesc('is_primary')
                ->first();
        }

        $languageId = optional($languageRecord)->language_id ?? 1;
        $languageCode = optional(optional($languageRecord)->language)->sort_code;

        $page = Page::where('slug', 'prohibited-items')->first();
        if ($page) {
            $translation = PageTranslation::where('page_id', $page->id)
                ->where('language_id', $languageId)
                ->where('is_published', 1)
                ->first();

            if (!$translation && $primaryLanguageId && $primaryLanguageId != $languageId) {
                $translation = PageTranslation::where('page_id', $page->id)
                    ->where('language_id', $primaryLanguageId)
                    ->where('is_published', 1)
                    ->first();
            }

            if (!$translation) {
                $translation = PageTranslation::where('page_id', $page->id)
                    ->where('is_published', 1)
                    ->orderBy('id')
                    ->first();
            }

            $prohibitedItems = ProhibitedItem::select('name', 'image')
                ->where('page_id', $page->id)
                ->where('status', 1)
                ->get()
                ->map(function ($item) use ($languageCode) {
                    if ($languageCode && Lang::has($item->name, $languageCode)) {
                        $item->name = __($item->name, [], $languageCode);
                    }
                    return $item;
                });

            $response = [
                'slug' => $page->slug,
                'title' => $translation->title ?? null,
                'description' => isset($translation->description) ? strip_tags($translation->description) : null,
                'bottom_title' => $translation->bottom_title ?? null,
                'bottom_description' => $translation->bottom_description ?? null,
                'prohibited_items' => $prohibitedItems,
            ];
        } else {
            $response = null;
        }
        return $this->successResponse($response, '', 201);
    }
}
