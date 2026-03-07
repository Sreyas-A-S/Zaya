<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscriptions = Newsletter::orderBy('created_at', 'desc')->get();
        return view('admin.newsletters.index', compact('subscriptions'));
    }

    public function updateStatus($id)
    {
        $subscription = Newsletter::findOrFail($id);
        $subscription->is_active = !$subscription->is_active;
        $subscription->save();

        return response()->json([
            'success' => 'Status updated successfully!',
            'is_active' => $subscription->is_active
        ]);
    }

    public function destroy($id)
    {
        $subscription = Newsletter::findOrFail($id);
        $subscription->delete();

        return response()->json(['success' => 'Subscription deleted successfully!']);
    }

    public function export()
    {
        $subscriptions = Newsletter::all();
        $filename = "newsletter_subscriptions_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fputcsv($handle, ['ID', 'Email', 'Status', 'Subscribed At']);
        
        foreach ($subscriptions as $sub) {
            fputcsv($handle, [
                $sub->id,
                $sub->email,
                $sub->is_active ? 'Active' : 'Unsubscribed',
                $sub->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($handle);
        exit;
    }
}
