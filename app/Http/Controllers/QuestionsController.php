<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuestionsController extends Controller
{
    public function list(Request $request): Response
    {
        return Inertia::render('Questions/List');
    }

    public function details(Request $request): Response
    {
        return Inertia::render('Questions/Details');
    }
}
