<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 追加

class CalendarController extends Controller
{
    public function index()
    {
        return response()->json(Calendar::all());
    }

    public function show(int $id)
    {
        return response()->json(Calendar::find($id));
    }


    public function create(Request $request)
    {
        $calendar = new Calendar();

        return $this->_saveCalendar($request, $calendar);
    }

    public function save(Request $request)
    {
        $calendar = Calendar::find($request->id);

        return $this->_saveCalendar($request, $calendar);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $calendar = Calendar::find($request->id);
        // カレンダータイプに紐づくイベントを削除
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
    private function _saveCalendar(Request $request, $calendar)
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
