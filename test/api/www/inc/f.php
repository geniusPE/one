<?php

$sessions = [
    "jim"=>'A25E9DB183737D62E6BD91A667ED8B1C',
];

$userids = [
    "jim"=>"23",
];

$visaids=[];

$bankcodes=[];

$phones=[
    "jim"=>"13573752816",
];

function getCliName(){
    return "CSL";
}

function getSessionKey($username){
    global $sessions;
    $result = $sessions[$username];
    return $result;
}

function getOrgCode($userName){
    return "992dd8b63a3b11e799e54b798c86ae83";
}

function getUserId($username){
    return "2";
}
