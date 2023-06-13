<?php

namespace App\Adm\Services;

use App\Models\AdmTranslation;
use Illuminate\Support\Str;

class TranslationService
{
    /**
     * @param string $model
     * @param $record
     * @param array $data
     * @return void
     */
    public static function translateModel(string $model, $record, array $data): void
    {
        $modelRecords = $model::query()->whereIn('id', $data['model_records_id'])->get();
        $unique = $modelRecords->unique('lang')->values()->all();
        $hash = Str::uuid();

        AdmTranslation::query()
            ->where('model_type', $model)
            ->whereIn('model_id', $data['model_records_id'])
            ->delete();

        foreach($unique as $item)
        {
            AdmTranslation::query()->create([
                'model_type' => $model,
                'model_id' => $item->id,
                'lang' => $item->lang,
                'hash' => $hash,
            ]);
        }
    }

    /**
     * @param string $model
     * @param $record
     * @return array
     */
    public static function getAllTranslationList(string $model, $record): array
    {
        $allItems = $model::query()->whereNot('lang', $record->lang)->get();
        $connectedItems = AdmTranslation::query()
            ->where('model_type', $model)
            ->whereIn('model_id', $allItems->pluck('id'))->get();
        $items = [];

        foreach ($allItems as $item) {
            if($record->lang == $item->lang) {
                continue;
            }
            $items[$item->id] = $item->lang .' | '.$item->title;
        }

        return $items;
    }
}
