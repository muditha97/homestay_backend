<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\homestay;
use App\Models\booking;
use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class HomestayController extends Controller
{
    function addHomestay(Request $request)
    {
        $homestay = new homestay;
        $homestay->name = $request->name;
        $homestay->description = $request->description;
        $homestay->noOfRooms = $request->rooms;
        $homestay->price = $request->price;
        $homestay->payment_method = $request->paymentMethod;
        $homestay->user_id = $request->user_id;
        $homestay->city_id = $request->city;
        $homestay->available = $request->available;
        $homestay->rating = $request->rating;
        $homestay->noOfRatings = 0;
        $homestay->entrance = $request->entrance;
        $homestay->graden = $request->graden;
        $homestay->furnished = $request->furnished;
        $homestay->bathrm = $request->bathrm;
        $homestay->parking = $request->parking;
        $homestay->ac = $request->ac;
        $homestay->hotWater = $request->hotWater;
        $homestay->electricity = $request->electricity;
        $homestay->cableTv = $request->cableTv;
        $homestay->fibre = $request->fibre;

        $result = $homestay->save();

        if ($result) {
            return ["Success" => true, "message" => 'Successfully added the homestay'];
        } else {
            return ["Success" => false, "message" => 'Something went wrong!'];
        }
    }

    function getHomestay()
    {
        $data = DB::table('homestays')->get();
        return $data;
    }

    function getHomestayById(Request $request)
    {
        $data = DB::table('homestays')->where('user_id', '=', $request->id)->get();
        return $data;
    }

    function bookHomestay(Request $request)
    {
        $hotel_id = $request->hotel_id;

        $hotel = homestay::where('id', $hotel_id)->first();

        if ($hotel->available >= 0) {
            $booking = new booking();
            $booking->hotel_id = $request->hotel_id;
            $booking->user_id = $request->user_id;
            $booking->check_in = $request->check_in;
            $booking->check_out = $request->check_out;
            $booking->status = 1;

            $result = $booking->save();
            $affected = DB::table('homestays')
                ->where('id', $hotel_id)
                ->update(['available' => ($hotel->available - 1)]);

            if ($result) {
                return ["Success" => true, "message" => 'Successfully booked the homestay'];
            } else {
                return ["Success" => false, "message" => null];
            }
        }
    }

    function getBookingsById(Request $request)
    {
        $bookings = DB::table('bookings')
            ->join('homestays', 'bookings.hotel_id', '=', 'homestays.id')
            ->select('bookings.*', 'homestays.*', 'bookings.id as book_id')
            ->where([['bookings.user_id', '=', $request->id], ['bookings.status', '=', 1]])
            ->get();

        return $bookings;
    }

    function cancelBooking(Request $request)
    {
        $affected = DB::table('bookings')
            ->where('id', $request->id)
            ->update(['status' => 0]);

        $hotel = homestay::where('id', $request->hotel_id)->first();
        //print_r($hotel->available);die();

        $affected1 = DB::table('homestays')
            ->where('id', $request->hotel_id)
            ->update(['available' => ($hotel->available + 1)]);

        if ($affected) {
            return ["Success" => true, "message" => 'Booking Cancel Successful!'];
        } else {
            return ["Success" => false, "message" => null];
        }
    }

    function getBookingsProvider(Request $request)
    {
        $data = DB::table('bookings')
            ->join('homestays', 'homestays.id', '=', 'bookings.hotel_id')
            ->select('bookings.*', 'homestays.*', 'bookings.id as book_id')
            ->where([['homestays.user_id', '=', $request->id], ['bookings.status', '=', 1]])
            ->get();

        return $data;
    }

    function bookingDone(Request $request)
    {
        $hotel_id = $request->hotel_id;
        $book_id = $request->book_id;

        $hotel = homestay::where('id', $hotel_id)->first();

        $affected = DB::table('homestays')
            ->where('id', $hotel_id)
            ->update(['available' => ($hotel->available + 1)]);

        $affected2 = DB::table('bookings')
            ->where('id', $book_id)
            ->update(['status' => 0]);

        if ($affected) {
            return ["Success" => true];
        } else {
            return ["Success" => false];
        }
    }

    function searchHomestay(Request $request)
    {
        $text = $request->text;

        $data = DB::table('homestays')->where('name', '=', $text)->get();
        return $data;
    }

    function ratings(Request $request)
    {
        $hotel_id = $request->hotel_id;
        $rate = $request->rate;

        $homestay = DB::table('homestays')->where('id', '=', $hotel_id)->first();
        $currentRating = $homestay->rating;
        $noOfRatings = $homestay->noOfRatings;

        $newRating = ($currentRating + $rate) / 2;
        $newRating = round($newRating);

        $affected = DB::table('homestays')
            ->where('id', $hotel_id)
            ->update(['noOfRatings' => ($noOfRatings + 1), 'rating' => $newRating]);

        $affected1 = DB::table('bookings')
            ->where('id', $request->book_id)
            ->update(['rated' => 1]);

        if ($affected) {
            return ["success" => true, 'message' => 'Rated successfully!'];
        } else {
            return ["success" => false, 'message' => 'Something went wrong!'];
        }
    }

    function deleteHomestay(Request $request)
    {
        $delete = DB::table('homestays')->where('id', '=', $request->id)->delete();

        if ($delete) {
            return ["success" => true, 'message' => 'Homestay deleted successfully!'];
        } else {
            return ["success" => false, 'message' => 'Something went wrong!'];
        }
    }

    function editHomestay(Request $request)
    {
        $homestay = new homestay;
        $homestay->name = $request->name;
        $homestay->description = $request->description;
        $homestay->noOfRooms = $request->rooms;
        $homestay->price = $request->price;
        $homestay->payment_method = $request->paymentMethod;
        $homestay->user_id = $request->user_id;
        $homestay->city_id = $request->city;
        $homestay->available = $request->available;
        $homestay->rating = $request->rating;
        $homestay->entrance = $request->entrance;
        $homestay->graden = $request->graden;
        $homestay->furnished = $request->furnished;
        $homestay->bathrm = $request->bathrm;
        $homestay->parking = $request->parking;
        $homestay->ac = $request->ac;
        $homestay->hotWater = $request->hotWater;
        $homestay->electricity = $request->electricity;
        $homestay->cableTv = $request->cableTv;
        $homestay->fibre = $request->fibre;

        $result = DB::table('homestays')
            ->where('id', $request->id)
            ->update(['name' => $request->name, 'description' => $request->description, 'noOfRooms' => $request->rooms, 'price' => $request->price, 'payment_method' => $request->paymentMethod, 'user_id' => $request->user_id, 'city_id' => $request->city, 'available' => $request->available, 'rating' => $request->rating, 'entrance' => $request->entrance, 'graden' => $request->graden, 'furnished' => $request->furnished, 'bathrm' => $request->bathrm, 'parking' => $request->parking, 'ac' => $request->ac, 'hotWater' => $request->hotWater, 'electricity' => $request->electricity, 'cableTv' => $request->cableTv, 'fibre' => $request->fibre]);

        if ($result) {
            return ["Success" => true, "message" => 'Successfully updated the homestay'];
        } else {
            return ["Success" => false, "message" => 'Something went wrong!'];
        }
    }

    function updateBooking(Request $request)
    {
        try {

            $affected = DB::table('bookings')
                ->where('id', $request->id)
                ->update(['check_in' => $request->check_in, 'check_out' => $request->check_out]);

            if ($affected) {
                return ["success" => true, "message" => 'Successfully updated the booking!'];
            } else {
                return ["success" => false, "message" => 'Something went wrong!'];
            }
        } catch (Exception $e) {
            return ["Error" => $e];
        }
    }
}
