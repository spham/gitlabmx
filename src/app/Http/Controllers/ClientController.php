<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::query()
            ->withCount('projects')
            ->latest('created_at')
            ->paginate(10);

        return view('pages.clients.index')
            ->with('clients', $clients);
    }

    public function create()
    {
        return view('pages.clients.create');
    }

    public function store(ClientRequest $request)
    {
        $data = array_merge(['is_active' => true], $request->validated());

        Client::create($data);

        return redirect()->route('clients.index')
            ->with('success', 'Client created');
    }

    public function show(Client $client)
    {
        return view('pages.clients.show')
            ->with('client', $client);
    }
}
