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

        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

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
        ], compact('staffNameConut'));
    }

    public function staff_profile(){
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        return view('staff.profile',[
            'comments' => Transfer::all(),
        ], compact('staffNameConut'));
    }

    public function all_products(){
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        return view('staff.all-products',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
            'stores' => Store::all(),
            'comments' => Transfer::all(),
        ], compact('staffNameConut'));
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

        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        return view('staff.single-export',[
            'product' => $product,
            'stores' => Store::all(),
            'users' => User::all(),
            'sales' => $sales,
            'chartData' => $chartData,
            'comments' => Transfer::all(),
        ], compact('staffNameConut'));
    }

    public function exported_products(Request $request){
        $currentDate = Carbon::now()->format('Y-m-d');

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

        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();
        
        return view('staff.exported-products',[
            'products' => Product::all(),
            'exports' => $exports->latest()->filter(request(['search']))->paginate(10),
            // 'export' => $exports,
            'comments' => Transfer::all(),
        ],compact('myexports','dateProfit','totalComponents', 'currentDate','staffNameConut'));
    }

    public function all_stores(){
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        return view('staff.all-stores',[
            'stores' => Store::all(),
            'comments' => Comment::all(),
        ], compact('staffNameConut'));
    }

    public function recommended(){
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        $notficationCounter = Transfer::where('staff_recommeded', Auth::guard('web')->user()->staff_name)->count();

        $products = Product::all();

        $transfers = Transfer::filter(request(['search']))->orderBy('id','asc')->get();

        $productName =[];
        $quantity = [];
        $staffRecommended = [];
        $storeName = [];
        
        foreach ($transfers as $index => $transfer) {
            $productName = json_decode($transfer->product_name, true);
            $quantity = json_decode($transfer->product_quantity, true);
            $staffRecommended = json_decode($transfer->staff_recommeded, true);
            $storeName = json_decode($transfer->store_name, true);
        }
        

        return view('staff.recommended',[
            'transfers' => $transfers,
            'comments' => Comment::latest()->filter(request(['search']))->paginate(10),
        ], compact('notficationCounter','staffNameConut','products','productName','quantity','staffRecommended','storeName'));
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
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        return view('staff.print',[
            'product' => $product,
            'comments' => Comment::all(),
        ], compact('staffNameConut'));
    }

    public function instock_product(){
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        return view('staff.instock-products',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
            'comments' => Transfer::all(),
        ], compact('staffNameConut'));
    }

    public function less_stock(){
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        return view('staff.less-product',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
            'comments' => Transfer::all(),
        ], compact('staffNameConut'));
    }

    public function outstock_products(){
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();

        $outstockProduct = Product::where('product_quantity',0)->filter(request(['search']))->orderBy('id','asc')->paginate(10);
        return view('staff.less-product',[
            'products' => $outstockProduct,
            'comments' => Transfer::all(),
        ], compact('staffNameConut'));
    }

    public function view_comment(){
        $loggedInStaff = Auth::guard('web')->user()->staff_name;

       $staffNameConut = Transfer::where('staff_recommeded', $loggedInStaff)->count();
       
        $comments = Comment::orderBy('id','asc')->get();
        return view('staff.view-comments', compact('comments','staffNameConut'));
    }
}
