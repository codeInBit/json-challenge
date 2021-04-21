<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\DateTimeManipulator;
use App\Models\User;
use App\Models\CreditCard;
use Illuminate\Support\Facades\DB;

class ProcessFile
{
    public function processJSON($filePath)
    {
        $path = storage_path('app') . '/' . $filePath;
        $json = json_decode(file_get_contents($path), true);
        foreach($json as $result){
            $dateOfBirth = date('Y-m-d H:i:s', strtotime($result['date_of_birth']));
            if (
                (DateTimeManipulator::dateDifferenceFromNow($dateOfBirth) >= 18)
                &&
                (DateTimeManipulator::dateDifferenceFromNow($dateOfBirth) <= 65)
            ) {
                DB::transaction(function () use ($result, $dateOfBirth){
                    $user = User::create([
                        'name' => $result['name'],
                        'address' => $result['address'],
                        'checked' => $result['checked'],
                        'description' => $result['description'],
                        'interest' => $result['interest'],
                        'date_of_birth' => $dateOfBirth,
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

    public function processCSV()
    {
        # code...
    }

    public function processXML()
    {
        # code...
    }
}
