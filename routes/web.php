<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StaffController;

Route::get('/index', [PageController::class, 'index'])->name('login');

Route::get('/', [PageController::class, 'home_page']);

Route::get('/admin/admin-dashboard', [PageController::class, 'admin_dashboard'])->middleware('auth');

Route::get('/admin/profile', [PageController::class, 'admin_profile'])->middleware('auth');

Route::get('/admin/all-products', [PageController::class, 'all_products'])->middleware('auth');

Route::get('/admin/all-stores', [PageController::class, 'store_loader'])->middleware('auth');

Route::get('/admin/users', [PageController::class, 'users_loader'])->middleware('auth');

Route::get('/admin/reports', [PageController::class, 'report_loader'])->middleware('auth');

Route::get('/admin/inventory-report', [App\Http\Controllers\PageController::class, 'inventory_report'])->middleware('auth');

Route::get('/admin/recommended', [PageController::class, 'recommended_product'])->middleware('auth');

Route::get('/admin/comments', [PageController::class, 'comments_loader'])->middleware('auth');

Route::get('/admin/settings', [PageController::class, 'settings'])->middleware('auth');

Route::get('/admin/exported-products', [PageController::class, 'exported_products'])->middleware('auth');

Route::get('/admin/all-sales', [PageController::class, 'view_all_sales'])->middleware('auth');

Route::get('/admin/view-order/{order}', [App\Http\Controllers\PageController::class, 'view_order'])->middleware('auth');

Route::put('/add-order/{order}', [App\Http\Controllers\PageController::class, 'add_product_order'])->middleware('auth');

Route::get('/admin/instock-products', [PageController::class, 'instock_product'])->middleware('auth');

Route::get('/admin/loans-product', [App\Http\Controllers\PageController::class, 'loans_products'])->middleware('auth');

Route::put('/edit-status/{export}', [App\Http\Controllers\PageController::class, 'edit_loan_details'])->middleware('auth');

Route::get('/admin/loans', [App\Http\Controllers\PageController::class, 'loans_manager'])->middleware('auth');

Route::get('/admin/create-orders', [App\Http\Controllers\PageController::class, 'make_orders'])->middleware('auth');

Route::get('/admin/view-products', [App\Http\Controllers\PageController::class, 'view_posters'])->middleware('auth');

Route::post('/post/orders', [App\Http\Controllers\PageController::class, 'post_orders'])->name('post.orders')->middleware('auth');

Route::post('/store_loans', [App\Http\Controllers\PageController::class, 'store_loans'])->name('create.loans')->middleware('auth');

Route::put('/edit-loan/{loan}', [App\Http\Controllers\PageController::class, 'edit_loan'])->middleware('auth');

Route::get('/admin/view-more/{order}', [App\Http\Controllers\PageController::class, 'single_order'])->middleware('auth');

Route::post('/create/product', [App\Http\Controllers\PageController::class, 'create_products'])->name('create.product')->middleware('auth');

Route::delete('/delete-order/{order}', [App\Http\Controllers\PageController::class, 'delete_order'])->middleware('auth');

Route::put('/edit-order/{order}', [App\Http\Controllers\PageController::class, 'edit_order'])->middleware('auth');

Route::get('/admin/less-product', [PageController::class, 'less_product'])->middleware('auth');

Route::get('/admin/outstock-product', [PageController::class, 'outstock_product'])->middleware('auth');

Route::post('/storeusers', [PageController::class, 'store_users'])->middleware('auth');

Route::post('/stores', [PageController::class, 'create_stores'])->middleware('auth');

Route::post('/products', [PageController::class, 'store_products'])->middleware('auth');

Route::post('/authenticate', [PageController::class, 'authentication']);

Route::put('/users/edit-profile/{user}', [PageController::class, 'edit_profile_details'])->middleware('auth');

Route::put('/users/pass-user-edit/{user}', [PageController::class, 'edit_user_pass'])->middleware('auth');

