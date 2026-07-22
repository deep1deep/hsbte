<?php

/*
|--------------------------------------------------------------------------
| Portal / organisation details
|--------------------------------------------------------------------------
|
| Ek hi jagah — footer, contact page, privacy policy sab yahin se padhte hain.
| Badalna ho to sirf yahan badlo (ya .env me override karo), blade files ko
| haath lagane ki zaroorat nahi.
|
| ⚠️  XX se shuru hone waali values PLACEHOLDER hain — inhe asli details se
|     replace karna zaroori hai. Ye jaanbujhkar obvious rakhi gayi hain taaki
|     galti se live na chali jaayein.
|
*/

return [

    'name'       => 'HSBTE Training Portal',
    'org'        => 'Haryana State Board of Technical Education',
    'org_short'  => 'HSBTE',

    'contact' => [
        // TODO: asli office address daalein
        'address' => env('PORTAL_ADDRESS', 'XX — office address pending'),

        // TODO: asli helpline number daalein
        'phone'   => env('PORTAL_PHONE', 'XX — phone number pending'),

        'email'   => env('PORTAL_EMAIL', 'support@hsbte.gov.in'),

        'hours'   => env('PORTAL_HOURS', 'Monday to Friday, 9:00 AM – 5:00 PM'),
    ],

    // Content review date — GIGW ke liye "last updated" dikhana hota hai
    'last_reviewed' => env('PORTAL_LAST_REVIEWED', '22 July 2026'),

    /*
    |----------------------------------------------------------------------
    | Dignitaries (home page leadership section)
    |----------------------------------------------------------------------
    |
    | ⚠️  Ye REAL public officials hain. Post badalte rehte hain — koi bhi
    |     transfer/election ke baad ye list turant update karni hogi, warna
    |     government portal pe galat naam dikhega.
    |
    | 'photo' => public/images/dignitaries/ me file ka naam.
    |            File na ho to apne aap initials wala avatar dikh jaayega,
    |            layout tootega nahi.
    |
    */
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
            // .png — source file PNG hai, bhale hi hsbte.org.in pe .jpg naam se pada tha
            'photo'       => 'secretary.png',
        ],
    ],

];
