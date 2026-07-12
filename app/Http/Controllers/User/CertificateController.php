<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::where('user_id', Auth::id())->with('event')->latest()->get();
        return view('user.certificates.index', compact('certificates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'certificate_file' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'title' => 'required|string|max:255',
        ]);

        $filePath = $request->file('certificate_file')->store('certificates/external', 'public');

        $cert = Certificate::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'file_path' => $filePath,
            'certificate_number' => 'EXT-' . strtoupper(uniqid()),
            // 'event_id' is null for external certificates uploaded by user
        ]);

        Auth::user()->notify(new \App\Notifications\ActivityNotification(
            "You successfully uploaded external certificate '{$cert->title}'.",
            route('user.certificates.index')
        ));

        return back()->with('success', 'Certificate uploaded successfully.');
    }

    public function destroy(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) abort(403);

        if (\Storage::disk('public')->exists($certificate->file_path)) {
            \Storage::disk('public')->delete($certificate->file_path);
        }

        $certificate->delete();

        return back()->with('success', 'Certificate deleted successfully.');
    }
}
