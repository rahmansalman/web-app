<?php
require_once __DIR__. "/lib/functions.php";

$accessCode = $_GET['access_code'] ?:null;

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
    <title>Application Status</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>

<section class="bg-gray-100 min-h-screen px-6 py-6 md:px-20 md-20">

    <div class="flex items-center flex-col flex-wrap h-full" style="min-height: 80vh">

        <h3 class="uppercase font-semibold text-3xl text-gray-900">Applicant's Status</h3>

        <div class="border rounded-md w-56 h-56 max-w-md max-h-full mt-4 flex items-center justify-center p-3">
            <img src="<?= $data['image_url'] ?>" alt="" class="max-w-md max-h-full">
        </div>

        <div class="rounded w-full lg:w-1/2 mt-4 text-lg">
            <p>
                I <span class="font-semibold">
                    <?= ucwords($data['firstname']." ".$data['lastname']) ?>
                    </span>
                , applied with the application code <span class="font-semibold"><?=$data['access_code']?></span>
            </p>

            <p class="mt-2">
                I live at <span class="font-semibold"><?= $data['address'] ?></span>
                and was born on <span class="font-semibold"><?= $data['date_of_birth'] ?></span>
            </p>

            <p class="mt-2">
                My favourite subjects are <span class="font-semibold"> <?= ucwords($data['best_subjects']) ?> </span>
            </p>
        </div>
    </div>
</section>

</body>
</html>