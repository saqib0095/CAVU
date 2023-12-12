<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ParkingService;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * Display the availability of parking spaces
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ParkingService $parkingService)
    {
        $validate = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        return $parkingService->checkAvailability($request->query('start_date'),$request->query('end_date'));
    }

    /**
     * Store a newly created reservation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ParkingService $parkingService)
    {
        $validate = $request->validate([
            'parking_space_id' => 'required|exists:ParkingSpace,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        return $parkingService->createReservation($validate);   
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParkingService $parkingService,$id)
    {
        $validate = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        return $parkingService->updateReservation($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParkingService $parkingService,$id)
    {
        return $parkingService->deleteReservation($id);
    }
}
