<?php namespace Dibrovnik\SportTech\ApiControllers;

use Octobro\API\Classes\ApiController;
use Illuminate\Http\Request;
use  Dibrovnik\SportTech\Models\SportEvent;
use  Dibrovnik\SportTech\Models\Discipline;
use Response;


class FilterController extends ApiController
{
   public static function getQueryOptions()
   {

   }
   public function renderPhp()
   {
      $phpFilePath = base_path('plugins/dibrovnik/sporttech/src/data/calendar.php');

      if (file_exists($phpFilePath)) {
         ob_start();
         include $phpFilePath;
         return ob_get_clean();
      }

      return 'PHP file not found!';
   }

}
