<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Tabuna\Breadcrumbs\Trail;

class CourseEditScreen extends Screen
{
    public ?Course $course = null;

    public function query(Course $course): array
    {
        $this->course = $course;
        return [
            'name'            => $course->name,
            'full_name'       => $course->full_name,
            'description'     => $course->description,
            'fees'            => $course->fees,
            'duration_months' => $course->duration_months,
        ];
    }

    public function name(): ?string
    {
        return "Edit Course: {$this->course->name}";
    }

    public function breadcrumbs(): array
    {
        return [
            fn(Trail $t) => $t
                ->parent('platform.courselist')
                ->push("Edit: {$this->course->name}"),
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Back')
                ->icon('bs.arrow-left')
                ->route('platform.courselist'),

            Button::make('Update')
                ->icon('bs.check-circle')
                ->method('update'),

            Button::make('Delete')
                ->icon('bs.trash')
                ->method('remove')
                ->confirm('Are you sure?'),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                \Orchid\Screen\Fields\Input::make('name')
                    ->title('Code')->required(),

                \Orchid\Screen\Fields\Input::make('full_name')
                    ->title('Full Name')->required(),

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

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:10',
            'full_name'       => 'required|string|max:255',
            'description'     => 'nullable|string',
            'fees'            => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
        ]);

        $course->update($data);
        \Orchid\Support\Facades\Alert::success('Course updated!');
        return redirect()->route('platform.courselist');
    }

    public function remove(Course $course)
    {
        $course->delete();
        \Orchid\Support\Facades\Alert::error('Course deleted!');
        return redirect()->route('platform.courselist');
    }
}