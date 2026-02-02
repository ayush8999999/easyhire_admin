<?php

declare(strict_types=1);

use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleGridScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\DashboardScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\MenuPageScreen;
use App\Orchid\Screens\ProductCreateScreen;
use App\Orchid\Screens\ProductEditScreen;
use App\Orchid\Screens\CourseListScreen;
use App\Orchid\Screens\CourseCreateScreen;
use App\Orchid\Screens\CourseEditScreen;
use App\Orchid\Screens\CandidateAppliedListScreen;
use App\Orchid\Screens\ShortlistedCandidatesScreen;
use App\Orchid\Screens\RejectedCandidatesScreen;
use App\Orchid\Screens\CandidateAppliedViewScreen;
use App\Orchid\Screens\EasyhireUserListScreen;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\InterviewScheduleScreen;
use App\Orchid\Screens\ScheduledInterviewsScreen;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "dashboard" middleware group. Now create something great!
|
*/

// Main Dashboard
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn(Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn(Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example Screens
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example Screen'));

Route::screen('/examples/form/fields', ExampleFieldsScreen::class)
    ->name('platform.example.fields');

Route::screen('/examples/form/advanced', ExampleFieldsAdvancedScreen::class)
    ->name('platform.example.advanced');

Route::screen('/examples/form/editors', ExampleTextEditorsScreen::class)
    ->name('platform.example.editors');

Route::screen('/examples/form/actions', ExampleActionsScreen::class)
    ->name('platform.example.actions');

Route::screen('/examples/layouts', ExampleLayoutsScreen::class)
    ->name('platform.example.layouts');

Route::screen('/examples/grid', ExampleGridScreen::class)
    ->name('platform.example.grid');

Route::screen('/examples/charts', ExampleChartsScreen::class)
    ->name('platform.example.charts');

Route::screen('/examples/cards', ExampleCardsScreen::class)
    ->name('platform.example.cards');

Route::screen('dashboard', DashboardScreen::class)
    ->name('platform.dashboard');

// ====================================================================
// PRODUCTS CRUD (WITH CORRECT platform. PREFIX)
// ====================================================================

// List Products
Route::screen('products', MenuPageScreen::class)
    ->name('platform.menupage')
    ->breadcrumbs(fn(Trail $t) => $t
        ->parent('platform.index')
        ->push('Products', route('platform.menupage')));

// Create Product
Route::screen('product/create', ProductCreateScreen::class)
    ->name('platform.product.create')
    ->breadcrumbs(fn(Trail $t) => $t
        ->parent('platform.menupage')
        ->push('Create Product', route('platform.product.create')));

// Edit Product
Route::screen('product/{product}/edit', ProductEditScreen::class)
    ->name('platform.product.edit')
    ->breadcrumbs(fn(Trail $t, $product) => $t
        ->parent('platform.menupage')
        ->push("Edit: {$product->name}", route('platform.product.edit', $product)));

// Delete from Edit Screen (optional)
Route::post('product/{product}/delete-from-edit', [ProductEditScreen::class, 'remove'])
    ->name('platform.product.delete.from.edit');


    //course 
    Route::screen('courses', CourseListScreen::class)
    ->name('platform.courselist')
    ->breadcrumbs(fn(Trail $t) => $t
        ->parent('platform.index')
        ->push('Courses', route('platform.courselist')));

Route::screen('course/create', CourseCreateScreen::class)
    ->name('platform.course.create')
    ->breadcrumbs(fn(Trail $t) => $t
        ->parent('platform.courselist')
        ->push('Create Course'));

Route::screen('course/{course}/edit', CourseEditScreen::class)
    ->name('platform.course.edit')
    ->breadcrumbs(fn(Trail $t, $course) => $t
        ->parent('platform.courselist')
        ->push("Edit: {$course->name}"));
        Route::screen('candidates', CandidateAppliedListScreen::class)
    ->name('platform.candidate.list');

    Route::screen('shortlisted-candidates', ShortlistedCandidatesScreen::class)
    ->name('platform.candidate.shortlisted');

    Route::screen('rejected-candidates', RejectedCandidatesScreen::class)
    ->name('platform.candidate.rejected');

    Route::screen('interview/schedule/{candidate}', InterviewScheduleScreen::class)
    ->name('platform.interview.schedule');

    Route::screen('interviews', ScheduledInterviewsScreen::class)
        ->name('platform.interviews.list');

Route::screen('candidates/{candidate}', CandidateAppliedViewScreen::class)
    ->name('platform.candidate.view');
    
    Route::screen('easyhire-users', EasyhireUserListScreen::class)
    ->name('platform.easyhire.users');