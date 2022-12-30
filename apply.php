<?php
require_once __DIR__ . "/lib/functions.php";
require_once __DIR__ . "/lib/FileUpload.php";

$validator = null;

if ( array_key_exists('submitted', $_POST) )
{
    require_once "lib/Validator.php";
    require_once __DIR__ . "/lib/functions.php";

    $rules = [
        'firstname' => ['required', 'string'],
        'lastname' => ['required'],
        'address' => ['required'],
        'marital_status' => ['required'],
        'edu_background' => ['required'],
        'best_subjects' => ['required'],
        'religion' => ['required'],
        'state_of_origin' => ['required'],
        'date_of_birth' => ['required', 'date'],
        'image' => ['required', 'file', 'size:600', 'image']
        ];
    $attributes = [
        'firstname' => 'First name',
        'lastname' => 'Last name',
        'marital_status' => 'Marital status',
        'edu_background' => 'Educational background',
        'best_subjects' => 'best subject',
        'state_of_origin' => 'state of origin',
        'date_of_birth' => 'date of birth'
    ];

    $validator = new Validator($rules, $attributes);

    if ($validator->passed()){
        //upload files
        $fileUpload = new FileUpload($_FILES);
        $fileUpload->setInputName('image');
        $fileUpload->setUploadNewFilename(time());
        $image_url = $fileUpload->process();

        $data = $validator->validated();
        $date = date("Y-m-d", strtotime($data['date_of_birth']));
        $best_subjects = implode(", ", $data['best_subjects']);
        $data['date_of_birth'] = $date;
        $data['best_subjects'] = $best_subjects;
        $data['image_url'] = $image_url;

        unset($data['submitted']);
        insertToTable("applicants", $data);
        updateRow("unique_codes", ['used' => 1], "WHERE access_code='{$data['access_code']}'");
        redirect("confirm", "access_code={$data['access_code']}");
    }

}

$access_code = $_GET['access_code'] ?? $_POST['access_code'] ?? null;

if ( is_null($access_code) || !is_array(checkAccessCode($access_code))){
    return redirect("login", "failed");
}


$bestSubjects = [
    'mathematics', 'english', 'science',
    'government', 'art', 'civic',
    'computer', 'history', 'agriculture'
];

$maritalStatus = ['single', 'married', 'divorced', 'widowed'];

