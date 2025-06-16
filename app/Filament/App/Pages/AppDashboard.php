<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\InboundUsersWidget;
use App\Filament\App\Widgets\OutboundUsersWidget;
use App\Filament\App\Widgets\PendingFitnessTestsWidget;
use Filament\Pages\Dashboard;
use Filament\Support\Enums\MaxWidth;

class AppDashboard extends Dashboard
{
    protected static ?string $title = 'Home';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenTwoExtraLarge;
    }

    public function getWidgets(): array
    {
        $widgets = [
            InboundUsersWidget::class,
            OutboundUsersWidget::class,
            PendingFitnessTestsWidget::class,
        ];

        $widgets = array_filter($widgets, function ($widget) {
            if (method_exists($widget, 'shouldRender')) {
                return $widget::shouldRender();
            }

            return true;
        });

        return $widgets;
    }
}
