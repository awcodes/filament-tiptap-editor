<?php

namespace FilamentTiptapEditor;

use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TipTapMedia
{
    /**
     * Get the default arguments for the TipTap editor.
     *
     * @return array
     */
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
     * Get the media collection name based on the model or editor.
     *
     * @param Model|TiptapEditor|null $component
     * @return string
     */
    public static function mediaCollection(null|Model|TiptapEditor $component = null): string
    {
        if ($component instanceof TiptapEditor) {
            return Str::afterLast($component->getModel(), '\\') . 'TipTapMedia';
        } elseif ($component instanceof Model) {
            return Str::afterLast(get_class($component), '\\') . 'TipTapMedia';
        } else {
            return 'TipTapMedia';
        }
    }

    /**
     * Handle media creation and update image URLs in the given columns.
     *
     * @param Model $record The Eloquent model instance.
     * @param array $columns The columns to check for images.
     * @return void
     */
    public static function OnCreated(Model $record, array $columns): void
    {
        foreach ($columns as $column) {
            // Find all images in the content
            preg_match_all('@<img.*src="([^"]*)"[^>/]*/?>@Ui', $record->{$column}, $allPreviousMatchedImages);
            $images = $allPreviousMatchedImages[1];

            foreach ($images as $image) {
                $cleanImagePath = Storage::path('public' . Str::remove(config('app.url') . '/storage', $image));
                // Add media to the collection
                $spatieMedia = $record->addMedia($cleanImagePath)->toMediaCollection(self::mediaCollection($record));
                $newUrl = $spatieMedia->getUrl();
                // Update the content with the new media URL
                $record->{$column} = Str::replace($image, $newUrl, $record->{$column});
            }
        }
        $record->save();
    }

    /**
     * Handle media updates after saving the record.
     *
     * @param Model $record The Eloquent model instance.
     * @param array $columns The columns to check for images.
     * @return void
     */
    public static function OnSaved(Model $record, array $columns): void
    {
        $record->load([
            'media' => fn ($query) => $query->where('collection_name', self::mediaCollection($record)),
        ]);

        // Create a map of UUIDs to media URLs
        $spatieMediaList = $record->media->mapWithKeys(function ($media) {
            return [$media->uuid => $media->getUrl()];
        })->toArray();

        foreach ($columns as $column) {
            // Find all images in the content
            preg_match_all('@<img.*src="([^"]*)"[^>/]*/?>@Ui', $record->{$column}, $allPreviousMatchedImages);
            $images = $allPreviousMatchedImages[1];

            // Determine the deletable media (not present in the content anymore)
            $deletable = array_diff($spatieMediaList, $images);
            if (!empty($deletable)) {
                $deletableSpatieRecords = $record->media->whereIn('uuid', array_keys($deletable));
                $deletableSpatieRecords->each->delete();
            }
        }
    }
}
