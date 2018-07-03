<?php
namespace App\Http\Controllers\Api;

use App\Rate;
use Illuminate\Http\Request;

class RateController extends ApiController
{
    public function __construct()
    {
        $this->middleware('token');
    }
    public function rates(Request $request)
    {
        $rates = new Rate;

        if($request->get("currencyCode") && !empty($request->get("currencyCode")))
        {
            $rates = $rates->where('currencyCode',$request->get("currencyCode"));
        }
        if($request->get("releaseType") && !empty($request->get("releaseType")))
        {
            $rates = $rates->where('releaseType',$request->get("releaseType"));
        }
        if($request->get("source") && !empty($request->get("source")))
        {
            $rates = $rates->where('source',$request->get("source"));
        }
        if($request->get("timeStamp") && !empty($request->get("timeStamp")))
        {
            $date = date("Y-m-d",strtotime($request->get("timeStamp")));
            $rates = $rates->where('date',$date);
        }
        $rates = $rates->orderBy('currencyCode','asc')->orderBy('source','asc')->orderBy('releaseType','asc')->orderBy('timeStamp','desc')->get(['currencyCode','timeStamp','close','count','hight','low','open','releaseType','source'],'priceUnit');

        return $this->success($rates);
    }
}