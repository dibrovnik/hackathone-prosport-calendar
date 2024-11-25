<?php namespace Dibrovnik\SportTech\ApiControllers;

use Octobro\API\Classes\ApiController;
use Dibrovnik\SportTech\Models\Sportsman;
use Illuminate\Http\Request;

class SportsmenController extends ApiController
{
    /**
     * Добавить мероприятие в подписки спортсмена
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSubscription(Request $request)
    {
        $sportsmanId = $request->get('sportsman_id');
        $eventCode = $request->get('event_code');

        if (!$sportsmanId || !$eventCode) {
            return response()->json(['error' => 'Missing parameters: sportsman_id or event_code'], 400);
        }

        $sportsman = Sportsman::find($sportsmanId);

        if (!$sportsman) {
            return response()->json(['error' => 'Sportsman not found'], 404);
        }

        $response = $sportsman->addSubscription($eventCode);

        return response()->json($response, isset($response['error']) ? 400 : 200);
    }

    /**
     * Удалить мероприятие из подписок спортсмена
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeSubscription(Request $request)
    {
        $sportsmanId = $request->get('sportsman_id');
        $eventCode = $request->get('event_code');

        if (!$sportsmanId || !$eventCode) {
            return response()->json(['error' => 'Missing parameters: sportsman_id or event_code'], 400);
        }

        $sportsman = Sportsman::find($sportsmanId);

        if (!$sportsman) {
            return response()->json(['error' => 'Sportsman not found'], 404);
        }

        $response = $sportsman->removeSubscription($eventCode);

        return response()->json($response, isset($response['error']) ? 400 : 200);
    }
}
