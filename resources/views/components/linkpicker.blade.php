@props([
    'statePath' => null,
    'icon' => 'link',
    'label' => __('filament-tiptap-editor::editor.link.insert_edit'),
    'active' => true,
])

@php
    $useActive = $active ? 'link' : false;
@endphp

<x-filament-tiptap-editor::button
    action="openModal()"
    :active="$useActive"
    :label="$label"
    :icon="$icon"
    x-data="{
        openModal() {
            let link = this.editor().getAttributes('link');

            let arguments = {
                href: link.href || '',
                coordinates: this.editor().view.state.selection.ranges,
            };

            $wire.dispatchFormEvent('tiptap::setLinkContent', '{{ $statePath }}', arguments);
        }
    }"
/>
