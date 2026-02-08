<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        return Client::latest()->paginate(20);
    }

    public function show(Client $client)
    {
        return $client->load('jobs');
    }
}