Route::post('/logout', [PageController::class, 'invalidate_users'])->middleware('auth')->middleware('auth');

Route::get('/admin/single-export/{product}', [PageController::class, 'single_export'])->middleware('auth');

Route::post('/exports', [PageController::class, 'store_exports'])->middleware('auth');

Route::put('/products/edit/{product}', [PageController::class, 'edit_product_imported'])->middleware('auth');

Route::post('/transfers', [PageController::class, 'transfer_product'])->middleware('auth');

Route::post('/comments', [PageController::class, 'store_comments'])->middleware('auth');

Route::get('/admin/single-user/{user}', [PageController::class, 'single_user_load'])->middleware('auth');

Route::put('/users/edituser/{user}', [PageController::class, 'edit_user'])->middleware('auth');

Route::delete('/users/deleteuser/{user}', [PageController::class, 'delete_user'])->middleware('auth');

Route::get('/admin/single-store/{store}', [PageController::class, 'show_single_store'])->middleware('auth');

Route::put('/stores/editstore/{store}', [PageController::class, 'edit_store'])->middleware('auth');

Route::delete('/stores/delete/{store}', [PageController::class, 'delete_store_details'])->middleware('auth');

Route::get('/admin/transfer-report', [App\Http\Controllers\PageController::class, 'transfer_report'])->middleware('auth');

Route::get('/reset-password', [PageController::class, 'reset_password']);

Route::get('/admin/logs', [App\Http\Controllers\PageController::class, 'system_logs'])->middleware('auth');

Route::delete('/delete-product/{post}', [App\Http\Controllers\PageController::class, 'delete_post_product'])->middleware('auth');

Route::put('/edit-product/{post}', [App\Http\Controllers\PageController::class, 'edit_post_product'])->middleware('auth');

Route::get('/staff/staff-dashboard', [StaffController::class, 'staff_dashboard'])->middleware('auth');

Route::get('/staff/profile', [StaffController::class, 'staff_profile'])->middleware('auth');

Route::get('/staff/all-products', [StaffController::class, 'all_products'])->middleware('auth');

Route::get('/staff/single-export/{export}', [StaffController::class, 'single_export'])->middleware('auth');

Route::get('/staff/exported-products', [StaffController::class, 'exported_products'])->middleware('auth');

Route::get('/staff/all-stores', [StaffController::class, 'all_stores'])->middleware('auth');

Route::get('/staff/recommended', [StaffController::class, 'recommended'])->middleware('auth');

Route::put('/transfers/editstatus/{transfer}', [StaffController::class, 'edit_transfer_status'])->middleware('auth');

Route::get('/admin/edit-exproduct/{product}', [PageController::class, 'edit_export_product'])->middleware('auth');

Route::put('/exports/edit-product-exp/{product}', [PageController::class, 'edit_sales'])->middleware('auth');

Route::get('/admin/transfered-products', [PageController::class, 'tranfered_products'])->middleware('auth');

Route::get('/admin/all-transfers', [PageController::class, 'all_transfers'])->middleware('auth');

Route::get('/admin/transfered-item/{transfer}', [PageController::class, 'single_transfer'])->middleware('auth');

Route::get('/admin/print/{product}', [PageController::class, 'print_invoice'])->middleware('auth');

Route::delete('/products/delete/{product}', [PageController::class, 'delete_single_product'])->middleware('auth');

Route::get('/staff/print/{product}', [StaffController::class, 'print_doc'])->middleware('auth');

Route::get('/staff/instock-products', [StaffController::class, 'instock_product'])->middleware('auth');

Route::get('/staff/less-product', [StaffController::class, 'less_stock'])->middleware('auth');

Route::get('/staff/outstock-product', [StaffController::class, 'outstock_products'])->middleware('auth');

Route::get('/staff/view-comments', [StaffController::class, 'view_comment'])->middleware('auth');