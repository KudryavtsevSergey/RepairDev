<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$countries = GetCountryArray();

$resCountries = [];
$citizenship = is_numeric($_POST['UF_USER_CITIZENSHIP']) ? $_POST['UF_USER_CITIZENSHIP'] : '';
for($i = 0; $i < count($countries['reference_id']); $i++){
    $resCountries[] = [
        'id' => $countries['reference_id'][$i],
        'name' => $countries['reference'][$i],
        'selected' => ($citizenship == $countries['reference_id'][$i]) ? 'selected' : '',
    ];
}
$arResult["COUNTRIES"] = $resCountries;
