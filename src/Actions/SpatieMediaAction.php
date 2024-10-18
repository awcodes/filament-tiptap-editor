<?php

namespace FilamentTiptapEditor\Actions;

use FilamentTiptapEditor\Traits\HasMediaActionFormSchema;
use FilamentTiptapEditor\Traits\HasMediaActionSupport;
use FilamentTiptapEditor\TipTapMedia;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use FilamentTiptapEditor\TiptapEditor;

/**
 * Class SpatieMediaAction
 *
 * Handles media-related actions within the Tiptap editor using Spatie's media handling.
 * This action enables users to insert media such as images or videos into the editor.
 */
class SpatieMediaAction extends Action
{
    use HasMediaActionSupport, HasMediaActionFormSchema;

    /**
     * Constant defining the column layout for the form schema.
     */
    public const FORM_COLUMN = 5;

    /**
     * Provides the default action name.
     *
     * @return string|null The default name of the action.
     */
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_media';
    }

    /**
     * Set up the action with view, arguments, and other form settings.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->view('asdf') // View to be rendered, should be replaced with the actual view path
            ->arguments(TipTapMedia::getTipTapEditorDefaultArguments()) // Default arguments for TipTap editor
            ->modalWidth('fit') // Modal width setting
            ->slideOver() // Modal behavior
            ->form(fn (TiptapEditor $component, ComponentContainer $form) => $this->getFormSchema($component, $form)) // Form schema definition
            ->mountUsing(fn (TiptapEditor $component, ComponentContainer $form, array $arguments) => $this->getMountWith($component, $form, $arguments)) // Mount form with provided arguments
            ->modalHeading(fn (array $arguments) => 'Media Manager') // Modal heading definition
            ->action(fn(TiptapEditor $component, array $data) => $this->handleTipTapMediaAction($component, $data)); // Action handling for media insertion
    }

    /**
     * Handles the action of inserting media into the editor.
     *
     * @param TiptapEditor $component The editor component instance.
     * @param array $data The media data collected from the form.
     */
    protected function handleTipTapMediaAction(TiptapEditor $component, array $data): void
    {
        // Clean the source URL before saving
        $source = $this->getCleanSourceOnSave($data);

        // Dispatch the media insertion event to the Livewire component
        $component->getLivewire()->dispatch(
            event: 'insertFromAction',
            type: 'media',
            statePath: $component->getStatePath(),
            media: [
                'src' => $source, // Source URL of the media
                'alt' => $data['alt'] ?? null, // Alt text for the media
                'title' => $data['title'], // Title for the media
                'width' => $data['width'], // Width of the media
                'height' => $data['height'], // Height of the media
                'lazy' => $data['lazy'] ?? false, // Lazy loading flag
                'link_text' => $data['link_text'] ?? null, // Link text for the media, if applicable
            ]
        );
    }
}
