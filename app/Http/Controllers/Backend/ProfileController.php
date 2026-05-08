<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class ProfileController extends Controller
{
    // --- Frontend (Personal Signature) ---

    public function signature()
    {
        $user = Auth::user();
        return view('backend.profile.signature', compact('user'));
    }

    public function updateSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required_without:signature_file|nullable|string',
            'signature_file' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        return $this->processSignatureUpdate($request, $user);
    }

    // --- Backend (Admin Management) ---

    public function adminSignature($id)
    {
        $user = User::findOrFail($id);
        return view('backend.profile.signature', compact('user'));
    }

    public function adminUpdateSignature(Request $request, $id)
    {
        $request->validate([
            'signature' => 'required_without:signature_file|nullable|string',
            'signature_file' => 'nullable|image|max:2048',
        ]);

        $user = User::findOrFail($id);
        return $this->processSignatureUpdate($request, $user);
    }

    // --- Helper Methods ---

    private function processSignatureUpdate(Request $request, $user)
    {
        $fileName = null;

        if ($request->hasFile('signature_file')) {
            $file = $request->file('signature_file');
            $fileName = 'user_sig_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('signatures', $fileName, 'public');
        } elseif ($request->signature) {
            $fileName = $this->saveBase64Signature($request->signature, $user->id);
        }

        if ($fileName) {
            if ($user->signature) {
                Storage::disk('public')->delete('signatures/' . $user->signature);
            }
            $user->update(['signature' => $fileName]);
            return redirect()->back()->with('success', 'บันทึกลายเซ็นเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    private function saveBase64Signature($base64Data, $userId)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]);
            $data = base64_decode($data);
            
            $fileName = 'user_sig_' . $userId . '_' . time() . '.' . $type;
            Storage::disk('public')->put('signatures/' . $fileName, $data);
            
            return $fileName;
        }
        return null;
    }
}
