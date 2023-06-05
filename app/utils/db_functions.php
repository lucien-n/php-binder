<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/connection.php');

function user_has_pending_likes($conn, $user_uuid)
{
    $binder = get_user_pending_like($conn, $user_uuid);
    return isset($binder);
}

function get_user_pending_like($conn, $user_uuid)
{
    $statement = $conn->prepare('SELECT * FROM pending WHERE liked_uuid = ? LIMIT 1');
    $statement->execute([$user_uuid]);
    $data = $statement->get_result()->fetch_assoc();
    if ($data && isset($data[0])) {
        return $data[0];
    }
    return null;
}

function get_binder_uuid_not_disliked_not_matched_not_pending($conn, $user_uuid)
{
    $i = 0;
    while ($i < 8) {
        $i++;
        $statement = $conn->prepare('SELECT uuid
        FROM users
        WHERE 
            (
                uuid NOT IN (SELECT disliked_uuid FROM dislikes WHERE disliker_uuid = ?)
                AND uuid NOT IN (SELECT liked_uuid FROM pending WHERE liker_uuid = ?)
            )
            AND uuid != ?
        ORDER BY RAND()
        LIMIT 1
        ');
        $statement->execute([$user_uuid, $user_uuid, $user_uuid]);
        $data = $statement->get_result()->fetch_row();
        if (isset($data[0])) {
            if (have_matched($conn, $user_uuid, $data[0]))
                continue;
            return $data[0];
        }
    }
}

function get_binder($conn, $user_uuid, $gender, $liked_gender)
{
    $i = 0;
    while ($i < 8) {
        $i++;
        $binder_uuid = get_binder_uuid_not_disliked_not_matched_not_pending($conn, $user_uuid);
        $binder = get_binder_by_uuid($conn, $binder_uuid);

        if (isset($binder[5]) && isset($binder[6])) {
            $binder_gender = $binder[5];
            $binder_liked_gender = $binder[6];

            if ($binder_liked_gender == 2 && $liked_gender == $binder_gender || $liked_gender == 2) {
                return $binder_uuid;
            } elseif ($binder_liked_gender == $gender && $binder_gender == $liked_gender) {
                return $binder_uuid;
            }
        }
    }
}


function binder_is_disliked_by_user($conn, $binder_uuid, $user_uuid)
{
    $statement = $conn->prepare('SELECT * FROM dislikes WHERE (disliked_uuid = ? AND disliker_uuid = ?) OR (disliked_uuid = ? AND disliker_uuid = ?)');
    $statement->execute([$binder_uuid, $user_uuid, $user_uuid, $binder_uuid]);
    $data = $statement->get_result()->fetch_row();
    return isset($data);
}

function delete_from_pending($conn, $binder_uuid, $user_uuid)
{
    $statement = $conn->prepare('DELETE FROM pending WHERE liked_uuid = ? AND liker_uuid = ? OR liked_uuid = ? AND liker_uuid = ?');
    $statement->execute([$binder_uuid, $user_uuid, $user_uuid, $binder_uuid]);
}

function get_random_binder($conn, $user_uuid)
{
    $statement = $conn->prepare('SELECT * FROM users WHERE uuid != ? ORDER BY RAND() LIMIT 1; ');
    $statement->execute([$user_uuid]);
    $data = $statement->get_result()->fetch_row();
    return $data;
}

function get_binder_by_uuid($conn, $binder_uuid)
{
    $statement = $conn->prepare('SELECT * FROM users WHERE uuid = ?');
    $statement->execute([$binder_uuid]);
    $data = $statement->get_result()->fetch_row();
    return $data;
}

function have_matched($conn, $user_uuid, $binder_uuid)
{
    $statement = $conn->prepare('SELECT * FROM matchs WHERE uuid_one = ? AND uuid_two = ? OR uuid_one = ? AND uuid_two = ?');
    $statement->execute([$binder_uuid, $user_uuid, $user_uuid, $binder_uuid]);
    $data = $statement->get_result()->fetch_row();
    return isset($data);
}

function clean_pending_likes($conn, $user_uuid, $binder_uuid)
{
    $statement = $conn->prepare('DELETE FROM pending WHERE (liker_uuid = ? AND liked_uuid = ?) OR (liker_uuid = ? AND liked_uuid = ?)');
    $statement->execute([$binder_uuid, $user_uuid, $user_uuid, $binder_uuid]);
}

function has_liked($conn, $user_uuid, $binder_uuid)
{
    $statement = $conn->prepare('SELECT * FROM pending WHERE (liker_uuid = ? AND liked_uuid = ?) OR (liker_uuid = ? AND liked_uuid = ?)');
    $statement->execute([$binder_uuid, $user_uuid, $user_uuid, $binder_uuid]);
    $data = $statement->get_result()->fetch_row();
    return isset($data);
}

function has_disliked($conn, $user_uuid, $binder_uuid)
{
    $statement = $conn->prepare('SELECT * FROM dislikes WHERE (disliker_uuid = ? AND disliked_uuid = ?) OR (disliker_uuid = ? AND disliked_uuid = ?)');
    $statement->execute([$binder_uuid, $user_uuid, $user_uuid, $binder_uuid]);
    $data = $statement->get_result()->fetch_row();
    return isset($data);
}

function like_binder($conn, $user_uuid, $binder_uuid)
{
    if (has_liked($conn, $user_uuid, $binder_uuid))
        return;
    $statement = $conn->prepare("INSERT INTO pending (liker_uuid, liked_uuid) VALUES (?, ?)");
    $statement->execute([$user_uuid, $binder_uuid]);
}

function dislike_binder($conn, $user_uuid, $binder_uuid)
{
    if (has_disliked($conn, $user_uuid, $binder_uuid))
        return;
    $statement = $conn->prepare("INSERT INTO dislikes (disliker_uuid, disliked_uuid) VALUES (?, ?)");
    $statement->execute([$user_uuid, $binder_uuid]);
}

function match_binder($conn, $user_uuid, $binder_uuid)
{
    if (have_matched($conn, $user_uuid, $binder_uuid))
        return;
    $statement = $conn->prepare("INSERT INTO matchs (uuid_one, uuid_two) VALUES (?, ?)");
    $statement->execute([$binder_uuid, $user_uuid]);
}
?>