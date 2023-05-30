<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Http\Requests\InquiryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class InquiryController extends Controller
{
    public function index()
    {
        return Inquiry::all();
    }

    public function show($id)
    {
        return Inquiry::find($id);
    }

    public function store(InquiryRequest $request)
    {
        return Inquiry::create($request->all());
    }

    public function update(InquiryRequest $request, Inquiry $inquiry)
    {
        $inquiry->update($request->all());

        return $inquiry;
    }

    public function delete(Request $request, Inquiry $inquiry)
    {
        $inquiry->delete();

        return 204;
    }
}
