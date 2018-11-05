<?php

class UserOrderList extends CBitrixComponent
{
    protected $request;

    private function checkRequest()
    {
        $this->request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        if (check_bitrix_sessid() && ($this->request->isPost())) {
            return true;
        }
        return false;
    }

    private function saveData()
    {
        global $USER;

        $carParsingCardData = $this->request->getPost('CAR_PARSING_CARD');
        $carParsingCardData['UF_USER_ID'] = $USER->GetID();

        $sparePartsData = $this->request->getPost('SPARE_PARTS');

        $carParsingCardId = \classes\stores\CarParsingCard::add($carParsingCardData);

        if (!empty($carParsingCardId)) {
            foreach ($sparePartsData as $sparePartId => $sparePartsDatum) {

                $continue = true;
                foreach ($sparePartsDatum as $item){
                    if(!empty($item)){
                        $continue = false;
                    }
                }

                if($continue){
                    continue;
                }

                $sparePartsDatum['UF_SPARE_PART_ID'] = $sparePartId;
                $sparePartsDatum['UF_CAR_PARSING_CARD'] = $carParsingCardId;
                \classes\stores\CarParsingCardSparePart::add($sparePartsDatum);
            }
        }

        LocalRedirect($this->arParams['LOCAL_REDIRECT']);
    }

    public function executeComponent()
    {
        if ($this->checkRequest()) {
            $this->saveData();
        }

        /*$template = \classes\stores\Template::getById($this->arParams['TEMPLATE']);

        $spareParts = [];
        if (!empty($template['PROPERTIES']['SPARE_PARTS']['VALUE'])) {
            $spareParts = \classes\stores\SparePart::get(['filter' => ['ID' => $template['PROPERTIES']['SPARE_PARTS']['VALUE'], ACTIVE' => 'Y']]);
        }*/
        $spareParts = \classes\stores\SparePart::get(['filter' => ['ACTIVE' => 'Y']]);

        $this->arResult['SPARE_PARTS'] = $spareParts;

        $this->IncludeComponentTemplate();
    }
}
