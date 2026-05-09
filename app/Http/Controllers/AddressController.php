<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Constructor: aplica middleware auth únicamente.
     * Hemos removido authorizeResource para evitar el chequeo de la tabla "permissions" faltante en el DDL.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra las direcciones del cliente autenticado.
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        $addresses = $customer->addresses()->latest()->get();
        
        return view('addresses.index', compact('addresses'));
    }

    /**
     * Muestra formulario para crear nueva dirección.
     */
    public function create()
    {
        return view('addresses.create');
    }

    /**
     * Guarda una nueva dirección.
     */
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

        $customer = Auth::user()->customer;
        $validated['customer_id'] = $customer->id;
        $validated['is_primary'] = $request->boolean('is_primary');

        $address = Address::create($validated);

        if ($address->is_primary) {
            $this->setOnlyPrimary($customer, $address);
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Dirección guardada correctamente.');
    }

    /**
     * Muestra formulario de edición.
     */
    public function edit(Address $address)
    {
        if ($this->isNotOwner($address)) {
            return redirect()->route('addresses.index')
                ->with('error', 'Acceso denegado a esta dirección.');
        }

        return view('addresses.edit', compact('address'));
    }

    /**
     * Actualiza una dirección existente.
     */
    public function update(Request $request, Address $address)
    {
        if ($this->isNotOwner($address)) {
            return redirect()->route('addresses.index')
                ->with('error', 'Acceso denegado a esta dirección.');
        }

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
            $this->setOnlyPrimary(Auth::user()->customer, $address);
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Dirección actualizada correctamente.');
    }

    /**
     * Elimina una dirección.
     */
    public function destroy(Address $address)
    {
        if ($this->isNotOwner($address)) {
            return redirect()->route('addresses.index')
                ->with('error', 'Acceso denegado a esta dirección.');
        }

        try {
            $address->delete();
            
            return redirect()->route('addresses.index')
                ->with('success', 'Dirección eliminada correctamente.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Manejo silencioso de error por llave foránea (Integridad Referencial)
            if ($e->getCode() == '23503') {
                return redirect()->route('addresses.index')
                    ->with('error', 'No se puede eliminar esta dirección porque ya está vinculada a un envío o pedido.');
            }

            return redirect()->route('addresses.index')
                ->with('error', 'Ocurrió un error al intentar eliminar la dirección.');
        }
    }

    /**
     * Establece una dirección como principal.
     */
    public function setPrimary(Address $address)
    {
        if ($this->isNotOwner($address)) {
            return redirect()->route('addresses.index')
                ->with('error', 'Acceso denegado a esta dirección.');
        }

        $customer = Auth::user()->customer;
        $this->setOnlyPrimary($customer, $address);

        return back()->with('success', 'Dirección principal actualizada.');
    }

    /**
     * Marca la dirección dada como principal y desmarca el resto en una transacción.
     */
    private function setOnlyPrimary($customer, Address $address): void
    {
        DB::transaction(function () use ($customer, $address) {
            $customer->addresses()->where('id', '!=', $address->id)
                     ->where('is_primary', true)
                     ->update(['is_primary' => false]);
            
            $address->update(['is_primary' => true]);
        });
    }

    /**
     * Verifica si la dirección NO pertenece al usuario autenticado.
     */
    private function isNotOwner(Address $address): bool
    {
        return $address->customer_id !== Auth::user()->customer->id;
    }
}