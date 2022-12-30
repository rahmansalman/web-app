<?php
require_once __DIR__. "/lib/functions.php";

$accessCode = $_GET['access_code'] ?? null;

if (is_null($accessCode))
    redirect('login');

$data = fetchApplicantByAccessCode($accessCode);

if (is_null($data))
    redirect('login');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Details</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>

<section class="bg-gray-100 min-h-screen px-6 py-6 md:px-20 md-20">

    <div class="flex items-center flex-col flex-wrap h-full" style="min-height: 80vh">

        <h3 class="uppercase font-semibold text-3xl text-gray-900">Applicant's Details</h3>

        <div class="border rounded-md w-56 h-56 max-w-md max-h-full mt-4 flex items-center justify-center p-3">
            <img src="<?= $data['image_url'] ?>" alt="" class="max-w-md max-h-full">
        </div>

        <div class="rounded w-full lg:w-1/2 mt-4 text-lg">
            <p>
                Dear <span class="font-semibold"><?= ucwords($data['firstname']." ".$data['lastname']) ?></span>,
                your application details have been received.
            </p>

            <p class="mt-2">
                Your Access code is <span class="font-semibold"><?=$data['access_code']?></span>. Kindly go through
                the details.
            </p>

            <div class="mt-10 border rounded border-gray-400">
                <div class="flex flex-row items-center px-6 border-b border-gray-400">
                    <div class="w-5/12 py-3">Address</div>
                    <div class="w-7/12 py-3 border-l border-gray-400 pl-4"><?=ucwords($data['address'])?></div>
                </div>
                <div class="flex flex-row items-center px-6 border-b border-gray-400">
                    <div class="w-5/12 py-3">Marital Status</div>
                    <div class="w-7/12 py-3 border-l border-gray-400 pl-4"><?=ucwords($data['marital_status'])?></div>
                </div>
                <div class="flex flex-row items-center px-6 border-b border-gray-400">
                    <div class="w-5/12 py-3">Educational Background</div>
                    <div class="w-7/12 py-3 border-l border-gray-400 pl-4"><?=ucwords($data['edu_background'])?></div>
                </div>
                <div class="flex flex-row items-center px-6 border-b border-gray-400">
                    <div class="w-5/12 py-3">Best Subjects</div>
                    <div class="w-7/12 py-3 border-l border-gray-400 pl-4"><?=ucwords($data['best_subjects'])?></div>
                </div>
                <div class="flex flex-row items-center px-6 border-b border-gray-400">
                    <div class="w-5/12 py-3">Religion</div>
                    <div class="w-7/12 py-3 border-l border-gray-400 pl-4"><?=ucwords($data['religion'])?></div>
                </div>
                <div class="flex flex-row items-center px-6 border-b border-gray-400">
                    <div class="w-5/12 py-3">State of Origin</div>
                    <div class="w-7/12 py-3 border-l border-gray-400 pl-4"><?=ucwords($data['state_of_origin'])?></div>
                </div>
                <div class="flex flex-row items-center px-6 border-b border-gray-400">
                    <div class="w-5/12 py-3">Date of Birth</div>
                    <div class="w-7/12 py-3 border-l border-gray-400 pl-4"><?=ucwords($data['date_of_birth'])?></div>
                </div>


            </div>
        </div>
    </div>
</section>

</body>
</html>