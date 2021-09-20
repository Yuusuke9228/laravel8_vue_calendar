<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        // 一緒にカレンダーを取得する様に変更
        return response()->json(Event::with('calendar')->get());
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Event::with('calendar')->find($id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $event = new Event();

        return $this->_saveEvent($request, $event);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $event = Event::find($request->id);

        return $this->_saveEvent($request, $event);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $event = Event::find($request->id);

        if ($event->delete()) {
            return response()->json($event);
        } else {
            return response()->json(['error', 'Delete Error']);
        }
    }

    /**
     * @param Request $request
     * @param $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function _saveEvent(Request $request, $event): \Illuminate\Http\JsonResponse
    {
        $event->name = $request->input('name');
        $event->start = new Carbon($request->input('start'));
        $event->end = new Carbon($request->input('end'));

        $event->timed = $request->input('timed');
        $event->calendar_id = $request->input('calendar_id');

        $event->description = $request->input('description');
        $event->color = $request->input('color');

        if ($event->save()) {
            return response()->json(Event::with('calendar')->find($event->id));
        } else {
            return response()->json(['error' => 'Save Error']);
        }
    }
}
