<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\ShippingAddress;
use RealRashid\SweetAlert\Facades\Alert;

class AddressController extends Controller
{
    public function index()
    {
        $data = [
            'addresses' => auth()->user()->addresses, // Assuming you have a relationship set up in User model
        ];
        return view('user.address.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'nullable|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:30',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = auth()->user();
                if ($request->is_default) {
                    $user->addresses()->update(['is_default' => false]);
                }
                $address = $user->addresses()->create([
                    'label' => $request->label,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country,
                    'phone_number' => $request->phone_number,
                    'is_default' => $request->is_default ? true : false,
                    'is_active' => true,
                ]);
            });
            Alert::success('Success', 'Alamat berhasil disimpan.');
            return redirect()->back()->with('success', 'Alamat berhasil disimpan.');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menyimpan alamat.');
            return redirect()->back()->withErrors('Gagal menyimpan alamat.');
        }
    }

    public function update(Request $request, ShippingAddress $address)
    {
        $request->validate([
            'label' => 'nullable|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:30',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $address) {
                $user = auth()->user();
                if ($address->user_id !== $user->id) {
                    abort(403);
                }
                if ($request->is_default) {
                    $user->addresses()->update(['is_default' => false]);
                }
                $address->update([
                    'label' => $request->label,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country,
                    'phone_number' => $request->phone_number,
                    'is_default' => $request->is_default ? true : false,
                ]);
            });
            Alert::success('Success', 'Alamat berhasil diupdate.');
            return redirect()->back()->with('success', 'Alamat berhasil diupdate.');
        } catch (\Exception $e) {

            Alert::error('Error', 'Gagal mengupdate alamat.');
            return redirect()->back()->withErrors('Gagal mengupdate alamat.');
        }
    }

    public function destroy(ShippingAddress $address)
    {
        try {
            DB::transaction(function () use ($address) {
                $user = auth()->user();
                if ($address->user_id !== $user->id) {
                    abort(403);
                }
                $address->delete();
            });
            Alert::success('Success', 'Alamat berhasil dihapus.');
            return redirect()->back()->with('success', 'Alamat berhasil dihapus.');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus alamat.');
            return redirect()->back()->withErrors('Gagal menghapus alamat.');
        }
    }

    public function setDefaultAddress(ShippingAddress $address)
    {
        try {
            DB::transaction(function () use ($address) {
                $user = auth()->user();
                if ($address->user_id !== $user->id) {
                    abort(403);
                }
                $user->addresses()->update(['is_default' => false]);
                $address->update(['is_default' => true]);
            });
            Alert::success('Success', 'Alamat utama berhasil diubah.');
            return redirect()->back()->with('success', 'Alamat utama berhasil diubah.');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mengubah alamat utama.');
            return redirect()->back()->withErrors('Gagal mengubah alamat utama.');
        }
    }

    public function setActiveAddress(ShippingAddress $address)
    {
        try {
            DB::transaction(function () use ($address) {
                $user = auth()->user();
                if ($address->user_id !== $user->id) {
                    abort(403);
                }
                $address->update(['is_active' => true]);
            });
            Alert::success('Success', 'Alamat berhasil diaktifkan.');
            return redirect()->back()->with('success', 'Alamat berhasil diaktifkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal mengaktifkan alamat.');
        }
    }

    public function setInactiveAddress(ShippingAddress $address)
    {
        try {
            DB::transaction(function () use ($address) {
                $user = auth()->user();
                if ($address->user_id !== $user->id) {
                    abort(403);
                }
                $address->update(['is_active' => false]);
            });
            Alert::success('Success', 'Alamat berhasil dinonaktifkan.');
            return redirect()->back()->with('success', 'Alamat berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menonaktifkan alamat.');
        }
    }
}
