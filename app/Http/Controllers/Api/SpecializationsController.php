<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class SpecializationsController extends Controller
{
    public function search(Request $request)
    {

        try {
            $this->validate($request, [
                'term' => 'required|string|min:2',
            ]);

            $search = $request->get('term');

            $specializations = Specialization::where('specialization','like',$search.'%')
            ->useIndex('specialization_index')
            ->get();

            $response = response()->json($specializations);

        } catch (Throwable $error) {
            $response = $this->errorResponse($error);
        } finally {
            return $response;
        }
    }
}
