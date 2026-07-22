<?php

namespace App\Http\Controllers;

/**
 * Static information pages — About, Contact, Privacy, Terms, Accessibility, Help.
 *
 * Ye sab GIGW (Guidelines for Indian Government Websites) ke under expected
 * hain. Content blade me hai; contact details config/portal.php se aati hain.
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
