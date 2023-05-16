<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function getProjects()
    {
        $url = env('URL_ARKFLEET') . '/projects';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $projects = json_decode($response->getBody()->getContents(), true)['data'];

        return $projects;
    }

    public function getEquipments($project = null)
    {
        $url = env('URL_EQUIPMENTS');

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $equipments = json_decode($response->getBody()->getContents(), true)['data'];

        if ($project) {
            $equipments = array_filter($equipments, function ($item) use ($project) {
                return $item['project'] == $project;
            });
        }

        return $equipments;
    }

    public function getLastTransaction($tyre_id)
    {
        $last_transaction = Transaction::where('tyre_id', $tyre_id)->orderBy('id', 'desc')->first();

        if ($last_transaction) {
            return $last_transaction;
        } else {
            return null;
        }
    }

    public function getFirstTransaction($tyre_id)
    {
        $first_transaction = Transaction::where('tyre_id', $tyre_id)->orderBy('id', 'asc')->first();

        if ($first_transaction) {
            return $first_transaction;
        } else {
            return null;
        }
    }

    public function getHMTyre($tyre_id)
    {
        $first_transaction = $this->getFirstTransaction($tyre_id);
        $last_transaction = $this->getLastTransaction($tyre_id);

        if ($first_transaction && $last_transaction) {
            $first_transaction_date = $first_transaction->hm;
            $last_transaction_date = $last_transaction->hm;

            $diff = $last_transaction_date - $first_transaction_date;

            return $diff;
        } else {
            return null;
        }
    }

    public function getUserRoles()
    {
        $roles = User::find(auth()->user()->id)->getRoleNames()->toArray();
        return $roles;
    }
}
