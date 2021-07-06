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
     *
     * @return JsonResponse
     */
    public function index()
    {
        return Operation::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
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
            'sum' => 'required|integer|min:1',
            'date' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        $balance = User::find(Auth::id())->balance;

        if ($request->input('type') === Category::DEBIT) {
            $balance->total -= $request->input('sum');
        } else if ($request->input('type') === Category::CREDIT) {
            $balance->total += $request->input('sum');
        }
        $balance->save();

        Operation::create([
            'category_id' => $request->input('category_id'),
            'sum' => $request->input('sum'),
            'balance_snapshot' => $balance->total,
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
        $operation->update($request->only(['category_id', 'sum', 'user_id']));
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
                'total' => $debitMonth + $creditMonth,
                'debit' => $debitMonth,
                'credit' => $creditMonth,
            ],
            'week' => [
                'quantity' => $operations_week->count(),
                'total' => $debitWeek + $creditWeek,
                'debit' => $debitWeek,
                'credit' => $creditWeek,
            ],
        ]);
    }
}
