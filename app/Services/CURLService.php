<?php

namespace App\Services;

class CURLService {

    public function callAPI($url): float|bool|null
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (!$err) {
                $data = json_decode($response, true);
                if (isset($data['rates']['EUR'])) {
                    return $data['rates']['EUR']; // float expected
                }
            }

            return null; // Return null if no error but EUR is not set
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

            return false; // Return false on exception
        }
    }

    public function callConversionAPI(): float|bool|null
    {
        return $this->callAPI(RATE_CONVERSION_API_URL);
    }
}
