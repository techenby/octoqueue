<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class DocsController extends Controller
{
    public function __invoke($slug)
    {
        $file = Storage::disk('docs')->get($slug . '.md');

        if(!$file) {
            return response(404);
        }

        $page = YamlFrontMatter::parse($file);

        return view('docs', [
            'copy' => (new \ParsedownExtra())->text($page->body()),
        ]);
    }
}
