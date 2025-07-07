<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['parent'], $data['academic']);
        return $data;
    }

     protected function afterCreate(): void
    {
        $student = $this->record;

        $student->parent()->create($this->data['parent']);

        $student->academic()->create($this->data['academic']);
    }
}
