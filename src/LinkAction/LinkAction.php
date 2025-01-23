<?php

namespace Codedor\FilamentCustomTiptapExtensions\LinkAction;

use Codedor\LinkPicker\Filament\LinkPickerInput;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Str;
use Livewire\Component;

class LinkAction extends Action
{
    private const PREFIX = '#link-picker=';

    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_link';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalWidth('md')
            ->arguments([
                'href' => '',
                'rel' => '',
            ])
            ->mountUsing(function (ComponentContainer $form, array $arguments) {
                $form->fill([
                    'href' => json_decode(
                        Str::of($arguments['href'])
                            ->replace(self::PREFIX, '', $arguments['href'])
                            ->replaceFirst('[[', '')
                            ->replaceLast(']]', ''),
                        true
                    ),
                ]);
            })
            ->modalHeading(function (array $arguments) {
                $context = blank($arguments['href']) ? 'insert' : 'update';

                return __('filament-tiptap-editor::link-modal.heading.' . $context);
            })
            ->form([
                Grid::make(['md' => 2])->schema([
                    LinkPickerInput::make('href')
                        ->hiddenLabel()
                        ->columnSpan('full')
                        ->dehydrateStateUsing(
                            fn ($state) => filled($state)
                                ? self::PREFIX . '[[' . json_encode($state) . ']]'
                                : null
                        ),
                ]),
            ])
            ->action(function (TiptapEditor $component, $data, array $arguments) {
                if (filled($data['href'])) {
                    $component->getLivewire()->dispatch(
                        event: 'insertFromAction',
                        type: 'link',
                        statePath: $component->getStatePath(),
                        href: $data['href'],
                        id: '',
                        coordinates: $arguments['coordinates'],
                    );
                } else {
                    $component->getLivewire()->dispatch(
                        'unset-link',
                        statePath: $component->getStatePath(),
                    );
                }

                $component->state($component->getState());
            });
    }
}
