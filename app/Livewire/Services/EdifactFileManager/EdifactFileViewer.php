<?php

namespace App\Livewire\Services\EdifactFileManager;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EdifactFileViewer extends Component
{
    public array $parsedMessages = [];

    public function mount(): void
    {
        $files = collect(Storage::files('parsed_edi'))
            ->filter(fn($f) => str_ends_with($f, '.json'));

        $this->parsedMessages = $files->map(function ($path) {
            return [
                'file' => basename($path),
                'content' => json_decode(Storage::get($path), true),
            ];
        })->toArray();
    }
    public function render()
    {
        return view('livewire.services.edifact-file-manager.edifact-file-viewer');
    }
}
