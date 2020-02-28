<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\daily_roi;
use App\investment;
use App\package;

class ROIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dailRoiData = daily_roi::with('investment')->get();

        return response()->json([
            'message'=> 'success',
            'status' => 'success',
            'data'=> $dailRoiData
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $investmentData = investment::get();


        foreach ($investmentData as $value) {
            $packageData = package::where('id', $value->package_id)->first();

            // $diffDays =date_diff($value->start_date,Carbon::now())->format("%a");
            $dailyAmount = $packageData->amount * ($packageData->roi / 100);
            $dailyPercent = ($packageData->roi / 100);

            // $dailRoiData = new daily_roi;
            // $dailRoiData->user_id = 1;
            // $dailRoiData->investment_id = $value->id;
            // $dailRoiData->amount = $dailyAmount;
            // $dailRoiData->percent = $packageData->roi;
            // $dailRoiData->save();
        }

        return response()->json([
            'message'=> 'success',
            'status' => 'success',
            'data'=> $packageData
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
