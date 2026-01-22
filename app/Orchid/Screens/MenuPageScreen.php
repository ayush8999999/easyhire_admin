<?php

namespace App\Orchid\Screens;

use App\Models\Product;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Http\Request;

class MenuPageScreen extends Screen
{
    public $name        = 'Products';
    public $description = 'Manage all products';

    public function query(): array
    {
        return [
            'products' => Product::query()
                ->defaultSort('id')
                ->paginate(5),
        ];
    }

    public function commandBar(): array
    {
        return [

            Button::make('Bulk Delete')
                ->icon('bs.trash')
                ->method('bulkDelete')
                ->confirm('Are you sure you want to delete the selected products?'),

            Link::make('Add Product')
                ->icon('bs.plus-circle')
                ->route('platform.product.create'),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('products', [
                // CHECKBOX COLUMN
                TD::make('select')
                    ->render(fn(Product $p) => 
                        '<input type="checkbox" name="selected_products[]" value="' . $p->id . '" class="form-checkbox h-4 w-4 text-red-600 rounded border-gray-300 focus:ring-red-500">'
                    ),

                TD::make('id', 'ID')->sort()->filter(TD::FILTER_TEXT),
                TD::make('name', 'Name')->sort()->filter(TD::FILTER_TEXT),
                TD::make('category', 'Category')->sort()->filter(TD::FILTER_TEXT),
                TD::make('price', 'Price')
                    ->render(fn($p) => '$' . number_format($p->price, 2))
                    ->sort(),
                TD::make('stock', 'Stock')->sort(),

                TD::make('Actions')
                    ->alignCenter()
                    ->render(fn(Product $p) => '<div class="d-flex justify-content-center gap-1">'
                        . Link::make('Edit')
                            ->route('platform.product.edit', $p->id)
                            ->icon('bs.pencil')
                            ->class('btn btn-sm btn-outline-primary')
                            ->render()

                        . Button::make('Delete')
                            ->icon('bs.trash')
                            ->class('btn btn-sm btn-outline-danger')
                            ->method('remove')
                            ->parameters(['product' => $p->id])
                            ->confirm('Delete this product?')
                            ->render()
                        . '</div>'),
            ]),
        ];
    }

    public function remove(Product $product)
    {
        $product->delete();
        \Orchid\Support\Facades\Alert::error('Product deleted successfully!');
        return redirect()->route('platform.menupage');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_products', []);

        if (empty($ids)) {
            \Orchid\Support\Facades\Alert::warning('No products selected.');
            return redirect()->route('platform.menupage');
        }

        Product::whereIn('id', $ids)->delete();

        $count = count($ids);
        \Orchid\Support\Facades\Alert::error("{$count} product(s) deleted successfully!");

        return redirect()->route('platform.menupage');
    }
}