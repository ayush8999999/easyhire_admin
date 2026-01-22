<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Layouts;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;

class DashboardScreen extends Screen
{
    /**
     * Display name
     *
     * @var string
     */
    public $name = 'Dashboard';

    /**
     * Description
     *
     * @var string|null
     */
    public $description = 'Welcome to Intucate Orchid Admin Panel';

    /**
     * Query data
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Button commands
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Learn More')
                ->href('https://orchid.software')
                ->icon('bs.book'),
        ];
    }

    /**
     * Views
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('admin.dashboard'),
        ];
    }
}
