<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Post;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Export;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Session;
use App\Models\Transfer;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{
    //
    public function index(){
        return view('index');
    }

    public function home_page(){
        $products = Product::filter(request(['search']))->orderBy('product_name','asc')->get();
        return view('home-page', compact('products'));
    }


    public function admin_dashboard()
{
    $reconnded = (int)DB::table('transfers')->where('staff_recommeded', '!=', 0)->count();

    $instock = (int)DB::table('products')->where('product_quantity', '!=', 0)->count();

    $lessProduct = (int)DB::table('products')->where('product_quantity', '<', 20)->count();
    
    $exports = Export::select('product_name', 'created_at', 'product_quantity')
                      ->orderBy('created_at', 'asc')
                      ->get();

    $chartData = $exports->map(function($export) {
        $productNames = is_string($export->product_name) ? json_decode($export->product_name) : [];
        $productQuantities = is_string($export->product_quantity) ? json_decode($export->product_quantity) : [];

        if (!is_array($productNames) || !is_array($productQuantities) || count($productNames) !== count($productQuantities)) {
            return null;
        }

        return [
            'product_names' => $productNames,
            'created_at' => $export->created_at->format('Y-m-d'),
            'product_quantities' => $productQuantities,
        ];
    })->filter();

    $dates = $chartData->pluck('created_at')->unique()->sort()->values();

    $dataByProduct = $chartData->flatMap(function($data) {
        return collect($data['product_names'])->mapWithKeys(function($productName, $index) use ($data) {
            if (isset($data['product_quantities'][$index])) {
                return [$productName => [$data['created_at'] => $data['product_quantities'][$index]]];
            } else {
                return [];
            }
        });
    })->groupBy(function($item, $key) {
        return $key;
    })->map(function($items) {
        return $items->collapse();
    });

    return view('admin.admin-dashboard', [
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


    public function admin_profile(){
        return view('admin.profile');
    }

    public function all_products(){

        $products = DB::table('products')
        ->select('product_quantity')
        ->groupBy('product_quantity')->get();

        foreach($products as $product){
            if($product->product_quantity >= 30){
                Product::where('product_quantity', $product->product_quantity)->update(['status' => 'Good']);
            }
            else{
                Product::where('product_quantity', $product->product_quantity)->update(['status' => 'Less']);
            }
        }

        $transfers = Transfer::all();

        foreach ($transfers as $key => $transfer) {
            $transfers[$key]->product_name = json_decode($transfer->product_name, true);
            $transfers[$key]->store_name = json_decode($transfer->store_name, true);
            $transfers[$key]->product_quantity = json_decode($transfer->product_quantity, true);
        }

        $myProducts = Product::filter(request(['search']))->orderBy('id','asc')->get();

        return view('admin.all-products',[
            'stores' => Store::all(),
            'products' => $myProducts,
            'users' => User::all(),
        ], compact('transfers'));
    }

    public function store_loader(){
        return view('admin.all-stores',[
            'stores' => Store::filter(request(['search']))->paginate(10),
        ]);
    }

    public function users_loader(){
        return view('admin.users',[
            'users' => User::filter(request(['search']))->paginate(10),
        ]);
    }

    public function report_loader(Request $request) {
        $transfers = Export::filter(request(['search']))->get();

        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($transfers as $key => $exported) {
            $transfers[$key]->productIds = json_decode($exported->product_id, true);
            $transfers[$key]->productNames = json_decode($exported->product_name, true);
            $transfers[$key]->quantities = json_decode($exported->product_quantity, true);
            $transfers[$key]->prices = json_decode($exported->product_price, true);
            $transfers[$key]->customerName = json_decode($exported->tin, true);

            if (is_array($transfers[$key]->quantities) && is_array($transfers[$key]->prices)) {
                foreach ($transfers[$key]->quantities as $index => $qty) {
                    $totalQuantity += $qty; 
                    $totalPrice += $qty * $transfers[$key]->prices[$index];
                }
            } else {
                $totalQuantity += $transfers[$key]->quantities;
                $totalPrice += $transfers[$key]->quantities * $transfers[$key]->prices;
            }
        }

        if ($request->has(['start_date', 'end_date']) && $request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $transfers = Export::whereBetween('created_at', [$startDate, $endDate])->get();
            $datePrice = 0;

            foreach ($transfers as $filteredExport) {
                $quantities = json_decode($filteredExport->product_quantity, true);
                $prices = json_decode($filteredExport->product_price, true);

                if (is_array($quantities) && is_array($prices)) {
                    foreach ($quantities as $key => $qty) {
                        $datePrice += $qty * $prices[$key];
                    }
                } else {
                    $datePrice += $quantities * $prices;
                }
            }
        } else {
            $datePrice = $totalPrice;
        }

        return view('admin.reports', [
            'exports' => $transfers,
            'myexports' => $totalPrice,
        ], compact('datePrice', 'totalQuantity', 'totalPrice'));
    }

    public function inventory_report(Request $request)
{
    $stores = Store::all();
    $products = Product::all();

    $totalQuantity = 0;
    $totalPrice = 0;
    $totalQuantityOnHand = 0;
    $ToatlQuantityIn = 0;
    $TotalQuantityOut = 0;

    if ($request->has(['start_date', 'end_date']) && $request->start_date && $request->end_date) {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $products = Product::whereBetween('created_at', [$startDate, $endDate])->get();
    }

    foreach ($products as $product) {
        $quantityOut = 0;
        $quantityOrdered = 0;

        $exports = Export::all();

        foreach ($exports as $export) {
            $exportedItems = json_decode($export->product_name, true);
            $exportedQuantities = json_decode($export->product_quantity, true);

            if (is_array($exportedItems) && is_array($exportedQuantities)) {
                foreach ($exportedItems as $index => $item) {
                    if (isset($item['product_name']) && isset($exportedQuantities[$index]['product_quantity'])) {
                        if (trim(strtolower($item['product_name'])) == trim(strtolower($product->product_name))) {
                            $quantityOut += $exportedQuantities[$index]['product_quantity'];
                        }
                    }
                }
            }
        }

        $quantityOrdered = $product->product_quantity + $quantityOut;

        $quantityOnHand = $product->product_quantity - $quantityOut;

        $product->quantity_ordered = $quantityOrdered;
        $product->quantity_out = $quantityOut;
        $product->quantity_on_hand = $quantityOnHand;

        $totalQuantityOnHand += $product->quantity_on_hand;
        $totalQuantity += $product->product_quantity;
        $totalPrice += $product->product_price * $product->product_quantity;
        $ToatlQuantityIn += $product->product_quantity;
        $TotalQuantityOut += $product->quantity_out;
    }

    return view('admin.inventory-report', compact('products', 'stores', 'totalQuantity', 'totalPrice','totalQuantityOnHand','ToatlQuantityIn','TotalQuantityOut'));
}


    public function recommended_product(){

        $transferProdt = Transfer::all();
        $productId = [];
        $createdAt = [];
        $productName = [];
        $productQuantity = [];
        $staffRecommended = [];
        $sourceStore = [];
        $destinationStore = [];

        foreach ($transferProdt as $transfer) {
            $productId = json_decode($transfer->id, true);
            $createdAt = json_decode($transfer->created_at, true);
            $productName = json_decode($transfer->product_name, true);
            $productQuantity = json_decode($transfer->product_quantity, true);
            $staffRecommended = json_decode($transfer->staff_recommeded, true);
            $sourceStore = json_decode($transfer->source_store, true);
            $destinationStore = json_decode($transfer->store_name, true);
        }
        return view('admin.recommended',[
            'transfers' => $transferProdt,
        ], compact('productId','createdAt','productName','productQuantity','staffRecommended','sourceStore','destinationStore'));
    }

    public function comments_loader(){
        return view('admin.comments',[
            'comments' => Comment::latest()->filter(request(['search']))->paginate(10),
        ]);
    }

    public function settings(){
        return view('admin.settings'); 
    }

    public function exported_products(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d');

        $customerName = [];

        $exports = Export::whereDate('created_at', $currentDate)->orderBy('id', 'desc')->filter(request(['search']))->get();
        
        foreach ($exports as $export) {
            $decodedTin = json_decode($export->tin, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $customerName[] = $decodedTin;
            } else {
                $customerName[] = $export->tin;
            }
        }

        $myexports = Export::all()->sum(function($export) {
            $quantities = json_decode($export->product_quantity, true);
            $prices = json_decode($export->product_price, true);
            

            if (is_array($quantities) && is_array($prices)) {
                $total = 0;
                foreach ($quantities as $index => $quantity) {
                    if (isset($prices[$index])) {
                        $total += (float)$quantity * (float)$prices[$index];
                    }
                }
                return $total;
            }

            return 0; 
        });


        // Calculate total of all products exported today
        $datePrice = Export::whereDate('created_at', $currentDate)->get()->sum(function($export) {
            $quantities = json_decode($export->product_quantity, true);
            $prices = json_decode($export->product_price, true);

            if (is_array($quantities) && is_array($prices)) {
                $total = 0;
                foreach ($quantities as $index => $quantity) {
                    if (isset($prices[$index])) {
                        $total += (float)$quantity * (float)$prices[$index];
                    }
                }
                return $total;
            }

            return 0;
        });

        // Count total components exported today
        $totalComponents = Export::whereDate('created_at', $currentDate)->whereNotNull('product_name')->count();

        if ($request->has('search') && $request->search != '') {
            $searchDate = $request->search;

            $datePrice = Export::whereDate('created_at', $searchDate)->get()->sum(function($export) {
                $quantities = json_decode($export->product_quantity, true);
                $prices = json_decode($export->product_price, true);

                if (is_array($quantities) && is_array($prices)) {
                    $total = 0;
                    foreach ($quantities as $index => $quantity) {
                        if (isset($prices[$index])) {
                            $total += (float)$quantity * (float)$prices[$index];
                        }
                    }
                    return $total;
                }

                return 0; 
            });

            $totalComponents = Export::whereDate('created_at', $searchDate)->whereNotNull('product_name')->count();
        }

        return view('admin.exported-products', [
            'products' => Product::all(),
            'exports' => $exports,
        ], compact('myexports', 'datePrice', 'totalComponents', 'currentDate','customerName'));
    }


    public function view_all_sales(Request $request){
        $currentDate = Carbon::now()->format('Y-m-d');

        $sales = Export::all();

        $customerName = [];

        $exports = Export::orderBy('id', 'desc')->get();
        
        foreach ($exports as $export) {
            $decodedTin = json_decode($export->tin, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $customerName[] = $decodedTin;
            } else {
                $customerName[] = $export->tin;
            }
        }

        $myexports = Export::all()->sum(function($export) {
            $quantities = json_decode($export->product_quantity, true);
            $prices = json_decode($export->product_price, true);

            if (is_array($quantities) && is_array($prices)) {
                $total = 0;
                foreach ($quantities as $index => $quantity) {
                    if (isset($prices[$index])) {
                        $total += (float)$quantity * (float)$prices[$index];
                    }
                }
                return $total;
            }

            return 0; 
        });

        $datePrice = $myexports;
        $totalComponents = Export::whereNotNull('product_name')->count();

        if ($request->has('search') && $request->search != '') {
            $searchDate = $request->search;

            $exports = Export::whereDate('created_at', $searchDate)->get();

            $datePrice = Export::whereDate('created_at', $searchDate)->get()->sum(function($export) {
                $quantities = json_decode($export->product_quantity, true);
                $prices = json_decode($export->product_price, true);

                if (is_array($quantities) && is_array($prices)) {
                    $total = 0;
                    foreach ($quantities as $index => $quantity) {
                        if (isset($prices[$index])) {
                            $total += (float)$quantity * (float)$prices[$index];
                        }
                    }
                    return $total;
                }

                return 0; 
            });

            $totalComponents = Export::whereDate('created_at', $searchDate)->whereNotNull('product_name')->count();
        }

        // $exports =  Export::whereDate('created_at', $currentDate);

        return view('admin.all-sales', [
            'products' => Product::all(),
            'exports' => $exports,
        ], compact('myexports', 'datePrice', 'totalComponents', 'currentDate','customerName'));
    }

    public function instock_product(){
        return view('admin.instock-products',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
        ]);
    }

    public function loans_products(Request $request) {
        $todayDate = Carbon::now()->format('Y-m-d');
    
        $exports = Export::filter(request(['search']))->get();
    
        $decodedExports = [];
        $customer_name = [];
    
        foreach ($exports as $export) {
            $decodedExports[] = [
                'id' => $export->id,
                'customerNames' => json_decode($export->tin, true),
                'productName' => json_decode($export->product_name, true),
                'unitPrice' => json_decode($export->product_price, true),
                'product_quantity' => json_decode($export->product_quantity, true),
                'staffName' => json_decode($export->staff_name, true),
                'status' => json_decode($export->status, true),
                'payment_date' => json_decode($export->payment_date, true),
                'saleMode' => json_decode($export->sale_mode, true),
                'created_at' => $export->created_at,
            ];
            $customer_name[] = json_decode($export->customer_name, true);
        }

        if($request->has(['start_date','end_date']) && $request->start_date != "" && $request->end_date != ""){
            $fromDate = $request->start_date;
            $toDate = $request->end_date;

            $exports = Export::whereJsonContains('payment_date', function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('payment_date', [$fromDate, $toDate]);
            })->get();
        }
    
        return view('admin.loans-product', [
            'exports' => $decodedExports,
            'todayDate' => $todayDate,
        ], compact('customer_name'));
    }
    
    public function less_product(){

        // $lessProducts = DB::table('products')
        // ->select('product_quantity', DB::ra('count(product_quantity) as quantity_cunter'))
        // ->groupBy('product_quantity')->get();

        // foreach($quantity_cunter as $counter){
            
        //     $outCounter = Product::where('product_quantity',$product->product_quantity);
            
        // }

        return view('admin.less-product',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
        ]);
    }

    public function outstock_product(){
        return view('admin.outstock-product',[
            'products' => Product::filter(request(['search']))->orderBy('id','asc')->paginate(10),
        ]);
    }

    public function store_users(Request $request){
        $userDetails = $request->validate([
            'staff_id' => 'required|string|max:255',
            'staff_name' => 'required|string|max:255',
            'staff_role' => 'required|string|max:255',
            'staff_email' => 'required|email|max:255',
            'staff_phone' => 'required|string|max:20',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'profile' =>'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('profile') && $request->file('profile')->isValid()) {
            $userDetails['profile'] = $request->file('profile')->store('profiles', 'public');
        }

        $userExists = User::where('staff_id', $request->input('staff_id'))->first();

        if ($userExists) {
            return redirect()->back()->with('user_exists', 'Sorry! User exists!');
        }

        $user = User::create($userDetails);

        // dd($request->all());

        if($user){
            return redirect()->back()->with('message_flash','User created successfully!');
        }
    }

    public function create_stores(Request $request){
        $storeDetails = $request->validate([
            'store_id' => 'required',
            'store_name' => 'required',
            'store_location' => 'required',
            'district' => 'required',
            'street' => 'required',
        ]);

        $existingStore = Store::where('store_id', $request->input('store_id'))->first();

        if($existingStore){
            return redirect()->back()->with('warning_flash','Sorry! Store exists!');
        }

        Store::create($storeDetails);

        return redirect()->back()->with('store_created_flash','Store created successfully!');
    }

    public function store_products(Request $request){
        $productsDetails = $request->validate([
            'product_id' => 'required',
            'product_name' => 'required|string|max:255',
            'product_quantity' => 'required|string|max:255',
            'product_price' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'description' => 'required:max:20',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'level' => 'nullable|integer',
        ]);

        $existingProduct = Product::where('product_id', $request->input('product_id'))->first();

        if($existingProduct){
            return redirect()->back()->with('exist_flash_msg','Sorry!, Product exists...');
        }

        if($request->hasFile('product_image')){
            $productsDetails['product_image'] = $request->file('product_image')->store('products','public');
        }

        Product::create($productsDetails);
        return redirect()->back()->with('product_created','Product created successfully!');
    }

    public function authentication(Request $request){
        $userLogin = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if(Auth::guard('web')->attempt($userLogin) && Auth::guard('web')->user()->staff_role == 'Admin'){

            $request->session()->regenerate();

            return redirect('/admin/admin-dashboard')->with('success_flash_msg','Congratulations! You Logged in successfully!');

        }else if(Auth::guard('web')->attempt($userLogin) && Auth::guard('web')->user()->staff_role == 'Staff'){

            $request->session()->regenerate();

            return redirect('/staff/staff-dashboard')->with('staff_login_success','Staff logged in successfully!');

        }else{

            return redirect()->back()->with('error_login','Incorrect username or password!');

        }

    }

    public function edit_profile_details(Request $request, User $user){
        $profileDetails = $request->validate([
            'staff_id' => 'required',
            'staff_name' => 'required',
            'staff_email' => 'required',
            'staff_phone' => 'required',
            'profile' => 'required',
        ]);
        if($request->hasFile('profile')){
            $profileDetails['profile'] = $request->file('profile')->store('profiles','public');
        }

        $user->update($profileDetails);

        return redirect()->back()->with('success_update','Profile updated successfully!');
    }

    public function edit_user_pass(Request $request, User $user){
        $user_passDetails = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'password_confirm' => 'required',
        ]);

        $password = $request->password;
        $password_confirm = $request->password_confirm;

        if($password != $password_confirm){
            return redirect()->back()->with('error_code','Sorry! Passwords do not match!');
        }else{
            
        $user->update([
            $user_passDetails['username'],
            $user_passDetails['password'],
        ]);

        return redirect()->back()->with('user_pass','Passwords updated successfully!');
        }
    }

    public function invalidate_users(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect('/index')->with('logout_flash_mgs','Logged out successfully!');
    }


    public function single_export($id)
    {

        $product = Product::find($id);

        $sales = Export::where('id', $id)->orderBy('created_at', 'asc')->get();

        $chartData = $sales->map(function($sale) {
            return [
                'created_at' => $sale->created_at->format('Y-m-d'),
                'product_quantity' => $sale->quantity,
                'product_name' => $sale->product_name,
            ];
        });

        $transfers = Transfer::all();

        foreach ($transfers as $key => $transfer) {
            $transfers[$key]->product_name = json_decode($transfer->product_name, true);
            $transfers[$key]->store_name = json_decode($transfer->store_name, true);
            $transfers[$key]->product_quantity = json_decode($transfer->product_quantity, true);
        }

        return view('admin.single-export', [
            // 'transfers' => Transfer::all(),
            'stores' => Store::all(),
            'product' => $product,
            'stores' => Store::all(),
            'users' => User::all(),
            'sales' => $sales,
            'chartData' => $chartData,
        ], compact('transfers'));
    }
    
    
    public function store_exports(Request $request) {
        $validatedData = $request->validate([
            'tin' => 'nullable',
            'product_name.*' => 'required',
            'customer_name' => 'nullable',
            'staff_name.*' => 'required',
            'product_quantity.*' => 'required|integer|min:1',
            'product_price.*' => 'required|numeric|min:0',
            'phone.*' => 'nullable|min:10|max:15',
            'sale_mode.*' => 'nullable|string|max:255',
            'payment_date.*' => 'nullable',
        ]);

        $tin = $validatedData['tin'];
        $productNames = $validatedData['product_name'];
        $customerNames = $validatedData['customer_name'];
        $staffNames = $validatedData['staff_name'];
        $productQuantities = $validatedData['product_quantity'];
        $productPrices = $validatedData['product_price'];
        $clientPhone = $validatedData['phone'];
        $sale_mode = $validatedData['sale_mode'];
        $payment_date = $validatedData['payment_date'];

    
        foreach ($productNames as $index => $productName) {
            $product = Product::where('product_name', $productName)->first();
            if ($product->product_quantity < $productQuantities[$index]) {
                return redirect()->back()->with('not_enough', 'Product quantity is not enough for ' . $productName);
            }
            
            $product->product_quantity -= $productQuantities[$index];
            $product->save();
        }

        $customerNames = is_array($customerNames) ? json_encode($customerNames) : $customerNames;
        $clientPhone = is_array($clientPhone) ? json_encode($clientPhone) : $clientPhone;
        $tin = is_array($tin) ? json_encode($tin) : $tin;
        $sale_mode = is_array($sale_mode) ? json_encode($sale_mode) : $sale_mode;
        $payment_date = is_array($payment_date) ? json_encode($payment_date) : $payment_date;

        Export::create([
            'tin' => $tin,
            'product_name' => json_encode($productNames),
            'customer_name' => $customerNames,
            'staff_name' => json_encode($staffNames),
            'product_quantity' => json_encode($productQuantities),
            'product_price' => json_encode($productPrices),
            'phone' => $clientPhone,
            'sale_mode' => $sale_mode,
            'payment_date' => $payment_date,
        ]);

        // dd($request->all());
    
        return redirect()->back()->with('export_message', 'Products exported successfully!');
    }
    
    public function edit_loan_details(Request $request, Export $export)
    {
        $loanDetails = $request->validate([
            'status.*' => 'required|string',
            'payment_date.*' => 'required|date', 
        ]);

        $loanStatus = $loanDetails['status'];
        $loanPaymentDate = $loanDetails['payment_date'];

        $export->update([
            'status' => json_encode($loanStatus),
            'payment_date' => json_encode($loanPaymentDate),
        ]);

        // dd($request->all());

        return redirect()->back();
    }


    public function make_orders(Request $request){
        $orders = Order::filter(request(['search']))->orderBy('id','desc')->get();

        $productName = [];
        $quantity = [];
        foreach ($orders as $key => $order) {
            $productName[] = json_decode($order->product_name, true);
            $quantity[] = json_decode($order->quantity, true);
        }

        if($request->has(['start_date','end_date']) && $request->start_date !="" && $request->end_date !=""){
            $fromDate = $request->start_date;
            $toDate = $request->end_date;

            $orders = Order::whereBetween('created_at', [$fromDate,$toDate])->get();
        }

        $containers = Container::orderBy('id','asc')->get();
        $posts = Post::filter(request(['search']))->orderBy('id','asc')->get();
        return view('admin.create-orders', compact('posts','containers','orders','productName','quantity'));
    }

    public function post_orders(Request $request)
    {

        $orderDetails = $request->validate([
            'order_name' => 'required|max:255',
            'staff_name' => 'required|string|max:255',
            'container_id' => 'required',
            // 'product_name' => 'required',
            // 'quantity' => 'required|integer',
        ]);

        $orderName = $orderDetails['order_name'];
        $staffName = $orderDetails['staff_name'];
        $containerIds = $orderDetails['container_id'];
        // $productNames = $orderDetails['product_name'];
        // $quantities = $orderDetails['quantity'];

        Order::create([
            'order_name' => $orderName,
            'staff_name' => $staffName,
            'container_id' => $containerIds,
            // 'product_name' => $productNames,
            // 'quantity' => $quantities,
        ]);

        return redirect()->back()->with('success_created', 'Order created successfully!');
    }


    public function single_order($id){
        $containers = Container::all();

        $order = Order::find($id);
        $orderId = json_decode($order->id, true);
        $staffName = json_decode($order->staff_name, true);
        $containerId = json_decode($order->container_id, true);
        $productName = json_decode($order->product_name, true);
        $quantity = json_decode($order->quantity, true);
        return view('admin.view-more', compact('order','staffName','containerId','productName','quantity','containers'));
    }

    public function create_products(Request $request){
        $postPrpduct = $request->validate([
            'product_id' => ['required', Rule::unique('posts','product_id')],
            'product_name' => 'required|string|max:255',
            'cbm' => 'required|numeric',
            // 'length' => 'required|numeric',
            // 'width' => 'required|numeric',
            // 'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
        
        $existingPost = Post::where('product_id', $request->input('product_id'))->first();

        if($existingPost){
            return redirect()->back()->withErrors('Product already exists!')->withInput();
        }

        Post::create($postPrpduct);

        return redirect()->back()->with('prod_created_flash_msg', 'Product registered successfully!');
    }
    
    

    public function edit_product_imported(Request $request, Product $product){
        $editedImported = $request->validate([
            // 'product_id' => 'required',
            'product_name' => 'required',
            'product_quantity' => 'required',
            'product_price' => 'required',
            'store_name' => 'required',
            'description' => 'required',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($request->hasFile('product_image')){
            $editedImported['product_image'] = $request->file('product_image')->store('products','public');
        }

        $product->update($editedImported);

        return redirect()->back()->with('import_success','Product impoted successfully');

    }

    public function transfer_product(Request $request)
{
    $validatedData = $request->validate([
        'product_name.*' => 'required',
        'staff_name.*' => 'required',
        'store_name.*' => 'required',
        'staff_recommeded.*' => 'required',
        'product_quantity.*' => 'required|integer|min:1',
        'source_store.*' => 'required',
        'reason.*' => 'required|max:255'
    ]);

    $productNames = $validatedData['product_name'];
    $staffNames = $validatedData['staff_name'];
    $storeNames = $validatedData['store_name'];
    $staffRecommended = $validatedData['staff_recommeded'];
    $productQuantities = $validatedData['product_quantity'];
    $sourceStores = $validatedData['source_store'];
    $reasons = $validatedData['reason'];

    foreach ($productNames as $index => $productName) {
        $product = Product::where('product_name', $productName)->first();
        if ($product->product_quantity < $productQuantities[$index]) {
            return redirect()->back()->withErrors('not_enough', 'Product quantity is not enough to transfer for product ' . $productName)->withInput();
        }
        // Reduce the quantity of each product
        $product->product_quantity -= $productQuantities[$index];
        $product->save();
    }

    Transfer::create([
        'product_name' => json_encode($productNames), 
        'staff_name' => json_encode($staffNames), 
        'store_name' => json_encode($storeNames), 
        'staff_recommeded' => json_encode($staffRecommended),
        'product_quantity' => json_encode($productQuantities), 
        'source_store' => json_encode($sourceStores), 
        'reason' => json_encode($reasons),
    ]);

    return redirect()->back()->with('success_transfer', 'Products transferred successfully!');
}



    public function store_comments(Request $request){
        $commentsDetails = $request->validate([
            'product_name' => 'required',
            'comment'=> 'required|string|max:255',
            'store_name' => 'required',
            'commented_staff' => 'required',
        ]);

        Comment::create($commentsDetails);

        return redirect()->back()->with('comment_sent','Comment sent successfully!');
    }

    public function single_user_load($id){
        return view('admin.single-user',[
            'user' => User::find($id),
        ]);
    }

    public function edit_user(Request $request, User $user){
        $editedProfile = $request->validate([
            'staff_id' => 'required',
            'staff_name' => 'required',
            'staff_role' => 'required',
            'staff_email' => 'required',
            'staff_phone' => 'required',
            'username' => 'required',
            'password' => 'required',
            'profile' => 'nullable',
        ]);

        if($request->hasFile('profile')){
            $editedProfile['profile'] = $request->file('profile')->store('profiles','public');
        }

        $user->update($editedProfile);
        return redirect()->back()->with('success_edit','User updated successfully!');
    }

    public function delete_user(Request $request, User $user){
        $user->delete();
        return redirect('/admin/users')->with('success_delete','User deleted successfully!');
    }

    public function show_single_store($id)
    {
        $product_name = Product::select('product_name')->groupBy('product_name')->get();
        $productDescrp = Product::select('description')->groupBy('description')->get();

        $store = Store::find($id);

        $singleTransfer = Transfer::all();

        $storeName = [];
        $productName = [];
        $productQuantity = [];
        $created_at = [];

        foreach ($singleTransfer as $index => $transfer) {
            $storeName[] = $this->decodeJson($transfer->store_name);
            $productName[] = $this->decodeJson($transfer->product_name);
            $productQuantity[] = $this->decodeJson($transfer->product_quantity);
            $created_at[] = $this->decodeJson($transfer->created_at);
        }
        
        return view('admin.single-store', [
            'users' => User::all(),
            'store' => $store,
            'products' => Product::orderBy('id', 'asc')->get(),
            'transfers' => Transfer::all(),
        ], compact('storeName', 'productName', 'productQuantity', 'created_at', 'product_name', 'productDescrp'));
    }

    private function decodeJson($data)
    {
        return is_string($data) && is_array(json_decode($data, true)) ? json_decode($data, true) : $data;
    }


    public function edit_store(Request $request, Store $store){
        $storeDetailsEdit = $request->validate([
            'store_id' => 'required',
            'store_name' => 'required',
            'store_location' => 'required',
        ]);

        $store->update($storeDetailsEdit);

        return redirect()->back()->with('success_store_edit','Store edited successfully!');
    }

    public function delete_store_details(Request $request, Store $store){
        $store->delete();
        return redirect('/admin/all-stores')->with('delete_success','Store deleted successfully!');
    }

    public function reset_password(){
        return view('reset-password');
    }

    public function edit_export_product($id){
        return view('admin.edit-exproduct',[
            'product' => Export::find($id),
        ]);
    }

    public function edit_sales(Request $request, Export $product){
        $salesData = $request->validate([
            // 'product_image' => 'required',
            // 'product_id' => 'required',
            'product_name' => 'required',
            'customer_name' => 'required',
            'staff_name' => 'required',
            'product_quantity' => 'required',
            'product_price' => 'required',
        ]);

        $product->update($salesData);

        return redirect('/admin/exported-products')->with('edit_success_sale','Sales updated successfully!');
    }

    public function tranfered_products(){
        $exports = Transfer::select('product_name', 'created_at', 'product_quantity')
                      ->orderBy('created_at', 'asc')
                      ->get();

    $chartData = $exports->map(function($export) {
        $productNames = is_string($export->product_name) ? json_decode($export->product_name) : [];
        $productQuantities = is_string($export->product_quantity) ? json_decode($export->product_quantity) : [];

        if (!is_array($productNames) || !is_array($productQuantities) || count($productNames) !== count($productQuantities)) {
            return null;
        }

        return [
            'product_names' => $productNames,
            'created_at' => $export->created_at->format('Y-m-d'),
            'product_quantities' => $productQuantities,
        ];
    })->filter();

    $dates = $chartData->pluck('created_at')->unique()->sort()->values();

    $dataByProduct = $chartData->flatMap(function($data) {
        return collect($data['product_names'])->mapWithKeys(function($productName, $index) use ($data) {
            if (isset($data['product_quantities'][$index])) {
                return [$productName => [$data['created_at'] => $data['product_quantities'][$index]]];
            } else {
                return [];
            }
        });
    })->groupBy(function($item, $key) {
        return $key;
    })->map(function($items) {
        return $items->collapse();
    });
        $nowDate = Carbon::now()->format('Y-m-d');
        return view('admin.transfered-products',[
            'transfers' => Transfer::latest()->filter(request(['search']))->paginate(10),
            'chartData' => $dataByProduct,
            'dates' => $dates,
        ], compact('nowDate'));
    }

    public function single_transfer($id){

        $stores = store::all();

        $nowDate = Carbon::now()->format('Y-m-d');

        $transfer = Transfer::find($id);

        $createdAt = json_decode($transfer->created_at, true);
        $staff_name = json_decode($transfer->staff_name, true);
        $reason = json_decode($transfer->reason, true);
        $staff_recommended = json_decode($transfer->staff_recommeded, true);
        $sourceStores = json_decode($transfer->source_store, true);
        $destinationStores = json_decode($transfer->store_name, true);
        $productNames = json_decode($transfer->product_name, true);
        $productQuantities = json_decode($transfer->product_quantity, true);

        return view('admin.transfered-item',[
            'transfer' => $transfer,
        ], compact('productNames', 'productQuantities', 'sourceStores', 'destinationStores','createdAt','staff_name','reason','staff_recommended','nowDate','stores'));
    }

    public function all_transfers(){
        $nowDate = Carbon::now()->format('Y-m-d');
        return view('admin.all-transfers',[
            'transfers' => Transfer::latest()->filter(request(['search']))->get(),
        ], compact('nowDate'));
    }

    public function print_invoice($id){
        $product = Export::find($id);
        $product->customer_name = json_decode($product->customer_name, true);
        $product->phone = json_decode($product->phone, true);
        $product->tin = json_decode($product->tin, true);
        return view('admin.print',[
            'product' => $product,
            'customer_name' => $product->customer_name,
            'customerPhone' => $product->phone,
            'tin' => $product->tin,
        ]);
    }

    public function delete_single_product(Request $request, Product $product){
        $product->delete();
        return redirect()->back()->with('success_delete_product','Product deleted successfully!');
    }

    public function system_logs(){
        $sessions = Session::filter(request(['search']))->orderBy('last_activity','desc')->get();
        return view('admin.logs', compact('sessions'));
    }

    public function transfer_report(Request $request)
    {
        $transfers = Transfer::all();
        $quantity = [];
        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($transfers as $key => $product) {
            $transfers[$key]->quantity = json_decode($product->product_quantity, true);
            $transfers[$key]->price = json_decode($product->product_price, true);
            $transfers[$key]->productId = json_decode($product->product_id, true);
            $transfers[$key]->productName = json_decode($product->product_name, true);
            $transfers[$key]->productDate = json_decode($product->created_at, true);
            $transfers[$key]->sourceStore = json_decode($product->source_store, true);
            $transfers[$key]->destinationStore = json_decode($product->store_name, true);

            $sourceStore = json_decode($product->source_store, true);
            $destinationStore = json_decode($product->store_name, true);
            $reason = json_decode($product->reason, true);
            $staffRec = json_decode($product->staff_recommeded, true);
            $status = json_decode($product->status, true);

            if (is_array($transfers[$key]->quantity) && is_array($transfers[$key]->price)) {
                foreach ($transfers[$key]->quantity as $idx => $qty) {
                    $totalQuantity += $qty;
                    $totalPrice += $qty * $transfers[$key]->price[$idx];
                }
            } elseif (is_numeric($transfers[$key]->quantity) && is_numeric($transfers[$key]->price)) {
                $totalQuantity += $transfers[$key]->quantity;
                $totalPrice += $transfers[$key]->quantity * $transfers[$key]->price;
            }
        }

        $myexports = $totalPrice;

        if ($request->has(['start_date', 'end_date']) && $request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $transfers = Transfer::whereBetween('created_at', [$startDate, $endDate])->get();

            $datePrice = 0;
            foreach ($transfers as $filteredExport) {
                $filteredQuantity = json_decode($filteredExport->product_quantity, true);
                $filteredPrice = json_decode($filteredExport->product_price, true);

                if (is_array($filteredQuantity) && is_array($filteredPrice)) {
                    foreach ($filteredQuantity as $idx => $qty) {
                        $datePrice += $qty * $filteredPrice[$idx];
                    }
                } else {
                    if (is_numeric($filteredQuantity) && is_numeric($filteredPrice)) {
                        $datePrice += $filteredQuantity * $filteredPrice;
                    }
                }
            }
        } else {
            $datePrice = $myexports;
        }

        $flattenArray = function ($array) {
            $flatArray = [];
            array_walk_recursive($array, function ($value) use (&$flatArray) {
                $flatArray[] = $value;
            });
            return $flatArray;
        };

        $quantities = array_column($transfers->toArray(), 'quantity');
        $flatQuantities = $flattenArray($quantities);
        $myquantities = array_sum($flatQuantities);

        return view('admin.transfer-report', [
            'totalQuantity' => $myquantities,
            'products' => Product::all(),
        ], compact('datePrice', 'transfers','sourceStore','destinationStore','staffRec','reason','status'));
    }

    public function delete_order(Request $request, Order $order){
        $order->delete();
        return redirect('/admin/create-orders')->with('order_deleted_flash_msg','Order deleted successfully!');
    }

    public function edit_order(Request $request, Order $order)
    {
        // Validate the input
        $orderDetails = $request->validate([
            'order_name' => 'required',
            'container_id' => 'required',
            'product_name.*' => 'required',
            'quantity.*' => 'required',
        ]);

        $orderName = $orderDetails['order_name'];
        $containerId = $orderDetails['container_id'];
        $productName = $orderDetails['product_name']; 
        $quantity = $orderDetails['quantity']; 

        $existingProductNames = is_array($order->product_name) ? $order->product_name : json_decode($order->product_name, true);
        $existingQuantities = is_array($order->quantity) ? $order->quantity : json_decode($order->quantity, true);

        $mergedProductNames = array_merge($existingProductNames ?? [], $productName);
        $mergedQuantities = array_merge($existingQuantities ?? [], $quantity);

        $order->update([
            'order_name' => $orderName,
            'container_id' => $containerId,
            'product_name' => json_encode($mergedProductNames), 
            'quantity' => json_encode($mergedQuantities), 
        ]);

        return redirect()->back()->with('order_updated_msg', 'Order updated successfully!');
    }

    public function view_posters(Request $request){
        $posts = Post::orderBy('id','asc')->get();

        if($request->has(['start_date','end_date']) && $request->start_date !="" && $request->end_date !=""){
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $posts = Post::whereBetween('created_at', [$startDate,$endDate])->get();
        }
        return view('admin.view-products', compact('posts'));
    }

    public function delete_post_product(Request $request, Post $post){
        $post->delete();
        return redirect()->back();
    }

    public function edit_post_product(Request $request, Post $post){
        $postDetails = $request->validate([
            'product_id' => 'required',
            'product_name' => 'required',
            'price' => 'required',
            'weight' => 'required',
            'cbm' => 'required',
        ]);

        $post->update($postDetails);

        return redirect()->back();
    }

    public function view_order($id){
        $containers = Container::all();
        $order = Order::find($id);
        
        $productName = json_decode($order->product_name, true);
        $quantity = json_decode($order->quantity, true);

        $posts = Post::all();
        return view('admin.view-order', compact('posts','order','containers','productName','quantity'));
    }

    public function add_product_order(Request $request, Order $order){
        $orderProductsAdd = $request->validate([
            'product_name.*' => 'required',
            'quantity.*' => 'required', 
        ]);

        $productName = $orderProductsAdd['product_name'];
        $quantity = $orderProductsAdd['quantity'];

        $order->update([
            'product_name' => json_encode($productName),
            'quantity' => json_encode($quantity),
        ]);

        return redirect()->back()->with('success_order_msg','Order Products added to this order!');
    }

    public function loans_manager(Request $request){

        $todayDate = Carbon::now()->format('Y-m-d');

        $loans = Loan::filter(request(['search']))->orderBy('id','asc')->get();

        // $productNames = [];
        // $unitPrice = [];
        // $quantities = [];
        foreach ($loans as $key => $loanrecord) {
            $loans[$key]->productNames = json_decode($loanrecord->product_name, true);
            $loans[$key]->unitPrice = json_decode($loanrecord->product_price, true);
            $loans[$key]->quantities = json_decode($loanrecord->product_quantity, true);
        }

        if($request->has(['start_date','end_date']) && $request->start_date != ""  && $request->end_date != ""){
            $fromDate = $request->start_date;
            $toDate = $request->end_date;

            $loans = Loan::whereBetween('created_at', [$fromDate,$toDate])->get();
        }

        $products = Product::all();

        return view('admin.loans', compact('products','loans','todayDate'));
    }

    public function store_loans(Request $request){
        $loansDetails = $request->validate([
            'customer_name' => 'required',
            'tin' => 'required',
            'phone' => 'required',
            'product_name.*' => 'required',
            'staff_name' => 'required',
            'product_quantity.*' => 'required',
            'product_price.*' => 'required',
            'payment_date' => 'required',
        ]);

        $customerName = $loansDetails['customer_name'];
        $tin = $loansDetails['tin'];
        $phone = $loansDetails['phone'];
        $productName = $loansDetails['product_name'];
        $staffName = $loansDetails['staff_name'];
        $quantity = $loansDetails['product_quantity'];
        $price = $loansDetails['product_price'];
        $paymentDate = $loansDetails['payment_date'];

        Loan::create([
            'customer_name' => $customerName,
            'tin' => $tin,
            'phone' => $phone,
            'product_name' => json_encode($productName),
            'staff_name' => $staffName,
            'product_quantity' => json_encode($quantity),
            'product_price' => json_encode($price),
            'payment_date' => $paymentDate,
        ]);

        // dd($request->all());

        return redirect()->back();
    }

    public function edit_loan(Request $request, Loan $loan){
        $loanUpdateDetaila = $request->validate([
            'payment_date' => 'required',
            'status' => 'required',
            'amount_paid' => 'required',
        ]);

        $loan->update($loanUpdateDetaila);

        // dd($request->all());

        return redirect()->back();
    }

}