<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
        ]);

        $operation = Operation::where('user_id', Auth::id());
        if ($request->has('fromDate')) {
            $operation->where('date', '>=', $request->input('fromDate'));

            if ($request->has('toDate')) {
                $operation->where('date', '<=', $request->input('toDate'));
            }
        } else {
            $operation->where('date', '>=', Carbon::now()->subDays(30)->toDateString());
        }

        $balance = 0;
        return $operation
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->transform(function ($item) use (&$balance) {
                $item->sum = $item->sum / Operation::AMOUNT_MULTIPLE;
                if ($item->category->type === Category::DEBIT) {
                    $balance -= $item->sum;
                } else {
                    $balance += $item->sum;
                }
                $item->balance = $balance;

                return $item;
            });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|integer|min:1',
            'sum' => 'required|numeric|min:1',
            'date' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        Operation::create([
            'category_id' => $request->input('category_id'),
            'sum' => $request->input('sum') * 100,
            'user_id' => Auth::id(),
            'date' => $request->input('date'),
            'comment' => $request->input('comment'),
        ]);

        return response()->json(['success' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return Operation::where('user_id', Auth::id())->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $operation = Operation::where('user_id', Auth::id())->findOrFail($id);
        $operation->update([
            'category_id' => $request->input('category_id'),
            'sum' => $request->input('sum') * 100,
            'date' => $request->input('date'),
            'comment' => $request->input('comment'),
        ]);
        return response()->json(['success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $operation = Operation::where('user_id', Auth::id())->findOrFail($id);
        $operation->delete();
        return response()->json(['success' => true], 200);
    }

    /**
     * Показывает статистику за определенный период
     *
     * @return JsonResponse
     */
    public function statistics()
    {
//        TODO Плохой код, переписать когда-нибудь
        $operations_month = Operation::where('user_id', Auth::id())
            ->where('date', '>=', Carbon::now()->subDays(30)->toDateString())
            ->get();
        $operations_week = Operation::where('user_id', Auth::id())
            ->where('date', '>=', Carbon::now()->subDays(7)->toDateString())
            ->get();

        $debitMonth = 0;
        $creditMonth = 0;
        foreach ($operations_month as $operation) {
            if ($operation->category->type === Category::DEBIT) {
                $debitMonth += $operation->sum;
            } else {
                $creditMonth += $operation->sum;
            }
        }
        $debitWeek = 0;
        $creditWeek = 0;
        foreach ($operations_week as $operation) {
            if ($operation->category->type === Category::DEBIT) {
                $debitWeek += $operation->sum;
            } else {
                $creditWeek += $operation->sum;
            }
        }

        return response()->json([
            'month' => [
                'quantity' => $operations_month->count(),
                'total' => ($debitMonth + $creditMonth) / Operation::AMOUNT_MULTIPLE,
                'debit' => $debitMonth / Operation::AMOUNT_MULTIPLE,
                'credit' => $creditMonth / Operation::AMOUNT_MULTIPLE,
            ],
            'week' => [
                'quantity' => $operations_week->count(),
                'total' => ($debitWeek + $creditWeek) / Operation::AMOUNT_MULTIPLE,
                'debit' => $debitWeek / Operation::AMOUNT_MULTIPLE,
                'credit' => $creditWeek / Operation::AMOUNT_MULTIPLE,
            ],
        ]);
    }
}
