<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\DateTimeManipulator;
use Illuminate\Support\Facades\DB;
use App\Services\Filters\Age;
use App\Models\CreditCard;
use App\Models\User;

class ProcessFile
{
    public function processJSON($filePath)
    {
        $path = storage_path('app') . '/' . $filePath;
        $json = json_decode(file_get_contents($path), true);
        foreach (array_chunk($json, 500) as $data)
        {
            foreach ($data as $result)
            {
                if (Age::filter($result['date_of_birth']) === true) {
                    DB::transaction(function () use ($result){
                        $user = User::create([
                            'name' => $result['name'],
                            'address' => $result['address'],
                            'checked' => $result['checked'],
                            'description' => $result['description'],
                            'interest' => $result['interest'],
                            'date_of_birth' => date('Y-m-d H:i:s', strtotime($result['date_of_birth'])),
                            'email' => $result['email'],
                            'account' => $result['account']
                        ]);
                        $user = $user->creditCards()->create([
                            'type' => $result['credit_card']['type'],
                            'number' => $result['credit_card']['number'],
                            'name' => $result['credit_card']['name'],
                            'expiration_date' => $result['credit_card']['expirationDate'],
                        ]);
                    });
                }
            }
        }
    }

    public function processCSV()
    {
        # If we want to process CSV, we write the method to process here...
    }

    public function processXML()
    {
        # If we want to process XML, we write the method to process here...
    }
}
