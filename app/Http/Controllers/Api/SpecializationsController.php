<?php

namespace App\Http\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Doctrine\DBAL\Query\QueryBuilder;
use Throwable;

class SpecializationsController extends Controller
{
    public function search(Request $request)
    {

        try {
            $this->validate($request, [
                'search' => 'required|string|min:3',
                'type' => 'required|string|in:public,private'
            ]);

            $search = $request->get('search');

            $specializations = Specialization::where(function (QueryBuilder $query) use ($search) {
                $query->where('grouping', 'like', $search . '%');
                $query->orWhere('classification', 'like', $search . '%');
                $query->orWhere('specialization', 'like', $search . '%');
            })->useIndex(
                ['grouping_index', 'classification_index', 'specialization_index']
            )->get();

            dd($specializations);
        } catch (Throwable $error) {
        } finally {
        }
    }
}
