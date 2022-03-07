<?php

namespace App\Http\Controllers;

use App\Models\DrawMaster;
use App\Http\Requests\StoreDrawMasterRequest;
use App\Http\Requests\UpdateDrawMasterRequest;
use Illuminate\Http\Request;

class DrawMasterController extends Controller
{
    public function autoDrawMaster(Request $request)
    {
        $requestedData = (object)$request->json()->all();
        $time_diff = $requestedData->timeDiff;
        $game_id = $requestedData->gameId;
        $truncate_table = $requestedData->truncate;

        $time_diff_br = explode(',', $time_diff);
        $game_id_br = explode(',', $game_id);

        if(count($time_diff_br) != count($game_id_br)){
            return response()->json(['success'=> 1, 'data' => 'data error'], 400);
        }

        if($truncate_table == 'yes'){
            DrawMaster::query()->truncate();
        }

        for($ac=0;$ac<count($time_diff_br);$ac++){

            $time_diff = (int)$time_diff_br[$ac];
            $game_id = (int)$game_id_br[$ac];

            $total_count = (60/$time_diff)*24;

            $start_hour = '00';  $start_min = '00';  $start_sec = '00';
            $end_hour = '00';  $end_min = str_pad($time_diff, 2 , '0',  STR_PAD_LEFT);  $end_sec = '00';

            $start_time = $start_hour . ':' . $start_min . ':' . $start_sec;
            $end_time = $end_hour . ':' . $end_min . ':' . $end_sec;


            for ($i = 1; $i<=$total_count; $i++ ){

                if($end_hour == 00){
                    $visible_time = '12'.':'.str_pad($end_min, 2 , '0',  STR_PAD_LEFT).' am';
                }else if($end_hour>12){
                    $visible_time =str_pad(((int)$end_hour-12), 2 , '0',  STR_PAD_LEFT).':'.str_pad($end_min, 2 , '0',  STR_PAD_LEFT).' pm';
                }else if($end_hour==12){
                    $visible_time =str_pad($end_hour, 2 , '0',  STR_PAD_LEFT).':'.str_pad($end_min, 2 , '0',  STR_PAD_LEFT).' pm';
                }else{
                    $visible_time = str_pad($end_hour, 2 , '0',  STR_PAD_LEFT).':'.str_pad($end_min, 2 , '0',  STR_PAD_LEFT).' am';
                }

                $drawMaster = new DrawMaster();
                $drawMaster->draw_name = ' ';
                $drawMaster->start_time = $start_time;
                $drawMaster->end_time = $end_time;
                $drawMaster->visible_time = $visible_time;
                $drawMaster->game_id = $game_id;
                $drawMaster->save();

                $start_time = $end_time;

                $end_min = (int)$end_min + $time_diff;

                if((int)$end_min>=60){
                    $end_hour = (string)((int)$end_hour + 1);
                    $end_min = '00';
                }

                if((int)$end_hour>23){
                    $end_hour = '00';
                    $end_min = '00';
                }

                $end_time = $end_hour . ':' . $end_min  . ':' . $end_sec;
            }

        }

        return response()->json(['success'=> 1, 'data' => 'Created Draw Master'], 200);
    }

    public function create()
    {
        //
    }

    public function store(StoreDrawMasterRequest $request)
    {
        //
    }

    public function show(DrawMaster $drawMaster)
    {
        //
    }

    public function edit(DrawMaster $drawMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDrawMasterRequest  $request
     * @param  \App\Models\DrawMaster  $drawMaster
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDrawMasterRequest $request, DrawMaster $drawMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DrawMaster  $drawMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(DrawMaster $drawMaster)
    {
        //
    }
}
