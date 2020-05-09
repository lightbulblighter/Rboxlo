<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx.php");

    header("Content-Type: text/plain");

    if (!isset($_GET["userId"]) || strlen($_GET["userId"]) <= 0 || !is_int((int)$_GET["userId"]))
    {
        exit("No user specified");
    }
    // $_GET["pagal"] is a cache buster

    $statement = $GLOBALS["sql"]->prepare("SELECT `avatar` FROM `users` WHERE `id` = ?");
    $statement->execute([$_GET["userId"]]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$result)
    {
        exit("No user exists under that userId");
    }
    $avatar = json_decode($result, true);

    // Fetch backpack
    $statement = $GLOBALS["sql"]->prepare("SELECT `asset_id` FROM `owned_items` WHERE `type` = 'gear' AND `owner_id` = ?");
    $statement->execute([$_GET["userId"]]);
    
    $backpack = [];
    foreach ($statement as $result)
    {
        $backpack[] = $result["asset_id"];
    }

    // Construct the avatar
    $avatar = json_encode([
        "resolvedAvatarType" => strtoupper($avatar["avatar_type"]),
        "accessoryVersionIds" => $avatar["hats"],
        "equippedGearVersionIds" => $avatar["gears"],
        "backpackVersionIds" => $backpack,
        "bodyColors" => [
            "HeadColor" => $avatar["colors"]["head"],
            "LeftArmColor" => $avatar["colors"]["left_arm"],
            "LeftLegColor" => $avatar["colors"]["left_leg"],
            "RightArmColor" => $avatar["colors"]["right_arm"],
            "RightLegColor" => $avatar["colors"]["right_leg"],
            "TorsoColor" => $avatar["colors"]["torso"]
        ],
        "animations" => [
            "Walk" => $avatar["r15_animations"]["walk"],
            "Idle" => $avatar["r15_animations"]["idle"],
            "Climb" => $avatar["r15_animations"]["climb"],
            "Jump" => $avatar["r15_animations"]["jump"],
            "Run" => $avatar["r15_animations"]["run"],
            "Swim" => $avatar["r15_animations"]["swim"],
            "Fall" => $avatar["r15_animations"]["fall"]
        ],
        "scales" => [
            "Width" => $avatar["r15_avatar_scaling"]["width"],
            "Height" => $avatar["r15_avatar_scaling"]["height"],
            "Head" => $avatar["r15_avatar_scaling"]["head"],
            "Depth" => $avatar["r15_avatar_scaling"]["depth"],
            "Proportion" => $avatar["r15_avatar_scaling"]["proportion"],
            "BodyType" => $avatar["r15_avatar_scaling"]["body_type"]
        ]
    ]);

    // Return
    exit($avatar);
?>