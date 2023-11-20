<?php

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\Tests\Models\Page;
use FilamentTiptapEditor\Tests\Fixtures\Livewire as LivewireFixture;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\CreatePage;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\EditPage;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Contracts\View\View;
use Livewire\Livewire;

it('has editor field', function() {
    Livewire::test(TestComponentWithForm::class)
       ->assertFormFieldExists('html_content')
       ->assertFormFieldExists('json_content')
       ->assertFormFieldExists('text_content');
});

it('creates record', function() {
    $page = Page::factory()->make();

    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => $page->title,
            'html_content' => $page->html_content,
            'json_content' => $page->json_content,
            'text_content' => $page->text_content,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Page::class, [
        'title' => $page->title,
    ]);

    $storedPage = Page::query()->where('title', $page->title)->first();

    expect($storedPage)
        ->html_content->toBe($page->html_content)
        ->json_content->toBe($page->json_content);
});

it('updates record', function() {
    $page = Page::factory()->create();
    $newData = Page::factory()->make();

    Livewire::test(EditPage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newData->title,
            'html_content' => $newData->html_content,
            'json_content' => $newData->json_content,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Page::class, [
        'title' => $newData->title,
    ]);

    $storedPage = Page::query()->where('id', $page->id)->first();

    expect($storedPage)
        ->html_content->toBe($newData->html_content)
        ->json_content->toBe($newData->json_content);
});

class TestComponentWithForm extends LivewireFixture
{
    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model(Page::class)
            ->schema([
                TextInput::make('title'),
                TiptapEditor::make('html_content')
                    ->output(TiptapOutput::Html),
                TiptapEditor::make('json_content')
                    ->output(TiptapOutput::Json),
                TiptapEditor::make('text_content')
                    ->output(TiptapOutput::Text),
            ]);
    }

    public function render(): View
    {
        return view('fixtures.form');
    }
}