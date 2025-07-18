<?php

namespace App\Traits;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

trait HasCustomFields
{
    public function getCustomField(string $field, ?string $default = null): mixed
    {
        return collect($this->custom_fields)->get($field, $default);
    }

    /**
     * Check if the current page has a custom view.
     */
    public function hasCustomView($slug = null): bool
    {
        $slug = $this->getSlug($slug);

        return view()->exists('filament-blog::' . $this->content_type . '.' . $slug);
    }

    /**
     * Extract the placeholders from a blade file.
     */
    private function getPlaceholdersFromBlade($bladeFilePath): array
    {
        // first check if file exists
        if (! File::exists($bladeFilePath)) {
            return [];
        }

        $bladeContent = File::get($bladeFilePath);

        preg_match_all('/{{-- \[(.*?)\] --}}/s', $bladeContent, $matches);

        return collect($matches[1])->mapWithKeys(function ($item, $key) {
            $data = json_decode('[' . $item . ']', true);

            // per non farlo bloccare in caso di placeholder mal formattati
            if (! is_array($data)) {
                return [];
            }

            $slug = Str::slug($data[0], '_');

            return [$slug => (object) [
                'name' => $data[0],
                'type' => $data[1] ?? 'text',
                'description' => $data[2] ?? null,
                'default' => $data[3] ?? null,
                'options' => $data[4] ?? null,
            ]];
        })->all();
    }

    private function createInputComponent($type, $key, $placeholder)
    {
        $component = match ($type) {
            'textarea' => Textarea::make('custom_fields.' . $key),
            'number' => TextInput::make('custom_fields.' . $key)->numeric(),
            'link' => TextInput::make('custom_fields.' . $key)->url(),
            'email' => TextInput::make('custom_fields.' . $key)->email(),
            'date' => DatePicker::make('custom_fields.' . $key),
            'bool' => Toggle::make('custom_fields.' . $key),
            'fileupload' => FileUpload::make('custom_fields.' . $key)->directory('filament-blog-files')->image()->imageEditor(),
            'select' => $this->createSelectComponent('custom_fields.' . $key, $placeholder),
            'placeholder' => Placeholder::make($placeholder->name)->label(null)->content(new HtmlString("<strong class='text-xl'>{$placeholder->name}</strong><p class='mb-4'>{$placeholder->description}</p><hr>")),
            default => TextInput::make('custom_fields.' . $key),
        };

        if ($type === 'placeholder') {
            return $component;
        }

        return $component
            ->label($placeholder->name)
            ->default($placeholder->default)
            ->afterStateHydrated(function ($state, Set $set) use ($placeholder, $key) {
                if (! $placeholder->default || $state) {
                    return;
                }

                $default = match ($placeholder->default) {
                    'true' => true,
                    'false' => false,
                    default => $placeholder->default,
                };

                $set('custom_fields.' . $key, $default);
            })
            ->helperText($placeholder->description)
            ->required(false);
    }

    public function hasPlaceholders($slug = null): bool|array
    {
        $slug = $this->getSlug($slug);

        if ($this->hasCustomView($slug)) {
            $bladeFilePath = resource_path('views/vendor/filament-blog/' . $this->content_type . '/' . $slug . '.blade.php');
        } else {
            $bladeFilePath = resource_path('views/vendor/filament-blog/' . $this->content_type . '.blade.php');
        }

        return $this->getPlaceholdersFromBlade($bladeFilePath);
    }

    public function getPlaceholders($slug = null): array
    {
        $slug = $this->getSlug($slug);

        $placeholders = $this->hasPlaceholders($slug);

        if (! $placeholders) {
            return [];
        }

        $customFields = [];
        foreach ($placeholders as $key => $placeholder) {
            $customFields[] = $this->createInputComponent($placeholder->type, $key, $placeholder);
        }

        return $customFields;
    }

    private function getSlug($slug = null): ?string
    {
        if ($slug !== null) {
            return $slug;
        }

        // return match ($this->content_type) {
        //     'page' => $this->slug,
        //     'post' => $this->category->slug,
        //     default => null,
        // };

        return match ($this->content_type) {
            'page' => $this->slug,
            'post' => method_exists($this, 'category') && $this->category ? $this->category->slug : null,
            default => null,
        };
    }

    private function createSelectComponent($key, $placeholder)
    {
        $options = [];
        if (isset($placeholder->options)) {
            $options = explode('|', $placeholder->options);
            $options = array_reduce($options, function ($carry, $option) {
                $parts = explode('=', $option);
                $carry[$parts[0]] = $parts[1];

                return $carry;
            }, []);
        }

        return Select::make($key)
            ->options($options)
            ->label($placeholder->name)
            ->default($placeholder->default)
            ->helperText($placeholder->description)
            ->required();
    }
}
