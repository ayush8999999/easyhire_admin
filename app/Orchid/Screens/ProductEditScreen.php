<?php

namespace App\Orchid\Screens;

use App\Models\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;          // ← added
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Tabuna\Breadcrumbs\Trail;

class ProductEditScreen extends Screen
{
    public ?Product $product = null;  // ← FIXED: Initialize

    public function query(Product $product): array
    {
        $this->product = $product;  // Now safe to assign
        return [
            'name'     => $product->name,
            'category' => $product->category,
            'price'    => $product->price,
            'stock'    => $product->stock,
        ];
    }

    public function name(): ?string
    {
        return "Edit Product: {$this->product->name}";  // Now safe
    }

    public function breadcrumbs(): array
    {
        return [
            fn(Trail $t) => $t
                ->parent('platform.menupage')
                ->push("Edit: {$this->product->name}"),
        ];
    }

    public function commandBar(): array
    {
        return [
            // ← BACK BUTTON
            Link::make('Back')
                ->icon('bs.arrow-left')
                ->route('platform.menupage'),

            // UPDATE BUTTON
            Button::make('Update')
                ->icon('bs.check-circle')
                ->method('update'),

            // DELETE BUTTON
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
                    ->title('Name')->required(),

                \Orchid\Screen\Fields\Input::make('category')
                    ->title('Category')->required(),

                \Orchid\Screen\Fields\Input::make('price')
                    ->title('Price')->type('number')
                    ->step('0.01')->required(),

                \Orchid\Screen\Fields\Input::make('stock')
                    ->title('Stock')->type('number')
                    ->required(),
            ]),
        ];
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
        ]);

        $product->update($data);
        \Orchid\Support\Facades\Alert::success('Product updated!');
        return redirect()->route('platform.menupage');
    }

    public function remove(Product $product)
    {
        $product->delete();
        \Orchid\Support\Facades\Alert::error('Product deleted!');
        return redirect()->route('platform.menupage');
    }
}