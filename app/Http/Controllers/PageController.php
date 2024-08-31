<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\Export;
use App\Models\Transfer;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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

        $myProducts = Product::filter(request(['search']))->orderBy('id','asc')->get();

        return view('admin.all-products',[
            'stores' => Store::all(),
            'products' => $myProducts,
            'users' => User::all(),
        ]);
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

    public function report_loader(Request $request){
        $exportations = Export::all();
    
        $productIds = [];
        $productNames = [];
        $quantities = [];
        $prices = [];
        $productDates = [];

        $totalQuantity = 0;
        $totalPrice = 0;
    
        foreach ($exportations as $exported) {
            $quantity = json_decode($exported->product_quantity, true);
            $price = json_decode($exported->product_price, true);
            $productId = json_decode($exported->product_id, true);
            $productName = json_decode($exported->product_name, true);
            $productDate = json_decode($exported->created_at, true);
    
            $productIds = array_merge($productIds, (array) $productId);
            $productNames = array_merge($productNames, (array) $productName);
            $quantities = array_merge($quantities, (array) $quantity);
            $prices = array_merge($prices, (array) $price);
            $productDates = array_merge($productDates, (array) $productDate);
    
            if (is_array($quantity) && is_array($price)) {
                foreach ($quantity as $key => $qty) {
                    $totalQuantity += $qty;
                    $totalPrice += $qty * $price[$key];
                }
            } else {
                $totalQuantity += $quantity;
                $totalPrice += $quantity * $price;
            }
        }
    
        $myexports = $totalPrice;
    
        if ($request->has('search') && $request->search != '') {
            $searchDate = $request->search;
            $datePrice = Export::whereDate('created_at', $searchDate)
                ->sum(DB::raw('CAST(product_quantity AS NUMERIC) * CAST(product_price AS NUMERIC)'));
        } else {
            $datePrice = $myexports;
        }
    
        return view('admin.reports', [
            'exports' => Export::filter(request(['search']))->paginate(10),
            'myexports' => $myexports,
        ], compact('datePrice', 'quantities', 'prices', 'productIds', 'productNames', 'productDates'));
    }
    

    public function recommended_product(){
        $transferProdt = Transfer::all();
        $createdAt = [];
        $productName = [];
        $productQuantity = [];
        $staffRecommended = [];
        $sourceStore = [];
        $destinationStore = [];
        foreach ($transferProdt as $transfer) {
            $createdAt[] = json_decode($transfer->created_at, true);
            $productName[] = json_decode($transfer->product_name, true);
            $productQuantity[] = json_decode($transfer->product_quantity, true);
            $staffRecommended[] = json_decode($transfer->staff_recommeded, true);
            $sourceStore[] = json_decode($transfer->source_store, true);
            $destinationStore[] = json_decode($transfer->store_name, true);
        }
        return view('admin.recommended',[
            'transfers' => $transferProdt,
        ], compact('createdAt','productName','productQuantity','staffRecommended','sourceStore','destinationStore'));
    }

    public function comments_loader(){
        return view('admin.comments',[
            'comments' => Comment::latest()->latest()->filter(request(['search']))->paginate(10),
        ]);
    }

    public function settings(){
        return view('admin.settings'); 
    }

    public function exported_products(Request $request)
{
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

    $datePrice = $myexports;
    $totalComponents = Export::whereNotNull('product_name')->count();

    // Handle search functionality
    if ($request->has('search') && $request->search != '') {
        $searchDate = $request->search;

        $datePrice = Export::whereDate('created_at', $searchDate)->get()->sum(function($export) {
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

    $exports =  Export::whereDate('created_at', $currentDate);

    return view('admin.exported-products', [
        'products' => Product::all(),
        'exports' => $exports->latest()->filter(request(['search']))->paginate(10),
    ], compact('myexports', 'datePrice', 'totalComponents', 'currentDate'));
}

    public function instock_product(){
        return view('admin.instock-products',[
            'products' => Product::latest()->filter(request(['search']))->paginate(10),
        ]);
    }

    public function less_product(){

        // $lessProducts = DB::table('products')
        // ->select('product_quantity', DB::ra('count(product_quantity) as quantity_cunter'))
        // ->groupBy('product_quantity')->get();

        // foreach($quantity_cunter as $counter){
            
        //     $outCounter = Product::where('product_quantity',$product->product_quantity);
            
        // }

        return view('admin.less-product',[
            'products' => Product::filter(request(['search']))->paginate(10),
        ]);
    }

    public function outstock_product(){
        return view('admin.outstock-product',[
            'products' => Product::filter(request(['search']))->paginate(10),
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
            
        $user->update($user_passDetails);

        return redirect()->back()->with('user_pass','User data updated successfully!');
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

        return view('admin.single-export', [
            'product' => $product,
            'stores' => Store::all(),
            'users' => User::all(),
            'sales' => $sales,
            'chartData' => $chartData,
        ]);
    }
    
    
    public function store_exports(Request $request) {
        $validatedData = $request->validate([
            'product_name.*' => 'required',
            'customer_name' => 'nullable',
            'staff_name.*' => 'required',
            'product_quantity.*' => 'required|integer|min:1',
            'product_price.*' => 'required|numeric|min:0',
            'phone.*' => 'nullable|min:10|max:15',
        ]);
    
        $productNames = $validatedData['product_name'];
        $customerNames = $validatedData['customer_name'];
        $staffNames = $validatedData['staff_name'];
        $productQuantities = $validatedData['product_quantity'];
        $productPrices = $validatedData['product_price'];
        $clientPhone = $validatedData['phone'];
    
        foreach ($productNames as $index => $productName) {
            $product = Product::where('product_name', $productName)->first();
            if ($product->product_quantity < $productQuantities[$index]) {
                return redirect()->back()->with('not_enough', 'Product quantity is not enough for ' . $productName);
            }
            // Reduce the quantity of each product
            $product->product_quantity -= $productQuantities[$index];
            $product->save();
        }

        $customerNames = is_array($customerNames) ? json_encode($customerNames) : $customerNames;
        $clientPhone = is_array($clientPhone) ? json_encode($clientPhone) : $clientPhone;

    
        // Combine all data into a single JSON object
        Export::create([
            'product_name' => json_encode($productNames),
            'customer_name' => $customerNames,
            'staff_name' => json_encode($staffNames),
            'product_quantity' => json_encode($productQuantities),
            'product_price' => json_encode($productPrices),
            'phone' => $clientPhone,
        ]);
    
        return redirect()->back()->with('export_message', 'Products exported successfully!');
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
        // $product->product_quantity -= $productQuantities[$index];
        // $product->save();
    }

    // Combine all data into a single JSON object
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

    public function show_single_store($id){

        // $store->store_name = Store::select('store_name')->groupBy('store_name')->first();
        $product_name = Product::select('product_name')->groupBy('product_name')->get();
        $productDescrp = Product::select('description')->groupBy('description')->get();

        $store = Store::find($id);

        $singleTransfer = Transfer::all();

        $storeName = [];
        $productName = [];
        $productQuantity = [];
        $created_at = [];

        foreach ($singleTransfer as $index => $transfer) {
            $storeName[] = json_decode($transfer->store_name, true);
            $productName[] = json_decode($transfer->product_name, true);
            $productQuantity[] = json_decode($transfer->product_quantity, true);
            $created_at[] = json_decode($transfer->created_at, true);
        }
        
        return view('admin.single-store',[
            'users' => User::all(),
            'store' => $store,
            'products' => Product::orderBy('id','asc')->get(),
            'transfers' => Transfer::all(),
        ], compact('storeName','productName','productQuantity','created_at','product_name','productDescrp'));
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
        $nowDate = Carbon::now()->format('Y-m-d');
        return view('admin.transfered-products',[
            'transfers' => Transfer::latest()->filter(request(['search']))->paginate(10),
        ], compact('nowDate'));
    }

    public function single_transfer($id){

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
        ], compact('productNames', 'productQuantities', 'sourceStores', 'destinationStores','createdAt','staff_name','reason','staff_recommended','nowDate'));
    }

    public function print_invoice($id){
        $product = Export::find($id);
        $product->customer_name = json_decode($product->customer_name, true);
        $product->phone = json_decode($product->phone, true);
        return view('admin.print',[
            'product' => $product,
            'customer_name' => $product->customer_name,
            'customerPhone' => $product->phone,
        ]);
    }

    public function delete_single_product(Request $request, Product $product){
        $product->delete();
        return redirect()->back()->with('success_delete_product','Product deleted successfully!');
    }
}