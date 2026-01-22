<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Tabuna\Breadcrumbs\Trail;

class CourseCreateScreen extends Screen
{
    public $name = 'Create Course';
    public $description = 'Add a new course (CA, CS, CMA, etc.)';

    public function query(): array { return []; }

    public function breadcrumbs(): array
    {
        return [
            fn(Trail $t) => $t
                ->parent('platform.courselist')
                ->push('Create Course'),
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Back')
                ->icon('bs.arrow-left')
                ->route('platform.courselist'),

            Button::make('Create')
                ->icon('bs.check-circle')
                ->method('create'),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                \Orchid\Screen\Fields\Input::make('name')
                    ->title('Code')->required()
                    ->placeholder('e.g., CA'),

                \Orchid\Screen\Fields\Input::make('full_name')
                    ->title('Full Name')->required()
                    ->placeholder('e.g., Chartered Accountant'),

                \Orchid\Screen\Fields\TextArea::make('description')
                    ->title('Description')->rows(3),

                \Orchid\Screen\Fields\Input::make('fees')
                    ->title('Fees (₹)')->type('number')
                    ->step('0.01')->required(),

                \Orchid\Screen\Fields\Input::make('duration_months')
                    ->title('Duration (Months)')->type('number')
                    ->required(),
            ]),
        ];
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:10',
            'full_name'       => 'required|string|max:255',
            'description'     => 'nullable|string',
            'fees'            => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
        ]);

        Course::create($data);

        \Orchid\Support\Facades\Alert::success('Course created!');
        return redirect()->route('platform.courselist');
    }
}