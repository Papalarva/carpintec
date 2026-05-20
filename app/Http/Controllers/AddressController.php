<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{ 
    public function __construct()
    {
        $this->middleware('auth');
    } 

    public function index()
    {
        $customer = Auth::user()->customer;
        $addresses = $customer->addresses()->latest()->get();
        
        return view('addresses.index', compact('addresses'));
    } 

    public function create()
    {
        if (str_contains(url()->previous(), 'checkout')) {
            session(['url.intended.address' => url()->previous()]);
        }
        return view('addresses.create');
    } 

    public function store(AddressRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $customer = Auth::user()->customer;
                $validated = $request->validated();
                $validated['customer_id'] = $customer->id;
                $validated['is_primary'] = $request->boolean('is_primary');

                $address = Address::create($validated);

                if ($address->is_primary) {
                    $this->setOnlyPrimary($customer, $address);
                }
            });

            $redirectUrl = session()->pull('url.intended.address', route('addresses.index'));
            return redirect($redirectUrl)->with('success', 'Dirección guardada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al guardar dirección: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al guardar tu dirección. Por favor, intenta de nuevo.');
        }
    } 

    public function edit(Address $address)
    {
        if ($this->isNotOwner($address)) {
            return redirect()->route('addresses.index')->with('error', 'Acceso denegado a esta dirección.');
        }

        if (str_contains(url()->previous(), 'checkout')) {
            session(['url.intended.address' => url()->previous()]);
        }

        return view('addresses.edit', compact('address'));
    } 

    public function update(AddressRequest $request, Address $address)
    {
        if ($this->isNotOwner($address)) {
            return redirect()->route('addresses.index')->with('error', 'Acceso denegado.');
        }

        try {
            DB::transaction(function () use ($request, $address) {
                $validated = $request->validated();
                $validated['is_primary'] = $request->boolean('is_primary');
                
                $address->update($validated);

                if ($address->is_primary) {
                    $this->setOnlyPrimary(Auth::user()->customer, $address);
                }
            });

            $redirectUrl = session()->pull('url.intended.address', route('addresses.index'));
            return redirect($redirectUrl)->with('success', 'Dirección actualizada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar dirección: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar tu dirección.');
        }
    } 

    public function destroy(Address $address)
    {
        if ($this->isNotOwner($address)) {
            return redirect()->route('addresses.index')->with('error', 'Acceso denegado.');
        }

        $address->delete();
        return redirect()->route('addresses.index')->with('info', 'Dirección eliminada de tu libreta.');
    } 

    public function setPrimary(Address $address)
    {
        if ($this->isNotOwner($address)) {
            return back()->with('error', 'Acceso denegado.');
        }

        $this->setOnlyPrimary(Auth::user()->customer, $address);
        return back()->with('success', 'Dirección principal actualizada.');
    } 

    private function setOnlyPrimary($customer, Address $address): void
    {
        $customer->addresses()->where('id', '!=', $address->id)
                 ->where('is_primary', true)
                 ->update(['is_primary' => false]);
                 
        $address->update(['is_primary' => true]);
    } 

    private function isNotOwner(Address $address): bool
    {
        return $address->customer_id !== Auth::user()->customer->id;
    }
}