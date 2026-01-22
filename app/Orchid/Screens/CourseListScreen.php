<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Http\Request;

class CourseListScreen extends Screen
{
    public $name        = 'Courses';
    public $description = 'Manage all courses (CA, CS, CMA, etc.)';

    public function query(Request $request): array
    {
        $query = Course::query();

        // Apply filters
        if ($request->filled('code')) {
            $query->where('name', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('full_name')) {
            $query->where('full_name', 'like', '%' . $request->full_name . '%');
        }
        if ($request->filled('fees_min')) {
            $query->where('fees', '>=', $request->fees_min);
        }
        if ($request->filled('fees_max')) {
            $query->where('fees', '<=', $request->fees_max);
        }
        if ($request->filled('duration')) {
            $query->where('duration_months', $request->duration);
        }

        return [
            'courses' => $query->defaultSort('id')->paginate(10),
            'filters' => $request->only(['code', 'full_name', 'fees_min', 'fees_max', 'duration']),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Bulk Delete')
                ->icon('bs.trash')
                ->method('bulkDelete')
                ->confirm('Are you sure you want to delete the selected courses?'),

            Link::make('Add Course')
                ->icon('bs.plus-circle')
                ->route('platform.course.create'),
        ];
    }

    public function layout(): array
    {
        return [
            // FILTER CARD
            Layout::rows([
                \Orchid\Screen\Fields\Input::make('code')
                    ->title('Code')
                    ->placeholder('e.g., CA')
                    ->datalist(
                        Course::pluck('name')->unique()->take(50)->toArray()
                    )
                    ->horizontal(),

                \Orchid\Screen\Fields\Input::make('full_name')
                    ->title('Full Name')
                    ->placeholder('e.g., Chartered Accountant')
                    ->datalist(
                        Course::pluck('full_name')->unique()->take(50)->toArray()
                    )
                    ->horizontal(),

                \Orchid\Screen\Fields\Group::make([
                    \Orchid\Screen\Fields\Input::make('fees_min')
                        ->title('Min Fees')
                        ->type('number')
                        ->placeholder('0'),

                    \Orchid\Screen\Fields\Input::make('fees_max')
                        ->title('Max Fees')
                        ->type('number')
                        ->placeholder('500000'),
                ]),

                \Orchid\Screen\Fields\Select::make('duration')
                    ->title('Duration (Months)')
                    ->options([
                        ''   => 'Any',
                        3    => '3 months',
                        6    => '6 months',
                        12   => '12 months',
                        18   => '18 months',
                        24   => '24 months',
                        30   => '30 months',
                        36   => '36 months',
                    ])
                    ->empty('Any duration'),

                Button::make('Apply Filters')
                    ->icon('bs.filter')
                    ->method('applyFilters'),
            ])->title('Filter Courses'),

            // TABLE
            Layout::table('courses', [
                // INDIVIDUAL CHECKBOX
                TD::make('select', '')
                    ->cantHide()
                    ->render(fn(Course $c) =>
                        '<input type="checkbox" name="selected_courses[]" value="' . $c->id . '" class="row-checkbox h-4 w-4 text-red-600 rounded border-gray-300 focus:ring-red-500">'
                    ),

                TD::make('id', 'ID')->sort(),
                TD::make('name', 'Code')->sort(),
                TD::make('full_name', 'Full Name')->sort(),
                TD::make('fees', 'Fees')
                    ->render(fn($c) => '₹' . number_format($c->fees, 2))
                    ->sort(),
                TD::make('duration_months', 'Duration (Months)')->sort(),

                TD::make('Actions')
                    ->alignCenter()
                    ->render(fn(Course $c) => '<div class="d-flex justify-content-center gap-1">'
                        . Link::make('Edit')
                            ->route('platform.course.edit', $c->id)
                            ->icon('bs.pencil')
                            ->class('btn btn-sm btn-outline-primary')
                            ->render()

                        . Button::make('Delete')
                            ->icon('bs.trash')
                            ->class('btn btn-sm btn-outline-danger')
                            ->method('remove')
                            ->parameters(['course' => $c->id])
                            ->confirm('Delete this course?')
                            ->render()
                        . '</div>'),
            ]),
        ];
    }

    // APPLY FILTERS
    public function applyFilters(Request $request)
    {
        return redirect()->route('platform.courselist', $request->all());
    }

    public function remove(Course $course)
    {
        $course->delete();
        \Orchid\Support\Facades\Alert::error('Course deleted successfully!');
        return redirect()->route('platform.courselist');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_courses', []);

        if (empty($ids)) {
            \Orchid\Support\Facades\Alert::warning('No courses selected.');
            return redirect()->route('platform.courselist');
        }

        Course::whereIn('id', $ids)->delete();

        $count = count($ids);
        \Orchid\Support\Facades\Alert::error("{$count} course(s) deleted successfully!");

        return redirect()->route('platform.courselist');
    }
}
