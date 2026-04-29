<?php
 

return [
    'client_id' => env('RMIS_CLIENT_ID'),      // Your RMIS ID: 7327
    'password'  => env('RMIS_PASSWORD'),        // Your password
    'version'   => '13',

    'delta_url'    => 'https://api.rmissecure.com/_c/std/api/DeltaAPI.aspx',
    'expanded_url' => 'https://api.rmissecure.com/_c/std/api/ExpandedCarrierAPI.aspx',
    'document_url' => 'https://api.rmissecure.com/_c/std/api/DocumentAPI.aspx',
];