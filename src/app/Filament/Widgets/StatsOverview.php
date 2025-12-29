<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Topic;
use App\Models\Score;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Siswa', User::count())
                ->description('Siswa terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Materi Tersedia', Topic::count())
                ->description('Topik pembelajaran')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Total Permainan', Score::count())
                ->description('Kali dimainkan')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'),
        ];
    }
}