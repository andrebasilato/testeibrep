<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;


class FlightsController extends Controller
{
    public function index()
    {
        return view('layouts.home');
    }

    public function show()
    {
        $response = Http::get('http://prova.123milhas.net/api/flights');
        $flights = $response->json();

        // dd($flights);

        return view('layouts.flights', compact('flights'));
    }

    public function details($id)
    {
        $response = Http::get('http://prova.123milhas.net/api/flights/' . $id);
        $details = $response->json();

        if (!$details) {
            return redirect()->route('voos.show');
        }

        return view('layouts.details', compact('details'));
    }

    public function outbound()
    {
        $response = Http::get('http://prova.123milhas.net/api/flights');
        $out = $response->json();

        $priceO = null;
        foreach ($out as $outbound) {
            if ($outbound['outbound'] == 1) {
                $outbounds[] = $outbound;
            } else {
                continue;
            }
        }

        $results = [];
        $priceAF = null;
        $priceDA = null;
        foreach ($outbounds as $outFare) {
            $results[$outFare['fare']][] = $outFare;
            if ($outFare['fare'] == "1AF") {
                $priceAF += $outFare['price'];
            } else {
                $priceDA += $outFare['price'];
            }
            // $results[$outFare['price']][] = $outFare['price'];
        }

        $result = [
            "results" => $results,
            "priceAF" => $priceAF,
            "priceDA" => $priceDA
        ];

        // dd($result);
        return view('layouts.outbound', compact('result'));
    }

    public function inbound()
    {
        $response = Http::get('http://prova.123milhas.net/api/flights');
        $in = $response->json();

        foreach ($in as $inbound) {
            if ($inbound['inbound'] == 1) {
                $inbounds[] = $inbound;
            } else {
                continue;
            }
        }

        $result = [];
        $priceAF = null;
        $priceDA = null;
        foreach($inbounds as $inbFare) {
            $results[$inbFare['fare']][] = $inbFare;
            if ($inbFare['fare'] == "1AF") {
                $priceAF += $inbFare['price'];
            } else {
                $priceDA += $inbFare['price'];
            }
        }

        $result = [
            "results" => $results,
            "priceAF" => $priceAF,
            "priceDA" => $priceDA
        ];
        // dd($result);

        return view('layouts.inbound', compact('result'));
    }


    public function roundtrip()
    {
        $response = Http::get('http://prova.123milhas.net/api/flights');
        $round = $response->json();

        $priceI = null;
        foreach ($round as $inbound) {
            if ($inbound['inbound'] == 1) {
                $inbounds[] = $inbound;
                $priceI += $inbound['price'];
            } else {
                continue;
            }
        }

        $priceO = null;
        foreach ($round as $outbound) {
            if ($outbound['outbound'] == 1) {
                $outbounds[] = $outbound;
                $priceO += $outbound['price'];
            } else {
                continue;
            }
        }

        $roundtrips = [
            "inbounds" => $inbounds,
            "outbounds" => $outbounds,
            "priceO" => $priceO,
            "priceI" => $priceI
        ];

        // dd($priceI);
        return view('layouts.roundtrips', compact('roundtrips'));
    }

    public function fare()
    {
        $response = Http::get('http://prova.123milhas.net/api/flights');
        $fares = $response->json();

        $result = [];
        $priceAF = null;
        $priceDA = null;
        foreach ($fares as $fare) {
            $results[$fare['fare']][] = $fare;
            if ($fare['fare'] == "1AF") {
                $priceAF += $fare['price'];
            } else {
                $priceDA += $fare['price'];
            }
        }

        $result = [
            "results" => $results,
            "priceAF" => $priceAF,
            "priceDA" => $priceDA
        ];

        // dd($result);

        return view('layouts.fare', compact('result'));
    }

    public function groups()
    {
        $response = Http::get('http://prova.123milhas.net/api/flights');
        $groups = $response->json();

        foreach ($groups as $group) {

            $results[$group['fare']][] = $group;
            if ($group['fare'] == "1AF" && $group['inbound'] == 1) {
                $afI[] = $group;
            } elseif($group['fare'] == "1AF" && $group['outbound'] == 1) {
                $afO[] = $group;
            } elseif($group['fare'] == "4DA" && $group['inbound'] == 1) {
                $daI[] = $group;
            } elseif ($group['fare'] == "4DA" && $group['outbound'] == 1) {
                $daO[] = $group;
            } else {
                continue;
            }
        }

        $result = [
            "daI" => $daI,
            "daO" => $daO,
            "afI" => $afI,
            "afO" => $afO
        ];

        // dd($flights);

        return view('layouts.groups', compact('result'));
    }

    public function total()
    {
        $response = Http::get('http://prova.123milhas.net/api/flights');
        $flights = $response->json();

        $results = [];
        $priceAF = null;
        $priceDA = null;
        foreach ($flights as $flight) {
            $results[$flight['fare']][] = $flight;
            if ($flight['fare'] == "1AF") {
                if($flight['outbound'] == 1) {
                    $outbound[] = $flight['id'];
                } else {
                    $inbound[] = $flight['id'];
                }
                $priceAF += $flight['price'];
            } else {
                if($flight['outbound'] == 1) {
                    $outbounds[] = $flight['id'];
                } else {
                    $inbounds[] = $flight['id'];
                }
                $priceDA += $flight['price'];
            }
        }

        $totalAF = $priceAF;
        $totalDA = $priceDA;

        if($totalAF > $totalDA) {
            $cheapestGroup = $totalDA;
        } else {
            $cheapestGroup = $totalAF;
        }


        $totalFlights = count($flights);
        $cheapestPrice = 2500;

        // $group = array_slice($groups, 0);


        $result = [
            "flights" => $flights,
            "groups" => $results,
            "totalFlights" => $totalFlights,
            "cheapestPrice" => $cheapestPrice,
            "cheapestGroup" => $cheapestGroup
            // "daI" => $daI,
            // "daO" => $daO,
            // "afI" => $afI,
            // "afO" => $afO
        ];

        // dd($result);

        return $result;
    }
}
