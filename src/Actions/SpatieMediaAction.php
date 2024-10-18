<?php

namespace FilamentTiptapEditor\Actions;

use FilamentTiptapEditor\Traits\HasMediaActionFormSchema;
use FilamentTiptapEditor\Traits\HasMediaActionSupport;
use FilamentTiptapEditor\TipTapMedia;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use FilamentTiptapEditor\TiptapEditor;

class SpatieMediaAction extends Action
{

    use HasMediaActionSupport,HasMediaActionFormSchema;

    public const FORM_COLUMN = 5;

    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_media';
    }





    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->view('asdf')
            ->arguments(TipTapMedia::getTipTapEditorDefaultArguments())
            ->modalWidth('fit')
            ->slideOver()
            ->form(fn(TiptapEditor $component,ComponentContainer $form) => $this->getFormSchema($component,$form))
            ->mountUsing(fn (TiptapEditor $component, ComponentContainer $form, array $arguments) => $this->getMountWith($component,$form,$arguments))
            ->modalHeading(fn (array $arguments) => 'Media Manager')
            ->action(fn(TiptapEditor $component, array $data) => $this->handleTipTapMediaAction($component,$data))

        ;
    }

    protected function handleTipTapMediaAction(TiptapEditor $component, array $data): void
    {
        $source = $this->getCleanSourceOnSave($data);

        $component->getLivewire()->dispatch(
            event: 'insertFromAction',
            type: 'media',
            statePath: $component->getStatePath(),
            media: [
                'src' => $source,
                'alt' => $data['alt'] ?? null,
                'title' => $data['title'],
                'width' => $data['width'],
                'height' => $data['height'],
                'lazy' => $data['lazy'] ?? false,
                'link_text' => $data['link_text'] ?? null,
            ],
        );
    }


}