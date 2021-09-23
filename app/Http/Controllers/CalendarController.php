<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Calendar::query()
            ->where('user_id', '=', Auth::id())
            ->get());
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Calendar::find($id));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CalendarRequest $request): \Illuminate\Http\JsonResponse
    {
        $calendar = new Calendar();

        return $this->_saveCalendar($request, $calendar);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(CalendarRequest $request): \Illuminate\Http\JsonResponse
    {
        $calendar = Calendar::find($request->id);

        return $this->_saveCalendar($request, $calendar);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $calendar = Calendar::find($request->id);
        $calendar->events()->each(function ($event) {
            $event->delete();
        });

        if ($calendar->delete()) {
            return response()->json($calendar);
        } else {
            return response()->json(['error', 'Delete Error']);
        }
    }


    /**
     * @param Request $request
     * @param $calendar
     * @return \Illuminate\Http\JsonResponse
     */
    private function _saveCalendar(CalendarRequest $request, $calendar): \Illuminate\Http\JsonResponse
    {
        $calendar->name = $request->input('name');
        $calendar->color = $request->input('color');
        $calendar->visibility = $request->input('visibility');
        $calendar->user_id = $request->input('user_id'); // 編集

        if ($calendar->save()) {
            return response()->json($calendar);
        } else {
            return response()->json(['error' => 'Save Error']);
        }
    }
}
