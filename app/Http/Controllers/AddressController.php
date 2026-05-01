<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Constructor: aplica middleware auth y autorización de recursos.
     * 'authorizeResource' protegerá automáticamente index, create, store, edit, update, destroy
     * usando la política AddressPolicy. Para setPrimary añadimos manualmente la autorización.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Address::class, 'address');
    }

    /**
     * Muestra las direcciones del cliente autenticado.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = $user->customer;
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
            'neighborhood'     => 'required|string|max:255',
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

        // Si se marcó como principal, desmarcar las demás
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
        return view('addresses.edit', compact('address'));
    }

    /**
     * Actualiza una dirección existente.
     */
    public function update(Request $request, Address $address)
    {
        $validated = $request->validate([
            'alias'           => 'nullable|string|max:100',
            'street'          => 'required|string|max:255',
            'exterior_number' => 'required|string|max:20',
            'interior_number' => 'nullable|string|max:20',
            'neighborhood'     => 'required|string|max:255',
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
            ->with('success', 'Dirección actualizada.');
    }

    /**
     * Elimina una dirección.
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return redirect()->route('addresses.index')
            ->with('success', 'Dirección eliminada.');
    }

    /**
     * Establece una dirección como principal (acción adicional).
     */
    public function setPrimary(Address $address)
    {
        // Verificación manual porque 'authorizeResource' no cubre este método
        $this->authorize('setPrimary', $address);

        $customer = Auth::user()->customer;
        $this->setOnlyPrimary($customer, $address);

        return back()->with('success', 'Dirección principal actualizada.');
    }

    /**
     * Marca la dirección dada como principal y desmarca el resto.
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
}