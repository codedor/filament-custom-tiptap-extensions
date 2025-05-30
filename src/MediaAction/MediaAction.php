<?php

namespace Codedor\FilamentCustomTiptapExtensions\MediaAction;

use Codedor\MediaLibrary\Filament\AttachmentInput;
use Codedor\MediaLibrary\Models\Attachment;
use Filament\Forms\Components\RichEditor;

class MediaAction extends \Filament\Actions\Action
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

        $this->mountUsing(function (RichEditor $component, \Filament\Schemas\Schema $schema, array $arguments) {
            preg_match(
                '/\/([\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12})\//iD',
                $arguments['src'] ?? null,
                $matches
            );

            $schema->fill(['attachment' => $matches[1] ?? null]);
        });

        $this->modalHeading(function (array $arguments) {
            $context = blank($arguments['src'] ?? null) ? 'insert' : 'update';

            return __('filament-tiptap-editor::media-modal.heading.' . $context);
        });

        $this->schema(function () {
            return [
                AttachmentInput::make('attachment')
                    ->hiddenLabel()
                    ->required()
                    ->reactive()
                    ->disableFormatter(),
            ];
        });

        $this->action(function (RichEditor $component, $data) {
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
