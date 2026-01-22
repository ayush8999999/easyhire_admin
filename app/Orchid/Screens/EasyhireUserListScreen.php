<?php

namespace App\Orchid\Screens;

use App\Models\EasyhireUser;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class EasyhireUserListScreen extends Screen
{
    public $name = 'EasyHire Users';
    public $description = 'View all registered users';

    public function query(Request $request): iterable
    {
        $users = EasyhireUser::query();

        if ($request->filled('full_name')) {
            $users->where('full_name', 'like', '%' . $request->full_name . '%');
        }

        if ($request->filled('email')) {
            $users->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('mobile_number')) {
            $users->where('mobile_number', 'like', '%' . $request->mobile_number . '%');
        }

        if ($request->filled('status')) {
            $users->where('status', $request->status);
        }

        return [
            'users' => $users->orderByDesc('id')->paginate(10),
        ];
    }

    public function layout(): iterable
    {
        return [

            Layout::rows([

                Group::make([
                    Input::make('full_name')
                        ->title('Full Name')
                        ->value(request('full_name')),

                    Input::make('email')
                        ->title('Email')
                        ->value(request('email')),

                    Input::make('mobile_number')
                        ->title('Mobile')
                        ->value(request('mobile_number')),

                    Select::make('status')
                        ->title('Status')
                        ->options([
                            '' => 'All',
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'blocked' => 'Blocked',
                        ])
                        ->value(request('status')),
                ]),

                Group::make([
                    Button::make('Apply Filter')
                        ->method('applyFilter')
                        ->type(Color::PRIMARY),
                ]),
            ])->title('Filters'),

            Layout::table('users', [
                TD::make('id', 'ID')->sort(),
                TD::make('full_name', 'Full Name'),
                TD::make('email', 'Email'),
                TD::make('mobile_number', 'Mobile'),
                TD::make('country_iso2', 'Country'),
                TD::make('gender', 'Gender'),
                TD::make('date_of_birth', 'DOB'),
                TD::make('email_verified', 'Verified')
                    ->render(fn($u) => $u->email_verified ? 'Yes' : 'No'),
                TD::make('user_role', 'Role'),
                TD::make('status', 'Status'),
                TD::make('created_at', 'Created')->sort(),
            ]),
        ];
    }

    public function applyFilter(Request $request)
    {
        return redirect()->route('platform.easyhire.users', $request->only([
            'full_name',
            'email',
            'mobile_number',
            'status',
        ]));
    }
}
