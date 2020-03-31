# trustpilot-api-wrapper
Simple wrapper for the trustpilot api.

Currently only supports the api to retreive a companies reviews.

```php
<?php

$dir = dirname(__DIR__);
include $dir . '/vendor/autoload.php';
   
$trustPilot = new \LukeRodham\TrustPilot\TrustPilot($apikey = '');
$reviews    = $trustPilot->reviews()->latest(['language' => 'en']);
$starRating = $trustPilot->reviews()->getStarRating();
$numberOfReviews = $trustPilot()->reviews()->getTotalNumberOfReviews();
$trustScore = $trustPilot()->reviews()->getTrustScore();
```
