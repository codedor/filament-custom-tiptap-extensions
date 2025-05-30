<?php

namespace Codedor\FilamentCustomTiptapExtensions\LinkAction;

use Codedor\LinkPicker\Filament\LinkPickerInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;
use Livewire\Component;

class LinkAction extends \Filament\Actions\Action
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
            ->mountUsing(function (\Filament\Schemas\Schema $schema, array $arguments) {
                $schema->fill([
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
            ->schema([
                \Filament\Schemas\Components\Grid::make(['md' => 2])->schema([
                    LinkPickerInput::make('href')
                        ->hiddenLabel()
                        ->columnSpan('full')
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? self::PREFIX . '[[' . json_encode($state) . ']]' : null
                        ),
                ]),
            ])
            ->action(function (RichEditor $component, $data, array $arguments, Component $livewire) {
                if (filled($data['href'])) {
                    // TODO: how to do this in Filament 4?
                    $component->getLivewire()->dispatch(
                        'insert-content',
                        type: 'link',
                        statePath: $component->getStatePath(),
                        href: $data['href'],
                        id: ''
                    );
                } else {
                    // TODO: how to do this in Filament 4?
                    $component->getLivewire()->dispatch(
                        'unset-link',
                        statePath: $component->getStatePath(),
                    );
                }

                $component->state($component->getState());
            });
    }
}
