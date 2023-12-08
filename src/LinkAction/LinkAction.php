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

        $this
            ->modalWidth('md')
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
            })->modalHeading(function (array $arguments) {
                $context = blank($arguments['href']) ? 'insert' : 'update';

                return __('filament-tiptap-editor::link-modal.heading.' . $context);
            })
            ->form([
                Grid::make(['md' => 2])
                    ->schema([
                        LinkPickerInput::make('href')
                            ->label(__('filament-tiptap-editor::link-modal.labels.url'))
                            ->columnSpan('full')
                            ->dehydrateStateUsing(fn ($state) => self::PREFIX . '[[' . json_encode($state) . ']]'),
                    ]),
            ])->action(function (TiptapEditor $component, $data, array $arguments, Component $livewire) {
                if (isset($arguments['remove_link']) && $arguments['remove_link'] === true) {
                    $component->getLivewire()->dispatch(
                        'insert-content',
                        type: 'link',
                        statePath: $component->getStatePath(),
                        href: '',
                        id: null
                    );

                    $component->state($component->getState());

                    return;
                }

                $component->getLivewire()->dispatch(
                    'insert-content',
                    type: 'link',
                    statePath: $component->getStatePath(),
                    href: $data['href'],
                    id: ''
                );

                $component->state($component->getState());
            })
            ->extraModalFooterActions(function (Action $action): array {
                return [
                    $action->makeModalSubmitAction('remove_link', [
                        'remove_link' => true,
                    ])
                        ->color('danger'),
                ];
            });
    }
}
