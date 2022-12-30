<?php
require_once __DIR__."/lib/functions.php";

if (array_key_exists('submitted', $_POST) && $_POST['submitted'] == 1)
{
    $access_code = $_POST['access_code'] ?? null ;

    if ( empty($access_code) || strlen($access_code) < 8 ){
        redirect("recover", "failed");
        exit();
    }

    if (!$data = fetchApplicantByAccessCode($access_code)){
        header("Location: recover.php?failed");
        exit();
    }

    header("Location: confirm.php?access_code={$data['access_code']}");
}


if (array_key_exists('failed', $_GET)){
    $error_msg = "Wrong/Invalid Access code supplied.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Retrieve Applicant Data</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="antialiased">

<section class="bg-gray-100 min-h-screen px-6 py-6 md:px-20 md-20">

    <div class="flex items-center flex-row flex-wrap justify-between h-full" style="min-height: 80vh">

        <div class="w-1/2">

            <div class="font-medium text-3xl px-10">
            You can login into this application once you have
            been issued an access code by the administrator
            </div>
        </div>

        <div class="w-1/2">
            <div class="max-w-md flex flex-col">
                <h3 class="text-center font-bold text-3xl pb-4">Recover Application</h3>
                <div class="border rounded w-full h-56 px-6 py-2 flex items-center">
                <form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" class="w-full" method="post">
                    <div class="">
                        <?php
                        if (!empty($error_msg)):
                        ?>
                        <div class="my-2 border border-pink-300 bg-pink-100 rounded px-3 py-2 text-red-600 text-sm">
                            <?= $error_msg ?>
                        </div>
                        <?php endif; ?>
                        <label class="block w-full ml-1">Access Code</label>
                        <input type="text" placeholder="Access Code" name="access_code" required maxlength="8"
                               class="border w-full px-2 py-2 mt-3 rounded outline-none">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="w-full uppercase justify-center inline-flex items-center px-4 py-2 border border-transparent
                        text-sm leading-5 font-medium rounded-md text-white bg-indigo-600
                        hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo
                        focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
                            Submit
                        </button>
                    </div>
                    <input type="hidden" name="submitted" value="1">
                </form>
            </div>
        </div>

    </div>

</section>

</body>
</html>