<?php

namespace Codedor\FilamentCustomTiptapExtensions\MediaAction;

use Codedor\MediaLibrary\Filament\AttachmentInput;
use Codedor\MediaLibrary\Models\Attachment;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use FilamentTiptapEditor\TiptapEditor;

class MediaAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_media';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->arguments([
            'src' => '',
        ]);

        $this->modalWidth('md');

        $this->mountUsing(function (TiptapEditor $component, ComponentContainer $form, array $arguments) {
            preg_match(
                '/\/([\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12})\//iD',
                $arguments['src'] ?? null,
                $matches
            );

            $form->fill(['attachment' => $matches[1] ?? null]);
        });

        $this->modalHeading(function (array $arguments) {
            $context = blank($arguments['src'] ?? null) ? 'insert' : 'update';

            return __('filament-tiptap-editor::media-modal.heading.' . $context);
        });

        $this->form(function () {
            return [
                AttachmentInput::make('attachment')
                    ->hiddenLabel()
                    ->required()
                    ->reactive()
                    ->disableFormatter(),
            ];
        });

        $this->action(function (TiptapEditor $component, $data) {
            $attachment = Attachment::find($data['attachment']);

            $component->getLivewire()->dispatch(
                'insert-content',
                type: 'media',
                statePath: $component->getStatePath(),
                media: [
                    'src' => $attachment->url,
                    'alt' => $attachment->alt,
                    'title' => $attachment->translated_name,
                    'width' => $attachment->width,
                    'height' => $attachment->height,
                    'link_text' => null,
                ],
            );
        });
    }
}
