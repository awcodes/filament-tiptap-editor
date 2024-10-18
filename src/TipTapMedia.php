<?php

namespace FilamentTiptapEditor;

use FilamentTiptapEditor\TiptapEditor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TipTapMedia
{


    public static function getTipTapEditorDefaultArguments(): array
    {
        return [
            'src' => '',
            'alt' => '',
            'title' => '',
            'width' => '',
            'height' => '',
            'lazy' => null,
        ];
    }


    /**
     * @param Model|TiptapEditor|null $component
     * @return string
     */
    public static function mediaCollection(null|Model|TiptapEditor $component = null): string
    {
        if ($component instanceof TiptapEditor)
        {
            return Str::afterLast($component->getModel(), '\\') . 'TipTapMedia';
        }elseif($component instanceof Model)
        {
            return Str::afterLast(get_class($component), '\\') . 'TipTapMedia';
        }else{
            return 'TipTapMedia';
        }

    }


    /**
     * @param Model $record
     * @param array $columns
     * @return void
     */
    public static function OnCreated(Model $record,array $columns): void
    {
        foreach ($columns as $column)
        {
            preg_match_all('@<img.*src="([^"]*)"[^>/]*/?>@Ui', $record->{$column}, $allPreviousMatchedImages);
            $images = $allPreviousMatchedImages[1];

            foreach ($images as $image)
            {
                $cleanImagePath = Storage::path('public'.Str::remove(config('app.url').'/storage',$image));
                $spatieMedia = $record->addMedia($cleanImagePath)->toMediaCollection(self::mediaCollection($record));
                $newUrl = $spatieMedia->getUrl();
                // Update Content
                $record->{$column} = Str::replace($image,$newUrl,$record->{$column});

            }
        }
        $record->save();
    }


    public static function OnSaved(Model $record,array $columns): void
    {
        $record->load([
            'media' => fn($query) => $query->where('collection_name',self::mediaCollection($record))
        ]);

        $spatieMediaList = $record->media->mapWithKeys(function ($media) {
            return [$media->uuid => $media->getUrl()];
        })->toArray();

        foreach ($columns as $column)
        {
            preg_match_all('@<img.*src="([^"]*)"[^>/]*/?>@Ui', $record->{$column}, $allPreviousMatchedImages);
            $images = $allPreviousMatchedImages[1];
            $deletable = array_diff($spatieMediaList,$images);
            if (!empty($deletable))
            {
                $deletableSpatieRecords = $record->media->whereIn('uuid',array_keys($deletable));
                $deletableSpatieRecords->each->delete();
            }
        }
    }


}
