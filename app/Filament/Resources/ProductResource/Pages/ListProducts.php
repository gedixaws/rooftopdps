<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductResource;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importProducts')
                ->label('Import Product')
                ->icon('heroicon-s-arrow-down-tray')
                ->color('danger')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Upload Template Product')
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/'. $data['attachment']);

                    try {
                        Excel::import(new ProductImport, $file);
                        Notification::make()
                            ->title('Product Imported')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Product failed to Import')
                            ->body($e->getMessage()) // Tambahkan detail error
                            ->danger()
                            ->send();
                    }
                }),
            Action::make("Download Template")
                ->url(route('download-template'))
                ->color('success'),
            Actions\CreateAction::make(),
        ];
    }
}
