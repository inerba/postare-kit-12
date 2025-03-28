<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Illuminate\Support\HtmlString;

class SocialShare
{
    public static function make(): Brick
    {
        return Brick::make('social-share')
            ->label('Pulsanti di condivisione')
            ->modalHeading('Impostazioni pulsanti di condivisione')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M176,160a39.89,39.89,0,0,0-28.62,12.09l-46.1-29.63a39.8,39.8,0,0,0,0-28.92l46.1-29.63a40,40,0,1,0-8.66-13.45l-46.1,29.63a40,40,0,1,0,0,55.82l46.1,29.63A40,40,0,1,0,176,160Zm0-128a24,24,0,1,1-24,24A24,24,0,0,1,176,32ZM64,152a24,24,0,1,1,24-24A24,24,0,0,1,64,152Zm112,72a24,24,0,1,1,24-24A24,24,0,0,1,176,224Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn (array $arguments): array => array_merge(
                array_merge(
                    Macro\Theme::getArguments($arguments),
                    ['theme' => [
                        'blockMaxWidth' => $arguments['theme']['blockMaxWidth'] ?? 'max-w-sm',
                        'blockVerticalPadding' => $arguments['theme']['blockVerticalPadding'] ?? 'py-8',
                        'blockVerticalPaddingLg' => $arguments['theme']['blockVerticalPaddingLg'] ?? 'lg:py-24',
                    ]],
                ),
                [
                    'header_title' => $arguments['header_title'] ?? 'Condividi',
                    'header_align' => $arguments['header_align'] ?? 'center',
                    'header_tagline' => $arguments['header_tagline'] ?? null,
                ],
                [
                    'section_title' => $arguments['section_title'] ?? null,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'social-share',
                                'values' => $data,
                                'path' => 'mason.social-share',
                                'view' => view('mason.social-share', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
