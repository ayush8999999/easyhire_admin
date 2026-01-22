<?php

namespace App\Orchid\Screens;

use App\Models\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;          // ← added
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Tabuna\Breadcrumbs\Trail;

class ProductCreateScreen extends Screen
{
    public $name = 'Create Product';
    public $description = 'Add a new product';

    public function query(): array { return []; }

    // Add breadcrumbs
    public function breadcrumbs(): array
    {
        return [
            fn(Trail $t) => $t
                ->parent('platform.menupage')
                ->push('Create Product'),
        ];
    }

    public function commandBar(): array
    {
        return [
            // ← BACK BUTTON
            Link::make('Back')
                ->icon('bs.arrow-left')
                ->route('platform.menupage'),

            // CREATE BUTTON
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
                    ->title('Name')->required()
                    ->placeholder('Enter product name'),

                \Orchid\Screen\Fields\Input::make('category')
                    ->title('Category')->required()
                    ->placeholder('e.g., Laptop'),

                \Orchid\Screen\Fields\Input::make('price')
                    ->title('Price')->type('number')
                    ->step('0.01')->required(),

                \Orchid\Screen\Fields\Input::make('stock')
                    ->title('Stock')->type('number')
                    ->required(),
            ]),
        ];
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
        ]);

        Product::create($data);

        \Orchid\Support\Facades\Alert::success('Product created!');
        return redirect()->route('platform.menupage');
    }
}