$religions = ['christianity', 'islam'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application</title>
    <link href="css/styles.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/pikaday.min.css">
</head>
<body class="antialiased">

<section class="bg-gray-100 min-h-screen px-6 py-6 md:px-20 md-20">

    <div class="flex items-center flex-col flex-wrap h-full" style="min-height: 80vh">
        
        <h3 class="uppercase font-semibold text-2xl text-gray-900">Online Application</h3>

        <div class="rounded w-1/2 mt-4">

            <div class="w-full">
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" autocomplete="off"
                      enctype="multipart/form-data" id="apply-form" data-parsley-validate>
                    <input type="hidden" name="submitted" value="1">
                    <input type="hidden" name="access_code" value="<?=$access_code ?? $_POST['access_code']?>">
                    <div class="flex flex-col items-center">
                        <div class="flex flex-col w-full items-center">
                            <div class="flex w-full items-center py-4 rounded border border-b-0 rounded-b-none px-3">
                                <label class="w-1/3 block">First Name</label>
                                <span class="w-2/3">
                                    <input type="text"
                                           name="firstname"
                                           data-parsley-type="alphanum" required minlength="3" maxlength="20"
                                           value="<?=$_POST['firstname'] ?? null?>"
                                       class="w-3/5 appearance-none rounded relative block w-full px-3 py-2 border
                                        border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5">

                                    <?php
                                    if (!is_null($validator) && !empty( $validator->hasError('firstname')) ){ ?>
                                        <span class="text-xs text-red-600"><?= $validator->getErrors()['firstname'][0] ?></span>
                                    <?php } ?>

                                </span>
                            </div>

                            <div class="flex w-full items-center py-4 px-3 border border-b-0">
                                <label class="w-1/3 block">Last Name</label>
                                <span class="w-2/3">
                                <input type="text" name="lastname"
                                       data-parsley-type="alphanum" required minlength="3" maxlength="20"
                                       value="<?=$_POST['lastname'] ?? null?>"
                                       class="w-3/5 appearance-none rounded relative block w-full px-3 py-2 border
                                border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5">
                                    <?php
                                    if (!is_null($validator) && !empty( $validator->hasError('lastname')) ){ ?>
                                        <span class="text-xs text-red-600"><?= $validator->getErrors()['lastname'][0] ?></span>
                                    <?php } ?>
                                </span>
                            </div>

                            <div class="flex w-full items-center py-4 px-3 border border-b-0">
                                <label class="w-1/3 block">Address</label>
                                <span class="w-2/3">
                                    <input type="text"
                                           name="address"
                                           value="<?=$_POST['address'] ?? null?>"
                                           required minlength="10" maxlength="255"
                                           class="appearance-none rounded relative block w-full px-3 py-2 border
                                    border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5">
                                <?php
                                if (show_error($validator, 'address')) { ?>
                                    <span class="text-xs text-red-600"><?= $validator->getErrors()['address'][0] ?></span>
                                <?php } ?>
                                </span>
                            </div>

                            <div class="flex w-full items-center py-4 px-3 border border-b-0">
                                <label class="w-1/3 block capitalize">Marital status</label>
                                <span class="w-2/3 flex flex-row flex-wrap">
                                    <?php
                                    $counter = 1;
                                    foreach($maritalStatus as $status): ?>
                                    <label class="inline-flex w-1/2 items-center mb-1">
                                        <input type="radio" required <?= $counter == 1
                                            ? 'data-parsley-errors-container="#mStatusJError"'
                                            : '' ?>
                                               class="form-checkbox" required
                                               name="marital_status"
                                               value="<?=$status?>"
                                            <?= $_POST['marital_status'] ?? null == $status ? 'checked' : "" ?>
                                        >
                                        <span class="ml-2 capitalize"><?= $status ?></span>
                                    </label>
                                    <?php
                                    $counter++;
                                    endforeach;
                                    unset($counter);
                                    if (show_error($validator, 'marital_status')) { ?>
                                        <span class="text-xs text-red-600"><?= $validator->getErrors()['marital_status'][0] ?></span>
                                    <?php } ?>
                                    <span id="mStatusJError"></span>
                                </span>
                            </div>

                            <div class="flex w-full items-center py-4 px-3 border border-b-0">
                                <label class="w-1/3 block capitalize">Educational background</label>
                                <span class="w-2/3">
                                    <input type="text" name="edu_background" required
                                           value="<?=$_POST['edu_background'] ?? null?>"
                                           class="appearance-none rounded relative block w-full px-3 py-2 border
                                    border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5">
                                <?php
                                if (show_error($validator, 'edu_background')) { ?>
                                    <span class="text-xs text-red-600"><?= $validator->getErrors()['edu_background'][0] ?></span>
                                <?php } ?>
                                </span>
                            </div>

                            <div class="flex w-full items-center py-4 px-3 border border-b-0">
                                <label class="w-1/3 block capitalize">Best subject</label>
                                <div class="w-2/3 ">
                                    <div class="flex flex-row flex-wrap">
                                    <?php
                                    $counter = 1;
                                    foreach($bestSubjects as $subject): ?>
                                        <label class="inline-flex w-1/2 items-center mb-1">
                                            <input type="checkbox" class="form-checkbox"
                                                <?= $counter == 1
                                                ? 'data-parsley-errors-container="#mBestSubjectJError"'
                                                : '' ?>
                                                   name="best_subjects[]" required
                                                   value="<?=$subject?>"
                                                   <?=in_array($subject, $_POST['best_subjects'] ?? []) ? 'checked': ''?>
                                            >
                                            <span class="ml-2 capitalize"><?= $subject ?></span>
                                        </label>
                                    <?php
                                    $counter++;
                                    endforeach; unset($counter); ?>
                                    </div>
                                    <div>
                                        <?php if (show_error($validator, 'best_subjects')) { ?>
                                            <span class="text-xs text-red-600"><?= $validator->getErrors()['best_subjects'][0] ?></span>
                                        <?php } ?>
                                        <span id="mBestSubjectJError"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="flex w-full items-center py-4 px-3 border border-b-0">
                                <label class="w-1/3 block capitalize">Religion</label>
                                <span class="w-2/3 flex flex-row flex-wrap">
                                    <?php
                                    $counter = 1;
                                    foreach($religions as $religion): ?>
                                        <label class="inline-flex w-1/2 items-center mb-1">
                                        <input type="radio"
                                            <?= $counter == 1
                                                ? 'data-parsley-errors-container="#mReligionJError"'
                                                : '' ?>
                                               class="form-checkbox" required
                                               name="religion"
                                               value="<?=$religion?>" <?= $_POST['religion'] ?? null == $religion ? 'checked' : "";?>>
                                        <span class="ml-2 capitalize"><?= $religion ?? "" ?></span>
                                    </label>
                                    <?php $counter++;
                                    endforeach; unset($counter);

                                    if (show_error($validator, 'religion')) { ?>
                                        <span class="text-xs text-red-600"><?= $validator->getErrors()['religion'][0] ?></span>
                                    <?php } ?>
                                    <span id="mReligionJError"></span>
                                </span>
                            </div>

                            <div class="flex w-full items-center py-4 px-3 border border-b-0">
                                <label class="w-1/3 block">State of Origin</label>
                                <span class="w-2/3">
                                    <input type="text" name="state_of_origin" required
                                           value="<?=$_POST['state_of_origin'] ?? null?>"
                                           class="appearance-none rounded relative block w-full px-3 py-2 border
                                    border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5">

                                    <?php if (show_error($validator, 'state_of_origin')) { ?>
                                        <span class="text-xs text-red-600"><?= $validator->getErrors()['state_of_origin'][0] ?></span>
                                    <?php } ?>
                                </span>
                            </div>

                            <div class="flex w-full items-center py-4 px-3 border border-b-0">
                                <label class="w-1/3 block">Date of Birth</label>
                                <span class="w-2/3">
                                    <input type="text" id="datepicker" name="date_of_birth" required
                                           value="<?=$_POST['date_of_birth'] ?? null?>"
                                           class="appearance-none rounded relative block w-full px-3 py-2 border
                                    border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5">

                                    <?php if (show_error($validator, 'date_of_birth')) { ?>
                                    <span class="text-xs text-red-600"><?= $validator->getErrors()['date_of_birth'][0] ?></span>
                                <?php } ?>
                                </span>
                            </div>

                            <div class="flex w-full items-center py-4 px-3 border rounded-b">
                                <label class="w-1/3 block">Image</label>
                                <span class="w-2/3">
                                    <input type="file" name="image" required
                                           class="appearance-none rounded relative block w-full px-3 py-2 border
                                    border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5">
                                <?php
                                if (!is_null($validator) && !empty( $validator->hasError('image')) ){ ?>
                                    <span class="text-xs text-red-600"><?= $validator->getErrors()['image'][0] ?></span>
                                <?php } ?>
                                </span>

                            </div>

                        </div>

                        <div class="mt-3">
                            <button type="submit" class="uppercase text-white bg-indigo-800 px-10 py-3 rounded">
                                Submit application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<script src="js/app.js"></script>
<script src="js/pikaday.min.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('datepicker') });
    $(function(){
        $('#apply-form').parsley()
    })
</script>
</body>
</html>