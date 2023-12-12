<?php

namespace App\Services;

use App\Models\ParkingPrices;
use App\Models\ParkingSpace;
use App\Models\Reservation;
use Carbon\Carbon;
use Exception;

class ParkingService {

    public function checkAvailability($startDate,$endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Get all parking spaces
        $parkingSpaces = ParkingSpace::all();

        // Initialize the result array
        $result = [];
        // Loop through each day within the date range
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Get reserved spaces for the current day
           $reservedSpaces = Reservation::whereBetween('start_time', [$startDate, $endDate])
                ->orWhereBetween('end_time', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_time', '<', $startDate)
                        ->where('end_time', '>', $endDate);
                })
                ->pluck('space_id');
            // Filter available spaces for the current day
            $availableSpaces = $parkingSpaces->reject(function ($space) use ($reservedSpaces) {
                return $reservedSpaces->contains('id',$space->space_id);
            });
            //check if date is in summer or winter and whether the date falls on a weekend.
            $season = $this->isSummerOrWinter($date->toDateString());            
            $isWeekend = $date->isWeekend() ? 'weekend' : 'weekday';
            $getPrices = ParkingPrices::where('season',$season)
                                        ->where('day_type',$isWeekend)
                                        ->first();

            //check if $availableSpaces is empty or not. If not empty add the prices too
            $availableSpacesArray = $availableSpaces->pluck('id')->toArray();
            $availableSpacesOutputArray = [];
            if(empty($availableSpacesArray)){
                //$availableSpacesOutputArray = ['No spaces available'];
                $availableSpacesOutputArray = [
                    'availability' => 'No spaces available'
                ];
            }else{
                $availableSpacesOutputArray = [
                    'availability' => $availableSpacesArray,
                    'prices' => $getPrices->price
                ];
            }

            // Store the result for the current day
            $result[$date->toDateString()] = $availableSpacesOutputArray;
        }

        return response()->json(['results' => $result]);
        
    }
    public function createReservation($data)
    {
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        //check whether the space_id given is free or not
        $checkParking = Reservation::
        where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_time', [$startDate, $endDate])
                ->orWhereBetween('end_time', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_time', '<', $startDate)
                        ->where('end_time', '>', $endDate);
                });
        })
        ->pluck('space_id');

        //if the space_id is not free return message
        if($checkParking->contains($data['parking_space_id'])){
            return response()->json([
                'message' => 'The following parking spaces '.$checkParking.' are not available',
                'created' => false,
            ],400);
        }
        
        //else create the reservation
        try{
            $Reservation = Reservation::create([
                'space_id' => $data['parking_space_id'],
                'start_time' => $startDate,
                'end_time' => $endDate
            ]);
            return response()->json([
                'message' => 'Your car has been booked. Drop Off date: '.$Reservation->start_time . ' Pick-Up Date: '.$Reservation->end_time,
                'reservation_id' => $Reservation->reservation_id,
                'space_id' => $Reservation->space_id,
                'created' => true
            ],201);
        }catch(Exception $e)
        {
            return throw new Exception($e->getMessage());
        }
        
    }
    public function updateReservation($request, $id)
    {
        //find the reservation using ID
        $reservation = Reservation::findOrFail($id);

        // Check for overlapping reservations
        //if the space_id is the same as another Reservation which clashes we don't want it to update
        $overlappingReservations = Reservation::where('space_id', $reservation->space_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request['start_date'], $request['end_date']])
                    ->orWhereBetween('end_time', [$request['start_date'], $request['end_date']])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_time', '<', $request['start_date'])
                            ->where('end_time', '>', $request['end_date']);
                    });
            })
            ->where('reservation_id', '!=', $reservation->reservation_id) // Exclude the current reservation
            ->exists();
        //if overlapping send error
        if ($overlappingReservations) {
            return response()->json(['error' => 'The updated reservation clashes with existing reservations.'], 400);
        }


        $reservation->update([
            'start_time' => $request['start_date'],
            'end_time' => $request['end_date']
        ]); 
        //get the updated fields
        $updateRes = $reservation->getChanges();
        //return null for startime and end_time if it hasn't been changed
        return response()->json([
            'message' => $id. ' has been updated',
            'reservation_id' => $id,
            'updatedstart_time' => $updateRes->start_time ?? null,
            'updatedend_time' => $updateRes->end_time ?? null
        ]);
    }
    public function deleteReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully']);
    }
    private function isSummerOrWinter($date)
    {
        $month = date('n', strtotime($date));

        // Define the months for summer and winter
        $summerMonths = [6, 7, 8]; // June, July, August
        $winterMonths = [12, 1, 2]; // December, January, February

        if (in_array($month, $summerMonths)) {
            return 'Summer';
        } elseif (in_array($month, $winterMonths)) {
            return 'Winter';
        } else {
            return 'Other season';
        }
    }

}