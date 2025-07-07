<?php

namespace App\Filament\Resources\TeacherSubjectResource\Pages;

use App\Filament\Resources\TeacherSubjectResource;
use App\Models\TeacherSubjectList;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewTeacherSubjectListData extends ViewRecord
{
    protected static string $resource = TeacherSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function deleteSubject($id)
    {
        TeacherSubjectList::findOrFail($id)->delete();

        Notification::make()
            ->title('Teacher Subject Deleted successfully')
            ->success()
            ->send();

        $this->record->refresh();
    }
}
