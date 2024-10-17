<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customerPage()
    {
        return view('pages.components.customer');
    }

    public function addCustomer(Request $request)
    {
        // Create a new customer in the database
        $user_id = $request->header('id');
        return Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'user_id' => $user_id
        ]);
    }


    function customerList(Request $request)
    {
        $user_id = $request->header('id');
        return Customer::where('user_id', $user_id)->get();
    }


    function customerDelete(Request $request)
    {
        $customer_id = $request->input('id');
        $user_id = $request->header('id');
        return Customer::where('id', $customer_id)->where('user_id', $user_id)->delete();
    }


    function customerByID(Request $request)
    {
        $customer_id = $request->input('id');
        $user_id = $request->header('id');
        return Customer::where('id', $customer_id)->where('user_id', $user_id)->first();
    }


    function customerUpdate(Request $request)
    {
        $customer_id = $request->input('id');
        $user_id = $request->header('id');
        return Customer::where('id', $customer_id)->where('user_id', $user_id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);
    }

}