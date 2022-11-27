<?php

namespace App\Http\Controllers;

class MarketingController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function marketing()
    {
        return view('marketing', [
            'features' => [
                ['label' => 'Print Monitoring', 'description' => "All of your receipts organized into one place, as long as you don't mind typing in the data by hand.", 'image' => asset('images/feature-1-ui-light.webp')],
                ['label' => 'Smart Queue', 'description' => "All of your receipts organized into one place, as long as you don't mind typing in the data by hand.", 'image' => asset('images/feature-2-ui-light.webp')],
                ['label' => 'Manage Materials', 'description' => "All of your receipts organized into one place, as long as you don't mind typing in the data by hand.", 'image' => asset('images/feature-3-ui-light.webp')],
                ['label' => 'Print Monitoring', 'description' => "All of your receipts organized into one place, as long as you don't mind typing in the data by hand.", 'image' => asset('images/feature-4-ui-light.webp')],
            ],
            'secondary' => [
                ['label' => 'Print Queue', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
                ['label' => 'Customizable Job Priority', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
                ['label' => 'Manage Materials', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
                ['label' => 'Printer Management', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
                ['label' => 'Print Monitoring', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
                ['label' => 'Queue History', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
                ['label' => 'Smart Alerts', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
            ],
        ]);
    }
}
