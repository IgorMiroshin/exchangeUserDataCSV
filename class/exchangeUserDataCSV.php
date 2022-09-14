<?php


class exchangeUserDataCSV
{
    public $fileCSV;

    public function __construct($fileCSV)
    {
        $this->fileCSV = file($fileCSV);
    }

    public function update()
    {
        $fileCSV = $this->fileCSV;

        $result = [];

        $array = [];

        foreach ($fileCSV as $fileCSVItem) {
            $data = explode(';', $fileCSVItem);

            $code = $data[0];
            $name = $data[1];
            $email = $data[2];
            $inn = $data[3];
            $kpp = $data[4];
            $ogrn = $data[5];

            if (empty($email)) {
                continue;
            }

            if (!empty(trim($code))) {
                $array[$email]["code"][] = $code;
            }
            if (!empty(trim($name))) {
                $array[$email]["name"] = $name;
            }
            if (!empty(trim($email))) {
                $array[$email]["email"] = $email;
            }
            if (!empty(trim($inn))) {
                $array[$email]["inn"] = $inn;
            }
            if (!empty(trim($kpp))) {
                $array[$email]["kpp"] = $kpp;
            }
            if (!empty(trim($ogrn))) {
                $array[$email]["ogrn"] = $ogrn;
            }
        }

        foreach ($array as $arrayItem) {
            $code = $arrayItem["code"];
            $name = $arrayItem["name"];
            $email = $arrayItem["email"];
            $inn = $arrayItem["inn"];
            $kpp = $arrayItem["kpp"];
            $ogrn = $arrayItem["ogrn"];
            $existID = $this->existUser($email);

            if ($existID) {
                $arFields = [
                    "NAME" => $name,
                    "EMAIL" => $email,
                    "GROUP_ID" => [2, 8],
                    "UF_INN" => $inn,
                    "UF_OGRN" => $kpp,
                    "UF_KPP" => $ogrn,
                    "UF_CODE" => $code,
                ];
                $user = new CUser;
                $ID = $user->Update($existID, $arFields);
                if (!$ID) {
                    $result["errors"]["update"][] = $user->LAST_ERROR;
                }
            } else {
                $password = randString(8);
                $arFields = [
                    "NAME" => $name,
                    "EMAIL" => $email,
                    "LOGIN" => $email,
                    "PASSWORD" => $password,
                    "CONFIRM_PASSWORD" => $password,
                    "GROUP_ID" => [2, 8],
                    "UF_INN" => $inn,
                    "UF_OGRN" => $kpp,
                    "UF_KPP" => $ogrn,
                    "UF_CODE" => $code,
                ];
                $user = new CUser;
                $ID = $user->Add($arFields);
                if (!$ID) {
                    $result["errors"]["add"][] = $user->LAST_ERROR;
                }
            }
        }

        return $result;
    }

    public function existUser($email)
    {
        $order = ['sort' => 'asc'];
        $tmp = 'sort';
        $filter = ["LOGIN" => $email];
        $itemsGetList = CUser::GetList($order, $tmp, $filter)->GetNext();
        return $itemsGetList["ID"];
    }
}