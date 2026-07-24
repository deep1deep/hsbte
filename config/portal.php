<?php

/*
|--------------------------------------------------------------------------
| Portal / organisation details
|--------------------------------------------------------------------------
|
| One single place — the footer, contact page, and privacy policy all read from here.
| To change anything, change it only here (or override it in .env); there's no
| need to touch the blade files.
|
| ⚠️  Values starting with XX are PLACEHOLDERS — they must be replaced with the
|     real details. They are kept deliberately obvious so they don't accidentally
|     go live.
|
*/

return [

    'name'       => 'HSBTE Training Portal',
    'org'        => 'Haryana State Board of Technical Education',
    'org_short'  => 'HSBTE',

    'contact' => [
        // TODO: add the real office address
        'address' => env('PORTAL_ADDRESS', 'XX — office address pending'),

        // TODO: add the real helpline number
        'phone'   => env('PORTAL_PHONE', 'XX — phone number pending'),

        'email'   => env('PORTAL_EMAIL', 'support@hsbte.gov.in'),

        'hours'   => env('PORTAL_HOURS', 'Monday to Friday, 9:00 AM – 5:00 PM'),
    ],

    // Content review date — GIGW requires showing a "last updated" date
    'last_reviewed' => env('PORTAL_LAST_REVIEWED', '22 July 2026'),

    /*
    |----------------------------------------------------------------------
    | Dignitaries (home page leadership section)
    |----------------------------------------------------------------------
    |
    | ON/OFF: 'show_dignitaries' — currently false, so the section is not
    | showing on the home page. Two ways to turn it back on:
    |   1. change false to true here, or
    |   2. set PORTAL_SHOW_DIGNITARIES=true in Render's Environment
    |      (without touching the code)
    |
    | Photos, names, CSS — everything is already in place, it's just not rendering.
    |
    | ⚠️  These are REAL public officials. Their posts keep changing — after any
    |     transfer/election this list must be updated immediately, otherwise the
    |     government portal will show the wrong name.
    |
    | 'photo' => the file name in public/images/dignitaries/.
    |            If the file is missing, an initials avatar is shown automatically,
    |            the layout won't break.
    |
    */
    'show_dignitaries' => env('PORTAL_SHOW_DIGNITARIES', false),

    'dignitaries' => [
        [
            'name'        => 'Sh. Nayab Singh Saini',
            'designation' => "Hon'ble Chief Minister, Haryana",
            'photo'       => 'chief-minister.jpg',
        ],
        [
            'name'        => 'Sh. Mahipal Dhanda',
            'designation' => "Hon'ble Education Minister, Haryana",
            'photo'       => 'education-minister.jpg',
        ],
        [
            'name'        => 'Sh. Apoorva Kumar Singh, IAS',
            'designation' => 'Additional Chief Secretary & Chairman, HSBTE',
            'photo'       => 'chairman.jpg',
        ],
        [
            'name'        => 'Sh. Rajesh Goel',
            'designation' => 'Secretary, HSBTE',
            // .png — the source file is a PNG, even though on hsbte.org.in it was named .jpg
            'photo'       => 'secretary.png',
        ],
    ],

];
