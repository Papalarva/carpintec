<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class AddressController extends Controller
{
    use AuthorizesRequests; 

    public function __construct()
    {
        $this->authorizeResource(Address::class, 'address');
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user(); 
        
        $addresses = $user->customer->addresses()->latest()->get();
        
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alias'           => 'nullable|string|max:100',
            'street'          => 'required|string|max:255',
            'exterior_number' => 'required|string|max:20',
            'interior_number' => 'nullable|string|max:20',
            'neighborhood'    => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'state'           => 'required|string|max:255',
            'postal_code'     => 'required|string|max:10',
            'country'         => 'nullable|string|max:255',
            'contact_phone'   => 'nullable|string|max:20',
            'is_primary'      => 'nullable|boolean',
        ]);

        /** @var User $user */
        $user = $request->user();
        $customer = $user->customer;
        
        $validated['customer_id'] = $customer->id;
        $validated['is_primary'] = $request->boolean('is_primary');

        $address = Address::create($validated);

        if ($address->is_primary) {
            $this->setOnlyPrimary($customer, $address);
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Dirección guardada correctamente.');
    }

    public function edit(Address $address)
    {
        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $validated = $request->validate([
            'alias'           => 'nullable|string|max:100',
            'street'          => 'required|string|max:255',
            'exterior_number' => 'required|string|max:20',
            'interior_number' => 'nullable|string|max:20',
            'neighborhood'    => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'state'           => 'required|string|max:255',
            'postal_code'     => 'required|string|max:10',
            'country'         => 'nullable|string|max:255',
            'contact_phone'   => 'nullable|string|max:20',
            'is_primary'      => 'nullable|boolean',
        ]);

        $validated['is_primary'] = $request->boolean('is_primary');
        $address->update($validated);

        if ($address->is_primary) {
            /** @var User $user */
            $user = $request->user();
            $this->setOnlyPrimary($user->customer, $address);
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Dirección actualizada.');
    }

    public function destroy(Address $address)
    {
        $address->delete();
        
        return redirect()->route('addresses.index')
            ->with('success', 'Dirección eliminada.');
    }

    public function setPrimary(Request $request, Address $address)
    {
        $this->authorize('update', $address); 
        
        /** @var User $user */
        $user = $request->user();
        $this->setOnlyPrimary($user->customer, $address);

        return back()->with('success', 'Dirección principal actualizada.');
    }

    private function setOnlyPrimary($customer, Address $address): void
    {
        DB::transaction(function () use ($customer, $address) {
            $customer->addresses()->where('id', '!=', $address->id)
                     ->where('is_primary', true)
                     ->update(['is_primary' => false]);
                     
            $address->update(['is_primary' => true]);
        });
    }
}