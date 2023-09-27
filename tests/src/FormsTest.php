<?php

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use FilamentTiptapEditor\Tests\Models\Page;
use FilamentTiptapEditor\Tests\Fixtures\Livewire;
use FilamentTiptapEditor\Tests\Resources\PageResource;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\CreatePage;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\EditPage;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Contracts\View\View;

use function FilamentTiptapEditor\Tests\livewire;

it('has editor field', function() {
    livewire(TestComponentWithForm::class)
       ->assertFormFieldExists('content');
});

it('has proper html', function() {
    $page = Page::factory()->make();

    livewire(TestComponentWithForm::class)
        ->fillForm($page->toArray())
        ->assertFormSet([
            'content' => $page->content
        ]);
});

it('creates proper html', function() {
    $page = Page::factory()->make();

    livewire(CreatePage::class)
        ->fillForm([
            'title' => $page->title,
            'content' => $page->content,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Page::class, [
        'title' => $page->title,
        'content' => $page->content,
    ]);
});

it('updates proper html', function() {
    $page = Page::factory()->create();
    $newData = Page::factory()->make();

    livewire(EditPage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newData->title,
            'content' => $newData->content,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($page->refresh())
        ->content->toBe($newData->content);
});

it('creates proper json', function() {
    $page = Page::factory()->make();

    livewire(TestComponentWithJsonForm::class)
        ->fillForm($page->toArray())
        ->assertFormSet([
            'content' => $page->content
        ])
        ->call('create');

    $this->assertDatabaseHas(Page::class, [
        'title' => $page->title,
        'content' => tiptap_converter()->asJSON($page->content),
    ]);
});

it('updates proper json', function() {
    $page = Page::factory()->json()->create();
    $newData = Page::factory()->make();

    livewire(TestComponentWithJsonForm::class)
        ->fillForm([
            'title' => $newData->title,
            'content' => $newData->content,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    ray($newData);

    expect($page->refresh())
        ->content->toBe(tiptap_converter()->asJSON($newData->content));
});

class TestComponentWithForm extends Livewire
{
    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model(Page::class)
            ->schema([
                TextInput::make('title'),
                TiptapEditor::make('content'),
            ]);
    }

    public function render(): View
    {
        return view('fixtures.form');
    }
}

class TestComponentWithJsonForm extends Livewire
{
    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model(Page::class)
            ->schema([
                TextInput::make('title'),
                TiptapEditor::make('content')
                    ->output(\FilamentTiptapEditor\Enums\TiptapOutput::Json),
            ]);
    }

    public function render(): View
    {
        return view('fixtures.form');
    }
}