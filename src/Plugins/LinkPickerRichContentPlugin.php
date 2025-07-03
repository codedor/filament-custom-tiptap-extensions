<?php

namespace Codedor\FilamentCustomTiptapExtensions\Plugins;

use Codedor\LinkPicker\Filament\LinkPickerInput;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Tiptap\Core\Extension;
use Tiptap\Marks\Link;

class LinkPickerRichContentPlugin implements RichContentPlugin
{
    private const PREFIX = '#link-picker=';

    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * @return array<Extension>
     */
    public function getTipTapPhpExtensions(): array
    {
        // This method should return an array of PHP TipTap extension objects.
        // See: https://github.com/ueberdosis/tiptap-php

        return [
            app(Link::class, []),
        ];
    }

    /**
     * @return array<string>
     */
    public function getTipTapJsExtensions(): array
    {
        // This method should return an array of URLs to JavaScript files containing
        // TipTap extensions that should be asynchronously loaded into the editor
        // when the plugin is used.

        return [];
    }

    /**
     * @return array<RichEditorTool>
     */
    public function getEditorTools(): array
    {
        // This method should return an array of `RichEditorTool` objects, which can then be
        // used in the `toolbarButtons()` of the editor.

        // The `jsHandler()` method allows you to access the TipTap editor instance
        // through `$getEditor()`, and `chain()` any TipTap commands to it.
        // See: https://tiptap.dev/docs/editor/api/commands

        // The `action()` method allows you to run an action (registered in the `getEditorActions()`
        // method) when the toolbar button is clicked. This allows you to open a modal to
        // collect additional information from the user before running a command.

        return [
            RichEditorTool::make('linkPicker')
                ->label(__('filament-forms::components.rich_editor.tools.link'))
                ->action(arguments: '{ url: $getEditor().getAttributes(\'link\')?.href, shouldOpenInNewTab: $getEditor().getAttributes(\'link\')?.target === \'_blank\' }')
                ->icon(Heroicon::Link)
                ->iconAlias('forms:components.rich-editor.toolbar.link'),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        // This method should return an array of `Action` objects, which can be used by the tools
        // registered in the `getEditorTools()` method. The name of the action should match
        // the name of the tool that uses it.

        // The `runCommands()` method allows you to run TipTap commands on the editor instance.
        // It accepts an array of `EditorCommand` objects that define the command to run,
        // as well as any arguments to pass to the command. You should also pass in the
        // `editorSelection` argument, which is the current selection in the editor
        // to apply the commands to.

        return [
            Action::make('linkPicker')
                ->label(__('filament-forms::components.rich_editor.actions.link.label'))
                ->modalHeading(__('filament-forms::components.rich_editor.actions.link.modal.heading'))
                ->modalWidth(Width::Large)
                ->fillForm(fn (array $arguments): array => [
                    'href' => json_decode(
                        Str::of($arguments['url'] ?? '')
                            ->replace(self::PREFIX, '', $arguments['url'] ?? '')
                            ->replaceFirst('[[', '')
                            ->replaceLast(']]', ''),
                        true
                    ),
                ])
                ->schema([
                    LinkPickerInput::make('href')
                        ->hiddenLabel()
                        ->dehydrateStateUsing(
                            fn ($state) => filled($state) ? self::PREFIX . '[[' . json_encode($state) . ']]' : null
                        ),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    $isSingleCharacterSelection = ($arguments['editorSelection']['head'] ?? null) === ($arguments['editorSelection']['anchor'] ?? null);

                    // Copied from vendor/filament/forms/src/Components/RichEditor/Actions/LinkAction.php
                    if (blank($data['href'])) {
                        $component->runCommands(
                            [
                                ...($isSingleCharacterSelection ? [EditorCommand::make(
                                    'extendMarkRange',
                                    arguments: ['link'],
                                )] : []),
                                EditorCommand::make('unsetLink'),
                            ],
                            editorSelection: $arguments['editorSelection'],
                        );

                        return;
                    }

                    $component->runCommands(
                        [
                            ...($isSingleCharacterSelection ? [EditorCommand::make(
                                'extendMarkRange',
                                arguments: ['link'],
                            )] : []),
                            EditorCommand::make(
                                'setLink',
                                arguments: [[
                                    'href' => $data['href'],
                                ]],
                            ),
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );
                }),
        ];
    }
}

