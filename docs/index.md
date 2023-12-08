** Some custom extensions for Filament Tiptap

## Introduction

This package provides extensions for the `awcodes/filament-tiptap-editor` package to integrate it with our own packages.

## Installation

First, install this package via the Composer package manager:

```bash
composer require codedor/filament-custom-tiptap-extensions
```

## LinkAction

This overrides the default `LinkAction` to use our [link picker](https://github.com/codedor/filament-link-picker) instead.

### Setup

Update the `filament-tiptap-editor` config file to use the custom `LinkAction`:

```php
return [
    // ...
    'extensions' => [
        // ...
        [
            'id' => 'linkpicker',
            'name' => 'Linkpicker',
            'button' => 'filament-custom-tiptap-extensions::linkpicker',
            'parser' => \Codedor\FilamentCustomTiptapExtensions\LinkAction\Linkpicker::class,
        ],
    ],
];
```

Follow the instructions [here](https://github.com/awcodes/filament-tiptap-editor#custom-extensions) to add a `extension.js` file.

```js
import Link from '@tiptap/extension-link'

window.TiptapEditorExtensions = {
  // ...
  linkpicker: [
    Link.configure({
      openOnClick: false,
      HTMLAttributes: {
        // Change rel to different value
        // Allow search engines to follow links(remove nofollow)
        rel: '',
        // Remove target entirely so links open in current tab
        target: null
      }
    })
  ]
}
```

Update the `link_action` in the config file to 
```php
return [
    // ...
    'link_action' => \Codedor\FilamentCustomTiptapExtensions\LinkAction\LinkAction::class,
    // ...
];
```

And replace `'link'` with `'linkpicker'` in the `toolbar` config.

## MediaAction

This overrides the default `MediaAction` to use our [media library](https://github.com/codedor/filament-media-library) instead.

### Setup

Update the `media_action` in the config file to
```php
return [
    // ...
    'media_action' => \Codedor\FilamentCustomTiptapExtensions\MediaAction\MediaAction::class,
    // ...
];
```
