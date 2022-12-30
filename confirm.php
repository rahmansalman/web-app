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
    <title>Application Confirmation</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>

<section class="bg-gray-100 min-h-screen px-6 py-6 md:px-20 md-20">

    <div class="flex items-center flex-col flex-wrap h-full" style="min-height: 80vh">

        <h3 class="uppercase font-semibold text-3xl text-gray-900">Online Application</h3>

        <div class="rounded w-full lg:w-1/2 mt-4 text-xl">
            <p>
                Dear <span class="font-semibold"><?=ucwords($data['firstname'] . " " . $data['lastname'])?></span>,
            </p>

            <p class="mt-2">
                Your application with the access code <span class="font-semibold"><?=$data['access_code']?></span> is successful
            </p>

            <p class="mt-2">
                Kindly print application status and application details by clicking the buttons below
            </p>
        </div>

        <div class="mt-10">
            <div class="inline-flex rounded-md shadow">
                <a href="<?="status.php?access_code=$accessCode"?>" class="inline-flex items-center justify-center px-5 py-3 border border-transparent
                text-base leading-6 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500
                focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Application Status
                </a>
            </div>

            <div class="inline-flex rounded-md shadow ml-6">
                <a href="<?="detail.php?access_code=$accessCode"?>" class="inline-flex items-center justify-center px-5 py-3 border border-transparent
                text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500
                focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Application Detail
                </a>
            </div>
        </div>
    </div>
</section>

</body>
</html>