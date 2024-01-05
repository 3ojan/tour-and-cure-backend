<?php

namespace App\Http\Controllers;

use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
Use App\Http\Requests\Inquiries\InquiryViewAllRequest as ViewAll;
Use App\Http\Requests\Inquiries\InquiryViewRequest as View;
Use App\Http\Requests\Inquiries\InquiryStoreRequest as Store;
Use App\Http\Requests\Inquiries\InquiryUpdateRequest as Update;
Use App\Http\Requests\Inquiries\InquiryDeleteRequest as Delete;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param ViewAll $request
     * @return JsonResponse
     */
    public function index(ViewAll $request)
    {
        $user = Auth::user();
        $query = Inquiry::query();

        if ($user->isAdmin()) {
            // Admin can see all inquiries
            $query->orderBy('created_at', 'desc');
        } elseif ($user->isClinicOwner() || $user->isClinicUser()) {
            // Clinic owner or clinic user can see inquiries with matching categories
            $categoryIds = $user->clinic->categories->pluck('id')->toArray();
            $query->whereIn('category_id', $categoryIds)
                ->where('is_closed', false)
                ->orderBy('created_at', 'desc');
        } else {
            // Regular user can see inquiries they made
            $query->where('user_id', $user->id);
        }

        // Add pagination
        $perPage = $request->input('per_page', 20);

        $inquiries = $query->paginate($perPage);

        return response()->json(array_merge([
            'status' => 'Success',
            'message' => 'All inquiries fetched successfully!',
        ], InquiryResource::collection($inquiries)->toArray()), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param View $request
     * @param Inquiry $inquiry
     * @return mixed
     */
    public function show(View $request, Inquiry $inquiry)
    {
        return $this->success(new InquiryResource($inquiry), 'Inquiry fetched successfully!');
    }

    public function store(Store $request)
    {
        $validated = $request->validated();
        $inquiry = Inquiry::create([
            'user_id' => Auth::user()->id,
            'category_id' => $validated->category_id,
            'form_json' => $validated->form_json

        ]);

        $inquiry->reload();

        return $this->success(new InquiryResource($inquiry), 'Inquiry created successfully!');
    }

    public function update(Update $request, Inquiry $inquiry)
    {
        $inquiry->update($request->validated());

        return $this->success(new InquiryResource($inquiry), 'Inquiry updated successfully!');
    }

    public function delete(Delete $request, Inquiry $inquiry)
    {
        $inquiry->delete();

        return $this->success('', 'Inquiry deleted successfully!');
    }
}
