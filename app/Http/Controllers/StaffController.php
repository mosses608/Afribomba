<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Store;
use App\Models\Export;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function staff_dashboard(){
        $staffAuth = Auth::guard('web')->user()->staff_name;
        // $reconnded = (int)DB::table('transfers')->where('staff_recommeded', '!=', 0)->count();
        $instock = (int)DB::table('products')->where('product_quantity', '!=', 0)->count();
        $lessProduct = (int)DB::table('products')->where('product_quantity', '<', 20)->count();
    
        $exports = Export::select('product_name', 'created_at', 'product_quantity')
                      ->orderBy('created_at', 'asc')
                      ->get();

    // Decode JSON data and prepare chart data
    $chartData = $exports->map(function($export) {
        return [
            'product_names' => json_decode($export->product_name),
            'created_at' => $export->created_at->format('Y-m-d'),
            'product_quantities' => json_decode($export->product_quantity),
        ];
    });

    $dates = $chartData->pluck('created_at')->unique()->sort()->values();

    // Prepare data for Chart.js
    $dataByProduct = $chartData->flatMap(function($data) {
        return collect($data['product_names'])->mapWithKeys(function($productName, $index) use ($data) {
            return [$productName => [$data['created_at'] => $data['product_quantities'][$index]]];
        });
    })->groupBy(function($item, $key) {
        return $key;
    })->map(function($items) {
        return $items->collapse();
    });

    
        $reconnded = DB::table('transfers')->where('staff_recommeded', '!=' , 0)->count();

        return view('staff.staff-dashboard',[
            'users' => User::all(),
            'products' => Product::all(),
            'stores' => Store::all(),
            'comments' => Comment::all(),
            'exports' => $exports,
            'lessProductCount' => $lessProduct,
            'instockCount' => $instock,
            'staff_recommednCounter' => $reconnded,
            'chartData' => $dataByProduct,
            'dates' => $dates,
        ]);
    }

    public function staff_profile(){
        return view('staff.profile',[
            'comments' => Transfer::all(),
        ]);
    }

    public function all_products(){
        return view('staff.all-products',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
            'stores' => Store::all(),
            'comments' => Transfer::all(),
        ]);
    }

    public function single_export($id){

        $product = Product::find($id);

        $sales = Export::where('id', $id)->orderBy('created_at', 'asc')->get();

        $chartData = $sales->map(function($sale) {
            return [
                'created_at' => $sale->created_at->format('Y-m-d'),
                'product_quantity' => $sale->quantity,
                'product_name' => $sale->product_name,
            ];
        });


        return view('staff.single-export',[
            'product' => $product,
            'stores' => Store::all(),
            'users' => User::all(),
            'sales' => $sales,
            'chartData' => $chartData,
            'comments' => Transfer::all(),
        ]);
    }

    public function exported_products(Request $request){
        $currentDate = Carbon::now()->format('Y-m-d');

    // Calculate total profit from all exports
    $myexports = Export::all()->sum(function($export) {
        $quantities = json_decode($export->product_quantity, true);
        $prices = json_decode($export->product_price, true);
        $total = 0;
        foreach ($quantities as $index => $quantity) {
            $total += (float)$quantity * (float)$prices[$index];
        }
        return $total;
    });

    $dateProfit = $myexports;
    $totalComponents = Export::whereNotNull('product_name')->count();

    // Handle search functionality
    if ($request->has('search') && $request->search != '') {
        $searchDate = $request->search;

        $dateProfit = Export::whereDate('created_at', $searchDate)->get()->sum(function($export) {
            $quantities = json_decode($export->product_quantity, true);
            $prices = json_decode($export->product_price, true);
            $total = 0;
            foreach ($quantities as $index => $quantity) {
                $total += (float)$quantity * (float)$prices[$index];
            }
            return $total;
        });

        $totalComponents = Export::whereDate('created_at', $searchDate)->whereNotNull('product_name')->count();
    }

        $exports = Export::whereDate('created_at',$currentDate);
        
        return view('staff.exported-products',[
            'products' => Product::all(),
            'exports' => $exports->latest()->filter(request(['search']))->paginate(10),
            // 'export' => $exports,
            'comments' => Transfer::all(),
        ],compact('myexports','dateProfit','totalComponents', 'currentDate'));
    }

    public function all_stores(){
        return view('staff.all-stores',[
            'stores' => Store::all(),
            'comments' => Transfer::all(),
        ]);
    }

    public function recommended(){
        return view('staff.recommended',[
            'transfers' => Transfer::latest()->filter(request(['search']))->paginate(10),
            'comments' => Transfer::latest()->filter(request(['search']))->paginate(10),
        ]);
    }

    public function edit_transfer_status(Request $request, Transfer $transfer){
        $transferStatus = $request->validate([
            'status' => 'required',
        ]);

        $transfer->update($transferStatus);

        return redirect()->back()->with('status_updated_success','Transfer checked successfully!');
    }

    public function print_doc($id){
        $product = Export::find($id);
        // $product->product_name = json_decode($product->product_name, true);
        // $product->product_quantity = json_decode($product->product_quantity, true);
        // $product->product_price = json_decode($product->product_price, true);
        return view('staff.print',[
            'product' => $product,
            'comments' => Transfer::all(),
        ]);
    }

    public function instock_product(){
        return view('staff.instock-products',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
            'comments' => Transfer::all(),
        ]);
    }

    public function less_stock(){
        return view('staff.less-product',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
            'comments' => Transfer::all(),
        ]);
    }

    public function outstock_products(){
        $outstockProduct = Product::where('product_quantity',0)->filter(request(['search']))->orderBy('id','asc')->paginate(10);
        return view('staff.less-product',[
            'products' => $outstockProduct,
            'comments' => Transfer::all(),
        ]);
    }

    public function view_comment(){
        $comments = Comment::orderBy('id','asc')->get();
        return view('staff.view-comments', compact('comments'));
    }
}
