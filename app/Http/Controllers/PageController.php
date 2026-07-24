<?php

namespace App\Http\Controllers;

/**
 * Static information pages — About, Contact, Privacy, Terms, Accessibility, Help.
 *
 * These are all expected under GIGW (Guidelines for Indian Government Websites).
 * The content lives in the blade views; contact details come from config/portal.php.
 */
class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function accessibility()
    {
        return view('pages.accessibility');
    }

    public function help()
    {
        return view('pages.help');
    }
}
